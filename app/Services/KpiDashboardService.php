<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\User;
use App\Models\Quotation;
use App\Models\Reservation;
use App\Models\EvaluationEntry;
use App\Models\ProposalEvaluation;
use App\Models\SupplierInvoice;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class KpiDashboardService
{
    private string $driver;

    public function __construct()
    {
        $this->driver = DB::connection()->getDriverName();
    }

    private function dateDiff(string $d1, string $d2): string
    {
        return $this->driver === 'mysql'
            ? "DATEDIFF({$d1}, {$d2})"
            : "julianday({$d1}) - julianday({$d2})";
    }

    private function monthExpr(string $col): string
    {
        return $this->driver === 'mysql'
            ? "DATE_FORMAT({$col}, '%Y-%m')"
            : "strftime('%Y-%m', {$col})";
    }

    private function dateExpr(string $col): string
    {
        return $this->driver === 'mysql'
            ? "DATE({$col})"
            : "DATE({$col})";
    }

    public function getReservationOfficerKpis(): array
    {
        $officers = User::where('is_active', true)->get();
        $now = now();
        $last7 = now()->subDays(7);
        $diffDays = $this->dateDiff('NOW()', 'created_at');

        return $officers->map(function ($user) use ($last7, $now, $diffDays) {
            $confirmedQuery = Quotation::where('status', 'confirmed');
            $proposals = Quotation::whereIn('status', ['draft', 'active', 'in_progress']);

            $picked7 = (clone $confirmedQuery)->where('updated_at', '>=', $last7)->count();
            $confirmed7 = (clone $confirmedQuery)->where('created_at', '>=', $last7)->count();
            $handling = (clone $proposals)->count();

            $pending2 = Quotation::where('status', 'active')
                ->where('created_at', '<', now()->subDays(2))
                ->where('created_at', '>=', now()->subDays(7))
                ->count();
            $pending7 = Quotation::where('status', 'active')
                ->where('created_at', '<', now()->subDays(7))
                ->count();

            $longestPending = Quotation::whereIn('status', ['draft', 'active'])
                ->orderBy('created_at')
                ->first();

            $cancelled = Quotation::where('status', 'cancelled')->count();

            $expired = Quotation::where('status', 'active')
                ->where('valid_until', '<', $now)
                ->count();

            $avgConfirm = Quotation::where('status', 'confirmed')
                ->whereNotNull('created_at')
                ->select(DB::raw("avg({$this->dateDiff('updated_at', 'created_at')}) as avg_days"))
                ->value('avg_days');

            $avgQuote = Lead::whereNotNull('quotation_sent_at')
                ->select(DB::raw("avg({$this->dateDiff('quotation_sent_at', 'created_at')}) as avg_days"))
                ->value('avg_days');

            $totalProposals = Quotation::count();
            $confirmed = Quotation::where('status', 'confirmed')->count();
            $draft = Quotation::where('status', 'draft')->count();
            $preConfirmed = Quotation::where('status', 'in_progress')->count();

            $completedItineraries = Quotation::where('status', 'completed')->count();
            $conversionRate = $totalProposals > 0 ? round(($confirmed / $totalProposals) * 100, 1) : 0;

            $reservationsCount = Reservation::count();
            $reservationPerf = $reservationsCount > 0
                ? round((Reservation::where('status', 'confirmed')->count() / $reservationsCount) * 100, 1)
                : 0;

            $weeklyNew = (clone $confirmedQuery)->where('created_at', '>=', now()->startOfWeek())->count();
            $monthlyNew = (clone $confirmedQuery)->where('created_at', '>=', now()->startOfMonth())->count();
            $quarterNew = (clone $confirmedQuery)->where('created_at', '>=', now()->startOfQuarter())->count();
            $yearNew = (clone $confirmedQuery)->where('created_at', '>=', now()->startOfYear())->count();

            $companyProd = (float) Quotation::where('status', 'confirmed')->sum('sell_total');
            $resProd = (float) Reservation::sum('amount_due');

            $ratings = $this->calculatePerformanceRankings($conversionRate, $reservationPerf);

            return [
                'name' => $user->name,
                'picked_proposals_7d' => $picked7,
                'confirmed_proposals_7d' => $confirmed7,
                'handling_proposals' => $handling,
                'pending_2d' => $pending2,
                'pending_7d' => $pending7,
                'longest_pending_days' => $longestPending ? now()->diffInDays($longestPending->created_at) : 0,
                'cancelled' => $cancelled,
                'expired' => $expired,
                'avg_confirm_days' => $avgConfirm ? round((float) $avgConfirm, 1) : 0,
                'avg_quote_days' => $avgQuote ? round((float) $avgQuote, 1) : 0,
                'total_proposals' => $totalProposals,
                'confirmed' => $confirmed,
                'pre_confirmed' => $preConfirmed,
                'draft' => $draft,
                'completed_itineraries' => $completedItineraries,
                'conversion_rate' => $conversionRate,
                'reservation_performance' => $reservationPerf,
                'weekly_perf' => $weeklyNew,
                'monthly_perf' => $monthlyNew,
                'quarter_perf' => $quarterNew,
                'year_perf' => $yearNew,
                'company_production' => $companyProd,
                'reservation_production' => $resProd,
                'ratings' => $ratings,
            ];
        })->toArray();
    }

    public function getEvaluationKpis(): array
    {
        $evaluations = ProposalEvaluation::all();
        $todayEvals = ProposalEvaluation::whereDate('created_at', today())->count();
        $pending = ProposalEvaluation::where('status', 'pending')->count();
        $approved = ProposalEvaluation::where('status', 'approved')->count();
        $invoices = SupplierInvoice::count();
        $missingInvoices = EvaluationEntry::where('status', 'missing_invoice')->count();
        $invoiceWaiting = SupplierInvoice::where('status', 'uploaded')->count();
        $overdue = SupplierInvoice::where('status', 'payment_ready')
            ->where('payment_deadline', '<', now())
            ->count();
        $largestVariance = EvaluationEntry::orderByDesc('discrepancy')->first();
        $recentActivity = EvaluationEntry::whereNotNull('evaluated_at')
            ->latest('evaluated_at')
            ->take(10)
            ->get();

        $upcomingPayments = SupplierInvoice::whereIn('status', ['approved', 'payment_ready'])
            ->whereNotNull('payment_deadline')
            ->orderBy('payment_deadline')
            ->take(10)
            ->get();

        $resLeaderboard = User::where('is_active', true)->get()->map(fn($u) => [
            'name' => $u->name,
            'evaluations' => ProposalEvaluation::where('reservation_officer_id', $u->id)->count(),
            'approved' => ProposalEvaluation::where('reservation_officer_id', $u->id)->where('status', 'approved')->count(),
        ])->sortByDesc('approved')->values();

        $supplierLeaderboard = DB::table('supplier_invoices')
            ->select('company_name', DB::raw('count(*) as total'), DB::raw('sum(amount) as total_amount'))
            ->groupBy('company_name')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        return [
            'today' => $todayEvals,
            'pending' => $pending,
            'approved' => $approved,
            'total_invoices' => $invoices,
            'missing_invoices' => $missingInvoices,
            'invoices_waiting' => $invoiceWaiting,
            'overdue_payments' => $overdue,
            'largest_variance' => $largestVariance?->discrepancy ?? 0,
            'largest_variance_entry' => $largestVariance,
            'recent_activity' => $recentActivity,
            'upcoming_payments' => $upcomingPayments,
            'reservation_leaderboard' => $resLeaderboard,
            'supplier_leaderboard' => $supplierLeaderboard,
        ];
    }

    public function getProposalAging(): Collection
    {
        return Quotation::select('id', 'reference', 'title', 'status', 'created_at', 'updated_at')
            ->whereIn('status', ['draft', 'active'])
            ->orderBy('created_at')
            ->get()
            ->map(fn($q) => [
                'id' => $q->id,
                'reference' => $q->reference,
                'title' => $q->title,
                'status' => $q->status,
                'created_at' => $q->created_at,
                'age_days' => now()->diffInDays($q->created_at),
            ]);
    }

    public function getWeeklyTrend(): Collection
    {
        $weeks = collect();
        for ($i = 6; $i >= 0; $i--) {
            $start = now()->subWeeks($i)->startOfWeek();
            $end = now()->subWeeks($i)->endOfWeek();
            $weeks->push([
                'label' => $start->format('M d'),
                'proposals' => Quotation::whereBetween('created_at', [$start, $end])->count(),
                'confirmed' => Quotation::where('status', 'confirmed')->whereBetween('created_at', [$start, $end])->count(),
                'invoices' => SupplierInvoice::whereBetween('created_at', [$start, $end])->count(),
            ]);
        }
        return $weeks;
    }

    public function getMonthlyTrend(): Collection
    {
        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $start = now()->subMonths($i)->startOfMonth();
            $end = now()->subMonths($i)->endOfMonth();
            $months->push([
                'label' => $start->format('M Y'),
                'proposals' => Quotation::whereBetween('created_at', [$start, $end])->count(),
                'confirmed' => Quotation::where('status', 'confirmed')->whereBetween('created_at', [$start, $end])->count(),
                'revenue' => (float) Quotation::where('status', 'confirmed')->whereBetween('created_at', [$start, $end])->sum('sell_total'),
            ]);
        }
        return $months;
    }

    public function getStatusDistribution(): Collection
    {
        return Quotation::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->map(fn($r) => ['label' => $r->status, 'value' => $r->count]);
    }

    public function getInvoiceStatusDistribution(): Collection
    {
        return SupplierInvoice::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->map(fn($r) => ['label' => $r->status, 'value' => $r->count]);
    }

    public function getHeatMapData(): Collection
    {
        $data = collect();
        for ($d = 29; $d >= 0; $d--) {
            $date = now()->subDays($d)->format('Y-m-d');
            $data->push([
                'date' => $date,
                'day' => now()->subDays($d)->format('D'),
                'count' => ProposalEvaluation::whereDate('created_at', $date)->count(),
            ]);
        }
        return $data;
    }

    private function calculatePerformanceRankings(float $conversion, float $perf): array
    {
        $score = ($conversion * 0.5) + ($perf * 0.5);
        $label = match (true) {
            $score >= 80 => 'Excellent',
            $score >= 60 => 'Good',
            $score >= 40 => 'Average',
            $score >= 20 => 'Needs Improvement',
            default => 'Poor',
        };
        return ['score' => round($score, 1), 'label' => $label];
    }
}
