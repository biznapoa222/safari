<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use App\Models\Accommodation;
use App\Models\Activity;
use App\Models\Country;
use App\Models\ItineraryV2;
use App\Models\WebsiteSetting;
use App\Models\CmsContentBlock;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CmsController extends Controller
{
    public function index(): View
    {
        $pages = CmsPage::orderBy('type')->orderBy('title')->paginate(20);
        return view('admin.cms.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.cms.form', ['page' => null]);
    }

    public function content(string $section): View
    {
        $definition = config("cms.pages.{$section}");
        abort_unless($definition, 404);
        $values = CmsContentBlock::where('page', $section)->pluck('value', 'key');
        return view('admin.cms.content', compact('section', 'definition', 'values'));
    }

    public function updateContent(Request $request, string $section): RedirectResponse
    {
        $definition = config("cms.pages.{$section}");
        abort_unless($definition, 404);

        $rules = [];
        foreach ($definition['fields'] as $key => $field) {
            $rules["content.{$key}"] = 'nullable|string|max:'.($field['type'] === 'textarea' ? 20000 : 2048);
            if ($field['type'] === 'image') {
                $rules["uploads.{$key}"] = 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:8192|dimensions:min_width=200,min_height=120,max_width=8000,max_height=8000';
                $rules["remove.{$key}"] = 'nullable|boolean';
            }
        }
        $request->validate($rules);

        foreach ($definition['fields'] as $key => $field) {
            $existing = CmsContentBlock::where(['page'=>$section, 'key'=>$key])->first();
            $value = $request->input("content.{$key}");
            if ($field['type'] === 'image' && $request->hasFile("uploads.{$key}")) {
                if ($existing && str_starts_with((string) $existing->value, 'website/content/')) Storage::disk('public')->delete($existing->value);
                $value = $request->file("uploads.{$key}")->store("website/content/{$section}", 'public');
            } elseif ($field['type'] === 'image' && $request->boolean("remove.{$key}")) {
                if ($existing && str_starts_with((string) $existing->value, 'website/content/')) Storage::disk('public')->delete($existing->value);
                $value = null;
            }
            CmsContentBlock::updateOrCreate(['page'=>$section, 'key'=>$key], ['type'=>$field['type'], 'value'=>$value]);
        }
        CmsContentBlock::flushPage($section);
        return back()->with('success', $definition['label'].' updated.');
    }

    public function homeSettings(): View
    {
        return view('admin.cms.home-settings', [
            'settings' => WebsiteSetting::home(),
            'destinations' => Country::where('is_active', true)->orderBy('name')->get(),
            'safaris' => ItineraryV2::where('published', true)->orderBy('title')->get(),
            'activities' => Activity::where('published_on_website', true)->orderBy('name')->get(),
            'accommodationsCount' => Accommodation::where('published', true)->count(),
        ]);
    }

    public function updateHomeSettings(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'hero_image' => 'nullable|string|max:2048',
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'nullable|string',
            'featured_destinations' => 'nullable|array',
            'featured_destinations.*' => 'integer|exists:countries,id',
            'featured_safaris' => 'nullable|array',
            'featured_safaris.*' => 'integer|exists:itineraries_v2,id',
            'featured_activities' => 'nullable|array',
            'featured_activities.*' => 'integer|exists:activities,id',
            'show_published_accommodation' => 'nullable|boolean',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'open_graph_image' => 'nullable|string|max:2048',
            'destination_media' => 'nullable|array',
            'destination_media.*.hero' => 'nullable|string|max:2048',
            'destination_media.*.gallery' => 'nullable|array|max:8',
            'destination_media.*.gallery.*' => 'nullable|string|max:2048',
            'destination_uploads' => 'nullable|array',
            'destination_uploads.*.hero' => 'nullable|image|max:8192',
            'destination_uploads.*.gallery' => 'nullable|array|max:8',
            'destination_uploads.*.gallery.*' => 'nullable|image|max:8192',
        ]);

        $data['featured_destinations'] = array_values($data['featured_destinations'] ?? []);
        $data['featured_safaris'] = array_values($data['featured_safaris'] ?? []);
        $data['featured_activities'] = array_values($data['featured_activities'] ?? []);
        $data['show_published_accommodation'] = $request->boolean('show_published_accommodation');
        $currentMedia = WebsiteSetting::home()->destination_media ?? [];
        $destinationMedia = [];
        foreach (array_keys(WebsiteSetting::destinationMediaDefaults()) as $slug) {
            $submitted = $data['destination_media'][$slug] ?? [];
            $hero = $submitted['hero'] ?? ($currentMedia[$slug]['hero'] ?? null);
            $gallery = array_values(array_filter($submitted['gallery'] ?? ($currentMedia[$slug]['gallery'] ?? [])));

            if ($request->hasFile("destination_uploads.{$slug}.hero")) {
                $hero = $request->file("destination_uploads.{$slug}.hero")->store("website/destinations/{$slug}", 'public');
            }
            foreach ($request->file("destination_uploads.{$slug}.gallery", []) as $upload) {
                $gallery[] = $upload->store("website/destinations/{$slug}", 'public');
            }

            $destinationMedia[$slug] = ['hero' => $hero, 'gallery' => array_slice(array_values(array_unique($gallery)), 0, 8)];
        }
        $data['destination_media'] = $destinationMedia;
        unset($data['destination_uploads']);

        WebsiteSetting::home()->update($data);

        return back()->with('success', 'Homepage settings updated.');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:page,blog,destination',
            'content' => 'nullable|string',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'cover_image' => 'nullable|string|max:2048',
            'cover_upload' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:8192|dimensions:min_width=200,min_height=120,max_width=8000,max_height=8000',
        ]);

        if ($request->hasFile('cover_upload')) $data['cover_image'] = $request->file('cover_upload')->store('website/pages', 'public');
        unset($data['cover_upload']);
        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(4);

        CmsPage::create($data);

        return redirect()->route('admin.cms.index')->with('success', 'Page created.');
    }

    public function edit(CmsPage $page): View
    {
        return view('admin.cms.form', ['page' => $page]);
    }

    public function update(Request $request, CmsPage $page): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:page,blog,destination',
            'content' => 'nullable|string',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'cover_image' => 'nullable|string|max:2048',
            'cover_upload' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:8192|dimensions:min_width=200,min_height=120,max_width=8000,max_height=8000',
            'published' => 'boolean',
        ]);

        if ($request->hasFile('cover_upload')) {
            if (str_starts_with((string) $page->cover_image, 'website/pages/')) Storage::disk('public')->delete($page->cover_image);
            $data['cover_image'] = $request->file('cover_upload')->store('website/pages', 'public');
        }
        unset($data['cover_upload']);
        $data['published'] = $request->boolean('published');
        if ($data['published'] && !$page->published_at) {
            $data['published_at'] = now();
        }

        $page->update($data);

        return redirect()->route('admin.cms.index')->with('success', 'Page updated.');
    }

    public function destroy(CmsPage $page): RedirectResponse
    {
        if (str_starts_with((string) $page->cover_image, 'website/pages/')) Storage::disk('public')->delete($page->cover_image);
        $page->delete();
        return redirect()->route('admin.cms.index')->with('success', 'Page deleted.');
    }

    public function togglePublish(CmsPage $page): RedirectResponse
    {
        $page->update([
            'published' => !$page->published,
            'published_at' => $page->published ? null : now(),
        ]);
        return back()->with('success', $page->published ? 'Published.' : 'Unpublished.');
    }
}
