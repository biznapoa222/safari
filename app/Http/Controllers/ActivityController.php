<?php

namespace App\Http\Controllers;

use App\Services\QuotationPricingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.activities.index', [
            'activities' => DB::table('tour_activities')
                ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%'.$request->search.'%'))
                ->orderBy('country')->orderBy('name')->paginate(20)->withQueryString(),
            'editing' => $request->filled('edit') ? DB::table('tour_activities')->find($request->integer('edit')) : null,
        ]);
    }

    public function store(Request $request, QuotationPricingService $pricing): RedirectResponse
    {
        $data = $this->validated($request);
        DB::table('tour_activities')->insert([
            ...$data,
            'sell_rate' => $pricing->sellingPrice((float) $data['buy_rate'], (float) $data['markup_percent']),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        return back()->with('success', 'Activity created with its marked-up selling rate.');
    }

    public function update(Request $request, int $activity, QuotationPricingService $pricing): RedirectResponse
    {
        $data = $this->validated($request);
        DB::table('tour_activities')->where('id', $activity)->update([
            ...$data,
            'sell_rate' => $pricing->sellingPrice((float) $data['buy_rate'], (float) $data['markup_percent']),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.activities.index')->with('success', 'Activity updated.');
    }

    public function destroy(int $activity): RedirectResponse
    {
        DB::table('tour_activities')->where('id', $activity)->delete();

        return back()->with('success', 'Activity deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'category' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:120'],
            'supplier' => ['nullable', 'string', 'max:180'],
            'calculation_type' => ['required', 'in:per_person,per_vehicle,per_group'],
            'buy_rate' => ['required', 'numeric', 'min:0'],
            'markup_percent' => ['required', 'numeric', 'min:0', 'max:500'],
            'currency' => ['required', 'string', 'size:3'],
            'daily_capacity' => ['nullable', 'integer', 'min:1', 'max:5000'],
            'duration_hours' => ['required', 'integer', 'min:1', 'max:24'],
            'status' => ['required', 'in:active,inactive'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
    }
}
