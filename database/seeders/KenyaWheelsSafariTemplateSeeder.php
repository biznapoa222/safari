<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KenyaWheelsSafariTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Clean existing
        DB::table('itinerary_templates')->where('name', 'like', '%Kenya on Wheels%')->delete();

        // 1. Destinations
        $destinations = [
            [
                'name' => 'Nairobi', 'country' => 'Kenya',
                'description' => 'The capital city of Kenya, a vibrant hub where urban energy meets wildlife adventure.',
                'highlights' => "Windsor Golf Hotel & Country Club\nKaren Blixen Museum\nNairobi National Park",
                'wildlife' => 'Giraffe, lion, zebra', 'best_time_to_visit' => 'Year round',
                'hero_image' => 'https://images.unsplash.com/photo-1580060839134-75a5edca2e99?w=1200', 'status' => 1,
            ],
            [
                'name' => 'Amboseli National Park', 'country' => 'Kenya',
                'description' => 'Famous for its large elephant herds and stunning views of Mount Kilimanjaro, Amboseli offers classic safari scenery with vast swamps and savannah plains.',
                'highlights' => "Large elephant herds\nMt. Kilimanjaro backdrop\nObservation Hill\nMaasai culture",
                'wildlife' => 'Elephant, lion, cheetah, giraffe, zebra, wildebeest',
                'best_time_to_visit' => 'June to October',
                'hero_image' => 'https://images.unsplash.com/photo-1456926631375-92c8ce872def?w=1200', 'status' => 1,
            ],
            [
                'name' => 'Tsavo National Park', 'country' => 'Kenya',
                'description' => 'One of the largest wildlife sanctuaries in the world, Tsavo is divided into Tsavo East and Tsavo West, known for its red elephants, volcanic landscapes, and Mzima Springs.',
                'highlights' => "Red elephants\nMzima Springs\nGalana River\nMudanda Rock\nVolcanic formations",
                'wildlife' => 'Elephant, lion, leopard, hippo, crocodile, buffalo',
                'best_time_to_visit' => 'June to October',
                'hero_image' => 'https://images.unsplash.com/photo-1559564472-71484b1070bb?w=1200', 'status' => 1,
            ],
            [
                'name' => 'Diani Beach', 'country' => 'Kenya',
                'description' => 'A stunning coastal paradise along the Indian Ocean, known for its white sandy beaches, palm trees, water sports, and vibrant marine life at Kisite Marine Park.',
                'highlights' => "White sandy beaches\nDiamond Leisure Golf Club\nKisite Marine Park\nSnorkeling & diving\nDhow cruises",
                'wildlife' => 'Whale sharks, dolphins, sea turtles, coral reef fish',
                'best_time_to_visit' => 'July to March',
                'hero_image' => 'https://images.unsplash.com/photo-1506929562872-bb421503ef21?w=1200', 'status' => 1,
            ],
        ];

        $destIds = [];
        foreach ($destinations as $d) {
            $d['created_at'] = now(); $d['updated_at'] = now();
            $destIds[] = DB::table('destinations')->insertGetId($d);
        }
        [$nairobiId, $amboseliId, $tsavoId, $dianiId] = $destIds;

        // 2. Hotels
        $hotels = [
            [
                'name' => 'Windsor Golf Hotel & Country Club', 'destination_id' => $nairobiId,
                'star_rating' => 5, 'tier' => 'luxury', 'meal_plan' => 'Half Board',
                'description' => 'A luxury hotel set on 150 acres of lush gardens with an 18-hole championship golf course, spa, pool, and elegant rooms.',
                'amenities' => '["Golf Course","Spa","Pool","Restaurant","Bar","Tennis Courts","WiFi"]',
                'hero_image' => 'https://images.unsplash.com/photo-1568084680786-a84f91d1153c?w=1200', 'status' => 1,
            ],
            [
                'name' => 'Amboseli Serena Safari Lodge', 'destination_id' => $amboseliId,
                'star_rating' => 4, 'tier' => 'luxury', 'meal_plan' => 'Full Board',
                'description' => 'Nestled with breathtaking views of Mount Kilimanjaro. Traditional architecture with modern comforts, pool, and restaurant.',
                'amenities' => '["Pool","Restaurant","Bar","Safari Shop"]',
                'hero_image' => 'https://images.unsplash.com/photo-1456926631375-92c8ce872def?w=1200', 'status' => 1,
            ],
            [
                'name' => 'Voi Safari Lodge', 'destination_id' => $tsavoId,
                'star_rating' => 4, 'tier' => 'luxury', 'meal_plan' => 'Full Board',
                'description' => 'Perched on a cliff overlooking the vast plains of Tsavo East, this lodge offers spectacular views, a pool, and game viewing from the terrace.',
                'amenities' => '["Pool","Restaurant","Bar","Viewing Deck","WiFi"]',
                'hero_image' => 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=1200', 'status' => 1,
            ],
            [
                'name' => 'Diani Reef Beach Resort & Spa', 'destination_id' => $dianiId,
                'star_rating' => 5, 'tier' => 'luxury', 'meal_plan' => 'Half Board',
                'description' => 'A premier beachfront resort on Diani Beach with luxurious rooms, multiple pools, spa, and direct beach access.',
                'amenities' => '["Pool","Spa","Beach Access","Restaurant","Bar","Water Sports"]',
                'hero_image' => 'https://images.unsplash.com/photo-1506929562872-bb421503ef21?w=1200', 'status' => 1,
            ],
        ];

        $hotelIds = [];
        foreach ($hotels as $h) {
            $h['country'] = $h['country'] ?? 'Kenya';
            $h['location'] = $h['location'] ?? 'Kenya';
            $h['status'] = $h['status'] ?? 'active';
            $h['created_at'] = now(); $h['updated_at'] = now();
            $hotelIds[] = DB::table('hotels')->insertGetId($h);
        }
        [$windsorId, $amboseliHotelId, $tsavoHotelId, $dianiHotelId] = $hotelIds;

        // 3. Itinerary Template
        $templateId = DB::table('itinerary_templates')->insertGetId([
            'name' => '10 Days 9 Nights Kenya on Wheels: Golf, Safari & Coastal Bliss',
            'trip_name' => 'Kenya on Wheels: Golf, Safari & Coastal Bliss',
            'destination_id' => $amboseliId,
            'duration_days' => 10,
            'category' => 'luxury',
            'overview' => 'Hit the open road with our Road Safari & Coastal Golf Adventure! Over 10 action-packed days, you\'ll explore Kenya\'s stunning landscapes, from the rolling hills of Nairobi to the wild wonders of Amboseli and Tsavo, before unwinding on the beautiful shores of Diani Beach. Play exhilarating rounds at premier golf courses, discover majestic wildlife up close, and indulge in vibrant coastal culture.',
            'highlights' => "Golf at Windsor's 18-hole championship course\nGame drives in Amboseli with Kilimanjaro views\nExploring Tsavo East & West\nMzima Springs hippo & croc viewing\nGolf at Diamond Leisure Golf Club\nBeach relaxation on Diani Beach\nOptional dhow trip to Kisite Marine Park\nProfessional safari guide throughout",
            'includes' => "Accommodation in Nairobi, Amboseli, Tsavo, and Diani Beach\nFull board meals during the safari\nHalf board in Diani\nAll ground transfers in a 4x4 safari vehicle\nGame drives in Amboseli and Tsavo parks\nEntry fees to national parks\n2 rounds of golf (Windsor Golf Club & Leisure Lodge)\nServices of a professional safari guide\nAirport transfers",
            'excludes' => "International flights\nTravel insurance\nVisa fees\nOptional activities (e.g. dhow trip, Maasai village visit)\nGolf equipment rental\nTips and gratuities\nPersonal expenses",
            'terms' => 'A 30% deposit is required to confirm your booking. Full payment is due 60 days before departure.',
            'booking_terms' => 'Deposit of 30% required. Balance due 60 days prior to departure.',
            'payment_schedule' => '30% on booking, 70% due 60 days before departure',
            'cancellation_policy' => '60+ days: Full refund minus 10% fee. 30-60 days: 50% refund. Under 30 days: Non-refundable.',
            'refund_policy' => 'Refunds processed within 14 business days. Bank charges may apply.',
            'important_notes' => 'Visa required for most nationalities. Yellow fever vaccination recommended. Golf equipment can be rented at both courses. Round-trip flights should arrive/depart JKIA Nairobi.',
            'notes' => 'This itinerary can be customized. Optional add-ons: Maasai village visit ($30), dhow trip to Kisite ($80), snorkeling gear rental.',
            'images' => json_encode(['https://shishifootsteps.com/wp-content/uploads/rhino-2878222-scaled.jpg']),
            'status' => 'active',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // 4. Template Days
        $days = [
            [
                'day_number' => 1,
                'title' => 'Arrival in Nairobi – Windsor Golf Hotel & Country Club',
                'destination_id' => $nairobiId,
                'hotel_id' => $windsorId,
                'hotel_name' => 'Windsor Golf Hotel & Country Club',
                'room_type' => 'Deluxe Room',
                'meal_plan' => 'Dinner, Drinking Water',
                'morning_activity' => 'Arrive at Jomo Kenyatta International Airport. Transfer to Windsor Golf Hotel & Country Club.',
                'afternoon_activity' => 'Play a round of golf at Windsor\'s 18-hole championship course.',
                'evening_activity' => 'Relax with cocktails at the clubhouse overlooking the expansive grounds.',
                'description' => 'Arrive in Nairobi and check into the Windsor Golf Hotel & Country Club. Spend the afternoon playing a round of golf at Windsor\'s 18-hole championship course. Relax in the evening with cocktails at the clubhouse overlooking the expansive grounds.',
                'destination_intro' => 'Nairobi is Kenya\'s vibrant capital, a city where urban sophistication meets wild adventure.',
                'included_services' => 'Airport pickup, hotel transfer, afternoon golf round, dinner',
                'optional_activities' => 'Spa treatment at the hotel',
                'wildlife_highlights' => 'None (city arrival)',
                'sort_order' => 1,
            ],
            [
                'day_number' => 2,
                'title' => 'Drive to Amboseli National Park',
                'destination_id' => $amboseliId,
                'hotel_id' => $amboseliHotelId,
                'hotel_name' => 'Amboseli Serena Safari Lodge',
                'room_type' => 'Standard Room',
                'meal_plan' => 'Breakfast, Lunch, Dinner, Drinking Water',
                'morning_activity' => 'Breakfast at the hotel. Depart for Amboseli National Park (approx. 4-5 hours by road).',
                'afternoon_activity' => 'Arrive for lunch at your lodge with stunning views of Mt. Kilimanjaro. Afternoon game drive in Amboseli.',
                'evening_activity' => 'Gourmet dinner at the lodge. Stargazing over the African savannah.',
                'description' => 'After breakfast, depart for Amboseli National Park. Arrive in time for lunch at your lodge with stunning views of Mt. Kilimanjaro. Afternoon game drive in Amboseli, famous for its elephant herds and views of the majestic Kilimanjaro. Overnight at a luxury tented camp.',
                'destination_intro' => 'Amboseli is renowned for its massive elephant herds and the iconic backdrop of Mount Kilimanjaro.',
                'included_services' => 'Breakfast, scenic drive, lunch, afternoon game drive, dinner',
                'optional_activities' => 'Night game drive (additional cost)',
                'wildlife_highlights' => 'Elephant, Kilimanjaro views, lion, cheetah, giraffe',
                'sort_order' => 2,
            ],
            [
                'day_number' => 3,
                'title' => 'Full-Day Amboseli Safari',
                'destination_id' => $amboseliId,
                'hotel_id' => $amboseliHotelId,
                'hotel_name' => 'Amboseli Serena Safari Lodge',
                'room_type' => 'Standard Room',
                'meal_plan' => 'Breakfast, Lunch, Dinner, Drinking Water',
                'morning_activity' => 'Early morning game drive to explore Amboseli\'s diverse landscapes, from swamps to savannah.',
                'afternoon_activity' => 'Visit Observation Hill for panoramic views of the park. Afternoon game drive.',
                'evening_activity' => 'Dinner at the lodge. Relax under the stars.',
                'description' => 'Morning and afternoon game drives to explore Amboseli\'s diverse landscapes, from swamps to savannah. Visit Observation Hill for panoramic views of the park. Optional visit to a local Maasai village to learn about their culture and traditions. Return to the lodge for a quiet evening under the stars.',
                'destination_intro' => 'Full-day immersion in Africa\'s best elephant-viewing destination.',
                'included_services' => 'Morning game drive, breakfast, afternoon game drive, lunch, dinner',
                'optional_activities' => 'Maasai village visit ($30 per person)',
                'wildlife_highlights' => 'Elephant herds, hippos at swamps, birdlife, Kilimanjaro sunrise',
                'sort_order' => 3,
            ],
            [
                'day_number' => 4,
                'title' => 'Drive to Tsavo East National Park',
                'destination_id' => $tsavoId,
                'hotel_id' => $tsavoHotelId,
                'hotel_name' => 'Voi Safari Lodge',
                'room_type' => 'Standard Room',
                'meal_plan' => 'Breakfast, Lunch, Dinner, Drinking Water',
                'morning_activity' => 'Breakfast at the lodge. Depart for Tsavo East National Park (approx. 4 hours). Game drive en route.',
                'afternoon_activity' => 'Game drive exploring the Galana River and Mudanda Rock.',
                'evening_activity' => 'Enjoy the sounds of the wild at night from your lodge.',
                'description' => 'After breakfast, drive to Tsavo East National Park. Game drive en route to your lodge, spotting elephants, lions, and the iconic red soil that colors the wildlife. An afternoon spent on a game drive exploring the Galana River and Mudanda Rock. Overnight at your Tsavo lodge, enjoying the sounds of the wild at night.',
                'destination_intro' => 'Tsavo East is one of the world\'s largest wildlife sanctuaries, known for its red elephants and dramatic landscapes.',
                'included_services' => 'Breakfast, scenic drive, lunch, game drive, dinner',
                'optional_activities' => 'Guided nature walk',
                'wildlife_highlights' => 'Red elephants, lions, galana river hippos, birdlife',
                'sort_order' => 4,
            ],
            [
                'day_number' => 5,
                'title' => 'Tsavo West National Park – Safari & Relaxation',
                'destination_id' => $tsavoId,
                'hotel_id' => $tsavoHotelId,
                'hotel_name' => 'Voi Safari Lodge',
                'room_type' => 'Standard Room',
                'meal_plan' => 'Breakfast, Lunch, Dinner, Drinking Water',
                'morning_activity' => 'Explore Tsavo West with a morning drive through stunning landscapes and volcanic formations.',
                'afternoon_activity' => 'Visit the famous Mzima Springs, where hippos and crocodiles can be spotted through clear waters. Afternoon at leisure.',
                'evening_activity' => 'Dinner at the lodge.',
                'description' => 'Explore Tsavo West today, with a morning drive through the stunning landscapes and volcanic formations. Visit the famous Mzima Springs, where hippos and crocodiles can be spotted through the clear waters. Afternoon at leisure back at the lodge.',
                'destination_intro' => 'Tsavo West offers diverse volcanic landscapes, underground rivers, and incredible wildlife.',
                'included_services' => 'Morning game drive, breakfast, Mzima Springs visit, lunch, dinner',
                'optional_activities' => 'Shetani lava flow hike',
                'wildlife_highlights' => 'Hippos, crocodiles, elephants, buffalo, volcanic landscapes',
                'sort_order' => 5,
            ],
            [
                'day_number' => 6,
                'title' => 'Drive to Diani Beach',
                'destination_id' => $dianiId,
                'hotel_id' => $dianiHotelId,
                'hotel_name' => 'Diani Reef Beach Resort & Spa',
                'room_type' => 'Ocean View Room',
                'meal_plan' => 'Breakfast, Dinner, Drinking Water',
                'morning_activity' => 'Early morning game drive before heading to Diani Beach (approx. 4-5 hours drive).',
                'afternoon_activity' => 'Check into your beach resort. Relax and unwind after the safari.',
                'evening_activity' => 'Walk along the white sandy beach. Enjoy a seafood dinner.',
                'description' => 'Early morning game drive before heading to Diani Beach. Check into your beach resort, where you can relax and unwind after the safari. Evening walk along the white sandy beach, enjoying a seafood dinner.',
                'destination_intro' => 'Diani Beach is a tropical paradise with powdery white sand, turquoise waters, and swaying palm trees.',
                'included_services' => 'Morning game drive, breakfast, scenic drive, resort check-in, dinner',
                'optional_activities' => 'Beach massage',
                'wildlife_highlights' => 'Beachside relaxation, Indian Ocean views',
                'sort_order' => 6,
            ],
            [
                'day_number' => 7,
                'title' => 'Diani Beach Golf & Leisure',
                'destination_id' => $dianiId,
                'hotel_id' => $dianiHotelId,
                'hotel_name' => 'Diani Reef Beach Resort & Spa',
                'room_type' => 'Ocean View Room',
                'meal_plan' => 'Breakfast, Lunch, Dinner, Drinking Water',
                'morning_activity' => 'Play golf at Diamond Leisure Golf Club.',
                'afternoon_activity' => 'Relax on the beach or by the pool.',
                'evening_activity' => 'Sunset cocktails at the resort bar.',
                'description' => 'Play golf at Diamond Leisure Golf Club, with mornings spent on the course and afternoons on the beach.',
                'destination_intro' => 'Diamond Leisure Golf Club offers a challenging course set amidst coconut palms with ocean breezes.',
                'included_services' => 'Green fee at Diamond Leisure, breakfast, lunch, dinner',
                'optional_activities' => 'Golf equipment rental',
                'wildlife_highlights' => 'Coastal scenery, ocean views from the course',
                'sort_order' => 7,
            ],
            [
                'day_number' => 8,
                'title' => 'Diani Beach Relaxation',
                'destination_id' => $dianiId,
                'hotel_id' => $dianiHotelId,
                'hotel_name' => 'Diani Reef Beach Resort & Spa',
                'room_type' => 'Ocean View Room',
                'meal_plan' => 'Breakfast, Lunch, Dinner, Drinking Water',
                'morning_activity' => 'Full day at leisure. Relax on the beach or by the pool.',
                'afternoon_activity' => 'Optional: snorkeling, kite surfing, or visiting the Colobus Conservation Center.',
                'evening_activity' => 'Seafood dinner at a beachfront restaurant.',
                'description' => 'Full-day relaxation or optional activities, including snorkeling, kite surfing, or visiting the Colobus Conservation Center.',
                'destination_intro' => 'A day to unwind on Kenya\'s most beautiful coastline.',
                'included_services' => 'All meals at the resort',
                'optional_activities' => 'Snorkeling trip ($50), Kite surfing lesson ($70), Colobus Conservation visit ($20)',
                'wildlife_highlights' => 'Colobus monkeys, marine life',
                'sort_order' => 8,
            ],
            [
                'day_number' => 9,
                'title' => 'Diani Beach – Exploration Day',
                'destination_id' => $dianiId,
                'hotel_id' => $dianiHotelId,
                'hotel_name' => 'Diani Reef Beach Resort & Spa',
                'room_type' => 'Ocean View Room',
                'meal_plan' => 'Breakfast, Lunch, Dinner, Drinking Water',
                'morning_activity' => 'Optional visit to Shimoni Caves or a dhow trip to Kisite Marine Park.',
                'afternoon_activity' => 'Return to resort. Afternoon at leisure.',
                'evening_activity' => 'Farewell dinner with sundowner cocktails.',
                'description' => 'Optional visit to Shimoni Caves or a dhow trip to Kisite Marine Park, where you can snorkel among colorful coral reefs and swim with dolphins and sea turtles.',
                'destination_intro' => 'Kisite Marine Park is a protected reserve with vibrant coral gardens and abundant marine life.',
                'included_services' => 'All meals at the resort',
                'optional_activities' => 'Dhow trip to Kisite Marine Park ($80 per person), Shimoni Caves tour ($40)',
                'wildlife_highlights' => 'Dolphins, sea turtles, coral reefs, tropical fish',
                'sort_order' => 9,
            ],
            [
                'day_number' => 10,
                'title' => 'Return to Nairobi & Departure',
                'destination_id' => $nairobiId,
                'hotel_id' => null,
                'hotel_name' => 'Departure Day',
                'room_type' => '',
                'meal_plan' => 'Breakfast',
                'morning_activity' => 'Breakfast at the resort. Depart Diani for Nairobi by road or opt for a quick flight.',
                'afternoon_activity' => 'Arrive in Nairobi. Transfer to the airport for your international departure.',
                'evening_activity' => '',
                'description' => 'After breakfast, depart Diani for Nairobi by road or opt for a quick flight. Arrive in Nairobi and transfer to the airport for your international departure.',
                'destination_intro' => 'Journey back to Nairobi with memories of an unforgettable Kenyan adventure.',
                'included_services' => 'Breakfast, transfer to Nairobi (road or flight), airport transfer',
                'optional_activities' => 'Nairobi sightseeing (if time permits)',
                'wildlife_highlights' => 'Final scenic drive through Kenya\'s landscapes',
                'sort_order' => 10,
            ],
        ];

        foreach ($days as $day) {
            $day['itinerary_template_id'] = $templateId;
            $day['created_at'] = now(); $day['updated_at'] = now();
            DB::table('template_days')->insert($day);
        }

        // 5. Pricing
        DB::table('template_pricing')->insert([
            [
                'itinerary_template_id' => $templateId,
                'currency' => 'USD',
                'price_per_person' => 4500,
                'single_supplement' => 1200,
                'total_cost' => 9000,
                'notes' => 'Based on 2 travellers sharing',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'itinerary_template_id' => $templateId,
                'currency' => 'KES',
                'price_per_person' => 585000,
                'single_supplement' => 156000,
                'total_cost' => 1170000,
                'notes' => 'KES equivalent',
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        // 6. Template Settings
        DB::table('proposal_template_settings')->insert([
            'itinerary_template_id' => $templateId,
            'settings' => json_encode([
                'cover_heading' => 'Kenya on Wheels',
                'client_name' => 'Your Safari Adventure',
                'consultant_name' => 'Grace Mwangi',
                'consultant_email' => 'grace@shishifootsteps.com',
                'consultant_phone' => '+254 725 346 022',
                'personal_letter' => 'Thank you for choosing Shishi Footsteps to curate your Kenyan road safari adventure. This 10-day journey combines the thrill of Africa\'s wild landscapes with the luxury of world-class golf courses and the tranquility of the Indian Ocean coast.',
                'guest_count' => '2 Adults',
                'show_investment' => true,
                'show_gallery' => true,
                'show_acceptance' => true,
                'show_company_profile' => true,
            ]),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->command->info("Kenya on Wheels template created! ID: $templateId");
        $this->command->info("Admin: /admin/itinerary-templates/$templateId");
        $this->command->info("Preview: /admin/itinerary-templates/$templateId/preview");
        $this->command->info("PDF: /admin/itinerary-templates/$templateId/pdf");
    }
}
