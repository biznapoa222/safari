<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LuxurySafariTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Clean existing
        DB::table('itinerary_templates')->where('name', 'Luxury Dark Safari Proposal')->delete();

        // 1. Destinations
        $destinations = [
            ['name' => 'Masai Mara', 'country' => 'Kenya', 'description' => 'The Masai Mara National Reserve is Kenya\'s finest wildlife destination. Renowned for the Great Migration, it offers exceptional game viewing with vast savannahs and abundant wildlife.', 'highlights' => "Great Migration\nBig Five\nMasai culture\nBalloon safaris", 'wildlife' => 'Lion, leopard, elephant, buffalo, rhino, cheetah', 'best_time_to_visit' => 'July to October', 'hero_image' => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=1200', 'status' => 1],
            ['name' => 'Lake Naivasha', 'country' => 'Kenya', 'description' => 'Lake Naivasha is a freshwater lake in the Great Rift Valley with abundant birdlife and hippo populations.', 'highlights' => "Boat safaris\nHippo viewing\nBird watching\nWalking safaris", 'wildlife' => 'Hippo, giraffe, zebra, 400+ bird species', 'best_time_to_visit' => 'Year round', 'hero_image' => 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=1200', 'status' => 1],
            ['name' => 'Amboseli National Park', 'country' => 'Kenya', 'description' => 'Famous for large elephant herds and stunning views of Mount Kilimanjaro.', 'highlights' => "Elephant herds\nKilimanjaro views\nBird watching\nPhotography", 'wildlife' => 'Elephant, lion, cheetah, giraffe, zebra', 'best_time_to_visit' => 'June to October', 'hero_image' => 'https://images.unsplash.com/photo-1559564472-71484b1070bb?w=1200', 'status' => 1],
        ];

        foreach ($destinations as $d) {
            $d['created_at'] = now(); $d['updated_at'] = now();
            $ids[] = DB::table('destinations')->insertGetId($d);
        }
        list($maraId, $naivashaId, $amboseliId) = $ids;

        // 2. Hotels
        $hotels = [
            ['name' => 'Mara Serena Safari Lodge', 'destination_id' => $maraId, 'star_rating' => 5, 'tier' => 'luxury', 'meal_plan' => 'Full Board', 'description' => 'Perched on a hill overlooking the Mara River with 74 luxurious rooms, infinity pool, and gourmet dining.', 'amenities' => '["Infinity Pool","Spa","Restaurant","Bar","WiFi","Laundry"]', 'hero_image' => 'https://images.unsplash.com/photo-1540541338287-41700207dee6?w=1200', 'status' => 1],
            ['name' => 'Lake Naivasha Sopa Resort', 'destination_id' => $naivashaId, 'star_rating' => 4, 'tier' => 'luxury', 'meal_plan' => 'Full Board', 'description' => 'Set on the shores of Lake Naivasha with 60 rooms, stunning lake views, and boat safaris.', 'amenities' => '["Pool","Restaurant","Bar","Gift Shop"]', 'hero_image' => 'https://images.unsplash.com/photo-1568084680786-a84f91d1153c?w=1200', 'status' => 1],
            ['name' => 'Amboseli Serena Safari Lodge', 'destination_id' => $amboseliId, 'star_rating' => 4, 'tier' => 'luxury', 'meal_plan' => 'Full Board', 'description' => 'Nestled with breathtaking views of Mount Kilimanjaro. 60 rooms with pool and restaurant.', 'amenities' => '["Pool","Restaurant","Bar","Safari Shop"]', 'hero_image' => 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=1200', 'status' => 1],
        ];

        $hotelIds = [];
        foreach ($hotels as $h) {
            $h['created_at'] = now(); $h['updated_at'] = now();
            $hotelIds[] = DB::table('hotels')->insertGetId($h);
        }
        list($maraHotelId, $naivashaHotelId, $amboseliHotelId) = $hotelIds;

        // 3. Itinerary Template
        $templateId = DB::table('itinerary_templates')->insertGetId([
            'name' => 'Luxury Dark Safari Proposal',
            'trip_name' => 'The Great Kenyan Safari Experience',
            'destination_id' => $maraId,
            'duration_days' => 5,
            'category' => 'luxury',
            'overview' => 'Experience the very best of Kenya\'s wilderness on this carefully curated 5-day luxury safari journey.',
            'highlights' => "Game drives in the Masai Mara\nViews of Mount Kilimanjaro\nBoat safari on Lake Naivasha\nWalking safari on Crescent Island\nLuxury accommodations\nProfessional safari guide\nPrivate 4x4 vehicle",
            'includes' => "All accommodation\nProfessional English-speaking guide\nPrivate 4x4 safari vehicle\nPark & conservation fees\nAll meals as per itinerary\nBottled water\nAirport transfers",
            'excludes' => "International flights\nTravel insurance\nVisa fees\nPersonal expenses\nTips & gratuities\nOptional activities",
            'terms' => 'A 30% deposit is required to confirm your booking. Full payment is due 60 days before departure.',
            'booking_terms' => 'Deposit of 30% required. Balance due 60 days prior.',
            'payment_schedule' => '30% on booking, 70% due 60 days before departure',
            'cancellation_policy' => '60+ days: Full refund minus 10% fee. 30-60 days: 50% refund. Under 30 days: Non-refundable.',
            'refund_policy' => 'Refunds within 14 business days. Bank charges may apply.',
            'important_notes' => 'Visa required. Yellow fever vaccination recommended.',
            'notes' => 'This itinerary can be customized.',
            'status' => 'active',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // 4. Template Days
        $days = [
            [
                'day_number' => 1, 'title' => 'Arrival & Amboseli', 'destination_id' => $amboseliId,
                'hotel_id' => $amboseliHotelId, 'hotel_name' => 'Amboseli Serena Safari Lodge',
                'room_type' => 'Standard Room', 'meal_plan' => 'Breakfast, Lunch, Dinner, Drinking Water',
                'morning_activity' => 'Arrive at JKIA. Meet your guide and drive to Amboseli.',
                'afternoon_activity' => 'Afternoon game drive with views of Mount Kilimanjaro.',
                'evening_activity' => 'Gourmet dinner at the lodge.',
                'description' => 'Upon arrival, you will be welcomed by your safari guide. Enjoy a scenic drive to Amboseli National Park, arriving for lunch. Afternoon game drive in the shadow of Africa\'s highest peak.',
                'destination_intro' => 'Amboseli is famous for its elephant herds and Kilimanjaro views.',
                'wildlife_highlights' => 'Elephant, Kilimanjaro, lions, cheetahs',
                'included_services' => 'Airport pickup, private transfer, lunch, game drive, dinner',
                'optional_activities' => 'Night photography',
                'sort_order' => 1,
            ],
            [
                'day_number' => 2, 'title' => 'Amboseli to Lake Naivasha', 'destination_id' => $naivashaId,
                'hotel_id' => $naivashaHotelId, 'hotel_name' => 'Lake Naivasha Sopa Resort',
                'room_type' => 'Lake View Room', 'meal_plan' => 'Breakfast, Lunch, Dinner, Drinking Water',
                'morning_activity' => 'Early morning game drive in Amboseli. Breakfast at the lodge.',
                'afternoon_activity' => 'Depart for Lake Naivasha via the Great Rift Valley. Afternoon boat safari.',
                'evening_activity' => 'Guided nature walk. Dinner at the resort.',
                'description' => 'Rise early for a morning game drive as Kilimanjaro catches the dawn light. Journey through the Rift Valley to Lake Naivasha. Afternoon boat safari among hippos and birdlife.',
                'destination_intro' => 'Lake Naivasha is a freshwater paradise in the Rift Valley.',
                'wildlife_highlights' => 'Hippos, fish eagles, 400+ bird species',
                'included_services' => 'Breakfast, scenic drive, lunch, boat safari, guided walk, dinner',
                'optional_activities' => 'Hell\'s Gate cycling tour',
                'sort_order' => 2,
            ],
            [
                'day_number' => 3, 'title' => 'Lake Naivasha Exploration', 'destination_id' => $naivashaId,
                'hotel_id' => $naivashaHotelId, 'hotel_name' => 'Lake Naivasha Sopa Resort',
                'room_type' => 'Lake View Room', 'meal_plan' => 'Breakfast, Lunch, Dinner, Drinking Water',
                'morning_activity' => 'Walking safari on Crescent Island among giraffe and zebra.',
                'afternoon_activity' => 'Optional Hell\'s Gate visit or relaxation at the lodge.',
                'evening_activity' => 'Sundowner cocktails by the lake.',
                'description' => 'Experience the unique opportunity to walk among free-roaming wildlife on Crescent Island. The afternoon offers optional adventure at Hell\'s Gate National Park.',
                'destination_intro' => 'Walking safaris and stunning Rift Valley scenery.',
                'wildlife_highlights' => 'Walking with giraffes, zebra, hippo viewing',
                'included_services' => 'Walking safari, all meals, guided activities',
                'optional_activities' => 'Hell\'s Gate cycling ($45)',
                'sort_order' => 3,
            ],
            [
                'day_number' => 4, 'title' => 'The Masai Mara', 'destination_id' => $maraId,
                'hotel_id' => $maraHotelId, 'hotel_name' => 'Mara Serena Safari Lodge',
                'room_type' => 'Maasai Suite', 'meal_plan' => 'Breakfast, Lunch, Dinner, Drinking Water',
                'morning_activity' => 'Depart for the Masai Mara. Scenic drive with lunch en route.',
                'afternoon_activity' => 'Afternoon game drive in the Mara. Search for the Big Five.',
                'evening_activity' => 'Sundowner in the bush. Gourmet dinner at the lodge.',
                'description' => 'Journey to the crown jewel of Kenyan safaris. Arrive at the luxurious lodge overlooking the Mara River. Afternoon game drive introduces the incredible wildlife. Sundowner in the bush at sunset.',
                'destination_intro' => 'The Masai Mara is Kenya\'s most celebrated safari destination.',
                'wildlife_highlights' => 'Lions, leopards, cheetahs, elephants, Great Migration',
                'included_services' => 'Breakfast, scenic drive, lunch, game drive, sundowner, dinner',
                'optional_activities' => 'Masai village visit ($30)',
                'sort_order' => 4,
            ],
            [
                'day_number' => 5, 'title' => 'Mara Farewell', 'destination_id' => $maraId,
                'hotel_id' => null, 'hotel_name' => 'Departure Day',
                'room_type' => '', 'meal_plan' => 'Breakfast',
                'morning_activity' => 'Early morning game drive. Breakfast at the lodge.',
                'afternoon_activity' => 'Depart for Nairobi. Transfer to airport.',
                'evening_activity' => '',
                'description' => 'Final game drive as the sun rises over the Mara. After breakfast, journey back to Nairobi for your departure flight.',
                'destination_intro' => 'Farewell to the Mara.',
                'wildlife_highlights' => 'Final wildlife sightings',
                'included_services' => 'Morning game drive, breakfast, transfer to Nairobi',
                'optional_activities' => 'Nairobi sightseeing',
                'sort_order' => 5,
            ],
        ];

        foreach ($days as $day) {
            $day['itinerary_template_id'] = $templateId;
            $day['created_at'] = now(); $day['updated_at'] = now();
            DB::table('template_days')->insert($day);
        }

        // 5. Pricing
        DB::table('template_pricing')->insert([
            ['itinerary_template_id' => $templateId, 'currency' => 'USD', 'price_per_person' => 4500, 'single_supplement' => 1200, 'total_cost' => 9000, 'notes' => 'Based on 2 travellers sharing', 'created_at' => now(), 'updated_at' => now()],
            ['itinerary_template_id' => $templateId, 'currency' => 'KES', 'price_per_person' => 585000, 'single_supplement' => 156000, 'total_cost' => 1170000, 'notes' => 'KES equivalent', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 6. Template Settings
        DB::table('proposal_template_settings')->insert([
            'itinerary_template_id' => $templateId,
            'settings' => json_encode([
                'cover_heading' => 'A Curated Safari Experience',
                'client_name' => 'Sarah & Michael Thompson',
                'consultant_name' => 'Grace Mwangi',
                'consultant_email' => 'grace@shishifootsteps.com',
                'consultant_phone' => '+254 712 345 678',
                'personal_letter' => 'Thank you for choosing Shishi Footsteps to curate your dream Kenyan safari.',
                'guest_count' => '2 Adults',
                'show_investment' => true,
                'show_gallery' => true,
                'show_acceptance' => true,
                'show_company_profile' => true,
            ]),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->command->info("Template created! ID: $templateId");
        $this->command->info("Admin: /admin/itinerary-templates/$templateId");
        $this->command->info("Preview: /admin/itinerary-templates/$templateId/preview");
        $this->command->info("PDF: /admin/itinerary-templates/$templateId/pdf");
    }
}
