<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class QuotationPricingService
{
    public function recalculate(int $quotationId): void
    {
        $totals = DB::table('quotation_items')
            ->join('quotation_days', 'quotation_days.id', '=', 'quotation_items.quotation_day_id')
            ->where('quotation_days.quotation_id', $quotationId)
            ->selectRaw('COALESCE(SUM(quotation_items.buy_total), 0) as buy_total, COALESCE(SUM(quotation_items.sell_total), 0) as sell_total')
            ->first();

        DB::table('quotations')->where('id', $quotationId)->update([
            'buy_total' => $totals->buy_total,
            'sell_total' => $totals->sell_total,
            'margin_total' => $totals->sell_total - $totals->buy_total,
            'updated_at' => now(),
        ]);
    }

    public function sellingPrice(float $buyRate, float $markupPercent): float
    {
        return round($buyRate * (1 + ($markupPercent / 100)), 2);
    }
}
