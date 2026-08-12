<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProposalWorkflowService
{
    public function synchronize(): void
    {
        $today = Carbon::today();
        $quotations = DB::table('quotations')->get();

        foreach ($quotations as $quotation) {
            $workflow = DB::table('proposal_workflows')->where('quotation_id', $quotation->id)->first();
            if (! $workflow) {
                DB::table('proposal_workflows')->insert([
                    'quotation_id' => $quotation->id,
                    'seller_id' => DB::table('users')->where('is_active', true)->whereIn('role', ['sales', 'administrator'])->value('id'),
                    'country' => $this->countryFor($quotation),
                    'proposal_type' => $this->typeFor((int) $quotation->id),
                    'client_token' => Str::random(64),
                    'created_at' => now(), 'updated_at' => now(),
                ]);
                $workflow = DB::table('proposal_workflows')->where('quotation_id', $quotation->id)->first();
            }

            if (! $workflow->client_token) {
                DB::table('proposal_workflows')->where('quotation_id', $quotation->id)->update([
                    'client_token' => Str::random(64), 'updated_at' => now(),
                ]);
            }

            $days = DB::table('quotation_days')->where('quotation_id', $quotation->id);
            $dayCount = (clone $days)->count();
            $completeDays = (clone $days)->whereNotNull('description')->where('description', '!=', '')->count();
            if ($dayCount > 0 && $dayCount === $completeDays && ! $workflow->itinerary_completed_at) {
                DB::table('proposal_workflows')->where('quotation_id', $quotation->id)->update([
                    'itinerary_completed_at' => now(), 'updated_at' => now(),
                ]);
            }

            if (in_array($quotation->status, ['confirmed', 'in_progress', 'completed'], true)) {
                $start = Carbon::parse($quotation->start_date)->startOfDay();
                $end = $start->copy()->addDays(max(0, (int) $quotation->duration_days - 1));
                $status = $today->gt($end) ? 'completed' : ($today->gte($start) ? 'in_progress' : 'confirmed');
                if ($status !== $quotation->status) {
                    DB::table('quotations')->where('id', $quotation->id)->update(['status' => $status, 'updated_at' => now()]);
                }
            }
        }
    }

    private function countryFor(object $quotation): string
    {
        $text = strtolower($quotation->start_location.' '.$quotation->title);
        return match (true) {
            str_contains($text, 'kenya'), str_contains($text, 'nairobi'), str_contains($text, 'mara'), str_contains($text, 'naivasha') => 'Kenya',
            str_contains($text, 'uganda'), str_contains($text, 'entebbe'), str_contains($text, 'bwindi') => 'Uganda',
            str_contains($text, 'south africa'), str_contains($text, 'cape town'), str_contains($text, 'kruger') => 'South Africa',
            default => 'Tanzania',
        };
    }

    private function typeFor(int $quotationId): string
    {
        $itemCount = DB::table('quotation_items')->join('quotation_days', 'quotation_days.id', '=', 'quotation_items.quotation_day_id')
            ->where('quotation_days.quotation_id', $quotationId)->count();
        return $itemCount > 0 ? 'Itinerary' : 'Manual';
    }
}
