<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\CmsPage;
use App\Models\ItineraryDayV2;
use App\Models\ItineraryV2;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Restores Rwanda destination journeys/activities from live WP + document FAQ.
 */
class RwandaContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCountryGuide();
        $this->seedGolfGorillaPackage();
        $this->seedClassicRwandaCircuit();
        $this->seedActivities();
        $this->command?->info('Rwanda content restored.');
    }

    private function seedCountryGuide(): void
    {
        $html = <<<'HTML'
<p>Rwanda — the Land of a Thousand Hills — is a compact, polished destination where gorilla trekking, highland scenery, savannah wildlife and championship golf sit within short, beautifully managed transfers.</p>
<h2>Why travel to Rwanda with Shishi Footsteps</h2>
<p>Rwanda is globally renowned for mountain gorilla trekking in Volcanoes National Park, one of the most sought-after wildlife experiences in the world. Beyond the forests, travellers find golden monkey tracking, chimpanzee walks in Nyungwe, Big Five game drives in Akagera, lake country around Musanze, and refined stays in and around Kigali.</p>
<p>Its small size makes it possible to combine multiple wildlife experiences in a single trip without rushed logistics. We design private journeys around your pace, permit dates, lodge style and whether you want to add golf at Kigali Golf Resort &amp; Villas or Karenge Hills.</p>
<h2>Signature experiences</h2>
<ul>
<li>Gorilla trekking in Volcanoes National Park</li>
<li>Golden monkey tracking in the Virunga foothills</li>
<li>Chimpanzee tracking and canopy walks in Nyungwe Forest National Park</li>
<li>Safari game drives and Lake Ihema boat cruises in Akagera National Park</li>
<li>Kigali city culture, memorials and craft markets</li>
<li>Championship golf at Kigali Golf Resort &amp; Villas</li>
<li>Scenic Twin Lakes visits (Burera and Ruhondo)</li>
</ul>
<h2>Best time to visit</h2>
<p>Rwanda can be visited year-round. Dry seasons (roughly June–September and December–February) are popular for trekking comfort and clearer trails, while green-season travel brings lush hillsides, fewer crowds and excellent birding. Gorilla permits are limited, so early booking is essential.</p>
<h2>How we plan Rwanda</h2>
<p>We secure gorilla permits in advance, arrange private transfers or domestic connections between Volcanoes, Nyungwe and Akagera, and match lodges to your comfort level — from intimate forest camps to polished highland retreats. Every itinerary remains tailor-made around your dates, fitness and interests.</p>
HTML;

        CmsPage::updateOrCreate(
            ['slug' => 'rwanda', 'type' => 'destination'],
            [
                'title' => 'Rwanda Travel Guide',
                'content' => $html,
                'seo_title' => 'Rwanda Safari & Golf Tours | Shishi Footsteps',
                'seo_description' => 'Private Rwanda journeys with gorilla trekking, Akagera safari, Nyungwe forests, Kigali culture and championship golf — designed around you.',
                'published' => true,
                'published_at' => now(),
            ]
        );
    }

    private function seedGolfGorillaPackage(): void
    {
        $days = [
            [1, 'Arrival in Kigali', 'Arrival at Kigali International Airport. Meet and greet by a Shishi Footsteps representative. Transfer to your luxury hotel in Kigali.'],
            [2, 'Kigali City Tour & Golf at Kigali Golf Club', 'Morning guided city tour exploring the Kigali Genocide Memorial, craft markets and local landmarks. Afternoon 18-hole round at Kigali Golf Club / Kigali Golf Resort & Villas, known for scenic views and challenging fairways.'],
            [3, 'Transfer to Musanze & Twin Lakes Visit', 'Drive to Musanze, gateway to the Virunga Volcanoes, enjoying terraced highland landscapes en route. Afternoon visit to the Twin Lakes (Burera and Ruhondo) for a scenic canoe ride or nature walk.'],
            [4, 'Gorilla Trekking Adventure', 'Early departure for Volcanoes National Park for mountain gorilla trekking through lush forest. Afternoon at leisure at the lodge, or optional community experiences.'],
            [5, 'Transfer to Akagera National Park', 'Scenic transfer to Akagera National Park, Rwanda’s premier savannah park. Afternoon sunset boat cruise on Lake Ihema spotting hippos, crocodiles and water birds.'],
            [6, 'Akagera Safari Game Drive & Golf at Karenge Hills', 'Early morning game drive for lions, elephants, giraffes and other wildlife. Afternoon at Karenge Hills for a unique 9-hole safari golf experience amidst rolling savannah hills.'],
            [7, 'Return to Kigali & Departure', 'Transfer back to Kigali with time for last-minute shopping or relaxation before your departure flight. Breakfast included.'],
        ];

        $notes = '<p>A signature Rwanda journey combining championship golf in Kigali, mountain gorilla trekking in Volcanoes National Park, Twin Lakes scenery and Big Five savannah safari in Akagera — matching the live Shishi Footsteps Golf and Gorilla Safari Adventure.</p>';

        $safari = ItineraryV2::updateOrCreate(
            ['slug' => 'golf-and-gorilla-safari-adventure-7-days-in-rwanda'],
            [
                'title' => 'Golf and Gorilla Safari Adventure – 7 Days in Rwanda',
                'summary' => 'Kigali golf, Volcanoes gorilla trekking, Twin Lakes, Akagera safari and Karenge Hills golf — a complete Rwanda highland and savannah circuit.',
                'duration_days' => 7,
                'country' => 'Rwanda',
                'region' => 'Kigali · Volcanoes · Akagera',
                'price_from' => 6000,
                'currency' => 'USD',
                'inclusions' => [
                    'Gorilla trekking permit',
                    'Golf rounds as listed (Kigali and Karenge Hills)',
                    'Park fees and guided game drives',
                    'Lake Ihema boat cruise',
                    'Private transfers and specialist guiding',
                    'Accommodation on a privately arranged basis',
                ],
                'exclusions' => [
                    'International flights',
                    'Travel insurance',
                    'Visa fees',
                    'Personal expenses and tips',
                    'Optional activities not listed',
                ],
                'notes' => $notes,
                'published' => true,
                'featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1559592413-7cec4d0cae2b?auto=format&fit=crop&w=1600&q=84&fm=webp',
                    'https://images.unsplash.com/photo-1517853782856-d7cc5de7a7fc?auto=format&fit=crop&w=1600&q=84&fm=webp',
                ],
            ]
        );

        $safari->days()->delete();
        foreach ($days as [$num, $title, $activities]) {
            ItineraryDayV2::create([
                'itinerary_v2_id' => $safari->id,
                'day_number' => $num,
                'title' => $title,
                'location' => 'Rwanda',
                'activities' => $activities,
                'meal_plan' => $num === 7 ? 'Breakfast' : 'As per itinerary',
                'sort_order' => $num - 1,
            ]);
        }

        // Marketing alias used on the golf page / live tee-off cards
        ItineraryV2::updateOrCreate(
            ['slug' => 'rwanda-championship-golf-week'],
            [
                'title' => 'Rwanda Championship Golf Week',
                'summary' => 'Kigali golf with gorilla trekking and elegant highland travel in Rwanda — the golf-led presentation of our 7-day Golf and Gorilla Safari Adventure.',
                'duration_days' => 7,
                'country' => 'Rwanda',
                'region' => 'Kigali · Volcanoes · Akagera',
                'price_from' => 6000,
                'currency' => 'USD',
                'inclusions' => $safari->inclusions,
                'exclusions' => $safari->exclusions,
                'notes' => '<p>Presented on the golf circuit as Rwanda Championship Golf Week. Full day-by-day detail matches the Golf and Gorilla Safari Adventure.</p>'.$notes,
                'published' => true,
                'featured' => true,
                'images' => $safari->images,
            ]
        )->days()->delete();

        $alias = ItineraryV2::where('slug', 'rwanda-championship-golf-week')->first();
        foreach ($days as [$num, $title, $activities]) {
            ItineraryDayV2::create([
                'itinerary_v2_id' => $alias->id,
                'day_number' => $num,
                'title' => $title,
                'location' => 'Rwanda',
                'activities' => $activities,
                'meal_plan' => $num === 7 ? 'Breakfast' : 'As per itinerary',
                'sort_order' => $num - 1,
            ]);
        }
    }

    private function seedClassicRwandaCircuit(): void
    {
        $days = [
            [1, 'Arrive Kigali', 'Meet and greet in Kigali, city orientation and overnight in the capital.'],
            [2, 'Transfer to Volcanoes National Park', 'Drive into the Virunga foothills. Afternoon Twin Lakes visit or community walk near Musanze.'],
            [3, 'Mountain gorilla trekking', 'Guided gorilla trek in Volcanoes National Park. Afternoon at leisure or optional cultural visit.'],
            [4, 'Golden monkeys or leisure', 'Optional golden monkey tracking, or a restful lodge day with highland views.'],
            [5, 'Akagera National Park', 'Cross to Akagera for savannah wildlife. Afternoon Lake Ihema boat cruise.'],
            [6, 'Full-day Akagera safari', 'Morning and afternoon game drives for Big Five and open plains wildlife.'],
            [7, 'Optional Nyungwe extension or depart', 'Return to Kigali for departure, or continue privately to Nyungwe for chimpanzees and canopy walks.'],
        ];

        $safari = ItineraryV2::updateOrCreate(
            ['slug' => 'rwanda-gorilla-akagera-circuit'],
            [
                'title' => 'Rwanda Gorilla & Akagera Circuit',
                'summary' => 'A classic non-golf Rwanda safari combining Volcanoes gorilla trekking, highland scenery and Akagera savannah wildlife — with optional Nyungwe forest extension.',
                'duration_days' => 7,
                'country' => 'Rwanda',
                'region' => 'Volcanoes · Akagera',
                'price_from' => null,
                'currency' => 'USD',
                'inclusions' => ['Gorilla permit', 'Park fees', 'Private guiding and transfers', 'Game drives', 'Lake Ihema boat cruise'],
                'exclusions' => ['International flights', 'Travel insurance', 'Nyungwe extension unless requested', 'Tips'],
                'notes' => '<p>Built from Shishi Footsteps Rwanda destination themes: gorilla trekking, golden monkeys, Akagera safari and optional Nyungwe chimpanzees.</p>',
                'published' => true,
                'featured' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1559592413-7cec4d0cae2b?auto=format&fit=crop&w=1600&q=84&fm=webp',
                    'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1600&q=84&fm=webp',
                ],
            ]
        );

        $safari->days()->delete();
        foreach ($days as [$num, $title, $activities]) {
            ItineraryDayV2::create([
                'itinerary_v2_id' => $safari->id,
                'day_number' => $num,
                'title' => $title,
                'location' => 'Rwanda',
                'activities' => $activities,
                'meal_plan' => 'As per itinerary',
                'sort_order' => $num - 1,
            ]);
        }
    }

    private function seedActivities(): void
    {
        $items = [
            ['Volcanoes National Park Gorilla Trekking', 'Volcanoes', 'Guided mountain gorilla trek in Volcanoes National Park with permit coordination by Shishi Footsteps.'],
            ['Golden Monkey Tracking – Volcanoes', 'Volcanoes', 'Track golden monkeys in the Virunga foothills — a lighter forest experience that pairs beautifully with gorilla trekking.'],
            ['Nyungwe Chimpanzee Tracking', 'Nyungwe', 'Chimpanzee tracking and forest immersion in Nyungwe Forest National Park, with optional canopy walk.'],
            ['Akagera National Park Game Drive', 'Akagera', 'Savannah game drives in Akagera for lions, elephants, giraffes and classic Big Five viewing.'],
            ['Lake Ihema Sunset Boat Cruise', 'Akagera', 'Sunset boat cruise on Lake Ihema for hippos, crocodiles and water birds.'],
            ['Kigali City & Culture Tour', 'Kigali', 'Guided Kigali orientation including memorials, craft markets and local landmarks.'],
            ['Twin Lakes Canoe Experience', 'Musanze', 'Scenic canoe or nature walk at Lakes Burera and Ruhondo in the Virunga highland landscape.'],
            ['Round at Kigali Golf Resort & Villas', 'Kigali', '18-hole championship golf on Kigali’s rolling highland layout, arranged with tee times and club support.'],
        ];

        foreach ($items as [$name, $location, $description]) {
            Activity::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'country' => 'Rwanda',
                    'region' => $location,
                    'location' => $location,
                    'description' => $description,
                    'published_on_website' => true,
                    'show_on_mobile_app' => true,
                    'activity_status' => 'active',
                    'currency' => 'USD',
                    'min_pax' => 1,
                    'images' => [
                        str_contains(Str::lower($location), 'nyungwe')
                            ? 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=1600&q=84&fm=webp'
                            : (str_contains(Str::lower($location), 'kigali') || str_contains(Str::lower($name), 'golf')
                                ? 'https://images.unsplash.com/photo-1593111774240-d529f12cf4bb?auto=format&fit=crop&w=1600&q=84&fm=webp'
                                : 'https://images.unsplash.com/photo-1559592413-7cec4d0cae2b?auto=format&fit=crop&w=1600&q=84&fm=webp'),
                    ],
                ]
            );
        }
    }
}
