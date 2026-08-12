<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use LogicException;

class ReservationMailService
{
    public function ensureForQuotation(int $quotationId): void
    {
        $items = DB::table('quotation_items')->join('quotation_days','quotation_days.id','=','quotation_items.quotation_day_id')
            ->where('quotation_days.quotation_id',$quotationId)->select('quotation_items.*','quotation_days.travel_date')->get();
        foreach ($items as $item) {
            if (DB::table('reservations')->where('quotation_item_id',$item->id)->exists()) continue;
            $type = $item->item_type === 'room' ? 'room' : ($item->item_type === 'vehicle' ? 'vehicle' : 'activity');
            $start = Carbon::parse($item->travel_date)->setTime($type === 'room' ? 14 : 8,0);
            $end = $type === 'room' ? $start->copy()->addDay()->setTime(10,0) : $start->copy()->addHours($type === 'activity' ? 8 : 24);
            DB::table('reservations')->insert([
                'quotation_id'=>$quotationId,'quotation_item_id'=>$item->id,'reservation_type'=>$type,'resource_id'=>$item->source_id,
                'starts_at'=>$start,'ends_at'=>$end,'quantity'=>max(1,(int)$item->quantity),'supplier'=>$item->source,
                'amount_due'=>$item->buy_total,'actual_cost'=>$item->buy_total,'paid_amount'=>0,'status'=>'pending',
                'notes'=>'Automatically created from the confirmed itinerary service.','created_at'=>now(),'updated_at'=>now(),
            ]);
        }
    }

    public function reservation(int $reservationId): ?object
    {
        $record = DB::table('reservations')->join('quotations','quotations.id','=','reservations.quotation_id')
            ->join('clients','clients.id','=','quotations.client_id')->leftJoin('quotation_items','quotation_items.id','=','reservations.quotation_item_id')
            ->where('reservations.id',$reservationId)->select('reservations.*','quotations.reference as quotation_reference','quotations.title as quotation_title','quotations.guest_count','clients.name as client_name','quotation_items.title as service_title','quotation_items.source as item_source')->first();
        if ($record) $record->supplier_email = $this->resolveRecipient($record);
        return $record;
    }

    public function recordsForQuotation(int $quotationId): \Illuminate\Support\Collection
    {
        return DB::table('reservations')->where('quotation_id',$quotationId)->orderBy('starts_at')->get()->map(function($row){
            $full=$this->reservation($row->id); return $full ?: $row;
        });
    }

    public function send(object $reservation, string $recipient, string $subject, string $message, int $userId): void
    {
        DB::transaction(function() use($reservation,$recipient,$subject,$message,$userId){
            $locked = DB::table('reservations')->where('id',$reservation->id)->lockForUpdate()->first();
            if (!$locked || $locked->reservation_mail_sent_at) throw new LogicException('This reservation email has already been sent and is locked.');
            Mail::send('emails.reservation-request',['reservation'=>$reservation,'body'=>$message],function($mail) use($recipient,$subject){$mail->to($recipient)->subject($subject);});
            DB::table('reservation_emails')->insert(['reservation_id'=>$reservation->id,'sent_by'=>$userId,'recipient'=>$recipient,'subject'=>$subject,'message'=>$message,'status'=>'sent','sent_at'=>now(),'created_at'=>now(),'updated_at'=>now()]);
            DB::table('reservations')->where('id',$reservation->id)->update(['status'=>'requested','reservation_mail_sent_at'=>now(),'reservation_mail_recipient'=>$recipient,'updated_at'=>now()]);
        });
    }

    public function subject(object $record): string
    {
        return 'Reservation request '.$record->quotation_reference.' · '.$record->client_name.' · '.($record->service_title ?: ucfirst($record->reservation_type));
    }

    public function message(object $record): string
    {
        return "Dear Reservations Team,\n\nPlease confirm availability for ".($record->service_title ?: ucfirst($record->reservation_type))." for {$record->client_name} ({$record->guest_count} guests), from ".Carbon::parse($record->starts_at)->format('d M Y')." to ".Carbon::parse($record->ends_at)->format('d M Y').".\n\nQuantity: {$record->quantity}. Please reply with the confirmation number, final rate and payment deadline.\n\nKind regards,\nShishi Footsteps Reservations";
    }

    private function resolveRecipient(object $record): ?string
    {
        if ($record->reservation_type === 'room' && $record->resource_id) {
            try {
                $email = DB::table('room_types')->join('hotels','hotels.id','=','room_types.hotel_id')->where('room_types.id',$record->resource_id)->value('hotels.reservation_email');
            } catch (\Throwable $e) {
                $email = null;
            }
            if ($email) return $email;
        }
        $supplier = $record->supplier ?: ($record->item_source ?? null);
        if ($supplier) {
            try {
                $email = DB::table('suppliers')->whereRaw('LOWER(name) = ?', [strtolower($supplier)])->value('email');
            } catch (\Throwable $e) {
                $email = null;
            }
            if ($email) return $email;
            try {
                $email = DB::table('accommodations')->whereRaw('LOWER(name) = ?', [strtolower($supplier)])->value('email');
            } catch (\Throwable $e) {
                $email = null;
            }
            if ($email) return $email;
        }
        return null;
    }
}
