<?php

namespace App\Http\Controllers;

use App\Models\ContentItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'revenue' => DB::table('proposals')->where('status', 'confirmed')->sum('quoted_amount'),
            'enquiries' => DB::table('website_enquiries')->where('created_at', '>=', now()->startOfMonth())->count(),
            'proposals' => DB::table('proposals')->whereIn('status', ['draft', 'active'])->count(),
            'departures' => DB::table('departures')->where('start_date', '>=', today())->count(),
        ];

        $proposals = DB::table('proposals')
            ->join('travel_requests', 'travel_requests.id', '=', 'proposals.travel_request_id')
            ->join('clients', 'clients.id', '=', 'travel_requests.client_id')
            ->select('proposals.*', 'clients.name as client_name', 'travel_requests.destination')
            ->latest('proposals.updated_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'proposals' => $proposals,
            'tasks' => DB::table('safari_tasks')->where('status', 'pending')->orderBy('due_at')->limit(5)->get(),
            'departures' => DB::table('departures')->where('start_date', '>=', today())->orderBy('start_date')->limit(4)->get(),
            'activities' => DB::table('activity_log')->latest()->limit(6)->get(),
            'monthlySales' => [42, 56, 48, 72, 64, 86, 78, 94, 88, 112, 104, 126],
        ]);
    }

    public function module(string $slug): View
    {
        $requested = Str::of($slug)->replace('-', ' ')->title()->toString();
        $navigation = collect(config('navigation'));
        $match = $navigation->first(fn (array $menu) => Str::slug($menu['label']) === $slug);
        $childMatch = null;

        if (! $match) {
            foreach ($navigation as $menu) {
                $child = collect($menu['children'] ?? [])->first(fn (string $item) => Str::slug($item) === $slug);
                if ($child) {
                    $match = $menu;
                    $childMatch = $child;
                    break;
                }
            }
        }

        $title = $childMatch ?? ($match['label'] ?? $requested);
        $typeMap = [
            'accommodations' => 'accommodation',
            'activities' => 'activity',
            'destination' => 'destination',
            'itineraries' => 'itinerary',
            'marketing' => 'safari_package',
        ];
        $type = $typeMap[Str::slug($match['label'] ?? '')] ?? null;
        $items = $type
            ? ContentItem::with('translations')->where('type', $type)->latest()->limit(8)->get()
            : collect();

        return view('admin.module', compact('title', 'match', 'items', 'type'));
    }

    public function translations(): View
    {
        return view('admin.translations', [
            'items' => ContentItem::with('translations')->orderBy('type')->orderBy('name')->get(),
        ]);
    }
}
