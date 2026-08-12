<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\AccommodationRoom;
use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\CmsPage;
use App\Models\ContentItem;
use App\Models\Country;
use App\Models\ItineraryTemplate;
use App\Models\ItineraryV2;
use App\Models\Lead;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use App\Models\WebsiteSetting;
use App\Notifications\WebsiteInquiryReceived;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class PublicController extends Controller
{
    public function home(): View
    {
        $settings = WebsiteSetting::home();
        $destinations = $this->featuredDestinations($settings);
        $activities = $this->featuredActivities($settings);
        $packages = $this->featuredSafaris($settings);
        $featuredAccommodations = Accommodation::where('published', true)->orderByDesc('featured')->limit(3)->get();
        $blogPosts = CmsPage::where('type', 'blog')->where('published', true)->latest()->limit(3)->get();

        return view('public.home', compact('settings', 'packages', 'destinations', 'activities', 'featuredAccommodations', 'blogPosts'));
    }

    public function destinations(): View
    {
        $settings = WebsiteSetting::home();
        $destinations = Country::with('regions')->where('is_active', true)->orderBy('name')->get();

        return view('public.destinations', compact('settings', 'destinations'));
    }

    public function safaris(Request $request)
    {
        $settings = WebsiteSetting::home();
        $search = trim((string) $request->get('search'));
        $country = $request->get('country');

        $v2safaris = ItineraryV2::where('published', true)
            ->when($country, fn($q) => $q->where('country', $country))
            ->when($search, function ($q) use ($search) {
                $like = '%'.$search.'%';
                $q->where(function ($qq) use ($like) {
                    $qq->where('title', 'like', $like)
                       ->orWhere('summary', 'like', $like)
                       ->orWhere('country', 'like', $like);
                });
            })
            ->orderBy('featured', 'desc')
            ->get();

        $templates = ItineraryTemplate::with(['days', 'destination', 'pricing'])
            ->where('status', 'active')
            ->get()
            ->filter(function ($t) use ($country, $search) {
                if ($country && ($t->destination?->country !== $country)) return false;
                if ($search) {
                    $like = stripos($t->name.' '.$t->trip_name.' '.($t->destination?->name ?? '').' '.($t->destination?->country ?? ''), $search);
                    if ($like === false) return false;
                }
                return true;
            })
            ->map(fn ($t) => $this->templateToItinerary($t));

        $all = collect()->merge($v2safaris)->merge($templates);

        $page = (int) $request->get('page', 1);
        $perPage = 12;
        $safaris = new LengthAwarePaginator(
            $all->forPage($page, $perPage)->values(),
            $all->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        if ($request->wantsJson() || $request->boolean('ajax')) {
            $html = view('public.partials._safari_grid', compact('safaris'))->render();
            return response()->json([
                'html' => $html,
                'count' => $safaris->count(),
                'total' => $all->count(),
            ]);
        }

        return view('public.safaris', compact('settings', 'safaris'));
    }

    public function destinationShow(string $slug): View
    {
        $names=['kenya'=>'Kenya','tanzania'=>'Tanzania','uganda'=>'Uganda','rwanda'=>'Rwanda','south-africa'=>'South Africa','namibia'=>'Namibia','botswana'=>'Botswana'];
        abort_unless(isset($names[$slug]),404); $name=$names[$slug];
        $destination=Country::with('regions')->where('slug',$slug)->orWhere('name',$name)->first() ?? (object)['name'=>$name,'slug'=>$slug,'description'=>null,'regions'=>collect()];
        $v2safaris=ItineraryV2::where('published',true)->where('country',$name)->orderByDesc('featured')->get();
        $templateSafaris = ItineraryTemplate::with(['days', 'destination', 'pricing'])
            ->where('status', 'active')
            ->get()
            ->filter(fn ($t) => $t->destination?->country === $name)
            ->map(fn ($t) => $this->templateToItinerary($t));
        $safaris = $v2safaris->concat($templateSafaris)->take(8);
        $activities=Activity::with('translations')->where('published_on_website',true)->where('country',$name)->limit(6)->get();
        $accommodations=Accommodation::where('published',true)->where('country',$name)->limit(6)->get();
        $settings=WebsiteSetting::home();
        return view('public.destination-show',compact('settings','destination','safaris','activities','accommodations','name','slug'));
    }

    public function destinationSection(string $slug, string $section): View
    {
        $names = ['kenya' => 'Kenya', 'tanzania' => 'Tanzania', 'uganda' => 'Uganda', 'rwanda' => 'Rwanda', 'south-africa' => 'South Africa', 'namibia' => 'Namibia', 'botswana' => 'Botswana'];
        abort_unless(isset($names[$slug]), 404);

        $name = $names[$slug];
        $sections = $this->destinationSectionLibrary($slug, $name);
        abort_unless(isset($sections[$section]), 404);

        $settings = WebsiteSetting::home();
        $destination = Country::with('regions')->where('slug', $slug)->orWhere('name', $name)->first();
        $v2safaris = ItineraryV2::where('published', true)->where('country', $name)->orderByDesc('featured')->get();
        $templateSafaris = ItineraryTemplate::with(['days', 'destination', 'pricing'])
            ->where('status', 'active')
            ->get()
            ->filter(fn ($t) => $t->destination?->country === $name)
            ->map(fn ($t) => $this->templateToItinerary($t));
        $safaris = $v2safaris->concat($templateSafaris)->take(3);
        $activities = Activity::with('translations')->where('published_on_website', true)->where('country', $name)->limit(3)->get();
        $accommodations = Accommodation::where('published', true)->where('country', $name)->limit(3)->get();
        $sectionData = $sections[$section];

        return view('public.destination-section', compact('settings', 'destination', 'name', 'slug', 'section', 'sections', 'sectionData', 'safaris', 'activities', 'accommodations'));
    }

    public function accommodations(): View
    {
        $accommodations = Accommodation::where('published', true)->orderBy('featured', 'desc')->paginate(12);
        $settings = WebsiteSetting::home();

        return view('public.accommodations', compact('settings', 'accommodations'));
    }

    public function experiences(): View
    {
        $activities = Activity::with('translations')->where('published_on_website', true)->paginate(12);
        $settings = WebsiteSetting::home();

        return view('public.experiences', compact('settings', 'activities'));
    }

    public function itineraries(Request $request)
    {
        $settings = WebsiteSetting::home();
        $search = trim((string) $request->get('search'));
        $country = $request->get('country');

        $v2safaris = ItineraryV2::where('published', true)
            ->with('days')
            ->when($country, fn($q) => $q->where('country', $country))
            ->when($search, function ($q) use ($search) {
                $like = '%'.$search.'%';
                $q->where(function ($qq) use ($like) {
                    $qq->where('title', 'like', $like)
                       ->orWhere('summary', 'like', $like)
                       ->orWhere('country', 'like', $like);
                });
            })
            ->orderBy('featured', 'desc')
            ->get();

        $templates = ItineraryTemplate::with(['days', 'destination', 'pricing'])
            ->where('status', 'active')
            ->get()
            ->filter(function ($t) use ($country, $search) {
                if ($country && ($t->destination?->country !== $country)) return false;
                if ($search) {
                    $haystack = strtolower($t->name.' '.$t->trip_name.' '.($t->destination?->name ?? '').' '.($t->destination?->country ?? ''));
                    if (strpos($haystack, strtolower($search)) === false) return false;
                }
                return true;
            })
            ->map(fn ($t) => $this->templateToItinerary($t));

        $all = collect()->merge($v2safaris)->merge($templates);

        $page = (int) $request->get('page', 1);
        $perPage = 9;
        $safaris = new LengthAwarePaginator(
            $all->forPage($page, $perPage)->values(),
            $all->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        if ($request->wantsJson() || $request->boolean('ajax')) {
            $html = view('public.partials._itinerary_rows', compact('safaris'))->render();
            return response()->json([
                'html' => $html,
                'count' => $safaris->count(),
                'total' => $all->count(),
            ]);
        }

        return view('public.itineraries', compact('settings', 'safaris'));
    }

    public function itineraryShow(string $slug): View
    {
        $settings = WebsiteSetting::home();

        $templateId = null;
        $itinerary = ItineraryV2::where('slug', $slug)->where('published', true)->with('days')->first();

        if (! $itinerary) {
            $template = ItineraryTemplate::with(['days', 'destination', 'pricing'])
                ->where('status', 'active')
                ->get()
                ->first(fn ($t) => Str::slug($t->name) === $slug || Str::slug($t->trip_name ?? '') === $slug);

            abort_unless($template, 404);

            $templateId = $template->id;
            $itinerary = $this->templateToItinerary($template);
        }

        $related = collect();

        if ($templateId) {
            $related = ItineraryV2::where('published', true)->limit(3)->get();
            if ($related->count() < 3) {
                $templateRelated = ItineraryTemplate::with('destination')
                    ->where('status', 'active')->where('id', '!=', $templateId)
                    ->get()->map(fn ($t) => $this->templateToItinerary($t))
                    ->take(3 - $related->count());
                $related = $related->concat($templateRelated);
            }
        } else {
            $related = ItineraryV2::where('published', true)->where('id', '!=', $itinerary->id)->limit(3)->get();
            if ($related->count() < 3) {
                $templateRelated = ItineraryTemplate::with('destination')
                    ->where('status', 'active')
                    ->get()->map(fn ($t) => $this->templateToItinerary($t))
                    ->take(3 - $related->count());
                $related = $related->concat($templateRelated);
            }
        }

        return view('public.itinerary-show', compact('settings', 'itinerary', 'related'));
    }

    private function templateToItinerary(ItineraryTemplate $template): object
    {
        $firstPrice = $template->pricing->first();

        $templateDays = $template->days->map(fn ($d) => (object) [
            'day_number' => $d->day_number,
            'title' => $d->title ?? 'Day '.$d->day_number,
            'location' => $d->hotel_name,
            'activities' => $d->description,
            'meal_plan' => $d->meal_plan,
            'transfers' => $d->included_services,
            'notes' => $d->optional_activities,
            'morning_activity' => $d->morning_activity,
            'afternoon_activity' => $d->afternoon_activity,
            'evening_activity' => $d->evening_activity,
        ]);

        $includes = $template->includes ? array_filter(explode("\n", $template->includes)) : [];
        $excludes = $template->excludes ? array_filter(explode("\n", $template->excludes)) : [];

        return (object) [
            'id' => $template->id,
            'title' => $template->trip_name ?? $template->name,
            'slug' => Str::slug($template->name),
            'summary' => $template->overview,
            'duration_days' => $template->duration_days,
            'country' => $template->destination?->country,
            'region' => null,
            'price_from' => $firstPrice?->price_per_person,
            'images' => $template->images ?? [],
            'inclusions' => $includes,
            'exclusions' => $excludes,
            'days' => $templateDays,
            'template' => true,
            'destination' => $template->destination,
        ];
    }

    public function golf(): View
    {
        $settings = WebsiteSetting::home();

        return view('public.golf', compact('settings'));
    }

    public function teeOffCountry(string $country): View
    {
        $countries = [
            'kenya' => 'Kenya',
            'tanzania' => 'Tanzania',
            'uganda' => 'Uganda',
            'rwanda' => 'Rwanda',
            'south-africa' => 'South Africa',
        ];

        abort_unless(isset($countries[$country]), 404);

        $settings = WebsiteSetting::home();
        $countryName = $countries[$country];
        $countrySlug = $country;

        return view('public.golf', compact('settings', 'countryName', 'countrySlug'));
    }

    public function about(): View
    {
        $settings = WebsiteSetting::home();

        return view('public.about', compact('settings'));
    }

    public function faqs(): View
    {
        $settings = WebsiteSetting::home();

        return view('public.faqs', compact('settings'));
    }

    public function contact(): View
    {
        $settings = WebsiteSetting::home();
        $destinations = $this->featuredDestinations($settings);

        return view('public.contact', compact('settings', 'destinations'));
    }

    public function blog(): View
    {
        $posts = CmsPage::where('type', 'blog')->where('published', true)->latest()->paginate(9);
        $settings = WebsiteSetting::home();

        return view('public.blog', compact('settings', 'posts'));
    }

    public function blogPost(string $slug): View
    {
        $post = CmsPage::where('slug', $slug)->where('type', 'blog')->firstOrFail();
        $settings = WebsiteSetting::home();

        return view('public.blog-post', compact('settings', 'post'));
    }

    public function safariShow(string $slug): View
    {
        $settings = WebsiteSetting::home();
        $safari = ItineraryV2::where('slug', $slug)->where('published', true)->with('days')->first();

        if (! $safari) {
            $template = ItineraryTemplate::with(['days', 'destination', 'pricing'])
                ->where('status', 'active')
                ->get()
                ->first(fn ($t) => Str::slug($t->name) === $slug || Str::slug($t->trip_name ?? '') === $slug);

            abort_unless($template, 404);

            $safari = $this->templateToItinerary($template);
        }

        $templateId = isset($template) ? $template->id : null;
        $related = collect();

        if ($templateId) {
            $related = ItineraryV2::where('published', true)->limit(3)->get();
            if ($related->count() < 3) {
                $templateRelated = ItineraryTemplate::with('destination')
                    ->where('status', 'active')->where('id', '!=', $templateId)
                    ->get()->map(fn ($t) => $this->templateToItinerary($t))
                    ->take(3 - $related->count());
                $related = $related->concat($templateRelated);
            }
        } else {
            $related = ItineraryV2::where('published', true)->where('id', '!=', $safari->id)->limit(3)->get();
            if ($related->count() < 3) {
                $templateRelated = ItineraryTemplate::with('destination')
                    ->where('status', 'active')
                    ->get()->map(fn ($t) => $this->templateToItinerary($t))
                    ->take(3 - $related->count());
                $related = $related->concat($templateRelated);
            }
        }

        return view('public.safari-show', compact('settings', 'safari', 'related'));
    }

    public function accommodationShow(string $slug): View
    {
        $accommodation = Accommodation::where('slug', $slug)->where('published', true)->with('rooms.rates')->firstOrFail();
        $settings = WebsiteSetting::home();
        $related = Accommodation::where('published', true)->where('id', '!=', $accommodation->id)->limit(3)->get();

        return view('public.accommodation-show', compact('settings', 'accommodation', 'related'));
    }

    public function experienceShow(string $slug): View
    {
        $activity = Activity::with('translations', 'category', 'prices', 'seasons')
            ->where('slug', $slug)->where('published_on_website', true)->firstOrFail();
        $settings = WebsiteSetting::home();
        $categories = ActivityCategory::withCount(['activities' => fn($q) => $q->where('published_on_website', true)])->get();
        $related = Activity::with('translations')->where('published_on_website', true)
            ->where('id', '!=', $activity->id)
            ->where('activity_category_id', $activity->activity_category_id)
            ->limit(3)->get();

        return view('public.experience-show', compact('settings', 'activity', 'categories', 'related'));
    }

    public function booking(Request $request): View
    {
        $settings = WebsiteSetting::home();
        $destinations = $this->featuredDestinations($settings);

        $selectedItinerary = $this->resolveItineraryFromRequest($request);

        return view('public.booking', [
            'settings' => $settings,
            'destinations' => $destinations,
            'selectedItinerary' => $selectedItinerary,
            'prefillDestination' => $selectedItinerary['destination'] ?? $request->input('destination'),
        ]);
    }

    public function bookingForm(Request $request, ?string $token = null): View
    {
        $settings = WebsiteSetting::home();
        $proposal = $token ? $this->proposalForBookingToken($token) : null;

        return view('public.booking-form', [
            'settings' => $settings,
            'proposal' => $proposal,
            'token' => $token,
        ]);
    }

    public function submitBookingForm(Request $request, ?string $token = null): RedirectResponse
    {
        $proposal = $token ? $this->proposalForBookingToken($token) : null;

        $data = $request->validate([
            'main_full_name' => ['required', 'string', 'max:255'],
            'main_email' => ['required', 'email', 'max:255'],
            'main_phone' => ['nullable', 'string', 'max:80'],
            'main_date_of_birth' => ['nullable', 'date'],
            'main_sex' => ['nullable', 'string', 'max:40'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'zip_code' => ['nullable', 'string', 'max:40'],
            'country' => ['nullable', 'string', 'max:120'],
            'payment_method' => ['nullable', 'string', 'max:120'],
            'emergency_name' => ['nullable', 'string', 'max:255'],
            'emergency_email' => ['nullable', 'email', 'max:255'],
            'emergency_phone' => ['nullable', 'string', 'max:80'],
            'flying_doctors' => ['nullable', 'in:yes,no'],
            'flying_doctors_people' => ['nullable', 'integer', 'min:0', 'max:120'],
            'arrival_date' => ['nullable', 'date'],
            'arrival_time' => ['nullable', 'string', 'max:40'],
            'arrival_airport' => ['nullable', 'string', 'max:160'],
            'arrival_flight_number' => ['nullable', 'string', 'max:80'],
            'early_checkin' => ['nullable', 'in:yes,no'],
            'departure_date' => ['nullable', 'date'],
            'departure_time' => ['nullable', 'string', 'max:40'],
            'departure_airport' => ['nullable', 'string', 'max:160'],
            'departure_flight_number' => ['nullable', 'string', 'max:80'],
            'late_checkout' => ['nullable', 'in:yes,no'],
            'soft_drinks' => ['nullable', 'in:yes,no'],
            'soft_drinks_people' => ['nullable', 'integer', 'min:0', 'max:120'],
            'binoculars' => ['nullable', 'in:yes,no'],
            'binoculars_count' => ['nullable', 'integer', 'min:0', 'max:50'],
            'baby_seats' => ['nullable', 'in:yes,no'],
            'baby_seats_count' => ['nullable', 'integer', 'min:0', 'max:50'],
            'travellers' => ['nullable', 'array'],
            'travellers.*.full_name' => ['nullable', 'string', 'max:255'],
            'travellers.*.date_of_birth' => ['nullable', 'date'],
            'travellers.*.sex' => ['nullable', 'string', 'max:40'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'passport_files' => ['nullable', 'array'],
            'passport_files.*' => ['file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $travellers = collect($data['travellers'] ?? [])
            ->filter(fn ($traveller) => !empty($traveller['full_name']))
            ->values();

        $consultant = User::where('role', 'sales')->where('is_active', true)->orderBy('id')->first()
            ?? User::where('role', 'administrator')->where('is_active', true)->orderBy('id')->first();

        $requestRecord = \App\Models\Request::create([
            'request_number' => $this->uniqueRequestNumber(),
            'request_date' => now()->toDateString(),
            'client_id' => $proposal->client_id ?? null,
            'client_name' => $data['main_full_name'],
            'client_email' => $data['main_email'],
            'client_phone' => $data['main_phone'] ?? null,
            'country' => $data['country'] ?? ($proposal->client_country ?? null),
            'destination' => $proposal->workflow_country ?? null,
            'arrival_date' => $data['arrival_date'] ?? ($proposal->start_date ?? null),
            'departure_date' => $data['departure_date'] ?? ($proposal->end_date ?? null),
            'adults' => max(1, 1 + $travellers->count()),
            'children' => 0,
            'infants' => 0,
            'nights' => $proposal?->duration_days ? max(0, (int) $proposal->duration_days - 1) : 0,
            'source' => 'website_booking_form',
            'language' => 'en',
            'priority' => 'high',
            'status' => 'new',
            'travel_type' => 'safari',
            'assigned_to' => $consultant?->id,
            'assigned_consultant_id' => $consultant?->id,
            'currency' => $proposal->currency ?? 'USD',
            'flight_required' => !empty($data['arrival_flight_number']) || !empty($data['departure_flight_number']),
            'pickup_required' => !empty($data['arrival_airport']),
            'insurance_required' => ($data['flying_doctors'] ?? 'no') === 'yes',
            'internal_notes' => $this->bookingFormNotes($data, $travellers, $proposal),
            'special_requests' => $data['notes'] ?? null,
        ]);

        $requestRecord->logStatus(null, 'new', 'Booking form completed online by client.');
        $this->storeBookingPassportFiles($request, $requestRecord, $consultant);

        return back()->with('success', 'Thank you. Your booking form has been received securely by Shishi Footsteps.');
    }

    public function enquire(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'destination' => 'nullable|string|max:255',
            'destination_override' => 'nullable|string|max:255',
            'travel_date' => 'nullable|string|max:100',
            'adults' => 'nullable|integer|min:1|max:60',
            'children' => 'nullable|integer|min:0|max:60',
            'travelers' => 'nullable|integer|min:1|max:120',
            'budget' => 'nullable|string|max:100',
            'safari_type' => 'nullable|string|max:120',
            'message' => 'nullable|string',
            'itinerary_id' => 'nullable|integer',
            'itinerary_slug' => 'nullable|string|max:180',
            'itinerary_title' => 'nullable|string|max:255',
            'itinerary_url' => 'nullable|string|max:500',
        ]);

        $adults = (int) ($data['adults'] ?? 0);
        $children = (int) ($data['children'] ?? 0);
        $travelers = (int) ($data['travelers'] ?? max(1, $adults + $children));
        $travelDate = $this->parseTravelDate($data['travel_date'] ?? null);
        $salesConsultant = User::where('role', 'sales')->where('is_active', true)->orderBy('id')->first()
            ?? User::where('role', 'administrator')->where('is_active', true)->orderBy('id')->first();
        $itinerary = $this->resolveItineraryFromRequest($request) ?: $this->resolveItineraryFromArray($data);
        $resolvedDestination = $data['destination_override'] ?? ($itinerary['country'] ?? null);
        if ($resolvedDestination) {
            $data['destination'] = $resolvedDestination;
        }
        $itineraryNotes = $itinerary ? [
            'Selected Itinerary: '.$itinerary['title'].' (#'.$itinerary['id'].')',
            !empty($itinerary['country']) ? 'Itinerary Country: '.$itinerary['country'] : null,
            !empty($itinerary['days']) ? 'Itinerary Duration: '.$itinerary['days'].' days' : null,
            !empty($itinerary['url']) ? 'Itinerary URL: '.$itinerary['url'] : null,
        ] : [];
        $notesParts = [
            'Country of residence: '.($data['country'] ?? 'Not provided'),
            'Preferred destination: '.($data['destination'] ?? 'Not sure yet'),
            'Travel dates: '.($data['travel_date'] ?? 'Flexible'),
            'Adults: '.($adults ?: 'Not provided'),
            'Children: '.$children,
            'Budget range: '.($data['budget'] ?? 'To be discussed'),
            'Safari type: '.($data['safari_type'] ?? 'Tailor-made safari'),
        ];
        $notesParts = array_merge($notesParts, array_filter($itineraryNotes));
        $notesParts[] = 'Message: '.($data['message'] ?? 'No additional notes.');
        $notes = implode("\n", $notesParts);

        $lead = Lead::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'country' => $data['country'] ?? null,
            'source' => 'website',
            'status' => 'new',
            'assigned_consultant_id' => $salesConsultant?->id,
            'destination' => $data['destination'] ?? null,
            'travel_date' => $travelDate,
            'travelers' => $travelers,
            'estimated_value' => $this->budgetValue($data['budget'] ?? null),
            'currency' => 'USD',
            'interests' => $data['safari_type'] ?? null,
            'notes' => $notes,
        ]);

        $this->createRequestFromInquiry($lead, $data, $itinerary, $salesConsultant, $request);

        $this->notifyInquiry($lead);

        return back()->with('success', 'Thank you. Your safari inquiry has been received and saved in our CRM. A Shishi Footsteps specialist will contact you shortly.');
    }

    private function resolveItineraryFromRequest(\Illuminate\Http\Request $request): ?array
    {
        return $this->resolveItineraryFromArray([
            'itinerary_id' => $request->input('itinerary_id'),
            'itinerary_slug' => $request->input('itinerary_slug'),
            'itinerary_title' => $request->input('itinerary_title'),
        ]);
    }

    private function resolveItineraryFromArray(array $data): ?array
    {
        $id = $data['itinerary_id'] ?? null;
        $slug = $data['itinerary_slug'] ?? null;

        $template = null;
        if ($id) {
            $template = ItineraryTemplate::withTrashed()->find($id);
        }
        if (!$template && $slug) {
            $template = ItineraryTemplate::withTrashed()
                ->get()
                ->first(function ($t) use ($slug) {
                    return Str::slug($t->name) === $slug || Str::slug($t->trip_name ?? '') === $slug;
                });
        }

        // Fallback: V2 safaris
        if (!$template && $slug) {
            $v2 = ItineraryV2::where('slug', $slug)->where('published', true)->first();
            if ($v2) {
                return [
                    'id' => $v2->id,
                    'type' => 'v2',
                    'title' => $v2->title,
                    'slug' => $v2->slug,
                    'country' => $v2->country,
                    'days' => $v2->duration_days,
                    'url' => route('public.safaris.show', $v2->slug),
                ];
            }
        }

        if (!$template) {
            return null;
        }

        return [
            'id' => $template->id,
            'type' => 'template',
            'title' => $template->trip_name ?: $template->name,
            'slug' => $data['itinerary_slug'] ?? Str::slug($template->name),
            'country' => $template->destination?->country,
            'days' => $template->duration_days,
            'url' => $data['itinerary_url'] ?? null,
        ];
    }

    private function createRequestFromInquiry(Lead $lead, array $data, ?array $itinerary, ?User $consultant, ?Request $httpRequest = null): void
    {
        $requestModel = \App\Models\Request::class;

        if (!class_exists($requestModel)) {
            return;
        }

        $httpRequest = $httpRequest ?: request();
        $adults = (int) ($data['adults'] ?? 0);
        $children = (int) ($data['children'] ?? 0);
        $travelers = (int) ($data['travelers'] ?? max(1, $adults + $children));
        $travelDate = $this->parseTravelDate($data['travel_date'] ?? null);
        $budget = $this->budgetValue($data['budget'] ?? null);

        $payload = [
            'request_number' => \App\Models\Request::generateRequestNumber(),
            'request_date' => now()->toDateString(),
            'client_name' => $data['name'],
            'client_email' => $data['email'],
            'client_phone' => $data['phone'] ?? null,
            'country' => $data['country'] ?? null,
            'destination' => $data['destination_override'] ?? ($data['destination'] ?? ($itinerary['country'] ?? null)),
            'arrival_date' => $travelDate,
            'adults' => max(1, $adults),
            'children' => max(0, $children),
            'infants' => 0,
            'nights' => $itinerary && !empty($itinerary['days']) ? max(1, ((int) $itinerary['days']) - 1) : 0,
            'budget' => $budget,
            'currency' => 'USD',
            'source' => 'website',
            'language' => $httpRequest->input('lang', 'en'),
            'priority' => 'medium',
            'status' => 'new',
            'travel_type' => 'safari',
            'accommodation_tier' => $data['budget'] && str_contains(strtolower($data['budget']), 'luxury') ? 'luxury' : null,
            'internal_notes' => $lead->notes,
            'special_requests' => $data['message'] ?? null,
            'itinerary_template_id' => $itinerary && ($itinerary['type'] ?? null) === 'template' ? $itinerary['id'] : null,
        ];

        if ($consultant) {
            $payload['assigned_consultant_id'] = $consultant->id;
            $payload['assigned_to'] = $consultant->id;
        }

        $record = \App\Models\Request::create($payload);
        $record->logStatus(null, 'new', 'Created automatically from website enquiry.');
    }

    public function subscribe(Request $request): RedirectResponse
    {
        $data = $request->validate(['newsletter_email' => ['required', 'email', 'max:255']]);

        NewsletterSubscriber::updateOrCreate(
            ['email' => strtolower($data['newsletter_email'])],
            ['source' => 'website_footer', 'status' => 'subscribed', 'subscribed_at' => now()],
        );

        return back()->with('newsletter_success', 'You are on the list. Safari stories will find their way to you.');
    }

    private function destinationSectionLibrary(string $slug, string $name): array
    {
        $image = fn ($path) => asset('images/wordpress/'.$path);

        $countryCopy = [
            'kenya' => [
                'summary' => 'Big cats, private conservancies, the Great Migration, warm coastal endings and routes shaped around your pace.',
                'parks' => 'Masai Mara, Amboseli, Samburu, Tsavo, Lake Nakuru and Laikipia give Kenya strong year-round safari variety, from elephant views beneath Kilimanjaro to private conservancy guiding.',
                'highlights' => 'Great Migration months, big-cat sightings, scenic flights, cultural visits, family-friendly conservancies, Indian Ocean beaches and golf around Nairobi or Vipingo.',
                'wildlife' => 'Lion, leopard, cheetah, elephant, rhino, giraffe, zebra, buffalo and exceptional birdlife can all fit the right Kenya route.',
                'image' => 'lions-21787-scaled.jpg',
            ],
            'tanzania' => [
                'summary' => 'Serengeti plains, Ngorongoro drama, Tarangire elephants, Kilimanjaro views and Zanzibar-style beach finales.',
                'parks' => 'Serengeti, Ngorongoro, Tarangire, Lake Manyara, Ruaha and Nyerere create a powerful mix of classic safari, remote wilderness and seasonal migration moments.',
                'highlights' => 'Migration crossings, crater wildlife, Kilimanjaro hikes, private camps, family safari pacing, southern parks and beach retreats.',
                'wildlife' => 'Wildebeest, zebra, lion, elephant, buffalo, leopard, cheetah, flamingos and wide-open plains game define many Tanzanian safari days.',
                'image' => 'elephant-8677546-scaled.jpg',
            ],
            'uganda' => [
                'summary' => 'Gorilla forests, chimpanzees, crater lakes, Nile adventures and deeply personal wildlife encounters.',
                'parks' => 'Bwindi, Queen Elizabeth, Murchison Falls, Kibale and Lake Mburo each bring a different rhythm of forest, savannah, river and highland travel.',
                'highlights' => 'Gorilla trekking, chimpanzee tracking, Nile rafting, birding, crater lake scenery, forest walks and warm local encounters.',
                'wildlife' => 'Mountain gorillas, chimpanzees, tree-climbing lions, elephants, hippos, shoebill and remarkable birdlife are Uganda standouts.',
                'image' => 'Nile-Uganda-scaled.jpg',
            ],
            'rwanda' => [
                'summary' => 'A compact, polished journey with Kigali, Volcanoes National Park, gorillas, golden monkeys and refined highland lodges.',
                'parks' => 'Volcanoes, Akagera and Nyungwe create a clear circuit of gorillas, savannah wildlife, forest canopy walks and lake-country pauses.',
                'highlights' => 'Gorilla trekking, golden monkeys, Kigali culture, lake stays, canopy walks and smooth short-stay logistics.',
                'wildlife' => 'Mountain gorillas, golden monkeys, chimpanzees, forest birds and Akagera savannah wildlife are Rwanda\'s key draws.',
                'image' => 'gorilla-7708352-scaled.jpg',
            ],
            'south-africa' => [
                'summary' => 'Private reserves, Cape Town, wine country, dramatic coastlines, cuisine and some of Africa\'s finest golf.',
                'parks' => 'Kruger, Greater Kruger private reserves, Addo, Pilanesberg and coastal reserves offer flexible safari options with excellent guiding and infrastructure.',
                'highlights' => 'Cape Town, Winelands, whale coast, private reserve safari, fine dining, villas, coastal drives and championship golf.',
                'wildlife' => 'Big Five viewing, rhino conservation, whales, penguins, marine life and diverse birding all fit different South Africa routes.',
                'image' => 'Cape-Town-scaled.jpg',
            ],
            'namibia' => [
                'summary' => 'Sculptural dunes, desert-adapted wildlife, remote lodges, stargazing and beautiful overland routes.',
                'parks' => 'Etosha, Namib-Naukluft, Skeleton Coast, Damaraland and private desert reserves shape Namibia\'s most memorable journeys.',
                'highlights' => 'Sossusvlei dunes, desert elephants, scenic flights, remote camps, photography, self-drive and privately guided overland travel.',
                'wildlife' => 'Desert-adapted elephant, rhino, oryx, springbok, giraffe, lion and rare arid-land species are Namibia highlights.',
                'image' => 'namibianheart-camel-shishifootsteps-scaled.jpg',
            ],
            'botswana' => [
                'summary' => 'Low-impact wilderness, Okavango waterways, elephant-rich landscapes and quiet luxury camps.',
                'parks' => 'Okavango Delta, Chobe, Moremi, Savuti and the Makgadikgadi Pans each bring a distinct safari character.',
                'highlights' => 'Mokoro rides, private concessions, huge elephant herds, mobile camps, predator viewing and water-based safari days.',
                'wildlife' => 'Elephants, wild dogs, lion, leopard, buffalo, hippo, giraffe, zebra and seasonal birdlife are central to Botswana.',
                'image' => 'elephants-1065632-scaled.jpg',
            ],
        ];

        $copy = $countryCopy[$slug];
        $hero = $image($copy['image']);

        return [
            'safaris-and-tours' => [
                'nav' => 'Safaris and tours',
                'eyebrow' => 'Private African journeys',
                'title' => $name.' safaris and tours',
                'heading' => 'Tailor-made '.$name.' safari ideas',
                'summary' => $copy['summary'],
                'image' => $hero,
                'paragraphs' => [
                    'These journeys are starting points, not fixed packages. We shape the route, lodge style, pace, guiding and experiences around the travellers.',
                    'Every trip is matched to season, budget, comfort level and the kind of memories you want to bring home.',
                ],
                'bullets' => ['Private routing', 'Handpicked lodges', 'Trusted local guiding', 'Optional golf and beach extensions'],
            ],
            'discover' => [
                'nav' => 'Discover '.$name,
                'eyebrow' => 'Country guide',
                'title' => 'Discover '.$name,
                'heading' => 'Why travel to '.$name.' with Shishi Footsteps',
                'summary' => $copy['summary'],
                'image' => $hero,
                'paragraphs' => [
                    $copy['summary'],
                    'We keep the planning personal: meaningful routes, sensible travel days, reliable suppliers and a clear flow from arrival to departure.',
                ],
                'bullets' => ['Best season guidance', 'Smooth arrival planning', 'Local specialist advice', 'Flexible private design'],
            ],
            'national-parks' => [
                'nav' => 'National parks',
                'eyebrow' => 'Parks and reserves',
                'title' => 'National parks in '.$name,
                'heading' => 'The parks and reserves that shape the journey',
                'summary' => $copy['parks'],
                'image' => $hero,
                'paragraphs' => [
                    $copy['parks'],
                    'We choose areas according to wildlife movement, road or flight logistics, lodge availability and the atmosphere each traveller wants.',
                ],
                'bullets' => ['Wildlife-led routing', 'Private reserves where possible', 'Flight or road planning', 'Balanced safari pacing'],
            ],
            'accommodation' => [
                'nav' => 'Accommodation',
                'eyebrow' => 'Where to stay',
                'title' => 'Accommodation in '.$name,
                'heading' => 'Camps, lodges and stays selected around the route',
                'summary' => 'Accommodation is chosen for location, comfort, service, guiding access and how each stay supports the full journey.',
                'image' => $hero,
                'paragraphs' => [
                    'We do not simply place nice hotels on a map. Each lodge, camp or private stay is selected to make the itinerary smoother and more meaningful.',
                    'For every proposal, we balance atmosphere, logistics, room type, meal plan, supplier reliability and value.',
                ],
                'bullets' => ['Safari camps', 'Boutique lodges', 'Family-friendly stays', 'Beach and city extensions'],
            ],
            'highlights' => [
                'nav' => 'Highlights',
                'eyebrow' => 'What to experience',
                'title' => $name.' highlights',
                'heading' => 'Signature moments worth building around',
                'summary' => $copy['highlights'],
                'image' => $hero,
                'paragraphs' => [
                    $copy['highlights'],
                    'We turn the highlights into a workable route, keeping enough breathing space so the trip feels polished rather than packed.',
                ],
                'bullets' => ['Scenic moments', 'Culture and community', 'Adventure add-ons', 'Photography-friendly timing'],
            ],
            'activities' => [
                'nav' => 'Activities',
                'eyebrow' => 'Things to do',
                'title' => 'Activities in '.$name,
                'heading' => 'Experiences added with purpose',
                'summary' => 'Activities are selected to enrich the itinerary, not overload it.',
                'image' => $hero,
                'paragraphs' => [
                    'Depending on the country and route, activities can include guided walks, cultural visits, cycling, hiking, water sports, wellness, conservation visits or golf.',
                    'We only add experiences that fit the traveller, the season and the travel day.',
                ],
                'bullets' => ['Guided walks', 'Cultural visits', 'Adventure experiences', 'Special-interest travel'],
            ],
            'wildlife' => [
                'nav' => 'Wildlife',
                'eyebrow' => 'Wildlife guide',
                'title' => 'Wildlife in '.$name,
                'heading' => 'Wildlife possibilities and seasonal planning',
                'summary' => $copy['wildlife'],
                'image' => $hero,
                'paragraphs' => [
                    $copy['wildlife'],
                    'Wildlife is never guaranteed, so we plan around strong habitats, trusted guides, sensible timing and realistic expectations.',
                ],
                'bullets' => ['Seasonal advice', 'Trusted guides', 'Conservation-minded travel', 'Photography pacing'],
            ],
        ];
    }

    private function featuredDestinations(WebsiteSetting $settings)
    {
        $query = Country::with('regions')->where('is_active', true)->orderBy('name');

        if (! empty($settings->featured_destinations)) {
            $query->whereIn('id', $settings->featured_destinations);
        }

        return $query->limit(6)->get();
    }

    private function featuredActivities(WebsiteSetting $settings)
    {
        $query = Activity::with('translations')->where('published_on_website', true)->orderBy('name');

        if (! empty($settings->featured_activities)) {
            $query->whereIn('id', $settings->featured_activities);
        }

        return $query->limit(6)->get();
    }

    private function featuredSafaris(WebsiteSetting $settings)
    {
        $query = ItineraryV2::where('published', true)->orderByDesc('featured');

        if (! empty($settings->featured_safaris)) {
            $query->whereIn('id', $settings->featured_safaris);
        }

        $safaris = $query->limit(6)->get();

        if ($safaris->isNotEmpty()) {
            $templateSafaris = ItineraryTemplate::where('status', 'active')
                ->orderByDesc('created_at')
                ->limit(6)
                ->get()
                ->map(fn ($t) => $this->templateToItinerary($t));

            return $safaris->concat($templateSafaris)->take(6);
        }

        $contentSafaris = ContentItem::with('translations')
            ->where('type', 'safari_package')
            ->where('status', 'published')
            ->orderBy('featured', 'desc')
            ->limit(6)
            ->get();

        $templateSafaris = ItineraryTemplate::where('status', 'active')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get()
            ->map(fn ($t) => $this->templateToItinerary($t));

        $merged = $contentSafaris->concat($templateSafaris)->take(6);

        return $merged;
    }

    private function parseTravelDate(?string $value): ?string
    {
        if (! $value || ! strtotime($value)) {
            return null;
        }

        return date('Y-m-d', strtotime($value));
    }

    private function proposalForBookingToken(string $token): ?object
    {
        return DB::table('proposal_workflows')
            ->join('quotations', 'quotations.id', '=', 'proposal_workflows.quotation_id')
            ->join('clients', 'clients.id', '=', 'quotations.client_id')
            ->leftJoin('users', 'users.id', '=', 'proposal_workflows.seller_id')
            ->where('proposal_workflows.client_token', $token)
            ->where('proposal_workflows.client_link_enabled', true)
            ->where(function ($query) {
                $query->whereNull('proposal_workflows.client_link_expires_at')
                    ->orWhere('proposal_workflows.client_link_expires_at', '>', now());
            })
            ->select(
                'quotations.id as quotation_id',
                'quotations.reference',
                'quotations.title',
                'quotations.start_date',
                'quotations.end_date',
                'quotations.duration_days',
                'quotations.guest_count',
                'quotations.currency',
                'clients.id as client_id',
                'clients.name as client_name',
                'clients.email as client_email',
                'clients.phone as client_phone',
                'clients.country as client_country',
                'proposal_workflows.country as workflow_country',
                'users.name as seller_name'
            )
            ->first();
    }

    private function bookingFormNotes(array $data, Collection $travellers, ?object $proposal): string
    {
        $lines = [
            'ONLINE BOOKING FORM',
            $proposal ? 'Proposal: '.$proposal->reference.' - '.$proposal->title : 'Proposal: Not linked',
            '',
            'MAIN BOOKER',
            'Full name: '.$data['main_full_name'],
            'Email: '.$data['main_email'],
            'Phone: '.($data['main_phone'] ?? 'Not provided'),
            'Date of birth: '.($data['main_date_of_birth'] ?? 'Not provided'),
            'Sex: '.($data['main_sex'] ?? 'Not provided'),
            'Address: '.($data['address'] ?? 'Not provided'),
            'City / ZIP: '.trim(($data['city'] ?? '').' '.($data['zip_code'] ?? '')),
            'Country: '.($data['country'] ?? 'Not provided'),
            'Preferred payment method: '.($data['payment_method'] ?? 'Not provided'),
            '',
            'EMERGENCY CONTACT',
            'Name: '.($data['emergency_name'] ?? 'Not provided'),
            'Email: '.($data['emergency_email'] ?? 'Not provided'),
            'Phone: '.($data['emergency_phone'] ?? 'Not provided'),
            '',
            'TRAVEL DOCUMENTS',
            'Passport copies: uploaded separately under request files when attached.',
            '',
            'EXTRA INSURANCE',
            'Flying Doctors: '.ucfirst($data['flying_doctors'] ?? 'no').' for '.($data['flying_doctors_people'] ?? 0).' people',
            '',
            'ARRIVAL FLIGHT',
            'Date/time: '.($data['arrival_date'] ?? 'Not provided').' '.($data['arrival_time'] ?? ''),
            'Airport: '.($data['arrival_airport'] ?? 'Not provided'),
            'Flight number: '.($data['arrival_flight_number'] ?? 'Not provided'),
            'Early check-in: '.ucfirst($data['early_checkin'] ?? 'no'),
            '',
            'DEPARTURE FLIGHT',
            'Date/time: '.($data['departure_date'] ?? 'Not provided').' '.($data['departure_time'] ?? ''),
            'Airport: '.($data['departure_airport'] ?? 'Not provided'),
            'Flight number: '.($data['departure_flight_number'] ?? 'Not provided'),
            'Late check-out: '.ucfirst($data['late_checkout'] ?? 'no'),
            '',
            'EXTRAS DURING SAFARI',
            'Soft drinks/snacks: '.ucfirst($data['soft_drinks'] ?? 'no').' for '.($data['soft_drinks_people'] ?? 0).' people',
            'Binoculars: '.ucfirst($data['binoculars'] ?? 'no').' - '.($data['binoculars_count'] ?? 0),
            'Baby seats: '.ucfirst($data['baby_seats'] ?? 'no').' - '.($data['baby_seats_count'] ?? 0),
            '',
            'OTHER TRAVELLERS',
        ];

        if ($travellers->isEmpty()) {
            $lines[] = 'No extra travellers added.';
        } else {
            foreach ($travellers as $index => $traveller) {
                $lines[] = ($index + 1).'. '.($traveller['full_name'] ?? 'Unnamed').' | DOB: '.($traveller['date_of_birth'] ?? 'Not provided').' | Sex: '.($traveller['sex'] ?? 'Not provided');
            }
        }

        $lines[] = '';
        $lines[] = 'CLIENT NOTES';
        $lines[] = $data['notes'] ?? 'None';

        return implode("\n", $lines);
    }

    private function storeBookingPassportFiles(Request $request, \App\Models\Request $requestRecord, ?User $consultant): void
    {
        if (! $request->hasFile('passport_files') || ! $consultant) {
            return;
        }

        foreach ($request->file('passport_files') as $file) {
            if (! $file || ! $file->isValid()) {
                continue;
            }

            $path = $file->store('booking-passports/'.$requestRecord->id, 'public');

            DB::table('request_files')->insert([
                'request_id' => $requestRecord->id,
                'user_id' => $consultant->id,
                'filename' => basename($path),
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'category' => 'passport',
                'notes' => 'Uploaded by client through online booking form.',
                'created_at' => now(),
            ]);
        }
    }

    private function uniqueRequestNumber(): string
    {
        do {
            $number = 'REQ-'.now()->format('YmdHis').'-'.random_int(100, 999);
        } while (\App\Models\Request::where('request_number', $number)->exists());

        return $number;
    }

    private function budgetValue(?string $value): ?float
    {
        if (! $value) {
            return null;
        }

        preg_match('/[0-9][0-9,]*/', $value, $matches);

        return isset($matches[0]) ? (float) str_replace(',', '', $matches[0]) : null;
    }

    private function notifyInquiry(Lead $lead): void
    {
        try {
            $admins = User::whereIn('role', ['administrator', 'sales', 'marketing'])->where('is_active', true)->get();

            if ($admins->isNotEmpty()) {
                Notification::send($admins, new WebsiteInquiryReceived($lead));
            }

            Notification::route('mail', $lead->email)->notify(new WebsiteInquiryReceived($lead, false));
        } catch (Throwable $exception) {
            logger()->warning('Website inquiry notification could not be sent.', [
                'lead_id' => $lead->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
