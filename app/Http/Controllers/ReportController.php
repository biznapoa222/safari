<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lead;
use App\Models\Activity;
use App\Models\User;
use App\Services\KpiDashboardService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        protected KpiDashboardService $kpiService,
    ) {}
    private function monthExpr(string $column): string
    {
        return DB::connection()->getDriverName() === 'mysql'
            ? "DATE_FORMAT({$column}, '%Y-%m')"
            : "strftime('%Y-%m', {$column})";
    }

    private function dateDiffExpr(string $d1, string $d2): string
    {
        return DB::connection()->getDriverName() === 'mysql'
            ? "DATEDIFF({$d1}, {$d2})"
            : "julianday({$d1}) - julianday({$d2})";
    }

    public function sales(Request $request): View
    {
        $query = Booking::select(
            DB::raw($this->monthExpr('created_at') . ' as month'),
            DB::raw('count(*) as total_bookings'),
            DB::raw('sum(total_amount) as revenue'),
            DB::raw('sum(amount_paid) as collected')
        )
            ->whereNotIn('status', ['cancelled', 'draft'])
            ->groupBy('month')->orderBy('month');

        if ($request->filled('from')) $query->where('created_at', '>=', $request->from);
        if ($request->filled('to')) $query->where('created_at', '<=', $request->to);

        $salesData = $query->get();

        $byCountry = Booking::select('currency', DB::raw('sum(total_amount) as revenue'))
            ->whereNotIn('status', ['cancelled', 'draft'])
            ->groupBy('currency')->get();

        $byConsultant = User::where('is_active', true)->get()->map(fn($u) => [
            'name' => $u->name,
            'revenue' => (float) Booking::where('assigned_consultant_id', $u->id)
                ->whereNotIn('status', ['cancelled', 'draft'])->sum('total_amount'),
        ])->sortByDesc('revenue');

        return view('admin.reports.sales', compact('salesData', 'byCountry', 'byConsultant'));
    }

    public function bookings(Request $request): View
    {
        $bookings = Booking::with('lead', 'consultant')
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('from'), fn($q) => $q->where('created_at', '>=', $request->from))
            ->when($request->filled('to'), fn($q) => $q->where('created_at', '<=', $request->to))
            ->latest()->paginate(30)->withQueryString();

        $summary = [
            'total' => Booking::count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'pending' => Booking::whereIn('status', ['draft', 'quotation_sent', 'pending_deposit'])->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        return view('admin.reports.bookings', compact('bookings', 'summary'));
    }

    public function suppliers(Request $request): View
    {
        $data = DB::table('suppliers')
            ->select('type', DB::raw('count(*) as total'))
            ->groupBy('type')->get();

        return view('admin.reports.suppliers', compact('data'));
    }

    public function activities(Request $request): View
    {
        $activities = Activity::select('activities.*', DB::raw('count(booking_items.id) as booking_count'))
            ->leftJoin('booking_items', function ($j) {
                $j->on('activities.id', '=', 'booking_items.itemable_id')
                    ->where('booking_items.itemable_type', 'App\\Models\\Activity');
            })
            ->groupBy('activities.id')
            ->orderByDesc('booking_count')
            ->paginate(20);

        return view('admin.reports.activities', compact('activities'));
    }

    public function weekly(Request $request): View
    {
        $weekStart = now()->startOfWeek();

        $data = [
            'week' => $weekStart->format('M d, Y') . ' - ' . now()->endOfWeek()->format('M d, Y'),
            'new_leads' => Lead::where('created_at', '>=', $weekStart)->count(),
            'converted_leads' => Lead::where('updated_at', '>=', $weekStart)->where('status', 'confirmed')->count(),
            'new_bookings' => Booking::where('created_at', '>=', $weekStart)->count(),
            'revenue' => Booking::where('created_at', '>=', $weekStart)->whereNotIn('status', ['cancelled', 'draft'])->sum('total_amount'),
            'collected' => Booking::where('created_at', '>=', $weekStart)->sum('amount_paid'),
            'outstanding' => Booking::whereNotIn('status', ['cancelled'])->where('balance', '>', 0)->sum('balance'),
            'consultant_kpis' => User::where('is_active', true)->get()->map(fn($u) => [
                'name' => $u->name,
                'leads' => $u->leads()->where('created_at', '>=', $weekStart)->count(),
                'converted' => $u->leads()->where('updated_at', '>=', $weekStart)->where('status', 'confirmed')->count(),
                'revenue' => $u->bookings()->where('created_at', '>=', $weekStart)->whereNotIn('status', ['cancelled', 'draft'])->sum('total_amount'),
            ]),
        ];

        return view('admin.reports.weekly', compact('data'));
    }

    public function exportWeekly(string $format)
    {
        $weekStart = now()->startOfWeek();
        $data = [
            'week' => $weekStart->format('Y-m-d') . ' to ' . now()->endOfWeek()->format('Y-m-d'),
            'new_leads' => Lead::where('created_at', '>=', $weekStart)->count(),
            'converted_leads' => Lead::where('updated_at', '>=', $weekStart)->where('status', 'confirmed')->count(),
            'revenue' => Booking::where('created_at', '>=', $weekStart)->whereNotIn('status', ['cancelled', 'draft'])->sum('total_amount'),
        ];

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.weekly-pdf', compact('data'));
            return $pdf->download('weekly-report-' . $weekStart->format('Y-m-d') . '.pdf');
        }

        // CSV export
        return response()->streamDownload(function () use ($data) {
            echo "Week,New Leads,Converted,Revenue\n";
            echo implode(',', [$data['week'], $data['new_leads'], $data['converted_leads'], $data['revenue']]);
        }, 'weekly-report-' . $weekStart->format('Y-m-d') . '.csv');
    }

    public function kpi(): View
    {
        $officerKpis = $this->kpiService->getReservationOfficerKpis();
        $evaluationKpis = $this->kpiService->getEvaluationKpis();
        $weeklyTrend = $this->kpiService->getWeeklyTrend();
        $monthlyTrend = $this->kpiService->getMonthlyTrend();
        $statusDistribution = $this->kpiService->getStatusDistribution();
        $invoiceStatus = $this->kpiService->getInvoiceStatusDistribution();
        $proposalAging = $this->kpiService->getProposalAging();

        return view('admin.reports.kpi', compact(
            'officerKpis', 'evaluationKpis', 'weeklyTrend', 'monthlyTrend',
            'statusDistribution', 'invoiceStatus', 'proposalAging',
        ));
    }
}
