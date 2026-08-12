<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ClientProposalController extends Controller
{
    public function show(string $token): View
    {
        $quotation = DB::table('proposal_workflows')
            ->join('quotations', 'quotations.id', '=', 'proposal_workflows.quotation_id')
            ->join('clients', 'clients.id', '=', 'quotations.client_id')
            ->leftJoin('users', 'users.id', '=', 'proposal_workflows.seller_id')
            ->where('proposal_workflows.client_token', $token)
            ->where('proposal_workflows.client_link_enabled', true)
            ->where(function ($query) {
                $query->whereNull('proposal_workflows.client_link_expires_at')
                    ->orWhere('proposal_workflows.client_link_expires_at', '>', now());
            })
            ->select('quotations.*', 'clients.name as client_name', 'clients.email as client_email',
                'clients.phone as client_phone', 'clients.country as client_country',
                'proposal_workflows.country as workflow_country', 'proposal_workflows.proposal_type',
                'users.name as seller_name')
            ->first();
        abort_unless($quotation, 404);

        $days = DB::table('quotation_days')->where('quotation_id', $quotation->id)->orderBy('day_number')->get();
        foreach ($days as $day) {
            $day->items = DB::table('quotation_items')->where('quotation_day_id', $day->id)->orderBy('id')->get();
        }

        $reservations = DB::table('reservations')->where('quotation_id', $quotation->id)->orderBy('starts_at')->get();
        $roomItems = collect($days)->flatMap(fn ($day) => $day->items)->where('item_type', 'room')->values();
        $totalPaid = (float) DB::table('quotation_payments')->where('quotation_id', $quotation->id)->sum('amount');
        $documents = DB::table('proposal_documents')->where('quotation_id',$quotation->id)->orderBy('category')->get();
        $adjustments = DB::table('proposal_adjustments')->where('quotation_id',$quotation->id)->get();
        $adjustmentNet = $adjustments->whereIn('type',['supplement','surcharge'])->sum(fn($a)=>(float)$a->unit_amount*(float)$a->quantity)
            - $adjustments->where('type','discount')->sum(fn($a)=>(float)$a->unit_amount*(float)$a->quantity);
        $proposalTotal = (float)$quotation->sell_total + $adjustmentNet;

        return view('public.client-proposal', compact('quotation', 'days', 'reservations', 'roomItems', 'totalPaid', 'documents', 'proposalTotal'));
    }

    public function document(string $token, int $document): BinaryFileResponse
    {
        $record = DB::table('proposal_documents')->join('proposal_workflows','proposal_workflows.quotation_id','=','proposal_documents.quotation_id')
            ->where('proposal_workflows.client_token',$token)->where('proposal_workflows.client_link_enabled',true)
            ->where('proposal_documents.id',$document)->select('proposal_documents.*')->first();
        abort_unless($record && Storage::exists($record->path),404);
        return response()->download(Storage::path($record->path),$record->file_name);
    }
}
