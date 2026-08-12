<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lead;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = now()->startOfDay();
        $monthStart = now()->startOfMonth();

        $stats = [
            'today_leads' => Lead::where('created_at', '>=', $today)->count(),
            'today_bookings' => Booking::where('created_at', '>=', $today)->count(),
            'today_revenue' => Booking::where('created_at', '>=', $today)->where('payment_status', 'paid')->sum('total_amount'),
            'month_leads' => Lead::where('created_at', '>=', $monthStart)->count(),
            'month_bookings' => Booking::where('created_at', '>=', $monthStart)->count(),
            'month_revenue' => Booking::where('created_at', '>=', $monthStart)->whereIn('payment_status', ['paid', 'partial'])->sum('total_amount'),
            'month_collected' => Booking::where('created_at', '>=', $monthStart)->sum('amount_paid'),
            'total_leads' => Lead::count(),
            'total_bookings' => Booking::count(),
            'won_leads' => Lead::where('status', 'confirmed')->count(),
            'lost_leads' => Lead::where('status', 'lost')->count(),
        ];

        $stats['conversion_rate'] = $stats['total_leads'] > 0
            ? round(($stats['won_leads'] / $stats['total_leads']) * 100, 1)
            : 0;

        $topConsultants = User::where('is_active', true)
            ->withCount(['leads as leads_assigned', 'leads as leads_converted' => fn($q) => $q->where('status', 'confirmed')])
            ->get()
            ->map(fn($u) => [
                'name' => $u->name,
                'leads_assigned' => $u->leads_assigned,
                'leads_converted' => $u->leads_converted,
                'conversion_rate' => $u->leads_assigned > 0 ? round(($u->leads_converted / $u->leads_assigned) * 100, 1) : 0,
            ])
            ->sortByDesc('leads_converted')
            ->take(5);

        $topActivities = Activity::select('name', DB::raw('count(*) as total'))
            ->join('booking_items', function ($j) {
                $j->on('activities.id', '=', 'booking_items.itemable_id')
                    ->where('booking_items.itemable_type', 'App\\Models\\Activity');
            })
            ->groupBy('activities.id', 'activities.name')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $driver = DB::connection()->getDriverName();
        $monthExpr = $driver === 'mysql'
            ? "DATE_FORMAT(created_at, '%Y-%m')"
            : "strftime('%Y-%m', created_at)";

        $monthlyRevenue = Booking::select(
            DB::raw("{$monthExpr} as month"),
            DB::raw('sum(total_amount) as revenue'),
            DB::raw('sum(amount_paid) as collected')
        )
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard-v2', compact('stats', 'topConsultants', 'topActivities', 'monthlyRevenue'));
    }
}
