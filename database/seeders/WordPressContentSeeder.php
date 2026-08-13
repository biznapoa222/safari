<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\CmsContentBlock;
use App\Models\CmsPage;
use App\Models\Country;
use App\Models\ItineraryDayV2;
use App\Models\ItineraryV2;
use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class WordPressContentSeeder extends Seeder
{
    private array $report = [
        'retained' => [],
        'updated' => [],
        'added' => [],
        'skipped' => [],
        'manual' => [],
    ];

    public function run(): void
    {
        $path = database_path('data/wordpress-content.json');
        if (! File::exists($path)) {
            $this->command?->warn('wordpress-content.json missing — skip WordPress import.');

            return;
        }

        $entries = json_decode(File::get($path), true) ?: [];
        $byKind = collect($entries)->groupBy('kind');

        $this->ensureRwanda();
        $this->seedCmsChrome($byKind);
        $this->seedPolicyAndHubPages($byKind);
        $this->seedBlogPosts($byKind);
        $this->seedCountryPages($byKind);
        $this->seedPackages($byKind);
        $this->seedExperienceActivities($byKind);

        $reportPath = database_path('data/wordpress-migration-report.json');
        File::put($reportPath, json_encode($this->report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $this->command?->info('WordPress content import complete. Report: '.$reportPath);
    }

    private function ensureRwanda(): void
    {
        $rwanda = Country::firstOrCreate(
            ['slug' => 'rwanda'],
            ['code' => 'RWA', 'name' => 'Rwanda', 'is_active' => true]
        );

        if ($rwanda->wasRecentlyCreated) {
            $this->report['added'][] = 'Country: Rwanda';
            foreach (['Volcanoes', 'Nyungwe', 'Akagera', 'Kigali'] as $region) {
                Region::firstOrCreate(
                    ['country_id' => $rwanda->id, 'slug' => Str::slug($region)],
                    ['name' => $region, 'is_active' => true]
                );
            }
        } else {
            $this->report['retained'][] = 'Country: Rwanda';
        }
    }

    private function seedCmsChrome($byKind): void
    {
        $contact = $byKind->get('hub')?->firstWhere('slug', 'contact');
        $teeOff = $byKind->get('hub')?->firstWhere('slug', 'tee-off');
        $luxuryGolf = $byKind->get('hub')?->firstWhere('slug', 'luxury-golf-tours');
        $journal = $byKind->get('hub')?->firstWhere('slug', 'journal');
        $faqs = $byKind->get('policy')?->firstWhere('slug', 'frequently-asked-questions');

        $blocks = [
            ['global', 'phone', 'text', '+254 725 346 022'],
            ['global', 'email', 'text', 'info@shishifootsteps.com'],
            ['global', 'bookings_email', 'text', 'bookings@shishifootsteps.com'],
            ['global', 'address', 'text', 'Nairobi, Kenya'],
            ['global', 'company_name', 'text', 'Shishi Footsteps'],
            ['global', 'footer_heading', 'text', 'Get safari inspiration'],
            ['global', 'footer_text', 'textarea', 'Explore golf insights, safari adventures, and all things travel from Shishi Footsteps.'],
            ['contact', 'hero_title', 'text', 'Let\'s Plan Your Ultimate Golf & Safari Journey!'],
            ['contact', 'hero_subtitle', 'textarea', $contact['summary'] ?? 'Whether you are ready to tee off on the finest greens or embark on a thrilling safari adventure, we are here to make it happen.'],
            ['contact', 'intro_title', 'text', 'Get in Touch'],
            ['contact', 'intro_text', 'textarea', 'Tell us more about your dream trip, and we will handle the rest.'],
            ['golf', 'hero_title', 'text', 'Beyond the Fairways'],
            ['golf', 'hero_subtitle', 'textarea', $teeOff['summary'] ?? $luxuryGolf['summary'] ?? 'Combining world-class golfing with Africa\'s wildlife, beaches and culture.'],
            ['golf', 'intro_title', 'text', 'Shishi Footsteps: luxury golf tours in Africa'],
            ['golf', 'intro_text', 'textarea', $luxuryGolf['summary'] ?? 'We offer luxurious golf tours in Africa, combining world-class golfing with cultural heritage and stunning landscapes.'],
            ['golf', 'cta_title', 'text', 'Where Passion for Golf Meets the Spirit of Adventure'],
            ['blog', 'hero_title', 'text', 'Our Journal'],
            ['blog', 'hero_subtitle', 'textarea', $journal['summary'] ?? 'Traveler stories, expert guides, and insider tips to fuel your wanderlust.'],
            ['faqs', 'hero_title', 'text', 'Frequently Asked Questions'],
            ['faqs', 'hero_subtitle', 'textarea', $faqs['summary'] ?? 'Answers about safari planning, destinations, wildlife and travel with Shishi Footsteps.'],
            ['faqs', 'editorial_title', 'text', 'Your questions, answered'],
            ['faqs', 'editorial_text', 'textarea', 'Whether you are planning a golf tour, combining a safari with your rounds, or arranging a complete African holiday, we are here to make the process effortless.'],
            ['about', 'mission_title', 'text', 'To be the premier provider of luxury golf and safari experiences in Africa'],
            ['about', 'mission_text', 'textarea', 'Renowned for offering exceptional golf and safari experiences alongside the continent\'s breathtaking natural beauty and rich cultural heritage.'],
            ['about', 'vision_title', 'text', 'Travel that leaves more than footprints'],
            ['about', 'vision_text', 'textarea', 'To deliver unparalleled journeys that seamlessly blend world-class golfing and safari with wildlife, pristine beaches, and vibrant culture.'],
            ['booking', 'hero_title', 'text', 'Enquire Now'],
            ['booking', 'hero_subtitle', 'textarea', 'Tell us about your dream safari or golf journey and our specialists will shape the itinerary around you.'],
        ];

        foreach ($blocks as [$page, $key, $type, $value]) {
            $this->upsertBlock($page, $key, $type, $value);
        }

        foreach (['global', 'contact', 'golf', 'blog', 'faqs', 'about', 'booking'] as $page) {
            CmsContentBlock::flushPage($page);
        }
    }

    private function seedPolicyAndHubPages($byKind): void
    {
        foreach ($byKind->get('policy', collect()) as $entry) {
            if ($entry['slug'] === 'frequently-asked-questions') {
                $this->upsertCmsPage([
                    'title' => 'Safari FAQ Reference',
                    'slug' => 'safari-faq-reference',
                    'type' => 'page',
                    'content' => $entry['html'],
                    'seo_title' => 'Safari FAQs | Shishi Footsteps',
                    'seo_description' => Str::limit(strip_tags($entry['html']), 155),
                ]);
                $this->report['manual'][] = 'FAQ Q&A UI still uses existing /faqs blade; full WP FAQ stored as /pages/safari-faq-reference';
                continue;
            }

            $this->upsertCmsPage([
                'title' => $entry['title'],
                'slug' => $entry['slug'],
                'type' => 'page',
                'content' => $entry['html'],
                'seo_title' => $entry['title'].' | Shishi Footsteps',
                'seo_description' => Str::limit(strip_tags($entry['html']), 155),
            ]);
        }

        $hubAsPages = ['footprints', 'beyond-golf', 'overlanding', 'adventure-runs', 'hiking', 'culture-heritage', 'water-sports', 'camping'];
        foreach ($byKind->get('hub', collect()) as $entry) {
            if (! in_array($entry['slug'], $hubAsPages, true)) {
                continue;
            }
            $this->upsertCmsPage([
                'title' => $entry['title'],
                'slug' => $entry['slug'],
                'type' => 'page',
                'content' => $entry['html'],
                'seo_title' => $entry['title'].' | Shishi Footsteps',
                'seo_description' => Str::limit($entry['summary'] ?? strip_tags($entry['html']), 155),
            ]);
        }

        foreach (['journal', 'luxury-golf-tours', 'tee-off', 'contact', 'book-safari'] as $slug) {
            $this->report['skipped'][] = "Hub mapped to existing route/CMS chrome: {$slug}";
        }
    }

    private function seedBlogPosts($byKind): void
    {
        foreach ($byKind->get('blog', collect()) as $entry) {
            $slug = $entry['slug'];
            if ($slug === 'kenya-through-your-senses') {
                $existing = CmsPage::where('type', 'blog')
                    ->where(function ($q) {
                        $q->where('slug', 'kenya-through-your-senses')
                            ->orWhere('title', 'like', 'Kenya Through Your Senses%');
                    })->first();
                if ($existing) {
                    $incomingLen = strlen(strip_tags($entry['html']));
                    $existingLen = strlen(strip_tags((string) $existing->content));
                    if ($incomingLen > $existingLen + 100) {
                        $existing->update([
                            'content' => $entry['html'],
                            'seo_description' => Str::limit($entry['summary'] ?? '', 155),
                            'published' => true,
                            'published_at' => $existing->published_at ?: now(),
                        ]);
                        $this->report['updated'][] = 'Blog: '.$existing->slug;
                    } else {
                        $this->report['retained'][] = 'Blog (richer or equal): '.$existing->slug;
                    }
                    continue;
                }
            }

            $this->upsertCmsPage([
                'title' => $entry['title'],
                'slug' => $slug,
                'type' => 'blog',
                'content' => $entry['html'],
                'seo_title' => $entry['title'].' | Shishi Footsteps Journal',
                'seo_description' => Str::limit($entry['summary'] ?? strip_tags($entry['html']), 155),
                'cover_image' => 'https://images.unsplash.com/photo-1489392191049-fc10c97e64b6?auto=format&fit=crop&w=1800&q=82&fm=webp',
            ]);
        }
    }

    private function seedCountryPages($byKind): void
    {
        foreach ($byKind->get('country', collect()) as $entry) {
            $this->upsertCmsPage([
                'title' => $entry['title'].' Travel Guide',
                'slug' => $entry['slug'],
                'type' => 'destination',
                'content' => $entry['html'],
                'seo_title' => $entry['title'].' Safari Tours | Shishi Footsteps',
                'seo_description' => Str::limit($entry['summary'] ?? strip_tags($entry['html']), 155),
            ]);
        }
    }

    private function seedPackages($byKind): void
    {
        $packages = $byKind->get('package', collect())
            ->merge($byKind->get('other', collect()))
            ->filter(fn ($e) => ! str_ends_with($e['slug'], '-2'));

        $activityLike = [
            'swim-with-whale-sharks',
            'kitesurfing',
            'snorkeling',
            'mangrove-sunset',
            'kaya-kinondo',
            'digo-village',
            'bike-tour',
            'shimba-hills',
            'discover-mambrui',
        ];

        foreach ($packages as $entry) {
            $slug = $entry['slug'];
            if (collect($activityLike)->contains(fn ($needle) => str_contains($slug, $needle))) {
                $this->upsertActivityFromEntry($entry);
                continue;
            }

            $existing = ItineraryV2::withTrashed()->where('slug', $slug)->first();
            if (str_contains($slug, 'kenya-on-wheels')) {
                $existing = ItineraryV2::where('slug', 'like', '%kenya%wheels%')
                    ->orWhere('title', 'like', '%Kenya on Wheels%')
                    ->orWhere('slug', $slug)
                    ->first() ?: $existing;
            }

            $payload = [
                'title' => $entry['title'],
                'slug' => $slug,
                'summary' => Str::limit($entry['summary'] ?? strip_tags($entry['html']), 500),
                'duration_days' => $entry['duration_days'] ?: max(1, substr_count(strtolower($entry['html']), '<h2>day ')),
                'country' => $entry['country'] ?: 'Kenya',
                'region' => 'Multi-destination',
                'price_from' => $entry['price_from'],
                'currency' => 'USD',
                'inclusions' => ['Accommodation', 'Guided activities as listed', 'Park fees where stated'],
                'exclusions' => ['International flights', 'Travel insurance', 'Items not listed'],
                'notes' => $entry['html'],
                'published' => true,
                'featured' => str_contains($slug, 'kenya-on-wheels') || str_contains($slug, 'garden-route'),
                'images' => [$this->imageForCountry($entry['country'])],
            ];

            if ($existing) {
                $incoming = strlen(strip_tags($entry['html']));
                $current = strlen(strip_tags((string) ($existing->notes ?: $existing->summary)));
                if ($incoming > $current + 80) {
                    $existing->update($payload);
                    $this->syncPackageDays($existing, $entry['html']);
                    $this->report['updated'][] = 'Package: '.$existing->slug;
                } else {
                    $this->report['retained'][] = 'Package: '.$existing->slug;
                }
            } else {
                $safari = ItineraryV2::create($payload);
                $this->syncPackageDays($safari, $entry['html']);
                $this->report['added'][] = 'Package: '.$safari->slug;
            }
        }
    }

    private function seedExperienceActivities($byKind): void
    {
        foreach ($byKind->get('hub', collect()) as $entry) {
            if (! in_array($entry['slug'], ['hiking', 'camping', 'water-sports', 'overlanding', 'adventure-runs', 'culture-heritage'], true)) {
                continue;
            }
            $this->upsertActivityFromEntry($entry);
        }
    }

    private function upsertActivityFromEntry(array $entry): void
    {
        $slug = $entry['slug'];
        $activity = Activity::where('slug', $slug)->orWhere('name', $entry['title'])->first();
        $payload = [
            'name' => $entry['title'],
            'slug' => $slug,
            'country' => $entry['country'] ?: 'Kenya',
            'region' => $entry['country'] ?: 'Kenya',
            'location' => $entry['country'] ?: 'Kenya',
            'description' => Str::limit(strip_tags($entry['html']), 2000),
            'published_on_website' => true,
            'show_on_mobile_app' => true,
            'activity_status' => 'active',
            'currency' => 'USD',
            'min_pax' => 1,
            'images' => [$this->imageForCountry($entry['country'])],
        ];

        if ($activity) {
            $incoming = strlen(strip_tags($entry['html']));
            $current = strlen(strip_tags((string) $activity->description));
            if ($incoming > $current + 80) {
                $activity->update($payload);
                $this->report['updated'][] = 'Activity: '.$slug;
            } else {
                $this->report['retained'][] = 'Activity: '.$slug;
            }
        } else {
            Activity::create($payload);
            $this->report['added'][] = 'Activity: '.$slug;
        }
    }

    private function syncPackageDays(ItineraryV2 $safari, string $html): void
    {
        if (! preg_match_all('/<h2>(Day\s+\d+[^<]*)<\/h2>\s*<p>(.*?)<\/p>/is', $html, $matches, PREG_SET_ORDER)) {
            if ($safari->days()->count() === 0) {
                ItineraryDayV2::create([
                    'itinerary_v2_id' => $safari->id,
                    'day_number' => 1,
                    'title' => 'Journey overview',
                    'location' => $safari->country,
                    'activities' => Str::limit(strip_tags($html), 500),
                    'meal_plan' => 'As per itinerary',
                    'sort_order' => 0,
                ]);
            }

            return;
        }

        $safari->days()->delete();
        foreach ($matches as $index => $match) {
            ItineraryDayV2::create([
                'itinerary_v2_id' => $safari->id,
                'day_number' => $index + 1,
                'title' => html_entity_decode(strip_tags($match[1])),
                'location' => $safari->country,
                'activities' => html_entity_decode(strip_tags($match[2])),
                'meal_plan' => 'As per itinerary',
                'sort_order' => $index,
            ]);
        }
    }

    private function upsertCmsPage(array $data): void
    {
        $page = CmsPage::where('slug', $data['slug'])->where('type', $data['type'])->first();
        $data['published'] = true;
        $data['published_at'] = now();

        if ($page) {
            $incoming = strlen(strip_tags($data['content'] ?? ''));
            $current = strlen(strip_tags((string) $page->content));
            if ($incoming > $current + 80) {
                $page->update($data);
                $this->report['updated'][] = strtoupper($data['type']).': '.$data['slug'];
            } else {
                $this->report['retained'][] = strtoupper($data['type']).': '.$data['slug'];
            }
        } else {
            CmsPage::create($data);
            $this->report['added'][] = strtoupper($data['type']).': '.$data['slug'];
        }
    }

    private function upsertBlock(string $page, string $key, string $type, string $value): void
    {
        $block = CmsContentBlock::firstOrNew(['page' => $page, 'key' => $key]);
        $isNew = ! $block->exists;
        $block->type = $type;
        if ($isNew || strlen($value) > strlen((string) $block->value)) {
            $block->value = $value;
            $block->save();
            $this->report[$isNew ? 'added' : 'updated'][] = "CMS {$page}.{$key}";
        } else {
            $this->report['retained'][] = "CMS {$page}.{$key}";
        }
    }

    private function imageForCountry(?string $country): string
    {
        return match ($country) {
            'Tanzania' => 'images/itineraries/tanzania-classic-cover.webp',
            'Botswana' => 'images/itineraries/botswana-luxury-cover.webp',
            'South Africa' => 'images/itineraries/tanzania-crater-day.webp',
            'Uganda', 'Rwanda' => 'images/itineraries/tanzania-crater-day.webp',
            default => 'images/itineraries/kenya-family-cover.webp',
        };
    }
}
