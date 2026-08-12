<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SouthAfricaContentSeeder extends Seeder
{
    public function run(): void
    {
        // Clean existing South Africa data
        DB::table('itinerary_templates')->where('name', 'like', '%South Africa%')->orWhere('name', 'like', '%Fairways%')->orWhere('name', 'like', '%Garden Route%')->orWhere('name', 'like', '%Kruger%')->orWhere('name', 'like', '%Cape Town%')->delete();

        $img = fn(string $id) => "https://images.unsplash.com/photo-{$id}?auto=format&fit=crop&w=1400&q=84&fm=webp";

        // 1. Destinations
        $destinations = [
            ['name' => 'Cape Town', 'country' => 'South Africa', 'description' => 'The Mother City, framed by Table Mountain and the Atlantic Ocean. A vibrant hub of culture, cuisine, and natural beauty.', 'highlights' => "Table Mountain\nV&A Waterfront\nBo-Kaap\nKirstenbosch Gardens\nCamps Bay", 'wildlife' => 'Penguins at Boulders Beach, whales, seabirds', 'best_time_to_visit' => 'October to April', 'hero_image' => $img('1484318571209-661cf29a69c3'), 'status' => 1],
            ['name' => 'Stellenbosch', 'country' => 'South Africa', 'description' => 'The heart of South Africa\'s wine country, known for Cape Dutch architecture, award-winning estates, and mountain scenery.', 'highlights' => "Wine estates\nCape Dutch architecture\nStellenbosch Golf Club\nHelshoogte Pass", 'wildlife' => 'Birdlife, horses', 'best_time_to_visit' => 'October to April', 'hero_image' => $img('1558030004-b4c2c0e3e7f6'), 'status' => 1],
            ['name' => 'Franschhoek', 'country' => 'South Africa', 'description' => 'The gourmet capital of South Africa, a historic French Huguenot town surrounded by vineyards and mountains.', 'highlights' => "Pearl Valley Golf Estate\nFine dining\nMéthode Cap Classique\nMotor Museum", 'wildlife' => 'Birdlife, mountain wildlife', 'best_time_to_visit' => 'October to April', 'hero_image' => $img('1571896349842-41c6b8c3f0e1'), 'status' => 1],
            ['name' => 'Hermanus', 'country' => 'South Africa', 'description' => 'World-renowned land-based whale watching destination with charming seaside atmosphere and coastal walks.', 'highlights' => "Whale watching\nBenguela Cove Wine Estate\nCliff paths\nCoastal scenery", 'wildlife' => 'Southern right whales, dolphins, seabirds', 'best_time_to_visit' => 'June to November', 'hero_image' => $img('1516483638261-f4dbaf036963'), 'status' => 1],
            ['name' => 'Durban', 'country' => 'South Africa', 'description' => 'A subtropical coastal city known for its golden beaches, Indian Ocean warmth, and vibrant multicultural energy.', 'highlights' => "Umhlanga Rocks\nuShaka Marine World\nDurban Country Club\nFlorida Road dining", 'wildlife' => 'Marine life, dolphins, sharks', 'best_time_to_visit' => 'April to September', 'hero_image' => $img('1507525428034-b723cf961d3e'), 'status' => 1],
            ['name' => 'Kruger National Park', 'country' => 'South Africa', 'description' => 'One of Africa\'s greatest wildlife reserves, home to the Big Five and an extraordinary diversity of ecosystems.', 'highlights' => "Big Five safaris\nSkukuza Golf Club\nGame drives\nBush dining", 'wildlife' => 'Lion, elephant, leopard, rhino, buffalo, cheetah, wild dog', 'best_time_to_visit' => 'May to September', 'hero_image' => $img('1534177616072-ef7dc120449d'), 'status' => 1],
            ['name' => 'Mossel Bay', 'country' => 'South Africa', 'description' => 'A coastal town along the Garden Route with beautiful beaches, historic lighthouse, and the Point of Human Origins.', 'highlights' => "Pinnacle Point Golf\nBeaches\nLighthouse\nCave tours", 'wildlife' => 'Whales, dolphins, seabirds', 'best_time_to_visit' => 'October to April', 'hero_image' => $img('1507525428034-b723cf961d3e'), 'status' => 1],
            ['name' => 'Knysna', 'country' => 'South Africa', 'description' => 'A jewel of the Garden Route, famous for its lagoon, forests, waterfront, and the Knysna Heads.', 'highlights' => "Knysna Lagoon\nFeatherbed Reserve\nSimola Golf Estate\nPezula Championship Course", 'wildlife' => 'Forest birds, marine life, elephants at Knysna Elephant Park', 'best_time_to_visit' => 'October to April', 'hero_image' => $img('1507525428034-b723cf961d3e'), 'status' => 1],
            ['name' => 'Plettenberg Bay', 'country' => 'South Africa', 'description' => 'A stunning beach town on the Garden Route with golden sands, marine reserves, and dramatic coastal scenery.', 'highlights' => "Robberg Nature Reserve\nBeaches\nSeal snorkeling\nWine tasting", 'wildlife' => 'Cape fur seals, dolphins, whales', 'best_time_to_visit' => 'October to April', 'hero_image' => $img('1507525428034-b723cf961d3e'), 'status' => 1],
            ['name' => 'Tsitsikamma', 'country' => 'South Africa', 'description' => 'A rugged stretch of coastline within the Garden Route National Park, known for ancient forests, dramatic cliffs, and adventure activities.', 'highlights' => "Storms River\nSuspension bridge\nKayaking\nForest hikes", 'wildlife' => 'Forest birds, otters, marine life', 'best_time_to_visit' => 'October to April', 'hero_image' => $img('1507525428034-b723cf961d3e'), 'status' => 1],
            ['name' => 'Jeffreys Bay', 'country' => 'South Africa', 'description' => 'South Africa\'s surf capital, famous for world-class waves, laid-back vibe, and stunning beaches.', 'highlights' => "Surfing\nSandboarding\nSeafood\nSt Francis Links Golf", 'wildlife' => 'Marine life, seabirds', 'best_time_to_visit' => 'December to March', 'hero_image' => $img('1507525428034-b723cf961d3e'), 'status' => 1],
            ['name' => 'Johannesburg', 'country' => 'South Africa', 'description' => 'South Africa\'s largest city, a dynamic hub of culture, history, and gateway to Kruger National Park.', 'highlights' => "Apartheid Museum\nSoweto\nGold Reef City\nPanorama Route", 'wildlife' => 'Lion Park, birdlife', 'best_time_to_visit' => 'Year round', 'hero_image' => $img('1580060839134-75a5edca2e99'), 'status' => 1],
        ];

        $destIds = [];
        foreach ($destinations as $d) {
            $d['created_at'] = now();
            $d['updated_at'] = now();
            $destIds[] = DB::table('destinations')->insertGetId($d);
        }
        [$capeTownId, $stellenboschId, $franschhoekId, $hermanusId, $durbanId, $krugerId, $mosselBayId, $knysnaId, $plettenbergId, $tsitsikammaId, $jeffreysId, $johannesburgId] = $destIds;

        // 2. Hotels
        $hotels = [
            ['name' => 'The Bay Hotel', 'destination_id' => $capeTownId, 'star_rating' => 5, 'tier' => 'luxury', 'meal_plan' => 'Bed & Breakfast', 'description' => 'Iconic beachfront hotel on Camps Bay with stunning ocean views, pool, and world-class dining.', 'amenities' => '["Pool","Restaurant","Bar","Spa","WiFi","Gym"]', 'hero_image' => $img('1535131749006-b7f58c99034b'), 'status' => 1],
            ['name' => 'Cape Heritage Hotel', 'destination_id' => $capeTownId, 'star_rating' => 4, 'tier' => 'boutique', 'meal_plan' => 'Bed & Breakfast', 'description' => 'Boutique heritage hotel in the heart of the Bo-Kaap district with Cape Malay charm.', 'amenities' => '["Restaurant","Bar","WiFi","Concierge"]', 'hero_image' => $img('1535131749006-b7f58c99034b'), 'status' => 1],
            ['name' => 'Steenberg Hotel', 'destination_id' => $capeTownId, 'star_rating' => 5, 'tier' => 'luxury', 'meal_plan' => 'Bed & Breakfast', 'description' => 'Luxury hotel set on a historic wine estate with championship golf course and award-winning spa.', 'amenities' => '["Golf Course","Spa","Pool","Restaurant","Bar","WiFi"]', 'hero_image' => $img('1535131749006-b7f58c99034b'), 'status' => 1],
            ['name' => 'Le Petit Manoir', 'destination_id' => $franschhoekId, 'star_rating' => 5, 'tier' => 'luxury', 'meal_plan' => 'Bed & Breakfast', 'description' => 'Intimate luxury manor house in Franschhoek with vineyard views and gourmet breakfast.', 'amenities' => '["Pool","Restaurant","WiFi","Garden","Wine Cellar"]', 'hero_image' => $img('1571896349842-41c6b8c3f0e1'), 'status' => 1],
            ['name' => 'The Marine Hermanus', 'destination_id' => $hermanusId, 'star_rating' => 5, 'tier' => 'luxury', 'meal_plan' => 'Bed & Breakfast', 'description' => 'Iconic seafront hotel overlooking Walker Bay with whale watching from the property.', 'amenities' => '["Pool","Restaurant","Bar","Spa","WiFi"]', 'hero_image' => $img('1516483638261-f4dbaf036963'), 'status' => 1],
            ['name' => 'Durban Country Club', 'destination_id' => $durbanId, 'star_rating' => 4, 'tier' => 'premium', 'meal_plan' => 'Half Board', 'description' => 'Historic beachfront hotel with direct access to golf course and Indian Ocean views.', 'amenities' => '["Golf Course","Pool","Restaurant","Bar","WiFi"]', 'hero_image' => $img('1507525428034-b723cf961d3e'), 'status' => 1],
            ['name' => 'Kruger Shalati', 'destination_id' => $krugerId, 'star_rating' => 5, 'tier' => 'luxury', 'meal_plan' => 'Full Board', 'description' => 'Iconic train-on-bridge hotel inside Kruger National Park overlooking the Sabie River.', 'amenities' => '["Pool","Restaurant","Bar","Spa","Game Drives","WiFi"]', 'hero_image' => $img('1534177616072-ef7dc120449d'), 'status' => 1],
            ['name' => 'Pinnacle Point Hotel', 'destination_id' => $mosselBayId, 'star_rating' => 5, 'tier' => 'luxury', 'meal_plan' => 'Half Board', 'description' => 'Luxury hotel overlooking the Indian Ocean with championship golf and spa.', 'amenities' => '["Golf Course","Spa","Pool","Restaurant","Bar","WiFi"]', 'hero_image' => $img('1507525428034-b723cf961d3e'), 'status' => 1],
            ['name' => 'Knysna Hollow Country Estate', 'destination_id' => $knysnaId, 'star_rating' => 4, 'tier' => 'premium', 'meal_plan' => 'Half Board', 'description' => 'Country estate and hotel set in lush gardens near the Knysna Lagoon.', 'amenities' => '["Pool","Restaurant","Bar","Tennis","WiFi"]', 'hero_image' => $img('1507525428034-b723cf961d3e'), 'status' => 1],
            ['name' => 'The Plettenberg Hotel', 'destination_id' => $plettenbergId, 'star_rating' => 5, 'tier' => 'luxury', 'meal_plan' => 'Bed & Breakfast', 'description' => 'Five-star boutique hotel perched on a rocky headland with panoramic ocean views.', 'amenities' => '["Pool","Restaurant","Bar","Spa","WiFi"]', 'hero_image' => $img('1507525428034-b723cf961d3e'), 'status' => 1],
            ['name' => 'Tsitsikamma Lodge', 'destination_id' => $tsitsikammaId, 'star_rating' => 4, 'tier' => 'premium', 'meal_plan' => 'Half Board', 'description' => 'Log cabin-style lodge surrounded by indigenous forest near Storms River.', 'amenities' => '["Pool","Restaurant","Bar","Spa","WiFi"]', 'hero_image' => $img('1507525428034-b723cf961d3e'), 'status' => 1],
            ['name' => 'Misty Waves Boutique Hotel', 'destination_id' => $jeffreysId, 'star_rating' => 4, 'tier' => 'boutique', 'meal_plan' => 'Bed & Breakfast', 'description' => 'Boutique hotel steps from the beach with relaxed surf-chic style.', 'amenities' => '["Pool","Restaurant","Bar","WiFi"]', 'hero_image' => $img('1507525428034-b723cf961d3e'), 'status' => 1],
            ['name' => 'Royal Livingstone Hotel', 'destination_id' => $johannesburgId, 'star_rating' => 5, 'tier' => 'luxury', 'meal_plan' => 'Half Board', 'description' => 'Luxury hotel located near Johannesburg with elegant rooms and premium amenities.', 'amenities' => '["Pool","Restaurant","Bar","Spa","WiFi","Gym"]', 'hero_image' => $img('1580060839134-75a5edca2e99'), 'status' => 1],
        ];

        $hotelIds = [];
        foreach ($hotels as $h) {
            $h['created_at'] = now();
            $h['updated_at'] = now();
            $hotelIds[] = DB::table('hotels')->insertGetId($h);
        }
        [$bayHotelId, $capeHeritageId, $steenbergHotelId, $petitManorId, $marineId, $durbanClubId, $shalatiId, $pinnacleId, $knysnaHollowId, $plettenbergHotelId, $tsitsikammaLodgeId, $mistyWavesId, $livingstoneId] = $hotelIds;

        // ========================================================================
        // ITINERARY 1: FAIRWAYS & THE MOTHER CITY (6-day golf)
        // ========================================================================
        $template1Id = DB::table('itinerary_templates')->insertGetId([
            'name' => 'Fairways & The Mother City',
            'trip_name' => 'Fairways & The Mother City: South Africa Golf Journey',
            'destination_id' => $capeTownId,
            'duration_days' => 6,
            'category' => 'luxury',
            'overview' => 'This journey is designed for first-time South Africa golf travellers who want iconic courses without long drives. Every golf course is chosen for heritage, playability, scenery, and proximity, ensuring minimal fatigue and maximum enjoyment. The routing flows naturally from city to vineyards to relaxed countryside.',
            'highlights' => "Royal Cape Golf Club – South Africa's oldest course\nSteenberg Golf Club – championship layout in wine country\nPearl Valley Golf Estate – top-ranked Winelands course\nTable Mountain cableway\nBo-Kaap cultural district\nFranschhoek wine tram experience\nCape Winelands dining",
            'includes' => "5 nights accommodation\nDaily breakfast\n3 rounds of golf at premier courses\nShared golf cart where applicable\nAirport transfers\nTable Mountain cable car ticket\nWine tasting at Steenberg Estate",
            'excludes' => "International flights\nTravel insurance\nVisa fees\nLunches and dinners (unless stated)\nGolf equipment rental\nCaddie fees\nTips and gratuities\nPersonal expenses",
            'terms' => 'A 30% deposit is required to confirm your booking. Full payment is due 60 days before departure.',
            'booking_terms' => 'Deposit of 30% required. Balance due 60 days prior to departure.',
            'payment_schedule' => '30% on booking, 70% due 60 days before departure',
            'cancellation_policy' => '60+ days: Full refund minus 10% fee. 30-60 days: 50% refund. Under 30 days: Non-refundable.',
            'refund_policy' => 'Refunds processed within 14 business days. Bank charges may apply.',
            'important_notes' => 'Access to Table Mountain may be limited due to weather conditions or cableway maintenance. In such cases, alternative sightseeing options will be arranged. Golf attire and soft spikes required at all clubs.',
            'notes' => 'This itinerary can be customized. Additional rounds at other Cape Town courses available on request.',
            'images' => json_encode([$img('1592919505780-303950717480'), $img('1535131749006-b7f58c99034b'), $img('1571896349842-41c6b8c3f0e1')]),
            'status' => 'active',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $template1Days = [
            [
                'day_number' => 1, 'title' => 'Arrival in Cape Town – Settling into the Mother City',
                'destination_id' => $capeTownId, 'hotel_id' => $bayHotelId, 'hotel_name' => 'The Bay Hotel',
                'room_type' => 'Ocean View Room', 'meal_plan' => 'Breakfast',
                'morning_activity' => 'Arrive at Cape Town International Airport. Private transfer to your hotel near the V&A Waterfront or Atlantic Seaboard.',
                'afternoon_activity' => 'The remainder of the day is left unstructured for rest and recovery after your flight. Optional sunset stroll or casual dinner overlooking the harbour.',
                'evening_activity' => 'Relaxed dinner at a waterfront restaurant. Early night recommended before golf begins.',
                'description' => 'Arrive at Cape Town International Airport and transfer to your hotel near the V&A Waterfront or Atlantic Seaboard. This location is intentional: it reduces driving time for early golf rounds and keeps you close to dining, shopping, and scenic promenades. The remainder of the day is left unstructured. Long-haul travellers benefit from light movement, fresh air, and rest before golf begins.',
                'destination_intro' => 'Cape Town is one of the world\'s most beautiful cities, framed by Table Mountain and the Atlantic Ocean.',
                'included_services' => 'Airport pickup, hotel transfer, accommodation',
                'optional_activities' => 'Sunset cruise at V&A Waterfront',
                'wildlife_highlights' => 'City arrival – no wildlife activities',
                'sort_order' => 1,
            ],
            [
                'day_number' => 2, 'title' => 'Royal Cape Golf Club – History, Strategy & Mountain Views',
                'destination_id' => $capeTownId, 'hotel_id' => $bayHotelId, 'hotel_name' => 'The Bay Hotel',
                'room_type' => 'Ocean View Room', 'meal_plan' => 'Breakfast',
                'morning_activity' => 'Morning tee time at Royal Cape Golf Club. South Africa\'s oldest course with parkland layout, mature trees, and mountain backdrop.',
                'afternoon_activity' => 'Post-round exploration of Cape Town\'s cultural highlights: Bo-Kaap, Company\'s Garden, or Table Mountain cableway if weather allows.',
                'evening_activity' => 'Traditional South African dinner in Camps Bay with beachside views.',
                'description' => 'Royal Cape is South Africa\'s oldest golf course, and it introduces you to the country\'s golfing heritage. Its parkland layout, mature trees, and mountain backdrop make it both forgiving and strategic — ideal for easing into the trip. Morning tee time ensures calm conditions. Post-round, the afternoon is dedicated to Cape Town\'s cultural highlights.',
                'destination_intro' => 'Royal Cape Golf Club has been a cornerstone of South African golf since 1885.',
                'included_services' => 'Breakfast, green fee at Royal Cape, shared cart, hotel transfer',
                'optional_activities' => 'Table Mountain cableway (weather permitting)',
                'wildlife_highlights' => 'City sights, mountain views',
                'sort_order' => 2,
            ],
            [
                'day_number' => 3, 'title' => 'Steenberg Golf Club – Championship Golf meets Wine Country',
                'destination_id' => $capeTownId, 'hotel_id' => $steenbergHotelId, 'hotel_name' => 'Steenberg Hotel',
                'room_type' => 'Luxury Room', 'meal_plan' => 'Breakfast',
                'morning_activity' => 'Morning round at Steenberg Golf Club, a modern championship layout set within South Africa\'s oldest wine region, Constantia.',
                'afternoon_activity' => 'Wine tasting at Steenberg Estate. Afternoon at leisure for recovery before the Winelands leg.',
                'evening_activity' => 'Gourmet dinner at the hotel or nearby Constantia restaurant.',
                'description' => 'Steenberg offers a modern championship layout set within South Africa\'s oldest wine region, Constantia. This pairing of elite golf and wine culture is uniquely Cape Town. Morning golf is followed by a wine tasting at Steenberg Estate, allowing golfers to unwind without further travel.',
                'destination_intro' => 'Constantia is South Africa\'s oldest wine-producing region, dating back to 1685.',
                'included_services' => 'Breakfast, green fee at Steenberg, shared cart, wine tasting',
                'optional_activities' => 'Spa treatment at Steenberg',
                'wildlife_highlights' => 'Wine estate views, Constantia greenbelt',
                'sort_order' => 3,
            ],
            [
                'day_number' => 4, 'title' => 'Cape Town to Franschhoek – Pearl Valley Golf Estate',
                'destination_id' => $franschhoekId, 'hotel_id' => $petitManorId, 'hotel_name' => 'Le Petit Manoir',
                'room_type' => 'Manor Room', 'meal_plan' => 'Breakfast',
                'morning_activity' => 'Scenic drive to the Cape Winelands (±1 hour) through beautiful mountain passes.',
                'afternoon_activity' => 'Afternoon round at Pearl Valley Golf Estate, consistently ranked among South Africa\'s top courses with dramatic water features and mountain-framed fairways.',
                'evening_activity' => 'Evening at leisure in Franschhoek. A deliberate choice to combine golf with exceptional dining.',
                'description' => 'Drive to the Cape Winelands (±1 hour). Pearl Valley is consistently ranked among South Africa\'s top golf courses due to its immaculate conditioning, dramatic water features, and mountain-framed fairways. It represents the peak of Winelands golf. The route avoids city traffic and introduces a slower, countryside pace.',
                'destination_intro' => 'Franschhoek is the gourmet capital of South Africa, with French Huguenot heritage and world-class restaurants.',
                'included_services' => 'Breakfast, scenic transfer, green fee at Pearl Valley, shared cart, hotel transfer',
                'optional_activities' => 'Franschhoek wine tram, fine dining reservation',
                'wildlife_highlights' => 'Winelands scenery, mountain views',
                'sort_order' => 4,
            ],
            [
                'day_number' => 5, 'title' => 'Leisure, Wine & Optional Golf',
                'destination_id' => $franschhoekId, 'hotel_id' => $petitManorId, 'hotel_name' => 'Le Petit Manoir',
                'room_type' => 'Manor Room', 'meal_plan' => 'Breakfast',
                'morning_activity' => 'Day at leisure. Optional extra round at Pearl Valley or a nearby course.',
                'afternoon_activity' => 'Guided wine experience or spa treatment. Franschhoek wine tram available.',
                'evening_activity' => 'Farewell dinner at a top Franschhoek restaurant.',
                'description' => 'This day exists for balance. Guests may add an extra round, enjoy a spa treatment, or take a guided wine experience. This flexibility is intentional — golf travel should never feel rushed.',
                'destination_intro' => 'A day to savour the Winelands at your own pace.',
                'included_services' => 'Breakfast',
                'optional_activities' => 'Extra golf round, wine tram tour, spa treatment, cooking class',
                'wildlife_highlights' => 'Winelands leisure',
                'sort_order' => 5,
            ],
            [
                'day_number' => 6, 'title' => 'Departure',
                'destination_id' => $capeTownId, 'hotel_id' => null, 'hotel_name' => 'Departure Day',
                'room_type' => '', 'meal_plan' => 'Breakfast',
                'morning_activity' => 'Breakfast at the hotel. Check out and transfer to Cape Town International Airport.',
                'afternoon_activity' => 'Departure flight.',
                'evening_activity' => '',
                'description' => 'Return to Cape Town International Airport for your onward flight.',
                'destination_intro' => 'Departure from Cape Town.',
                'included_services' => 'Breakfast, transfer to airport',
                'optional_activities' => 'Last-minute souvenir shopping at Waterfront',
                'wildlife_highlights' => '',
                'sort_order' => 6,
            ],
        ];

        foreach ($template1Days as $day) {
            $day['itinerary_template_id'] = $template1Id;
            $day['created_at'] = now();
            $day['updated_at'] = now();
            DB::table('template_days')->insert($day);
        }

        DB::table('template_pricing')->insert([
            ['itinerary_template_id' => $template1Id, 'currency' => 'USD', 'price_per_person' => 3200, 'single_supplement' => 950, 'total_cost' => 6400, 'notes' => 'Based on 2 travellers sharing. Golf included.', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('proposal_template_settings')->insert([
            'itinerary_template_id' => $template1Id,
            'settings' => json_encode([
                'cover_heading' => 'Fairways & The Mother City',
                'client_name' => 'Your South Africa Golf Adventure',
                'consultant_name' => 'Grace Mwangi',
                'consultant_email' => 'grace@shishifootsteps.com',
                'consultant_phone' => '+254 725 346 022',
                'personal_letter' => 'Thank you for choosing Shishi Footsteps to curate your South Africa golf journey. This 6-day itinerary combines iconic Cape Town courses with the relaxed elegance of the Cape Winelands.',
                'guest_count' => '2 Adults',
                'show_investment' => true, 'show_gallery' => true, 'show_acceptance' => true, 'show_company_profile' => true,
            ]),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->command->info("Template created: Fairways & The Mother City (ID: $template1Id)");

        // ========================================================================
        // ITINERARY 2: CAPE TOWN, WHALES & WINE ROUTES (6-day self-drive)
        // ========================================================================
        $template2Id = DB::table('itinerary_templates')->insertGetId([
            'name' => 'Cape Town, Whales & Wine Routes',
            'trip_name' => '6 Days in South Africa: Cape Town, Whales & Wine Routes',
            'destination_id' => $capeTownId,
            'duration_days' => 6,
            'category' => 'premium',
            'overview' => 'This thoughtfully designed 6-day South Africa itinerary is perfect for travellers who want big highlights without rushing, combining vibrant cities, dramatic coastal scenery, whale encounters, and some of the finest wine regions in Africa. Begin in Cape Town, travel along the coast to Hermanus, then head inland to Franschhoek and Stellenbosch.',
            'highlights' => "Table Mountain cableway\nBo-Kaap & Cape Malay heritage\nKirstenbosch Botanical Gardens\nSunset champagne cruise\nHermanus whale watching (seasonal)\nBenguela Cove Wine Estate\nFranschhoek walking tour\nMéthode Cap Classique tasting\nStellenbosch wine estates",
            'includes' => "5 nights hand-picked accommodation\nDaily breakfast\nRental car for full duration\nSunset champagne cruise\nSelected wine tastings\nPersonalized travel guidance\n24/7 support while travelling",
            'excludes' => "International flights\nVisas and travel insurance\nFuel\nLunches and dinners (unless stated)\nPrivate guide (available on request)\nTips and gratuities",
            'terms' => 'A 30% deposit is required to confirm your booking. Full payment is due 60 days before departure.',
            'booking_terms' => 'Deposit of 30% required. Balance due 60 days prior to departure.',
            'payment_schedule' => '30% on booking, 70% due 60 days before departure',
            'cancellation_policy' => '60+ days: Full refund minus 10% fee. 30-60 days: 50% refund. Under 30 days: Non-refundable.',
            'refund_policy' => 'Refunds processed within 14 business days. Bank charges may apply.',
            'important_notes' => 'Whale watching is seasonal (June to November). Self-drive experience – valid driver\'s license required. Access to Table Mountain may be limited due to weather.',
            'notes' => 'This itinerary can be extended to include the Garden Route or a luxury safari. Optional private guide available on request.',
            'images' => json_encode([$img('1516483638261-f4dbaf036963'), $img('1558030004-b4c2c0e3e7f6'), $img('1571896349842-41c6b8c3f0e1')]),
            'status' => 'active',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $template2Days = [
            [
                'day_number' => 1, 'title' => 'Arrival in Cape Town',
                'destination_id' => $capeTownId, 'hotel_id' => $capeHeritageId, 'hotel_name' => 'Cape Heritage Hotel',
                'room_type' => 'Standard Room', 'meal_plan' => 'Breakfast',
                'morning_activity' => 'Arrive at Cape Town International Airport. Collect rental car. Scenic drive into the city.',
                'afternoon_activity' => 'Relaxed walk around the V&A Waterfront. Harbour views, restaurants, and lively atmosphere.',
                'evening_activity' => 'Welcome dinner at a Waterfront restaurant.',
                'description' => 'Welcome to the Mother City! Arrive at Cape Town International Airport and collect your rental car. Your journey begins with a scenic drive into the city, where mountain views, ocean air, and vibrant energy immediately set the tone. Depending on your arrival time, enjoy a relaxed walk around the V&A Waterfront.',
                'destination_intro' => 'Cape Town is framed by Table Mountain and the Atlantic Ocean, one of the world\'s most beautiful cities.',
                'included_services' => 'Accommodation, breakfast',
                'optional_activities' => 'Sunset stroll at Sea Point promenade',
                'wildlife_highlights' => 'City arrival',
                'sort_order' => 1,
            ],
            [
                'day_number' => 2, 'title' => 'Cape Town Exploration & Sunset Champagne Cruise',
                'destination_id' => $capeTownId, 'hotel_id' => $capeHeritageId, 'hotel_name' => 'Cape Heritage Hotel',
                'room_type' => 'Standard Room', 'meal_plan' => 'Breakfast',
                'morning_activity' => 'Table Mountain cableway (weather permitting). Visit Bo-Kaap\'s colourful streets and Cape Malay heritage.',
                'afternoon_activity' => 'Visit Kirstenbosch Botanical Gardens. Coastal drive to Camps Bay or Hout Bay.',
                'evening_activity' => 'Sunset champagne cruise from the V&A Waterfront.',
                'description' => 'Today is dedicated to discovering Cape Town\'s diverse highlights. Popular options include Table Mountain, Bo-Kaap, Kirstenbosch Botanical Gardens, and coastal drives to Camps Bay. As the day winds down, enjoy a sunset champagne cruise from the V&A Waterfront — an unforgettable way to experience Cape Town from the water.',
                'destination_intro' => 'Cape Town offers a rich blend of natural beauty, history, and coastal charm.',
                'included_services' => 'Breakfast, sunset champagne cruise',
                'optional_activities' => 'Hop-on hop-off bus tour, helicopter flip',
                'wildlife_highlights' => 'Kirstenbosch gardens, coastal scenery',
                'sort_order' => 2,
            ],
            [
                'day_number' => 3, 'title' => 'Coastal Drive to Hermanus & Vineyard Views',
                'destination_id' => $hermanusId, 'hotel_id' => $marineId, 'hotel_name' => 'The Marine Hermanus',
                'room_type' => 'Sea View Room', 'meal_plan' => 'Breakfast',
                'morning_activity' => 'Scenic coastal drive along Clarence Drive to Hermanus (±2 hours).',
                'afternoon_activity' => 'Visit Benguela Cove Wine Estate for nature walk paired with wine tasting. Sweeping lagoon views meet award-winning wines.',
                'evening_activity' => 'Seaside dinner in Hermanus. Evening walk along the cliff paths.',
                'description' => 'After breakfast, drive along the scenic coastline toward Hermanus. This charming seaside town is internationally known for land-based whale watching during the season, as well as its relaxed atmosphere and coastal walking paths. Later, head to Benguela Cove Wine Estate.',
                'destination_intro' => 'Hermanus is renowned for land-based whale watching and laid-back coastal charm.',
                'included_services' => 'Breakfast, wine tasting at Benguela Cove',
                'optional_activities' => 'Whale watching boat tour (seasonal)',
                'wildlife_highlights' => 'Southern right whales (June-Nov), seabirds, marine life',
                'sort_order' => 3,
            ],
            [
                'day_number' => 4, 'title' => 'Penguins, Passes & Franschhoek Wine Country',
                'destination_id' => $franschhoekId, 'hotel_id' => $petitManorId, 'hotel_name' => 'Le Petit Manoir',
                'room_type' => 'Manor Room', 'meal_plan' => 'Breakfast',
                'morning_activity' => 'Depart Hermanus. Stop at Stony Point Nature Reserve, Betty\'s Bay, home to an African penguin colony.',
                'afternoon_activity' => 'Arrive in Franschhoek. Guided walking tour of the historic town. Méthode Cap Classique tasting at L\'Ormarins and visit to Franschhoek Motor Museum.',
                'evening_activity' => 'Gourmet dinner at a top Franschhoek restaurant.',
                'description' => 'Depart Hermanus after breakfast and journey inland to Franschhoek, with scenic stops along the way. Highlights include Stony Point Nature Reserve\'s African penguin colony and views over Theewaterskloof Dam. Arrive in Franschhoek, known for its French heritage, gourmet cuisine, and exceptional wines.',
                'destination_intro' => 'Franschhoek is South Africa\'s gourmet capital, set in a beautiful valley surrounded by vineyards.',
                'included_services' => 'Breakfast, MCC tasting, motor museum entry',
                'optional_activities' => 'Franschhoek wine tram',
                'wildlife_highlights' => 'African penguins at Stony Point, mountain scenery',
                'sort_order' => 4,
            ],
            [
                'day_number' => 5, 'title' => 'Stellenbosch & Return to Cape Town',
                'destination_id' => $stellenboschId, 'hotel_id' => $capeHeritageId, 'hotel_name' => 'Cape Heritage Hotel',
                'room_type' => 'Standard Room', 'meal_plan' => 'Breakfast',
                'morning_activity' => 'Scenic drive through Helshoogte Pass to Stellenbosch.',
                'afternoon_activity' => 'Explore historic Stellenbosch town centre on foot. Admire Cape Dutch architecture. Wine tasting at a surrounding estate. Return to Cape Town.',
                'evening_activity' => 'Farewell dinner overlooking the city or ocean.',
                'description' => 'Today, drive through the scenic Helshoogte Pass to Stellenbosch, the heart of South Africa\'s wine industry. Explore the historic town centre on foot, admire Cape Dutch architecture, and enjoy time at one of the surrounding wine estates. Later in the afternoon, return to Cape Town for your final evening.',
                'destination_intro' => 'Stellenbosch is the heart of South Africa\'s wine country, with 300-year-old heritage.',
                'included_services' => 'Breakfast, wine tasting',
                'optional_activities' => 'Stellenbosch golf round, cellar tour',
                'wildlife_highlights' => 'Winelands scenery, Cape Dutch architecture',
                'sort_order' => 5,
            ],
            [
                'day_number' => 6, 'title' => 'Departure or Extend Your Journey',
                'destination_id' => $capeTownId, 'hotel_id' => null, 'hotel_name' => 'Departure Day',
                'room_type' => '', 'meal_plan' => 'Breakfast',
                'morning_activity' => 'Breakfast at hotel. Drive to Cape Town International Airport.',
                'afternoon_activity' => 'Onward flight or optional extension.',
                'evening_activity' => '',
                'description' => 'After breakfast, drive to Cape Town International Airport for your onward flight. Would you like to extend your adventure? This itinerary pairs beautifully with a luxury safari experience, the Garden Route, or a beach escape along South Africa\'s coast. Shishi Footsteps will tailor the next chapter for you.',
                'destination_intro' => 'Departure from Cape Town.',
                'included_services' => 'Breakfast',
                'optional_activities' => 'Garden Route extension, safari extension, beach escape',
                'wildlife_highlights' => '',
                'sort_order' => 6,
            ],
        ];

        foreach ($template2Days as $day) {
            $day['itinerary_template_id'] = $template2Id;
            $day['created_at'] = now();
            $day['updated_at'] = now();
            DB::table('template_days')->insert($day);
        }

        DB::table('template_pricing')->insert([
            ['itinerary_template_id' => $template2Id, 'currency' => 'USD', 'price_per_person' => 2400, 'single_supplement' => 750, 'total_cost' => 4800, 'notes' => 'Based on 2 travellers sharing. Self-drive.', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('proposal_template_settings')->insert([
            'itinerary_template_id' => $template2Id,
            'settings' => json_encode([
                'cover_heading' => 'Cape Town, Whales & Wine Routes',
                'client_name' => 'Your Western Cape Adventure',
                'consultant_name' => 'Grace Mwangi',
                'consultant_email' => 'grace@shishifootsteps.com',
                'consultant_phone' => '+254 725 346 022',
                'personal_letter' => 'Thank you for choosing Shishi Footsteps to curate your Western Cape journey. This 6-day itinerary blends iconic Cape Town with the whale coast and South Africa\'s finest wine regions.',
                'guest_count' => '2 Adults',
                'show_investment' => true, 'show_gallery' => true, 'show_acceptance' => true, 'show_company_profile' => true,
            ]),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->command->info("Template created: Cape Town, Whales & Wine Routes (ID: $template2Id)");

        // ========================================================================
        // ITINERARY 3: 7 DAYS 6 NIGHTS SOUTH AFRICA GOLF & TRAVEL
        // ========================================================================
        $template3Id = DB::table('itinerary_templates')->insertGetId([
            'name' => '7 Days 6 Nights South Africa Golf & Travel',
            'trip_name' => 'South Africa Golf & Travel: Cape Town to Kruger',
            'destination_id' => $capeTownId,
            'duration_days' => 7,
            'category' => 'luxury',
            'overview' => 'An epic South Africa golf journey combining Cape Town\'s finest courses, Durban\'s coastal links, Stellenbosch wine country, and a Big Five safari in Kruger National Park. This itinerary flows naturally from city to vineyards to coast to wilderness.',
            'highlights' => "Westlake Golf Club – scenic Cape Town round\nTable Mountain cableway\nMowbray Golf Club – heritage city course\nStellenbosch wine tasting\nStellenbosch Golf Club\nDurban Country Club Beachwood Course\nuShaka Marine World\nKruger National Park safari\nSkukuza Golf Club – golf with wildlife",
            'includes' => "6 nights premium accommodation\nDaily breakfast\n3 rounds of golf (Westlake, Mowbray, Stellenbosch)\nTable Mountain cable car\nStellenbosch wine tasting\nKruger game drive (afternoon + morning)\nDomestic flight Cape Town to Durban\nDomestic flight Durban to Nelspruit\nAirport transfers",
            'excludes' => "International flights\nTravel insurance\nVisa fees\nLunches and dinners (unless stated)\nGolf equipment rental\nCaddie fees\nKruger conservation fees\nTips and gratuities\nPersonal expenses",
            'terms' => 'A 30% deposit is required to confirm your booking. Full payment is due 60 days before departure.',
            'booking_terms' => 'Deposit of 30% required. Balance due 60 days prior to departure.',
            'payment_schedule' => '30% on booking, 70% due 60 days before departure',
            'cancellation_policy' => '60+ days: Full refund minus 10% fee. 30-60 days: 50% refund. Under 30 days: Non-refundable.',
            'refund_policy' => 'Refunds processed within 14 business days. Bank charges may apply.',
            'important_notes' => 'Access to Table Mountain may be limited due to weather. Kruger game drives depend on seasonal conditions and wildlife movement. Golf at Skukuza is a 9-hole course inside the park – wildlife may be present on fairways. Valid passport and visa required for South Africa.',
            'notes' => 'This itinerary can be customized. Optional Garden Route or Sun City extensions available. Additional golf rounds at Durban Country Club or Gary Player Country Club can be arranged.',
            'images' => json_encode([$img('1592919505780-303950717480'), $img('1535131749006-b7f58c99034b'), $img('1534177616072-ef7dc120449d')]),
            'status' => 'active',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $template3Days = [
            [
                'day_number' => 1, 'title' => 'Arrival in Cape Town & Westlake Golf',
                'destination_id' => $capeTownId, 'hotel_id' => $bayHotelId, 'hotel_name' => 'The Bay Hotel',
                'room_type' => 'Ocean View Room', 'meal_plan' => 'Breakfast, Dinner',
                'morning_activity' => 'Arrive in Cape Town and transfer to your hotel. Settle in with breathtaking views of Table Mountain.',
                'afternoon_activity' => 'Play your first round at Westlake Golf Club. Scenic beauty and accessibility offer a great introduction to South Africa\'s golf scene.',
                'evening_activity' => 'Explore the lively V&A Waterfront for dinner and shopping. Sunset views along the harbour.',
                'description' => 'Arrive in Cape Town and transfer to your hotel. Settle in and relax after your journey with breathtaking views of Table Mountain. Play your first round at Westlake Golf Club, known for its scenic beauty and accessibility — a great introduction to South Africa\'s golf scene.',
                'destination_intro' => 'Cape Town welcomes you with mountain views, ocean air, and world-class golf.',
                'included_services' => 'Airport pickup, hotel transfer, green fee at Westlake, dinner',
                'optional_activities' => 'Sunset cruise, Waterfront shopping',
                'wildlife_highlights' => 'City arrival – scenic mountain views',
                'sort_order' => 1,
            ],
            [
                'day_number' => 2, 'title' => 'Cape Town City Exploration & Mowbray Golf',
                'destination_id' => $capeTownId, 'hotel_id' => $bayHotelId, 'hotel_name' => 'The Bay Hotel',
                'room_type' => 'Ocean View Room', 'meal_plan' => 'Breakfast, Dinner',
                'morning_activity' => 'Cable car ride up Table Mountain for panoramic views. Visit Bo-Kaap\'s colourful streets and Cape Malay culture.',
                'afternoon_activity' => 'Head to Mowbray Golf Club, one of Cape Town\'s oldest courses. Challenging layout with stunning Table Mountain views.',
                'evening_activity' => 'Traditional South African dinner in Camps Bay, famous for its beachside views and trendy restaurants.',
                'description' => 'Start your day with a cable car ride up Table Mountain for panoramic views. Visit Bo-Kaap, the colourful historic district. Head to Mowbray Golf Club, one of Cape Town\'s oldest courses, offering a challenging layout with stunning views of Table Mountain.',
                'destination_intro' => 'Cape Town offers a perfect blend of urban exploration and world-class golf.',
                'included_services' => 'Breakfast, Table Mountain cable car, green fee at Mowbray, dinner',
                'optional_activities' => 'Kirstenbosch Gardens visit',
                'wildlife_highlights' => 'Table Mountain views, city sights',
                'sort_order' => 2,
            ],
            [
                'day_number' => 3, 'title' => 'Wine Tasting in Stellenbosch & Golf',
                'destination_id' => $stellenboschId, 'hotel_id' => $bayHotelId, 'hotel_name' => 'The Bay Hotel',
                'room_type' => 'Ocean View Room', 'meal_plan' => 'Breakfast, Dinner',
                'morning_activity' => 'Drive to Stellenbosch wine region. Morning wine tasting at renowned vineyards like Spier or Delaire Graff Estate.',
                'afternoon_activity' => 'Play a round at Stellenbosch Golf Club. Backdrop of majestic mountains and vineyards provides a relaxing yet challenging experience.',
                'evening_activity' => 'Farm-to-table dinner in Stellenbosch, pairing regional cuisine with world-class wines.',
                'description' => 'Drive to Stellenbosch, South Africa\'s famous wine region. Enjoy a morning of wine tasting at renowned vineyards. Play a round at Stellenbosch Golf Club, one of South Africa\'s oldest clubs open to the public. With a backdrop of majestic mountains and vineyards, this course provides a relaxing yet challenging experience.',
                'destination_intro' => 'Stellenbosch is the heart of South African wine country, surrounded by mountains and vineyards.',
                'included_services' => 'Breakfast, wine tasting, green fee at Stellenbosch GC, dinner',
                'optional_activities' => 'Additional wine estate visit',
                'wildlife_highlights' => 'Winelands scenery, mountain views',
                'sort_order' => 3,
            ],
            [
                'day_number' => 4, 'title' => 'Fly to Durban & Coastal Golf',
                'destination_id' => $durbanId, 'hotel_id' => $durbanClubId, 'hotel_name' => 'Durban Country Club',
                'room_type' => 'Standard Room', 'meal_plan' => 'Breakfast, Dinner',
                'morning_activity' => 'Fly from Cape Town to Durban. Transfer to your hotel and unwind on the beaches of Umhlanga Rocks.',
                'afternoon_activity' => 'Tee off at Durban Country Club Beachwood Course. Excellent public course with sweeping views of the Indian Ocean and rolling dunes.',
                'evening_activity' => 'Fresh seafood at a beachfront restaurant in Umhlanga, soaking in the coastal atmosphere.',
                'description' => 'Fly from Cape Town to Durban. Transfer to your hotel and unwind on the beaches of Umhlanga Rocks. Tee off at Durban Country Club Beachwood Course, an excellent public course offering sweeping views of the Indian Ocean and rolling dunes. The challenging links-style course will test your game.',
                'destination_intro' => 'Durban is a subtropical coastal city with golden beaches, warm Indian Ocean waters, and excellent golf.',
                'included_services' => 'Breakfast, domestic flight CPT-DUR, green fee at Beachwood, dinner',
                'optional_activities' => 'Umhlanga beach walk, Moses Mabhida Stadium',
                'wildlife_highlights' => 'Indian Ocean views, coastal scenery',
                'sort_order' => 4,
            ],
            [
                'day_number' => 5, 'title' => 'Durban Adventure & Golf',
                'destination_id' => $durbanId, 'hotel_id' => $durbanClubId, 'hotel_name' => 'Durban Country Club',
                'room_type' => 'Standard Room', 'meal_plan' => 'Breakfast, Dinner',
                'morning_activity' => 'Visit uShaka Marine World for marine life exhibits, water slides, and dolphin shows.',
                'afternoon_activity' => 'Play at Windsor Park Golf Course. Popular municipal course near the city centre, surrounded by lush green landscapes.',
                'evening_activity' => 'Explore local cuisine in Florida Road, Durban\'s hub for lively restaurants and nightlife.',
                'description' => 'Visit uShaka Marine World for a fun mix of marine life exhibits, water slides, and dolphin shows. Play at Windsor Park Golf Course, one of Durban\'s most popular municipal golf courses. Located near the city centre, it offers a good round for golfers of all levels.',
                'destination_intro' => 'Durban combines beach relaxation with urban energy and excellent golf options.',
                'included_services' => 'Breakfast, uShaka Marine World entry, green fee at Windsor Park, dinner',
                'optional_activities' => 'uShaka water park, beach time',
                'wildlife_highlights' => 'Marine life at uShaka, dolphin shows',
                'sort_order' => 5,
            ],
            [
                'day_number' => 6, 'title' => 'Safari Experience in Kruger National Park',
                'destination_id' => $krugerId, 'hotel_id' => $shalatiId, 'hotel_name' => 'Kruger Shalati',
                'room_type' => 'Luxury Suite', 'meal_plan' => 'Breakfast, Lunch, Dinner',
                'morning_activity' => 'Fly from Durban to Nelspruit, the gateway to Kruger National Park.',
                'afternoon_activity' => 'Transfer to your lodge for a thrilling afternoon game drive. Experience the wild beauty of South Africa as you spot the Big Five.',
                'evening_activity' => 'Bush dinner under the stars at your lodge, surrounded by the sounds of nature.',
                'description' => 'Fly from Durban to Nelspruit. Transfer to your lodge in Kruger for a thrilling afternoon game drive. Experience the wild beauty of South Africa as you spot lions, elephants, leopards, rhinos, and buffalo. Enjoy a bush dinner under the stars at your lodge.',
                'destination_intro' => 'Kruger National Park is one of Africa\'s greatest wildlife reserves, home to the Big Five.',
                'included_services' => 'Breakfast, domestic flight DUR-MQP, game drive, all meals at lodge',
                'optional_activities' => 'Night game drive (additional cost)',
                'wildlife_highlights' => 'Big Five: lion, elephant, leopard, rhino, buffalo',
                'sort_order' => 6,
            ],
            [
                'day_number' => 7, 'title' => 'Golf & Safari in Kruger – Departure',
                'destination_id' => $krugerId, 'hotel_id' => null, 'hotel_name' => 'Departure Day',
                'room_type' => '', 'meal_plan' => 'Breakfast, Lunch',
                'morning_activity' => 'Early morning safari to catch wildlife at its most active.',
                'afternoon_activity' => 'Play a round at Skukuza Golf Club inside Kruger National Park. This 9-hole course is not fenced — expect wildlife like warthogs and impala nearby. Transfer to Nelspruit Airport for departure.',
                'evening_activity' => '',
                'description' => 'Begin your day with another early morning safari to catch the wildlife at its most active. For a unique experience, play a round of golf at Skukuza Golf Club inside Kruger National Park. This 9-hole course is not fenced, so it\'s common to see wildlife like warthogs and impala wandering nearby. Head back to your lodge for a farewell lunch, then transfer to Nelspruit Airport.',
                'destination_intro' => 'A once-in-a-lifetime chance to mix golf with African wildlife.',
                'included_services' => 'Morning game drive, breakfast, green fee at Skukuza, lunch, airport transfer',
                'optional_activities' => 'Kruger gate safari drive',
                'wildlife_highlights' => 'Early morning wildlife, Skukuza course wildlife',
                'sort_order' => 7,
            ],
        ];

        foreach ($template3Days as $day) {
            $day['itinerary_template_id'] = $template3Id;
            $day['created_at'] = now();
            $day['updated_at'] = now();
            DB::table('template_days')->insert($day);
        }

        DB::table('template_pricing')->insert([
            ['itinerary_template_id' => $template3Id, 'currency' => 'USD', 'price_per_person' => 3800, 'single_supplement' => 1200, 'total_cost' => 7600, 'notes' => 'Based on 2 travellers sharing. Golf & flights included.', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('proposal_template_settings')->insert([
            'itinerary_template_id' => $template3Id,
            'settings' => json_encode([
                'cover_heading' => 'South Africa Golf & Travel',
                'client_name' => 'Your South Africa Golf Safari',
                'consultant_name' => 'Grace Mwangi',
                'consultant_email' => 'grace@shishifootsteps.com',
                'consultant_phone' => '+254 725 346 022',
                'personal_letter' => 'Thank you for choosing Shishi Footsteps to curate this extraordinary South Africa golf and safari adventure. From Cape Town\'s finest fairways to the wild beauty of Kruger, every detail has been crafted around you.',
                'guest_count' => '2 Adults',
                'show_investment' => true, 'show_gallery' => true, 'show_acceptance' => true, 'show_company_profile' => true,
            ]),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->command->info("Template created: 7 Days 6 Nights South Africa Golf & Travel (ID: $template3Id)");

        // ========================================================================
        // ITINERARY 4: THE ROAD TO KRUGER (narrative guide as CMS page + template)
        // ========================================================================
        $template4Id = DB::table('itinerary_templates')->insertGetId([
            'name' => 'The Road to Kruger',
            'trip_name' => 'The Road to Kruger: Two Inspiring Ways to Reach Kruger National Park',
            'destination_id' => $krugerId,
            'duration_days' => 5,
            'category' => 'premium',
            'overview' => 'A safari to Kruger National Park is on many travellers\' bucket lists. This guide explores two inspiring routes to reach Kruger — via Johannesburg with the Panorama Route, or via Pretoria — turning the journey itself into a complete adventure.',
            'highlights' => "Johannesburg cultural landmarks\nApartheid Museum\nPanorama Route\nBlyde River Canyon\nGod's Window\nShangana Cultural Village\nKruger National Park safaris\nPretoria historic landmarks\nBig Five wildlife viewing",
            'includes' => "Accommodation as per selected route\nGame drives in Kruger National Park\nProfessional guide\nPark fees\nAll meals on safari days",
            'excludes' => "International flights\nTravel insurance\nVisa fees\nPersonal expenses\nTips and gratuities",
            'terms' => 'A 30% deposit is required to confirm your booking. Full payment is due 60 days before departure.',
            'booking_terms' => 'Deposit of 30% required. Balance due 60 days prior to departure.',
            'payment_schedule' => '30% on booking, 70% due 60 days before departure',
            'cancellation_policy' => '60+ days: Full refund minus 10% fee. 30-60 days: 50% refund. Under 30 days: Non-refundable.',
            'refund_policy' => 'Refunds processed within 14 business days. Bank charges may apply.',
            'important_notes' => 'Yellow fever vaccination recommended. Visa required for most nationalities. Kruger safari drives depend on seasonal wildlife movement and weather conditions.',
            'notes' => 'Choose between Johannesburg or Pretoria starting point. Both routes include the Panorama Route and Kruger safari. Customization available.',
            'images' => json_encode([$img('1534177616072-ef7dc120449d'), $img('1580060839134-75a5edca2e99'), $img('1504432842672-1a79f78e4084')]),
            'status' => 'active',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $template4Days = [
            [
                'day_number' => 1, 'title' => 'Arrival in Johannesburg – City Discovery',
                'destination_id' => $johannesburgId, 'hotel_id' => $livingstoneId, 'hotel_name' => 'Royal Livingstone Hotel',
                'room_type' => 'Standard Room', 'meal_plan' => 'Breakfast, Dinner',
                'morning_activity' => 'Arrive at Johannesburg OR Tambo Airport. Transfer to hotel.',
                'afternoon_activity' => 'Visit Apartheid Museum and explore Soweto, home to Nelson Mandela\'s former residence. Optional Gold Reef City or Red Bus Tour.',
                'evening_activity' => 'Welcome dinner at a local restaurant.',
                'description' => 'Johannesburg is a city of stories — from its gold-mining roots to its powerful role in South Africa\'s political history. Before setting off for Kruger, take time to explore its landmarks, museums, and vibrant neighbourhoods.',
                'destination_intro' => 'Johannesburg is South Africa\'s largest city, rich in history and culture.',
                'included_services' => 'Airport pickup, hotel transfer, accommodation, dinner',
                'optional_activities' => 'Gold Reef City, Red Bus Tour',
                'wildlife_highlights' => 'City sights – no wildlife',
                'sort_order' => 1,
            ],
            [
                'day_number' => 2, 'title' => 'The Panorama Route – Nature at Its Best',
                'destination_id' => $johannesburgId, 'hotel_id' => null, 'hotel_name' => 'Panorama Route Lodge',
                'room_type' => 'Standard Room', 'meal_plan' => 'Breakfast, Lunch, Dinner',
                'morning_activity' => 'Depart Johannesburg for the Panorama Route. View the vast Blyde River Canyon.',
                'afternoon_activity' => 'Visit God\'s Window, Bourke\'s Luck Potholes, and the historic town of Pilgrim\'s Rest.',
                'evening_activity' => 'Overnight near the Panorama Route. Dinner at the lodge.',
                'description' => 'The Panorama Route is one of South Africa\'s most scenic road journeys. Along the way, you\'ll discover the Blyde River Canyon, God\'s Window, Bourke\'s Luck Potholes, and Pilgrim\'s Rest. For travellers interested in culture, a visit to Shangana Cultural Village offers insight into traditional music, dance, and storytelling.',
                'destination_intro' => 'The Panorama Route showcases South Africa\'s most dramatic natural landscapes.',
                'included_services' => 'Breakfast, scenic drive, guided Panorama Route tour, lunch, dinner',
                'optional_activities' => 'Shangana Cultural Village visit',
                'wildlife_highlights' => 'Blyde River Canyon, mountain scenery',
                'sort_order' => 2,
            ],
            [
                'day_number' => 3, 'title' => 'Entering Kruger National Park',
                'destination_id' => $krugerId, 'hotel_id' => $shalatiId, 'hotel_name' => 'Kruger Shalati',
                'room_type' => 'Luxury Suite', 'meal_plan' => 'Breakfast, Lunch, Dinner',
                'morning_activity' => 'Drive to Kruger National Park. Enter the park and begin wildlife viewing.',
                'afternoon_activity' => 'Afternoon game drive across Kruger\'s vast landscapes. Search for lions, elephants, rhinos, leopards, and buffalo.',
                'evening_activity' => 'Bush dinner under the stars at your lodge.',
                'description' => 'Kruger National Park is famous for its wildlife diversity and vast open landscapes. From sunrise game drives to guided bush walks, every day offers something new. Visitors can encounter lions, elephants, rhinos, leopards, buffalo, giraffes, zebras, hippos, and hundreds of bird species.',
                'destination_intro' => 'Kruger National Park delivers unforgettable safari moments across 19,000 square kilometres of wilderness.',
                'included_services' => 'Breakfast, park entry, afternoon game drive, all meals at lodge',
                'optional_activities' => 'Guided bush walk, night drive',
                'wildlife_highlights' => 'Big Five, giraffe, zebra, hippo, 500+ bird species',
                'sort_order' => 3,
            ],
            [
                'day_number' => 4, 'title' => 'Full Day Kruger Safari',
                'destination_id' => $krugerId, 'hotel_id' => $shalatiId, 'hotel_name' => 'Kruger Shalati',
                'room_type' => 'Luxury Suite', 'meal_plan' => 'Breakfast, Lunch, Dinner',
                'morning_activity' => 'Sunrise game drive when wildlife is most active. Follow elephant herds and search for predators.',
                'afternoon_activity' => 'Picnic lunch at a scenic rest camp. Afternoon game drive exploring different sectors of the park.',
                'evening_activity' => 'Sundowner cocktails overlooking the bush. Farewell dinner at the lodge.',
                'description' => 'A full day of wildlife exploration in Kruger. Follow elephant herds at waterholes, track lions on the hunt, and witness the drama of Africa\'s most celebrated national park. Whether watching elephants at a waterhole or spotting a leopard in the trees, Kruger delivers unforgettable moments.',
                'destination_intro' => 'A full day immersed in the wild wonders of Kruger National Park.',
                'included_services' => 'Sunrise game drive, breakfast, lunch, afternoon game drive, dinner',
                'optional_activities' => 'Photography workshop, bush braai',
                'wildlife_highlights' => 'Lion, leopard, cheetah, wild dog, elephant, rhino, buffalo',
                'sort_order' => 4,
            ],
            [
                'day_number' => 5, 'title' => 'Departure',
                'destination_id' => $johannesburgId, 'hotel_id' => null, 'hotel_name' => 'Departure Day',
                'room_type' => '', 'meal_plan' => 'Breakfast',
                'morning_activity' => 'Final morning game drive. Breakfast at the lodge.',
                'afternoon_activity' => 'Transfer to Nelspruit Airport or return to Johannesburg for onward connection.',
                'evening_activity' => '',
                'description' => 'A final morning game drive to bid farewell to the African bush. Transfer to the airport for your journey home or onward extension.',
                'destination_intro' => 'Departure with memories of an unforgettable Kruger safari.',
                'included_services' => 'Morning game drive, breakfast, airport transfer',
                'optional_activities' => 'Johannesburg city tour (if time permits)',
                'wildlife_highlights' => 'Final wildlife sightings',
                'sort_order' => 5,
            ],
        ];

        foreach ($template4Days as $day) {
            $day['itinerary_template_id'] = $template4Id;
            $day['created_at'] = now();
            $day['updated_at'] = now();
            DB::table('template_days')->insert($day);
        }

        DB::table('template_pricing')->insert([
            ['itinerary_template_id' => $template4Id, 'currency' => 'USD', 'price_per_person' => 3200, 'single_supplement' => 950, 'total_cost' => 6400, 'notes' => 'Based on 2 travellers sharing. Kruger safari included.', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('proposal_template_settings')->insert([
            'itinerary_template_id' => $template4Id,
            'settings' => json_encode([
                'cover_heading' => 'The Road to Kruger',
                'client_name' => 'Your Kruger Safari Adventure',
                'consultant_name' => 'Grace Mwangi',
                'consultant_email' => 'grace@shishifootsteps.com',
                'consultant_phone' => '+254 725 346 022',
                'personal_letter' => 'Thank you for choosing Shishi Footsteps to guide you on the road to Kruger. From Johannesburg\'s stories to the Panorama Route\'s beauty and Kruger\'s wild heart, every mile tells a story.',
                'guest_count' => '2 Adults',
                'show_investment' => true, 'show_gallery' => true, 'show_acceptance' => true, 'show_company_profile' => true,
            ]),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->command->info("Template created: The Road to Kruger (ID: $template4Id)");
        $this->command->info("South Africa content seeding complete! 4 itineraries created.");
    }
}
