<?php

namespace Database\Seeders;

use App\Models\Itinerary;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ItinerarySeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            [
                'code' => 'ITI-2026-0001',
                'title' => '10-Day Kenya Family Safari & Indian Ocean',
                'countries' => 'Kenya',
                'summary' => 'A privately guided family journey combining Nairobi, the elephants of Amboseli, Lake Naivasha, the Maasai Mara and a restful Diani Beach finale.',
                'description' => "Designed for families who want meaningful wildlife encounters without rushed travel days. The route balances private game drives, child-friendly activities and time to relax.\n\nEvery stay has been selected for warm hosting, flexible meal times and suitable family room configurations.",
                'duration_days' => 10, 'nights' => 9, 'price_from' => 5480,
                'travel_style' => 'Private family safari', 'difficulty' => 'Easy',
                'start_location' => 'Nairobi', 'end_location' => 'Diani Beach',
                'best_time' => 'June to October and January to March',
                'accommodation_level' => 'Premium family lodges and beach resort',
                'featured' => true,
                'days' => [
                    ['Welcome to Kenya', 'Nairobi', 'Four Points by Sheraton Nairobi Airport', 'Dinner', 18, .5, 'A warm airport welcome and a gentle first evening in Nairobi.', 'Your Shishi Footsteps representative meets the family after immigration and assists with luggage. Transfer to the hotel for a private trip briefing, time to rest and an early dinner.', ['Private airport meet and assist', 'Family safari briefing']],
                    ['Across the plains to Amboseli', 'Amboseli', 'Ol Tukai Lodge', 'Breakfast, lunch and dinner', 215, 4.5, 'Drive south to elephant country beneath Mount Kilimanjaro.', 'Leave Nairobi after breakfast in a private 4x4. Reach Amboseli for lunch before an afternoon game drive across the park’s wetlands and open plains.', ['Scenic private transfer', 'Afternoon game drive']],
                    ['Elephants and Kilimanjaro', 'Amboseli', 'Ol Tukai Lodge', 'Breakfast, lunch and dinner', 65, 3, 'A full day shaped around wildlife, photography and family pace.', 'Begin early when Kilimanjaro is often clearest. Return to the lodge during the warm middle hours, then explore again in the late afternoon.', ['Sunrise game drive', 'Junior ranger activity', 'Sundowner']],
                    ['Great Rift Valley and Lake Naivasha', 'Lake Naivasha', 'Enashipai Resort', 'Breakfast, lunch and dinner', 340, 6.5, 'Travel through changing landscapes to the freshwater shores of Naivasha.', 'A relaxed road journey with comfort stops and a picnic lunch. Arrive in time for a lakeside walk while colobus monkeys move through the gardens.', ['Rift Valley viewpoint', 'Lakeside nature walk']],
                    ['Boat safari and Crescent Island', 'Lake Naivasha', 'Enashipai Resort', 'Breakfast and dinner', 30, 2, 'Hippos, fish eagles and a walking safari among plains game.', 'Take a private boat across the lake with a professional captain, then walk with a local guide on Crescent Island. The afternoon is free for the pool or spa.', ['Private boat safari', 'Guided Crescent Island walk']],
                    ['Into the Maasai Mara', 'Maasai Mara', 'Mara Serena Safari Lodge', 'Breakfast, lunch and dinner', 235, 5.5, 'Enter Kenya’s most celebrated wildlife landscape.', 'Drive through the highlands and descend toward the Mara. After lunch, enjoy the first game drive in search of lion, elephant, giraffe and cheetah.', ['Afternoon Mara game drive', 'Family wildlife checklist']],
                    ['A full day on safari', 'Maasai Mara', 'Mara Serena Safari Lodge', 'Breakfast, picnic lunch and dinner', 120, 7, 'A flexible private game-viewing day planned around current wildlife movement.', 'Carry a picnic lunch so the guide can follow the best sightings without returning to camp. Visit the Mara River when conditions and wildlife reports make it worthwhile.', ['Full-day private game drive', 'Picnic in the reserve', 'Mara River exploration']],
                    ['Mara culture and conservation', 'Maasai Mara', 'Mara Serena Safari Lodge', 'Breakfast, lunch and dinner', 55, 3, 'Wildlife at dawn, cultural exchange and a slow final Mara afternoon.', 'After a short sunrise drive, visit a community initiative for a respectful introduction to Maasai life. Spend the afternoon at leisure before a private farewell sundowner.', ['Sunrise game drive', 'Community visit', 'Private sundowner']],
                    ['Fly to the coast', 'Diani Beach', 'The Sands at Nomad', 'Breakfast and dinner', 35, 1.5, 'A light aircraft flight replaces a long road journey and delivers the family to the Indian Ocean.', 'Transfer to the Mara airstrip for the scheduled flight to Ukunda. A coastal representative meets the aircraft and drives you to the beach resort.', ['Mara to Ukunda flight', 'Private resort transfer', 'Beach at leisure']],
                    ['Indian Ocean farewell', 'Diani Beach', 'The Sands at Nomad', 'Breakfast', 45, 1.5, 'A final beach morning before the onward flight or optional extension.', 'Enjoy a leisurely breakfast and time by the ocean. Your private driver collects you at the agreed time for Ukunda Airport or the Mombasa rail and airport connections.', ['Beach morning', 'Private departure transfer']],
                ],
            ],
            [
                'code' => 'ITI-2026-0002',
                'title' => '7-Day Tanzania Northern Circuit',
                'countries' => 'Tanzania',
                'summary' => 'A classic private safari through Tarangire, the Ngorongoro Highlands and the Serengeti, with carefully paced drives and atmospheric tented camps.',
                'description' => 'An essential northern Tanzania route for first-time safari travellers. Distances, game-drive timing and lodge locations are balanced to keep the focus on wildlife rather than long transfers.',
                'duration_days' => 7, 'nights' => 6, 'price_from' => 4290,
                'travel_style' => 'Private classic safari', 'difficulty' => 'Easy',
                'start_location' => 'Arusha', 'end_location' => 'Serengeti',
                'best_time' => 'Year-round; migration locations vary seasonally',
                'accommodation_level' => 'Luxury lodges and tented camps',
                'featured' => false,
                'days' => [
                    ['Arrival beneath Mount Meru', 'Arusha', 'Arusha Coffee Lodge', 'Dinner', 45, 1, 'Meet your host and settle into a peaceful coffee estate.', 'Private airport welcome and transfer to Arusha. Your specialist reviews the route and current wildlife conditions before dinner.', ['Airport meet and assist', 'Private safari briefing']],
                    ['Baobabs of Tarangire', 'Tarangire', 'Nimali Tarangire', 'Breakfast, lunch and dinner', 145, 3, 'Begin among ancient baobabs and large elephant herds.', 'Drive to Tarangire and enter the park before lunch. Explore riverine habitats and open woodland on an afternoon game drive.', ['Afternoon game drive', 'Sundowner']],
                    ['Tarangire at dawn', 'Tarangire', 'Nimali Tarangire', 'Breakfast, lunch and dinner', 80, 4, 'A full day following wildlife between the river and quieter southern plains.', 'Start early for predators and cool-weather activity. Return for lunch and rest, then take a second drive in golden afternoon light.', ['Sunrise game drive', 'Afternoon game drive']],
                    ['The Ngorongoro Highlands', 'Karatu', 'Gibb’s Farm', 'Breakfast, lunch and dinner', 135, 3.5, 'Travel through the Rift Valley to a working farm in the green highlands.', 'Pause at local viewpoints and arrive for a farm-to-table lunch. The afternoon offers a guided garden walk or time to unwind.', ['Rift Valley viewpoints', 'Guided farm walk']],
                    ['Ngorongoro Crater', 'Ngorongoro', 'Lion’s Paw Camp', 'Breakfast, picnic lunch and dinner', 95, 4.5, 'Descend into the world’s largest intact volcanic caldera.', 'An early descent gives the best chance of uncrowded sightings. Explore grassland, forest and soda lake habitats before a scenic picnic.', ['Crater game drive', 'Private picnic lunch']],
                    ['Across the plains to Serengeti', 'Central Serengeti', 'Kubu Kubu Tented Lodge', 'Breakfast, lunch and dinner', 160, 5, 'Follow the wildlife corridor from the highlands onto the endless plains.', 'Travel via the Ngorongoro Conservation Area, stopping for wildlife and landscapes. Enter the Serengeti for an afternoon game drive en route to camp.', ['Scenic transfer game drive', 'Serengeti sunset']],
                    ['Serengeti finale', 'Central Serengeti', 'Kubu Kubu Tented Lodge', 'Breakfast and lunch', 75, 3.5, 'A final dawn game drive followed by your scheduled bush flight.', 'Search for cats and plains game at sunrise, return for breakfast, then transfer to the airstrip for the flight to Arusha or Zanzibar.', ['Sunrise game drive', 'Airstrip transfer']],
                ],
            ],
        ];

        foreach ($programs as $programIndex => $program) {
            $days = $program['days'];
            unset($program['days']);
            $itinerary = Itinerary::query()->create([
                ...$program,
                'slug' => Str::slug($program['title']),
                'currency' => 'USD',
                'minimum_guests' => 2,
                'maximum_guests' => 12,
                'status' => 'published',
                'published_at' => now()->subDays(10 - $programIndex),
                'inclusions' => [
                    'Private 4x4 safari vehicle and professional English-speaking driver-guide',
                    'Accommodation and meals exactly as shown in the day-by-day program',
                    'Park, conservancy and activity fees listed in the itinerary',
                    'Airport and airstrip transfers within the confirmed route',
                    '24-hour local operations support during travel',
                ],
                'exclusions' => [
                    'International flights, visas and comprehensive travel insurance',
                    'Premium drinks, laundry and personal purchases',
                    'Tips and gratuities for guides, lodge teams and activity staff',
                    'Activities or services not specifically listed as included',
                ],
                'important_notes' => 'Rates are indicative and remain subject to lodge and flight availability. Final rooming, child ages and travel dates must be confirmed before a quotation is converted into reservations.',
                'seo_title' => $program['title'].' | Shishi Footsteps',
                'seo_description' => $program['summary'],
            ]);

            $cover = $this->createDemoImage($itinerary, 'cover', $program['countries'], $programIndex);
            $itinerary->update(['cover_image' => $cover]);
            $itinerary->images()->create([
                'path' => $cover, 'caption' => $program['countries'].' safari landscapes',
                'alt_text' => $program['title'], 'credit' => 'Shishi Footsteps collection',
                'sort_order' => 1, 'is_cover' => true,
            ]);

            foreach ($days as $index => [$title, $location, $accommodation, $meals, $distance, $hours, $summary, $description, $activities]) {
                $image = $this->createDemoImage($itinerary, 'day-'.($index + 1), $location, $index + $programIndex);
                $day = $itinerary->days()->create([
                    'day_number' => $index + 1, 'title' => $title, 'location' => $location,
                    'overnight' => $location, 'accommodation' => $accommodation, 'meal_plan' => $meals,
                    'distance_km' => $distance, 'driving_hours' => $hours, 'summary' => $summary,
                    'description' => $description, 'activities' => $activities, 'primary_image' => $image,
                ]);
                $itinerary->images()->create([
                    'itinerary_day_id' => $day->id, 'path' => $image, 'caption' => $location,
                    'alt_text' => $title, 'credit' => 'Shishi Footsteps collection', 'sort_order' => $index + 2,
                ]);
            }
        }
    }

    private function createDemoImage(Itinerary $itinerary, string $name, string $label, int $palette): string
    {
        if (! function_exists('imagecreatetruecolor')) {
            $fallbacks = [
                'images/itineraries/kenya-family-cover.webp',
                'images/itineraries/tanzania-classic-cover.webp',
                'images/itineraries/botswana-luxury-cover.webp',
            ];

            return $fallbacks[$palette % count($fallbacks)];
        }

        $colors = [[35, 82, 65], [129, 104, 51], [71, 105, 43], [97, 75, 48]];
        [$red, $green, $blue] = $colors[$palette % count($colors)];
        $image = imagecreatetruecolor(1400, 800);
        $background = imagecolorallocate($image, $red, $green, $blue);
        $accent = imagecolorallocate($image, min(255, $red + 45), min(255, $green + 38), min(255, $blue + 25));
        $gold = imagecolorallocate($image, 224, 193, 119);
        $white = imagecolorallocate($image, 248, 247, 239);
        imagefill($image, 0, 0, $background);
        imagefilledpolygon($image, [0, 610, 420, 340, 790, 620, 1100, 390, 1400, 590, 1400, 800, 0, 800], 7, $accent);
        imagefilledellipse($image, 1130, 160, 125, 125, $gold);
        imagestring($image, 5, 70, 650, strtoupper(Str::limit($label, 38, '')), $white);
        imagestring($image, 3, 72, 687, 'SHISHI FOOTSTEPS - EAST AFRICA', $gold);
        ob_start();
        imagejpeg($image, null, 88);
        $contents = ob_get_clean();
        imagedestroy($image);

        $path = "itineraries/{$itinerary->id}/demo-{$name}.jpg";
        Storage::disk('public')->put($path, $contents);

        return $path;
    }
}
