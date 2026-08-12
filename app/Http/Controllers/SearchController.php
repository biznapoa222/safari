<?php

namespace App\Http\Controllers;

use App\Models\ItineraryTemplate;
use App\Models\ItineraryV2;
use App\Models\Destination;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function itineraries(Request $request): JsonResponse
    {
        $term = trim((string) $request->get('term'));
        $country = $request->get('country');
        $includeAll = $request->boolean('all') || $term === '';

        $items = collect();

        if ($includeAll || $term !== '') {
            $v2 = ItineraryV2::where('published', true)
                ->when($country, fn($q) => $q->where('country', $country))
                ->when($term !== '', function ($q) use ($term) {
                    $like = '%'.$term.'%';
                    $q->where(function ($qq) use ($like) {
                        $qq->where('title', 'like', $like)
                           ->orWhere('summary', 'like', $like)
                           ->orWhere('country', 'like', $like);
                    });
                })
                ->orderByDesc('featured')
                ->limit(30)
                ->get(['id', 'title', 'summary', 'country', 'duration_days', 'slug']);
            foreach ($v2 as $row) {
                $items->push([
                    'id' => $row->id,
                    'type' => 'safari',
                    'title' => $row->title,
                    'subtitle' => trim(($row->country ?? '').($row->duration_days ? ' · '.$row->duration_days.' days' : '')),
                    'url' => route('public.itineraries.show', $row->slug),
                    'slug' => $row->slug,
                    'country' => $row->country,
                    'duration_days' => $row->duration_days,
                    'image' => is_array($row->images ?? null) && count($row->images) ? \App\Support\MediaPath::publicUrl($row->images[0]) : asset('images/itineraries/kenya-family-cover.webp'),
                ]);
            }
        }

        if ($includeAll || $term !== '') {
            $templates = ItineraryTemplate::with('destination')
                ->where('status', 'active')
                ->get()
                ->filter(function ($t) use ($country, $term) {
                    if ($country && ($t->destination?->country !== $country)) return false;
                    if ($term !== '') {
                        $haystack = strtolower($t->name.' '.($t->trip_name ?? '').' '.($t->destination?->name ?? '').' '.($t->destination?->country ?? ''));
                        if (strpos($haystack, strtolower($term)) === false) return false;
                    }
                    return true;
                })
                ->take(30);
            foreach ($templates as $t) {
                $title = $t->trip_name ?: $t->name;
                $items->push([
                    'id' => $t->id,
                    'type' => 'template',
                    'title' => $title,
                    'subtitle' => trim(($t->destination?->country ?? '').' · '.$t->duration_days.' days'),
                    'url' => route('public.itineraries.show', Str::slug($t->name)),
                    'country' => $t->destination?->country,
                    'duration_days' => $t->duration_days,
                    'image' => is_array($t->images ?? null) && count($t->images) ? \App\Support\MediaPath::publicUrl($t->images[0]) : asset('images/itineraries/kenya-family-cover.webp'),
                ]);
            }
        }

        return response()->json([
            'items' => $items->values(),
            'count' => $items->count(),
        ]);
    }

    public function countries(): JsonResponse
    {
        $countries = collect(['Kenya','Tanzania','Uganda','Rwanda','South Africa','Namibia','Botswana']);
        $dbCountries = Destination::where('status', 1)->orderBy('name')->pluck('name');
        $merged = $countries->merge($dbCountries)->unique()->values();
        return response()->json(['countries' => $merged]);
    }
}
