<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use App\Models\ItineraryDay;
use App\Models\ItineraryImage;
use App\Support\MediaPath;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ItineraryController extends Controller
{
    public function index(Request $request): View
    {
        $itineraries = Itinerary::query()
            ->withCount(['days', 'images'])
            ->when($request->filled('search'), fn ($query) => $query->where(function ($query) use ($request) {
                $query->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('code', 'like', '%'.$request->search.'%')
                    ->orWhere('countries', 'like', '%'.$request->search.'%');
            }))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest('updated_at')
            ->paginate(12)
            ->withQueryString();

        return view('admin.itineraries.index', compact('itineraries'));
    }

    public function create(): View
    {
        return view('admin.itineraries.form', ['itinerary' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateItinerary($request);
        $data['code'] = ($data['code'] ?? null) ?: $this->nextCode();
        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['featured'] = $request->boolean('featured');
        $data['published_at'] = $data['status'] === 'published' ? now() : null;
        $data['inclusions'] = $this->lines($request->input('inclusions_text'));
        $data['exclusions'] = $this->lines($request->input('exclusions_text'));

        $itinerary = Itinerary::query()->create($data);
        if ($request->hasFile('cover_image_upload')) {
            $this->saveCover($itinerary, $request->file('cover_image_upload'));
        }

        return redirect()->route('admin.itineraries.edit', $itinerary)
            ->with('success', 'Itinerary created. Add its detailed day-by-day program and images.');
    }

    public function show(Itinerary $itinerary): View
    {
        $itinerary->load(['days.images', 'images']);

        return view('admin.itineraries.show', compact('itinerary'));
    }

    public function edit(Itinerary $itinerary): View
    {
        $itinerary->load(['days.images', 'images']);

        return view('admin.itineraries.form', compact('itinerary'));
    }

    public function update(Request $request, Itinerary $itinerary): RedirectResponse
    {
        $data = $this->validateItinerary($request, $itinerary);
        $data['slug'] = $this->uniqueSlug($data['title'], $itinerary->id);
        $data['featured'] = $request->boolean('featured');
        $data['published_at'] = $data['status'] === 'published'
            ? ($itinerary->published_at ?: now())
            : null;
        $data['inclusions'] = $this->lines($request->input('inclusions_text'));
        $data['exclusions'] = $this->lines($request->input('exclusions_text'));
        $itinerary->update($data);

        if ($request->hasFile('cover_image_upload')) {
            $this->saveCover($itinerary, $request->file('cover_image_upload'));
        }

        return back()->with('success', 'Itinerary details updated.');
    }

    public function destroy(Itinerary $itinerary): RedirectResponse
    {
        Storage::disk('public')->deleteDirectory('itineraries/'.$itinerary->id);
        $itinerary->delete();

        return redirect()->route('admin.itineraries.index')->with('success', 'Itinerary deleted.');
    }

    public function duplicate(Itinerary $itinerary): RedirectResponse
    {
        $itinerary->load(['days.images', 'images']);
        $copy = DB::transaction(function () use ($itinerary) {
            $copy = $itinerary->replicate(['code', 'slug', 'cover_image', 'published_at']);
            $copy->code = $this->nextCode();
            $copy->title = $itinerary->title.' - Copy';
            $copy->slug = $this->uniqueSlug($copy->title);
            $copy->status = 'draft';
            $copy->featured = false;
            $copy->save();

            $dayMap = [];
            foreach ($itinerary->days as $day) {
                $newDay = $day->replicate(['primary_image']);
                $newDay->itinerary_id = $copy->id;
                $newDay->primary_image = $this->copyImage($day->primary_image, $copy);
                $newDay->save();
                $dayMap[$day->id] = $newDay->id;
            }

            foreach ($itinerary->images as $image) {
                $newImage = $image->replicate();
                $newImage->itinerary_id = $copy->id;
                $newImage->itinerary_day_id = $image->itinerary_day_id ? ($dayMap[$image->itinerary_day_id] ?? null) : null;
                $newImage->path = $this->copyImage($image->path, $copy);
                $newImage->save();
                if ($image->is_cover) {
                    $copy->update(['cover_image' => $newImage->path]);
                }
            }

            if (! $copy->cover_image && $itinerary->cover_image) {
                $copy->update(['cover_image' => $this->copyImage($itinerary->cover_image, $copy)]);
            }

            return $copy;
        });

        return redirect()->route('admin.itineraries.edit', $copy)->with('success', 'Itinerary duplicated as a new draft.');
    }

    public function storeDay(Request $request, Itinerary $itinerary): RedirectResponse
    {
        $data = $this->validateDay($request);
        $data['activities'] = $this->lines($request->input('activities_text'));
        $data['day_number'] = $data['day_number'] ?: (($itinerary->days()->max('day_number') ?? 0) + 1);
        $day = $itinerary->days()->create($data);

        if ($request->hasFile('primary_image_upload')) {
            $day->update(['primary_image' => $request->file('primary_image_upload')->store("itineraries/{$itinerary->id}/days", 'public')]);
        }
        if ($request->hasFile('images')) {
            $this->saveGallery($request, $itinerary, $day);
        }

        return back()->with('success', "Day {$day->day_number} added.");
    }

    public function updateDay(Request $request, Itinerary $itinerary, ItineraryDay $day): RedirectResponse
    {
        abort_unless($day->itinerary_id === $itinerary->id, 404);
        $data = $this->validateDay($request, $day);
        $data['activities'] = $this->lines($request->input('activities_text'));
        $day->update($data);

        if ($request->hasFile('primary_image_upload')) {
            if (MediaPath::isManagedUpload($day->primary_image)) {
                Storage::disk('public')->delete($day->primary_image);
            }
            $day->update(['primary_image' => $request->file('primary_image_upload')->store("itineraries/{$itinerary->id}/days", 'public')]);
        }
        if ($request->hasFile('images')) {
            $this->saveGallery($request, $itinerary, $day);
        }

        return back()->with('success', "Day {$day->day_number} updated.");
    }

    public function destroyDay(Itinerary $itinerary, ItineraryDay $day): RedirectResponse
    {
        abort_unless($day->itinerary_id === $itinerary->id, 404);
        if (MediaPath::isManagedUpload($day->primary_image)) {
            Storage::disk('public')->delete($day->primary_image);
        }
        Storage::disk('public')->delete(
            $day->images()->pluck('path')->filter(fn ($path) => MediaPath::isManagedUpload($path))->all(),
        );
        $day->delete();

        return back()->with('success', 'Itinerary day deleted.');
    }

    public function storeImages(Request $request, Itinerary $itinerary): RedirectResponse
    {
        $this->saveGallery($request, $itinerary);

        return back()->with('success', 'Gallery images uploaded.');
    }

    public function destroyImage(Itinerary $itinerary, ItineraryImage $image): RedirectResponse
    {
        abort_unless($image->itinerary_id === $itinerary->id, 404);
        if (MediaPath::isManagedUpload($image->path)) {
            Storage::disk('public')->delete($image->path);
        }
        if ($itinerary->cover_image === $image->path) {
            $itinerary->update(['cover_image' => null]);
        }
        $image->delete();

        return back()->with('success', 'Image removed.');
    }

    public function setCover(Itinerary $itinerary, ItineraryImage $image): RedirectResponse
    {
        abort_unless($image->itinerary_id === $itinerary->id, 404);
        $itinerary->images()->update(['is_cover' => false]);
        $image->update(['is_cover' => true]);
        $itinerary->update(['cover_image' => $image->path]);

        return back()->with('success', 'Cover image updated.');
    }

    public function downloadPdf(Itinerary $itinerary): Response
    {
        $itinerary->load(['days.images', 'images']);

        return Pdf::loadView('admin.itineraries.pdf', [
            'itinerary' => $itinerary,
            'imageData' => fn (?string $path) => $this->imageData($path),
        ])->setPaper('a4')->download($itinerary->slug.'.pdf');
    }

    private function validateItinerary(Request $request, ?Itinerary $itinerary = null): array
    {
        return $request->validate([
            'code' => ['nullable', 'string', 'max:40', Rule::unique('itineraries')->ignore($itinerary?->id)],
            'title' => ['required', 'string', 'max:180'],
            'countries' => ['required', 'string', 'max:180'],
            'summary' => ['required', 'string', 'max:1500'],
            'description' => ['nullable', 'string', 'max:20000'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:90'],
            'nights' => ['required', 'integer', 'min:0', 'max:89'],
            'minimum_guests' => ['required', 'integer', 'min:1', 'max:100'],
            'maximum_guests' => ['required', 'integer', 'min:1', 'max:100', 'gte:minimum_guests'],
            'price_from' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'travel_style' => ['required', 'string', 'max:100'],
            'difficulty' => ['required', 'string', 'max:60'],
            'start_location' => ['nullable', 'string', 'max:150'],
            'end_location' => ['nullable', 'string', 'max:150'],
            'best_time' => ['nullable', 'string', 'max:180'],
            'accommodation_level' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:draft,published,archived'],
            'important_notes' => ['nullable', 'string', 'max:5000'],
            'seo_title' => ['nullable', 'string', 'max:180'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'cover_image_upload' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ]);
    }

    private function validateDay(Request $request, ?ItineraryDay $day = null): array
    {
        return $request->validate([
            'day_number' => [
                'nullable', 'integer', 'min:1', 'max:90',
                Rule::unique('itinerary_days')->where('itinerary_id', $day?->itinerary_id ?? $request->route('itinerary')?->id)->ignore($day?->id),
            ],
            'title' => ['required', 'string', 'max:180'],
            'location' => ['nullable', 'string', 'max:180'],
            'accommodation' => ['nullable', 'string', 'max:180'],
            'meal_plan' => ['nullable', 'string', 'max:100'],
            'distance_km' => ['nullable', 'integer', 'min:0', 'max:3000'],
            'driving_hours' => ['nullable', 'numeric', 'min:0', 'max:24'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:15000'],
            'overnight' => ['nullable', 'string', 'max:180'],
            'primary_image_upload' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'caption' => ['nullable', 'string', 'max:200'],
            'credit' => ['nullable', 'string', 'max:180'],
        ]);
    }

    private function saveCover(Itinerary $itinerary, $file): void
    {
        if (MediaPath::isManagedUpload($itinerary->cover_image)) {
            Storage::disk('public')->delete($itinerary->cover_image);
        }
        $path = $file->store("itineraries/{$itinerary->id}", 'public');
        $itinerary->update(['cover_image' => $path]);
    }

    private function saveGallery(Request $request, Itinerary $itinerary, ?ItineraryDay $day = null): void
    {
        $request->validate([
            'images' => ['required', 'array', 'min:1', 'max:12'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'caption' => ['nullable', 'string', 'max:200'],
            'credit' => ['nullable', 'string', 'max:180'],
        ]);

        foreach ($request->file('images', []) as $index => $file) {
            $path = $file->store("itineraries/{$itinerary->id}/gallery", 'public');
            $itinerary->images()->create([
                'itinerary_day_id' => $day?->id,
                'path' => $path,
                'caption' => $request->caption,
                'alt_text' => $request->caption ?: $itinerary->title,
                'credit' => $request->credit,
                'sort_order' => ($itinerary->images()->max('sort_order') ?? 0) + $index + 1,
            ]);
        }
    }

    private function lines(?string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $value))
            ->map(fn ($line) => trim($line))->filter()->values()->all();
    }

    private function nextCode(): string
    {
        return 'ITI-'.now()->format('Y').'-'.str_pad((string) ((Itinerary::query()->max('id') ?? 0) + 1), 4, '0', STR_PAD_LEFT);
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'itinerary';
        $slug = $base;
        $counter = 2;
        while (Itinerary::query()->where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }

    private function copyImage(?string $path, Itinerary $copy): ?string
    {
        if ($path && preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }

        $source = MediaPath::localPath($path);
        if (! $source) {
            return null;
        }

        $extension = pathinfo($source, PATHINFO_EXTENSION) ?: 'jpg';
        $newPath = "itineraries/{$copy->id}/copies/".Str::uuid().'.'.$extension;
        Storage::disk('public')->put($newPath, file_get_contents($source));

        return $newPath;
    }

    private function imageData(?string $path): ?string
    {
        $localPath = MediaPath::localPath($path);
        if (! $localPath) {
            return null;
        }

        $mime = mime_content_type($localPath) ?: 'image/jpeg';

        return 'data:'.$mime.';base64,'.base64_encode(file_get_contents($localPath));
    }
}
