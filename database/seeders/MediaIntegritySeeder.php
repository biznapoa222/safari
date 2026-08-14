<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\CmsContentBlock;
use App\Models\ItineraryV2;
use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;

/**
 * Repairs missing/wrong destination media without changing architecture.
 * Uses existing Unsplash URL style + local itinerary covers.
 */
class MediaIntegritySeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCmsVideos();
        $this->resetDestinationMediaOverrides();
        $this->repairPackages();
        $this->repairActivities();
        $this->command?->info('Media integrity repairs applied.');
    }

    private function u(string $id): string
    {
        return "https://images.unsplash.com/photo-{$id}?auto=format&fit=crop&w=1600&q=84&fm=webp";
    }

    private function seedCmsVideos(): void
    {
        // Working Shishi Footsteps YouTube (golf/safari). Previous home default 1CYVG70ZbyQ is unavailable.
        foreach (['home', 'golf'] as $page) {
            $block = CmsContentBlock::firstOrNew(['page' => $page, 'key' => 'youtube_id']);
            if (! $block->exists || blank($block->value) || $block->value === '1CYVG70ZbyQ') {
                $block->value = 'iG5nlWiP9Ro';
                $block->type = 'text';
                $block->save();
            }
        }
    }

    private function resetDestinationMediaOverrides(): void
    {
        $settings = WebsiteSetting::home();
        // Clear saved overrides so country-correct defaults in WebsiteSetting apply.
        if (! empty($settings->destination_media)) {
            $settings->destination_media = null;
            $settings->save();
        }
    }

    private function repairPackages(): void
    {
        $map = [
            // Kenya — keep local Kenya assets
            'the-coastal-golf-and-beach-safari-circuit' => ['images/itineraries/kenya-coast-day.webp'],
            'the-great-rift-valley-golf-safari-circuit' => ['images/itineraries/kenya-family-cover.webp'],
            '10-days-9-nights-kenya-on-wheels-golf-safari-coastal-bliss' => [
                'images/itineraries/kenya-family-cover.webp',
                'images/itineraries/kenya-coast-day.webp',
            ],

            // Tanzania
            '7-day-tanzania-northern-circuit-classic' => [
                'images/itineraries/tanzania-classic-cover.webp',
                'images/itineraries/tanzania-crater-day.webp',
            ],
            'tanzania-serengeti-migration-focus' => ['images/itineraries/tanzania-classic-cover.webp'],

            // Uganda — gorilla / Nile / forest (no Kenya/Tanzania covers)
            'gorilla-trekking-golf-safari-in-western-uganda' => [$this->u('1559592413-7cec4d0cae2b')],
            'murchison-falls-golf-safari-experience' => [$this->u('1540573133985-87b6da6d54a9')],
            'source-of-the-nile-golf-and-adventure-safari' => [$this->u('1500534314209-a25ddb2bd429')],
            'queen-elizabeth-tooro-golf-safari' => [$this->u('1470071459604-3b5ec3a7fe05')],
            '4-days-bwindi-gorilla-trekking-safari-in-uganda' => [$this->u('1559592413-7cec4d0cae2b')],

            // Rwanda
            'golf-and-gorilla-safari-adventure-7-days-in-rwanda' => [
                $this->u('1559592413-7cec4d0cae2b'),
                $this->u('1517853782856-d7cc5de7a7fc'),
            ],
            'rwanda-championship-golf-week' => [
                $this->u('1559592413-7cec4d0cae2b'),
                $this->u('1593111774240-d529f12cf4bb'),
            ],
            'rwanda-gorilla-akagera-circuit' => [
                $this->u('1559592413-7cec4d0cae2b'),
                $this->u('1516426122078-c23e76319801'),
            ],
            'nyungwe-forest-chimpanzee-canopy-extension' => [
                $this->u('1441974231531-c6227db76b6e'),
                $this->u('1470071459604-3b5ec3a7fe05'),
            ],

            // South Africa
            '7-days-6-nights-south-africa-golf-travel' => [
                $this->u('1484318571209-661cf29a69c3'),
                $this->u('1580060839134-75a5edca2e99'),
            ],
            '6-days-in-south-africa-cape-town-whales-wine-routes' => [
                $this->u('1484318571209-661cf29a69c3'),
                $this->u('1516483638261-f4dbaf036963'),
            ],
            '10-day-south-africa-garden-route-road-trip' => [
                $this->u('1507525428034-b723cf961d3e'),
                $this->u('1516483638261-f4dbaf036963'),
            ],
            'the-road-to-kruger' => [
                $this->u('1534177616072-ef7dc120449d'),
                $this->u('1516426122078-c23e76319801'),
            ],
            'fairways-the-mother-city-cape-town-golf-wine-itinerary-6-days' => [
                $this->u('1484318571209-661cf29a69c3'),
                $this->u('1592919505780-303950717480'),
            ],

            // Namibia
            'namibia-etosha-sossusvlei-circuit' => [
                $this->u('1509316785289-025f5b846b35'),
                $this->u('1469854523086-cc02fe5d8800'),
            ],

            // Botswana — local covers
            '5-day-luxury-botswana-safari' => [
                'images/itineraries/botswana-luxury-cover.webp',
                'images/itineraries/botswana-chobe-day.webp',
            ],
            'botswana-okavango-moremi-explorer' => [
                'images/itineraries/botswana-chobe-day.webp',
                'images/itineraries/botswana-luxury-cover.webp',
            ],
        ];

        foreach ($map as $slug => $images) {
            $safari = ItineraryV2::where('slug', $slug)->first();
            if ($safari) {
                $safari->images = $images;
                $safari->save();
            }
        }
    }

    private function repairActivities(): void
    {
        $bySlug = [
            // Kenya
            'maasai-mara-game-drive-experience' => [$this->u('1516426122078-c23e76319801')],
            'amboseli-elephant-safari' => [$this->u('1559564472-71484b1070bb')],
            'vipingo-ridge-championship-round' => [$this->u('1592919505780-303950717480')],

            // Tanzania
            'serengeti-game-drive' => ['images/itineraries/tanzania-classic-cover.webp'],
            'ngorongoro-crater-descent' => ['images/itineraries/tanzania-crater-day.webp'],
            'tarangire-elephant-baobab-safari' => [$this->u('1523805009345-7448845a9e53')],
            'serengeti-balloon-safari' => [$this->u('1500530855697-b586d89ba3ee')],

            // Uganda
            'bwindi-gorilla-trekking' => [$this->u('1559592413-7cec4d0cae2b')],
            'kazinga-channel-boat-safari' => [$this->u('1540573133985-87b6da6d54a9')],
            'murchison-falls-nile-cruise' => [$this->u('1500534314209-a25ddb2bd429')],
            'jinja-white-water-rafting' => [$this->u('1500534314209-a25ddb2bd429')],
            'tooro-golf-club-round' => [$this->u('1593111774240-d529f12cf4bb')],

            // Rwanda
            'volcanoes-national-park-gorilla-trekking' => [$this->u('1559592413-7cec4d0cae2b')],
            'golden-monkey-tracking-volcanoes' => [$this->u('1517853782856-d7cc5de7a7fc')],
            'nyungwe-chimpanzee-tracking' => [$this->u('1441974231531-c6227db76b6e')],
            'nyungwe-canopy-walk' => [$this->u('1441974231531-c6227db76b6e')],
            'akagera-national-park-game-drive' => [$this->u('1516426122078-c23e76319801')],
            'lake-ihema-sunset-boat-cruise' => [$this->u('1500534314209-a25ddb2bd429')],
            'kigali-city-culture-tour' => [$this->u('1517853782856-d7cc5de7a7fc')],
            'twin-lakes-canoe-experience' => [$this->u('1500534314209-a25ddb2bd429')],
            'round-at-kigali-golf-resort-villas' => [$this->u('1593111774240-d529f12cf4bb')],

            // South Africa
            'kruger-big-five-game-drive' => [$this->u('1534177616072-ef7dc120449d')],
            'stellenbosch-wine-tasting' => [$this->u('1484318571209-661cf29a69c3')],
            'table-mountain-cable-car-experience' => [$this->u('1484318571209-661cf29a69c3')],
            'skukuza-golf-club-round' => [$this->u('1580060839134-75a5edca2e99')],
            'cape-whale-watching' => [$this->u('1516483638261-f4dbaf036963')],

            // Namibia
            'sossusvlei-dune-walk' => [$this->u('1509316785289-025f5b846b35')],
            'etosha-waterhole-safari' => [$this->u('1469854523086-cc02fe5d8800')],
            'rossmund-desert-golf-round' => [$this->u('1592919505780-303950717480')],

            // Botswana
            'okavango-mokoro-excursion' => ['images/itineraries/botswana-luxury-cover.webp'],
            'chobe-river-sunset-cruise' => ['images/itineraries/botswana-chobe-day.webp'],
        ];

        foreach ($bySlug as $slug => $images) {
            $activity = Activity::where('slug', $slug)->first();
            if ($activity) {
                $activity->images = $images;
                $activity->save();
            }
        }

        // Catch remaining wrong-country generic Kenya cover on non-Kenya activities
        Activity::query()
            ->where('country', '!=', 'Kenya')
            ->get()
            ->each(function (Activity $activity) use ($bySlug) {
                $imgs = is_array($activity->images) ? $activity->images : [];
                $joined = strtolower(implode(' ', $imgs));
                if (! str_contains($joined, 'kenya-family-cover') && ! str_contains($joined, 'tanzania-crater') && ! str_contains($joined, 'kenya-coast')) {
                    return;
                }
                if (isset($bySlug[$activity->slug])) {
                    return;
                }
                $country = strtolower($activity->country);
                $fallback = match (true) {
                    str_contains($country, 'uganda') => [$this->u('1559592413-7cec4d0cae2b')],
                    str_contains($country, 'rwanda') => [$this->u('1559592413-7cec4d0cae2b')],
                    str_contains($country, 'namibia') => [$this->u('1509316785289-025f5b846b35')],
                    str_contains($country, 'south africa') => [$this->u('1484318571209-661cf29a69c3')],
                    str_contains($country, 'botswana') => ['images/itineraries/botswana-luxury-cover.webp'],
                    str_contains($country, 'tanzania') => ['images/itineraries/tanzania-classic-cover.webp'],
                    default => $imgs,
                };
                $activity->images = $fallback;
                $activity->save();
            });
    }
}
