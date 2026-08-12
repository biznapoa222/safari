<?php

namespace App\Http\Controllers;

use App\Models\ItineraryTemplate;
use App\Models\ProposalTemplateSetting;
use App\Models\TemplateDay;
use App\Models\TemplateDayActivity;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItineraryTemplateController extends Controller
{
    public function index(Request $request)
    {
        $query = ItineraryTemplate::query()->with('destination')->withCount('days');

        if ($search = $request->get('search')) {
            $like = '%'.$search.'%';
            $query->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                  ->orWhere('trip_name', 'like', $like)
                  ->orWhereHas('destination', function ($dq) use ($like) {
                      $dq->where('name', 'like', $like)->orWhere('country', 'like', $like);
                  });
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        $perPage = (int) $request->get('per_page', 20);
        $templates = $query->latest()->paginate($perPage)->withQueryString();

        if ($request->wantsJson() || $request->boolean('ajax')) {
            $html = view('admin.itinerary-templates.partials._table_rows', compact('templates'))->render();
            $pagination = $templates->hasPages() ? (string) $templates->links() : '';

            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'count' => $templates->count(),
                'total' => $templates->total(),
            ]);
        }

        return view('admin.itinerary-templates.index', compact('templates'));
    }

    public function create(): View
    {
        $destinations = Destination::where('status', 1)->orderBy('name')->get();
        $countries = $this->countryOptions();
        $categories = ItineraryTemplate::categories();
        $statuses = ['active' => 'Active', 'inactive' => 'Inactive'];
        return view('admin.itinerary-templates.create', compact('destinations', 'countries', 'categories', 'statuses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'trip_name' => 'nullable|string|max:255',
            'destination_id' => 'nullable|exists:destinations,id',
            'duration_days' => 'required|integer|min:1',
            'category' => 'nullable|string|max:255',
            'overview' => 'nullable|string',
            'highlights' => 'nullable|string',
            'includes' => 'nullable|string',
            'excludes' => 'nullable|string',
            'terms' => 'nullable|string',
            'booking_terms' => 'nullable|string',
            'payment_schedule' => 'nullable|string',
            'cancellation_policy' => 'nullable|string',
            'refund_policy' => 'nullable|string',
            'important_notes' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive,archived',
            'days' => 'nullable|array',
            'days.*.day_number' => 'required|integer|min:1',
            'days.*.title' => 'nullable|string|max:255',
            'days.*.destination_id' => 'nullable|exists:destinations,id',
            'days.*.hotel_id' => 'nullable|exists:hotels,id',
            'days.*.hotel_name' => 'nullable|string|max:255',
            'days.*.room_type' => 'nullable|string|max:255',
            'days.*.meal_plan' => 'nullable|string|max:255',
            'days.*.description' => 'nullable|string',
            'days.*.morning_activity' => 'nullable|string',
            'days.*.afternoon_activity' => 'nullable|string',
            'days.*.evening_activity' => 'nullable|string',
            'days.*.included_services' => 'nullable|string',
            'days.*.optional_activities' => 'nullable|string',
            'days.*.activities' => 'nullable|array',
            'days.*.activities.*.activity_id' => 'nullable|exists:activities,id',
            'days.*.activities.*.activity_name' => 'nullable|string|max:255',
            'days.*.activities.*.description' => 'nullable|string',
            'days.*.activities.*.start_time' => 'nullable|string',
            'days.*.activities.*.end_time' => 'nullable|string',
            'days.*.activities.*.is_included' => 'nullable|boolean',
        ]);

        $template = ItineraryTemplate::create([
            'name' => $validated['name'],
            'trip_name' => $validated['trip_name'] ?? null,
            'destination_id' => $validated['destination_id'] ?? null,
            'duration_days' => $validated['duration_days'],
            'category' => $validated['category'] ?? null,
            'overview' => $validated['overview'] ?? null,
            'highlights' => $validated['highlights'] ?? null,
            'includes' => $validated['includes'] ?? null,
            'excludes' => $validated['excludes'] ?? null,
            'terms' => $validated['terms'] ?? null,
            'booking_terms' => $validated['booking_terms'] ?? null,
            'payment_schedule' => $validated['payment_schedule'] ?? null,
            'cancellation_policy' => $validated['cancellation_policy'] ?? null,
            'refund_policy' => $validated['refund_policy'] ?? null,
            'important_notes' => $validated['important_notes'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => $validated['status'] ?? 'active',
        ]);

        if (!empty($validated['days'])) {
            foreach ($validated['days'] as $dayData) {
                $day = TemplateDay::create([
                    'itinerary_template_id' => $template->id,
                    'day_number' => $dayData['day_number'],
                    'title' => $dayData['title'] ?? null,
                    'destination_id' => $dayData['destination_id'] ?? null,
                    'hotel_id' => $dayData['hotel_id'] ?? null,
                    'hotel_name' => $dayData['hotel_name'] ?? null,
                    'room_type' => $dayData['room_type'] ?? null,
                    'meal_plan' => $dayData['meal_plan'] ?? null,
                    'description' => $dayData['description'] ?? null,
                    'morning_activity' => $dayData['morning_activity'] ?? null,
                    'afternoon_activity' => $dayData['afternoon_activity'] ?? null,
                    'evening_activity' => $dayData['evening_activity'] ?? null,
                    'included_services' => $dayData['included_services'] ?? null,
                    'optional_activities' => $dayData['optional_activities'] ?? null,
                ]);

                if (!empty($dayData['activities'])) {
                    foreach ($dayData['activities'] as $actData) {
                        TemplateDayActivity::create([
                            'template_day_id' => $day->id,
                            'activity_id' => $actData['activity_id'] ?? null,
                            'activity_name' => $actData['activity_name'] ?? null,
                            'description' => $actData['description'] ?? null,
                            'start_time' => $actData['start_time'] ?? null,
                            'end_time' => $actData['end_time'] ?? null,
                            'is_included' => $actData['is_included'] ?? true,
                            'sort_order' => $actData['sort_order'] ?? 0,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.itinerary-templates.show', $template)
            ->with('success', 'Itinerary template created successfully.');
    }

    public function show(int $id): View
    {
        $template = ItineraryTemplate::with(['days.activities', 'days.destination', 'days.hotel', 'destination', 'pricing'])->findOrFail($id);
        $categories = ItineraryTemplate::categories();
        return view('admin.itinerary-templates.show', compact('template', 'categories'));
    }

    public function edit(int $id): View
    {
        $template = ItineraryTemplate::with(['days.activities', 'days.destination', 'days.hotel', 'destination', 'pricing'])->findOrFail($id);
        $destinations = Destination::where('status', 1)->orderBy('name')->get();
        $countries = $this->countryOptions();
        $categories = ItineraryTemplate::categories();
        $statuses = ['active' => 'Active', 'inactive' => 'Inactive'];
        return view('admin.itinerary-templates.edit', compact('template', 'destinations', 'countries', 'categories', 'statuses'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $template = ItineraryTemplate::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'trip_name' => 'nullable|string|max:255',
            'destination_id' => 'nullable|exists:destinations,id',
            'duration_days' => 'required|integer|min:1',
            'category' => 'nullable|string|max:255',
            'overview' => 'nullable|string',
            'highlights' => 'nullable|string',
            'includes' => 'nullable|string',
            'excludes' => 'nullable|string',
            'terms' => 'nullable|string',
            'booking_terms' => 'nullable|string',
            'payment_schedule' => 'nullable|string',
            'cancellation_policy' => 'nullable|string',
            'refund_policy' => 'nullable|string',
            'important_notes' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive,archived',
            'days' => 'nullable|array',
            'days.*.day_number' => 'required|integer|min:1',
            'days.*.title' => 'nullable|string|max:255',
            'days.*.destination_id' => 'nullable|exists:destinations,id',
            'days.*.hotel_id' => 'nullable|exists:hotels,id',
            'days.*.hotel_name' => 'nullable|string|max:255',
            'days.*.room_type' => 'nullable|string|max:255',
            'days.*.meal_plan' => 'nullable|string|max:255',
            'days.*.description' => 'nullable|string',
            'days.*.morning_activity' => 'nullable|string',
            'days.*.afternoon_activity' => 'nullable|string',
            'days.*.evening_activity' => 'nullable|string',
            'days.*.included_services' => 'nullable|string',
            'days.*.optional_activities' => 'nullable|string',
            'days.*.activities' => 'nullable|array',
            'days.*.activities.*.activity_id' => 'nullable|exists:activities,id',
            'days.*.activities.*.activity_name' => 'nullable|string|max:255',
            'days.*.activities.*.description' => 'nullable|string',
            'days.*.activities.*.start_time' => 'nullable|string',
            'days.*.activities.*.end_time' => 'nullable|string',
            'days.*.activities.*.is_included' => 'nullable|boolean',
        ]);

        $template->update([
            'name' => $validated['name'],
            'trip_name' => $validated['trip_name'] ?? null,
            'destination_id' => $validated['destination_id'] ?? null,
            'duration_days' => $validated['duration_days'],
            'category' => $validated['category'] ?? null,
            'overview' => $validated['overview'] ?? null,
            'highlights' => $validated['highlights'] ?? null,
            'includes' => $validated['includes'] ?? null,
            'excludes' => $validated['excludes'] ?? null,
            'terms' => $validated['terms'] ?? null,
            'booking_terms' => $validated['booking_terms'] ?? null,
            'payment_schedule' => $validated['payment_schedule'] ?? null,
            'cancellation_policy' => $validated['cancellation_policy'] ?? null,
            'refund_policy' => $validated['refund_policy'] ?? null,
            'important_notes' => $validated['important_notes'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => $validated['status'] ?? 'active',
        ]);

        // Sync days
        $template->days()->delete();

        if (!empty($validated['days'])) {
            foreach ($validated['days'] as $dayData) {
                $day = TemplateDay::create([
                    'itinerary_template_id' => $template->id,
                    'day_number' => $dayData['day_number'],
                    'title' => $dayData['title'] ?? null,
                    'destination_id' => $dayData['destination_id'] ?? null,
                    'hotel_id' => $dayData['hotel_id'] ?? null,
                    'hotel_name' => $dayData['hotel_name'] ?? null,
                    'room_type' => $dayData['room_type'] ?? null,
                    'meal_plan' => $dayData['meal_plan'] ?? null,
                    'description' => $dayData['description'] ?? null,
                    'morning_activity' => $dayData['morning_activity'] ?? null,
                    'afternoon_activity' => $dayData['afternoon_activity'] ?? null,
                    'evening_activity' => $dayData['evening_activity'] ?? null,
                    'included_services' => $dayData['included_services'] ?? null,
                    'optional_activities' => $dayData['optional_activities'] ?? null,
                ]);

                if (!empty($dayData['activities'])) {
                    foreach ($dayData['activities'] as $actData) {
                        TemplateDayActivity::create([
                            'template_day_id' => $day->id,
                            'activity_id' => $actData['activity_id'] ?? null,
                            'activity_name' => $actData['activity_name'] ?? null,
                            'description' => $actData['description'] ?? null,
                            'start_time' => $actData['start_time'] ?? null,
                            'end_time' => $actData['end_time'] ?? null,
                            'is_included' => $actData['is_included'] ?? true,
                            'sort_order' => $actData['sort_order'] ?? 0,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.itinerary-templates.show', $template)
            ->with('success', 'Itinerary template updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $template = ItineraryTemplate::findOrFail($id);
        $template->delete();
        return redirect()->route('admin.itinerary-templates.index')
            ->with('success', 'Itinerary template deleted successfully.');
    }

    public function restore(int $id): RedirectResponse
    {
        $template = ItineraryTemplate::withTrashed()->findOrFail($id);
        $template->restore();
        return redirect()->route('admin.itinerary-templates.show', $template)
            ->with('success', 'Itinerary template restored successfully.');
    }

    public function duplicate(int $id): RedirectResponse
    {
        $template = ItineraryTemplate::with('days.activities')->findOrFail($id);

        $copy = $template->replicate();
        $copy->name = $template->name . ' (Copy)';
        $copy->save();

        foreach ($template->days as $day) {
            $dayCopy = $day->replicate();
            $dayCopy->itinerary_template_id = $copy->id;
            $dayCopy->save();

            foreach ($day->activities as $act) {
                $actCopy = $act->replicate();
                $actCopy->template_day_id = $dayCopy->id;
                $actCopy->save();
            }
        }

        return redirect()->route('admin.itinerary-templates.show', $copy)
            ->with('success', 'Itinerary template duplicated successfully.');
    }

    public function search(Request $request): JsonResponse
    {
        $term = $request->get('term');
        $results = ItineraryTemplate::where('status', '!=', 'archived')
            ->when($term, function ($q) use ($term) {
                $q->where(function ($query) use ($term) {
                    $query->where('name', 'like', "%{$term}%")
                          ->orWhere('trip_name', 'like', "%{$term}%");
                });
            })
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'trip_name', 'destination_id', 'duration_days', 'category']);

        return response()->json($results);
    }

    public function getDays(int $id): JsonResponse
    {
        $template = ItineraryTemplate::with(['days' => function ($q) {
            $q->orderBy('day_number');
        }, 'days.destination', 'days.hotel', 'days.activities'])->findOrFail($id);

        return response()->json($template->days);
    }

    public function destinations(): JsonResponse
    {
        return response()->json(Destination::where('status', 1)->orderBy('name')->get(['id', 'name', 'country']));
    }

    public function hotels(Request $request): JsonResponse
    {
        $query = Hotel::where('status', 1)->orderBy('name');
        if ($destinationId = $request->get('destination_id')) {
            $country = Destination::where('id', $destinationId)->value('country');
            if ($country) {
                $destinationIds = Destination::where('country', $country)->pluck('id');
                $query->whereIn('destination_id', $destinationIds);
            } else {
                $query->where('destination_id', $destinationId);
            }
        }
        return response()->json($query->get(['id', 'name', 'destination_id', 'star_rating', 'tier', 'meal_plan']));
    }

    public function activities(Request $request): JsonResponse
    {
        $query = Activity::where('status', 1)->orderBy('name');
        if ($destinationId = $request->get('destination_id')) {
            $query->where('destination_id', $destinationId);
        }
        return response()->json($query->get(['id', 'name', 'destination_id', 'duration', 'is_included', 'price']));
    }

    private function countryOptions()
    {
        return Destination::where('status', 1)
            ->whereNotNull('country')
            ->orderBy('country')
            ->orderBy('name')
            ->get(['id', 'name', 'country'])
            ->groupBy('country')
            ->map(function ($items, $country) {
                return (object) [
                    'id' => $items->first()->id,
                    'country' => $country,
                    'destinations_count' => $items->count(),
                ];
            })
            ->values();
    }

    public function preview(int $id): View
    {
        $template = ItineraryTemplate::with(['days.activities', 'days.destination', 'days.hotel', 'destination', 'pricing'])->findOrFail($id);
        $categories = ItineraryTemplate::categories();
        return view('admin.itinerary-templates.preview', compact('template', 'categories'));
    }

    public function generatePdf(int $id)
    {
        $template = ItineraryTemplate::with(['days.activities', 'days.destination', 'days.hotel', 'destination', 'pricing'])->findOrFail($id);
        $categories = ItineraryTemplate::categories();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.itinerary', compact('template', 'categories'));
        $pdf->setPaper('a4');

        $filename = \Illuminate\Support\Str::slug($template->name) . '-itinerary.pdf';
        return $pdf->download($filename);
    }

    public function previewProposal(int $id): View
    {
        $template = ItineraryTemplate::with(['days.destination', 'days.hotel', 'days.activities', 'pricing'])->findOrFail($id);
        $settings = ProposalTemplateSetting::firstOrCreate(['itinerary_template_id' => $id]);

        return view('itinerary-templates.luxury-dark.proposal', [
            'version' => null,
            'proposal' => $template,
            'template' => $template,
            'settings' => $settings->settings ?? [],
            'agency' => (object) [
                'name' => 'Shishi Footsteps',
                'tagline' => 'Luxury African Safari Experiences',
                'logo' => asset('images/brand/shishi-paw-white.png'),
                'email' => 'info@shishifootsteps.com',
                'phone' => '+254 700 000 000',
            ],
            'pdf' => false,
        ]);
    }

    public function generateProposalPdf(int $id)
    {
        $template = ItineraryTemplate::with(['days.destination', 'days.hotel', 'days.activities', 'pricing'])->findOrFail($id);
        $settings = ProposalTemplateSetting::firstOrCreate(['itinerary_template_id' => $id]);

        $html = view('itinerary-templates.luxury-dark.proposal', [
            'version' => null,
            'proposal' => $template,
            'template' => $template,
            'settings' => $settings->settings ?? [],
            'agency' => (object) [
                'name' => 'Shishi Footsteps',
                'tagline' => 'Luxury African Safari Experiences',
                'logo' => asset('images/brand/shishi-paw-white.png'),
                'email' => 'info@shishifootsteps.com',
                'phone' => '+254 700 000 000',
            ],
            'pdf' => true,
        ])->render();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);

        return $pdf->download('safari-proposal.pdf');
    }
}
