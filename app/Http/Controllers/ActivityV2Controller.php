<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\ActivityPrice;
use App\Models\ActivitySeason;
use App\Models\ActivityTranslation;
use App\Models\PaymentScheme;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ActivityV2Controller extends Controller
{
    public function index(Request $request): View
    {
        $activities = Activity::with(['translations', 'category', 'suppliers'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;
                $q->where(function ($q) use ($s) {
                    $q->where('name', 'like', "%{$s}%")
                        ->orWhere('location', 'like', "%{$s}%")
                        ->orWhere('region', 'like', "%{$s}%")
                        ->orWhere('country', 'like', "%{$s}%")
                        ->orWhere('keywords', 'like', "%{$s}%")
                        ->orWhere('tags', 'like', "%{$s}%")
                        ->orWhereHas('translations', fn($t) => $t->where('title','like',"%{$s}%")->orWhere('description','like',"%{$s}%")->orWhere('location','like',"%{$s}%"))
                        ->orWhereHas('suppliers', fn($q) => $q->where('name', 'like', "%{$s}%"));
                });
            })
            ->when($request->filled('country'), fn($q) => $q->where('country', $request->country))
            ->when($request->filled('status'), fn($q) => $q->where('activity_status', $request->status))
            ->orderBy('country')->orderBy('name')
            ->paginate(20)->withQueryString();

        return view('admin.activities.v2.index', [
            'activities' => $activities,
            'countries' => Activity::distinct()->orderBy('country')->pluck('country'),
        ]);
    }

    public function create(): View
    {
        return view('admin.activities.v2.form', [
            'activity' => null,
            'categories' => ActivityCategory::orderBy('name')->get(),
            'suppliers' => Supplier::where('type', 'activity')->orWhere('type', 'accommodation')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'activity_category_id' => 'nullable|exists:activity_categories,id',
            'country' => 'required|string|max:100',
            'region' => 'nullable|string|max:100',
            'location' => 'required|string|max:255',
            'min_pax' => 'nullable|integer|min:1',
            'min_age' => 'nullable|integer|min:0',
            'duration_hours' => 'nullable|integer|min:1',
            'pickup_time' => 'nullable|string|max:50',
            'currency' => 'required|string|size:3',
            'description' => 'nullable|string',
            'keywords' => 'nullable|string',
            'tags' => 'nullable|string',
        ]);

        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(4);
        $data['activity_status'] = 'active';

        $activity = Activity::create($data);
        ActivityTranslation::updateOrCreate(['activity_id'=>$activity->id,'locale'=>app()->getLocale()],['title'=>$data['name'],'description'=>$data['description'] ?? null,'location'=>$data['location'],'region'=>$data['region'] ?? null]);

        if ($request->filled('suppliers')) {
            $activity->suppliers()->sync($request->suppliers);
        }

        return redirect()->route('admin.activities.edit', $activity)
            ->with('success', 'Activity created. Add translations and pricing.');
    }

    public function show(Activity $activity): View
    {
        $activity->load(['translations', 'category', 'suppliers', 'prices', 'seasons', 'paymentScheme']);
        return view('admin.activities.v2.show', compact('activity'));
    }

    public function edit(Activity $activity): View
    {
        $activity->load(['translations', 'prices', 'seasons', 'suppliers', 'paymentScheme']);
        return view('admin.activities.v2.form', [
            'activity' => $activity,
            'categories' => ActivityCategory::orderBy('name')->get(),
            'suppliers' => Supplier::where('type', 'activity')->orWhere('type', 'accommodation')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Activity $activity): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'activity_category_id' => 'nullable|exists:activity_categories,id',
            'country' => 'required|string|max:100',
            'region' => 'nullable|string|max:100',
            'location' => 'required|string|max:255',
            'min_pax' => 'nullable|integer|min:1',
            'min_age' => 'nullable|integer|min:0',
            'duration_hours' => 'nullable|integer|min:1',
            'pickup_time' => 'nullable|string|max:50',
            'currency' => 'required|string|size:3',
            'price_status_current_year' => 'nullable|string|max:50',
            'price_status_next_year' => 'nullable|string|max:50',
            'payment_scheme_status' => 'nullable|string|max:50',
            'activity_status' => 'required|in:active,inactive',
            'published_on_website' => 'boolean',
            'show_on_mobile_app' => 'boolean',
            'description' => 'nullable|string',
            'keywords' => 'nullable|string',
            'tags' => 'nullable|string',
        ]);

        $data['published_on_website'] = $request->boolean('published_on_website');
        $data['show_on_mobile_app'] = $request->boolean('show_on_mobile_app');

        $activity->update($data);
        ActivityTranslation::updateOrCreate(['activity_id'=>$activity->id,'locale'=>app()->getLocale()],['title'=>$data['name'],'description'=>$data['description'] ?? null,'location'=>$data['location'],'region'=>$data['region'] ?? null]);

        if ($request->has('suppliers')) {
            $activity->suppliers()->sync($request->suppliers ?? []);
        }

        return redirect()->route('admin.activities.edit', $activity)
            ->with('success', 'Activity updated successfully.');
    }

    public function destroy(Activity $activity): RedirectResponse
    {
        $activity->delete(); // soft delete
        return redirect()->route('admin.activities.index')
            ->with('success', 'Activity deleted (soft delete).');
    }

    public function duplicate(Activity $activity): RedirectResponse
    {
        $clone = $activity->replicate();
        $clone->name = $activity->name . ' (Copy)';
        $clone->slug = Str::slug($clone->name) . '-' . Str::random(4);
        $clone->save();

        return redirect()->route('admin.activities.edit', $clone)
            ->with('success', 'Activity duplicated.');
    }

    public function preview(Activity $activity): View
    {
        $activity->load(['translations', 'category', 'prices']);
        return view('admin.activities.v2.preview', compact('activity'));
    }

    public function storeTranslation(Request $request, Activity $activity): RedirectResponse
    {
        $data = $request->validate([
            'locale' => 'required|string|size:2',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:100',
        ]);

        ActivityTranslation::updateOrCreate(
            ['activity_id' => $activity->id, 'locale' => $data['locale']],
            $data
        );

        return back()->with('success', 'Translation saved.');
    }

    public function storePrice(Request $request, Activity $activity): RedirectResponse
    {
        $data = $request->validate([
            'type' => 'required|in:standard,resident,non_resident,child,group',
            'season' => 'required|in:high,low,peak',
            'year' => 'required|integer|min:2024|max:2099',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after_or_equal:valid_from',
        ]);

        $activity->prices()->create($data);

        return back()->with('success', 'Price added.');
    }

    public function destroyPrice(Activity $activity, ActivityPrice $price): RedirectResponse
    {
        $price->delete();
        return back()->with('success', 'Price deleted.');
    }

    public function storeSeason(Request $request, Activity $activity): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|in:high,low,peak',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $activity->seasons()->create($data);

        return back()->with('success', 'Season added.');
    }

    public function destroySeason(Activity $activity, ActivitySeason $season): RedirectResponse
    {
        $season->delete();
        return back()->with('success', 'Season deleted.');
    }

    public function editPaymentScheme(Activity $activity): View
    {
        $scheme = $activity->paymentScheme()->first();
        return view('admin.activities.v2.payment-scheme', compact('activity', 'scheme'));
    }

    public function updatePaymentScheme(Request $request, Activity $activity): RedirectResponse
    {
        $data = $request->validate([
            'deposit_percent' => 'required|numeric|min:0|max:100',
            'full_payment_rules' => 'nullable|string',
            'cancellation_rules' => 'nullable|string',
        ]);

        $activity->paymentScheme()->updateOrCreate(
            ['schemeable_type' => Activity::class, 'schemeable_id' => $activity->id],
            $data
        );

        return back()->with('success', 'Payment scheme updated.');
    }

    public function syncSuppliers(Request $request, Activity $activity): RedirectResponse
    {
        $activity->suppliers()->sync($request->suppliers ?? []);
        return back()->with('success', 'Suppliers synced.');
    }

    public function togglePublish(Activity $activity): RedirectResponse
    {
        $activity->update(['published_on_website' => !$activity->published_on_website]);
        return back()->with('success', $activity->published_on_website ? 'Published.' : 'Unpublished.');
    }
}
