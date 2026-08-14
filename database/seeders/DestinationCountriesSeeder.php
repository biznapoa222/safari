<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\CmsPage;
use App\Models\ItineraryDayV2;
use App\Models\ItineraryV2;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Enriches Kenya, Tanzania, Uganda, South Africa, Namibia and Botswana
 * destination guides, journeys and activities — plus dedicated Nyungwe (Rwanda).
 */
class DestinationCountriesSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedGuides();
        $this->seedKenya();
        $this->seedTanzania();
        $this->seedUganda();
        $this->seedSouthAfrica();
        $this->seedNamibia();
        $this->seedBotswana();
        $this->seedNyungwe();
        $this->seedActivities();
        $this->command?->info('Destination country content enriched (incl. Nyungwe).');
    }

    private function seedGuides(): void
    {
        $guides = [
            'kenya' => [
                'Kenya Travel Guide',
                'Kenya Safari Tours | Shishi Footsteps',
                'Maasai Mara, Amboseli, Tsavo, private conservancies, coastal endings and championship golf — private Kenya journeys designed around you.',
                <<<'HTML'
<p>Kenya — often called the Cradle of Mankind — remains one of Africa’s most legendary safari destinations. From the Maasai Mara’s predator-rich plains to Amboseli elephants beneath Kilimanjaro, from Tsavo’s red-dust wilderness to warm Indian Ocean beaches, Kenya offers extraordinary variety in a single country.</p>
<h2>Why travel to Kenya with Shishi Footsteps</h2>
<p>We design private Kenya itineraries around season, wildlife movement, lodge style and pace. Whether you want Great Migration river crossings, family-friendly conservancies, cultural encounters, coastal golf at Vipingo Ridge, or a road safari through Amboseli and Tsavo, every detail is curated.</p>
<h2>Signature experiences</h2>
<ul>
<li>Maasai Mara safari and private conservancy game drives</li>
<li>Great Migration viewing (seasonal)</li>
<li>Amboseli elephants with Kilimanjaro views</li>
<li>Tsavo East and West wilderness</li>
<li>Hot-air balloon safaris</li>
<li>Coastal golf, Diani beaches and marine parks</li>
<li>Nairobi golf at Windsor, Karen and Muthaiga</li>
</ul>
<h2>Best time to visit</h2>
<p>Dry months (June–October) are excellent for wildlife viewing and Migration crossings. The green season brings lush landscapes, fewer crowds and strong birding, with superb value for many travellers.</p>
HTML,
            ],
            'tanzania' => [
                'Tanzania Travel Guide',
                'Tanzania Safari Tours | Shishi Footsteps',
                'Serengeti, Ngorongoro, Tarangire, Nyerere and Zanzibar — iconic Tanzania safari landscapes with private pacing and handpicked stays.',
                <<<'HTML'
<p>Tanzania offers some of the most iconic wildlife landscapes in the world: vast Serengeti plains, the UNESCO-listed Ngorongoro Crater, baobab-dotted Tarangire, remote southern reserves and Indian Ocean endings on Zanzibar.</p>
<h2>Why travel to Tanzania with Shishi Footsteps</h2>
<p>We shape Tanzania around the Great Migration calendar, crater wildlife, elephant herds and the atmosphere you want — classic northern circuit, quieter southern parks, or a safari-and-beach combination.</p>
<h2>Signature experiences</h2>
<ul>
<li>Serengeti National Park and Migration viewing</li>
<li>Ngorongoro Crater Big Five days</li>
<li>Tarangire elephant herds and baobabs</li>
<li>Balloon safaris over the plains</li>
<li>Remote wilderness in Nyerere / Ruaha</li>
<li>Zanzibar beach finales</li>
</ul>
<h2>Best time to visit</h2>
<p>Dry season (June–October) delivers outstanding wildlife viewing. Green season offers dramatic skies, fewer vehicles and excellent birdlife. River crossings are typically strongest July–October in the north.</p>
HTML,
            ],
            'uganda' => [
                'Uganda Travel Guide',
                'Uganda Safari & Gorilla Tours | Shishi Footsteps',
                'Bwindi gorilla trekking, Queen Elizabeth, Murchison Falls, Jinja adventure and highland golf — private Uganda journeys with permit coordination.',
                <<<'HTML'
<p>Uganda is the Pearl of Africa: misty gorilla forests, tree-climbing lions, the mighty Nile, crater lakes and warm highland hospitality. It is one of the world’s premier destinations for mountain gorilla trekking.</p>
<h2>Why travel to Uganda with Shishi Footsteps</h2>
<p>We secure gorilla permits, arrange scenic flights or road transfers, and combine Bwindi with Queen Elizabeth, Lake Mburo, Murchison Falls or Jinja adventure — with optional highland golf at Tooro, Lake Victoria Serena and Lake Mburo.</p>
<h2>Signature experiences</h2>
<ul>
<li>Gorilla trekking in Bwindi Impenetrable National Park</li>
<li>Chimpanzee tracking in Kibale</li>
<li>Queen Elizabeth game drives and Kazinga Channel boat safari</li>
<li>Murchison Falls and Nile boat cruises</li>
<li>Lake Mburo walking and boat safaris</li>
<li>Source of the Nile rafting and Jinja adventure</li>
<li>Golf at Tooro, Lake Victoria Serena and Lake Mburo</li>
</ul>
<h2>Best time to visit</h2>
<p>Dry seasons (June–September and December–February) are popular for trekking comfort. Gorilla permits are limited — book early.</p>
HTML,
            ],
            'south-africa' => [
                'South Africa Travel Guide',
                'South Africa Safari & Golf Tours | Shishi Footsteps',
                'Kruger and private reserves, Cape Town, Winelands, Garden Route, whale coast and championship golf — luxury South Africa itineraries.',
                <<<'HTML'
<p>South Africa combines Big Five safari, Cape Town drama, world-class wine estates, coastal drives and some of Africa’s finest golf courses in one polished destination.</p>
<h2>Why travel to South Africa with Shishi Footsteps</h2>
<p>We design flexible circuits that may include private Greater Kruger reserves, Kruger National Park, Cape Town and Table Mountain, Stellenbosch wine country, the Garden Route, Durban coast and golf from Westlake to Skukuza.</p>
<h2>Signature experiences</h2>
<ul>
<li>Big Five safari in Kruger and private reserves such as Sabi Sands</li>
<li>Cape Town, Table Mountain and the V&amp;A Waterfront</li>
<li>Wine tasting in Stellenbosch and Franschhoek</li>
<li>Garden Route coastal scenery</li>
<li>Whale watching on the Western Cape coast</li>
<li>Championship and scenic golf, including Skukuza inside Kruger</li>
</ul>
<h2>Best time to visit</h2>
<p>Dry winter months (May–September) are ideal for wildlife spotting. Summer is greener with vibrant Cape and coastal energy. Whale season peaks roughly June–November on the Cape coast.</p>
HTML,
            ],
            'namibia' => [
                'Namibia Travel Guide',
                'Namibia Safari Tours | Shishi Footsteps',
                'Sossusvlei dunes, Etosha wildlife, Skeleton Coast drama, desert-adapted species and rare desert golf — private Namibia journeys.',
                <<<'HTML'
<p>Namibia is Africa at its most sculptural: towering dunes, desert-adapted wildlife, immense skies and remote lodges where silence is part of the luxury.</p>
<h2>Why travel to Namibia with Shishi Footsteps</h2>
<p>We design privately guided or self-drive-supported journeys through Sossusvlei, Etosha, Damaraland and the Skeleton Coast, with optional desert golf near Swakopmund at unique oasis courses such as Rossmund.</p>
<h2>Signature experiences</h2>
<ul>
<li>Sossusvlei and Deadvlei dune landscapes</li>
<li>Etosha National Park wildlife around waterholes</li>
<li>Desert-adapted elephants in Damaraland</li>
<li>Skeleton Coast scenery and photography</li>
<li>Stargazing and remote lodge stays</li>
<li>Desert golf near the Atlantic coast</li>
</ul>
<h2>Best time to visit</h2>
<p>May–October is excellent for Etosha wildlife viewing. Dune photography is dramatic year-round; summer brings hotter temperatures inland.</p>
HTML,
            ],
            'botswana' => [
                'Botswana Travel Guide',
                'Botswana Safari Tours | Shishi Footsteps',
                'Okavango Delta waterways, Chobe elephants, Moremi and Savuti — low-impact wilderness safari with private camps.',
                <<<'HTML'
<p>Botswana is synonymous with pristine wilderness: mokoro trails through the Okavango, elephant-rich Chobe riverfronts, predator country in Moremi and Savuti, and camps designed for quiet immersion.</p>
<h2>Why travel to Botswana with Shishi Footsteps</h2>
<p>We favour private concessions, sensible flight connections and camps matched to your style — classic safari, photographic focus, or a Chobe-and-Delta combination with polished guiding.</p>
<h2>Signature experiences</h2>
<ul>
<li>Okavango Delta mokoro and boat safaris</li>
<li>Chobe river cruises and huge elephant herds</li>
<li>Moremi and Savuti predator viewing</li>
<li>Private concession game drives</li>
<li>Mobile or permanent luxury camps</li>
</ul>
<h2>Best time to visit</h2>
<p>Dry months (May–October) concentrate wildlife and make Delta water channels especially rewarding. Green season brings lush scenery and outstanding birding.</p>
HTML,
            ],
        ];

        foreach ($guides as $slug => [$title, $seoTitle, $seoDesc, $html]) {
            CmsPage::updateOrCreate(
                ['slug' => $slug, 'type' => 'destination'],
                [
                    'title' => $title,
                    'content' => trim($html),
                    'seo_title' => $seoTitle,
                    'seo_description' => $seoDesc,
                    'published' => true,
                    'published_at' => now(),
                ]
            );
        }
    }

    private function upsertPackage(string $slug, array $data, array $days): void
    {
        $safari = ItineraryV2::updateOrCreate(['slug' => $slug], $data + [
            'published' => true,
            'currency' => 'USD',
        ]);

        $safari->days()->delete();
        foreach ($days as $i => [$title, $activities, $meal]) {
            ItineraryDayV2::create([
                'itinerary_v2_id' => $safari->id,
                'day_number' => $i + 1,
                'title' => $title,
                'location' => $data['country'],
                'activities' => $activities,
                'meal_plan' => $meal ?? 'As per itinerary',
                'sort_order' => $i,
            ]);
        }
    }

    private function seedKenya(): void
    {
        $this->upsertPackage('the-coastal-golf-and-beach-safari-circuit', [
            'title' => 'The Coastal Golf and Beach Safari Circuit',
            'summary' => 'Golf, Indian Ocean beaches and marine life along Kenya’s coast — Nyali, Leisure Lodge, Vipingo Ridge, Shimba Hills and Diani.',
            'duration_days' => 7,
            'country' => 'Kenya',
            'region' => 'Coast',
            'price_from' => 2000,
            'featured' => true,
            'inclusions' => ['Green fees as listed', 'Park fees', 'Private transfers', 'Selected water activities'],
            'exclusions' => ['International flights', 'Travel insurance', 'Tips'],
            'notes' => '<p>Kenya coastal golf and beach circuit matching Shishi Footsteps live golf itineraries.</p>',
            'images' => ['images/itineraries/kenya-coast-day.webp'],
        ], [
            ['Arrival in Mombasa – Nyali Golf', 'Transfer to Nyali Golf & Country Club. Afternoon round at Nyali Golf Club.', 'All-inclusive'],
            ['Leisure Lodge Golf & Shimba Hills', 'Morning golf at Leisure Lodge Golf Resort. Afternoon game drive in Shimba Hills National Reserve. Overnight Diani Beach.', 'Full board'],
            ['Water Sports and Beach Day', 'Snorkeling or diving at Kisite Marine Park. Beachfront overnight in Diani.', 'Full board'],
            ['Vipingo Ridge & Mombasa Culture', 'Morning golf at Vipingo Ridge Baobab Course. Afternoon Fort Jesus and Old Town tour.', 'Full board'],
            ['Diani Leisure & Sunset Cruise', 'Beach or spa day at leisure. Evening sunset dhow cruise on the Indian Ocean.', 'Full board'],
            ['Malindi & Watamu Excursion', 'Full-day excursion to Malindi marine park and Swahili cultural experiences.', 'Full board'],
            ['Departure', 'Transfer to Mombasa for departure flight.', 'Breakfast'],
        ]);

        $this->upsertPackage('the-great-rift-valley-golf-safari-circuit', [
            'title' => 'The Great Rift Valley Golf Safari Circuit',
            'summary' => 'Nairobi golf, Great Rift Valley Lodge, Lake Naivasha boat safari, Nakuru flamingos and Hell’s Gate adventure.',
            'duration_days' => 7,
            'country' => 'Kenya',
            'region' => 'Rift Valley',
            'price_from' => 2500,
            'featured' => true,
            'inclusions' => ['Green fees for 3 rounds', 'Park fees', 'Private vehicle and guide', 'Selected meals'],
            'exclusions' => ['International flights', 'Travel insurance', 'Tips'],
            'notes' => '<p>Kenya Rift Valley golf safari circuit from Shishi Footsteps WordPress content.</p>',
            'images' => ['images/itineraries/kenya-family-cover.webp'],
        ], [
            ['Arrive Nairobi – Windsor Golf', 'Meet and greet at JKIA. Transfer to Windsor Golf Hotel & Country Club. Optional 9-hole round.', 'Half board'],
            ['Windsor Golf & transfer to Naivasha', 'Morning 18-hole round at Windsor. Transfer to Lake Naivasha (approx 2.5 hours).', 'Full board'],
            ['Great Rift Valley Golf & boat safari', 'Morning golf at Great Rift Valley Lodge. Afternoon boat safari on Lake Naivasha.', 'Full board'],
            ['Lake Nakuru National Park', 'Full-day game drive for flamingos, rhinos and lakeside wildlife. Overnight Nakuru.', 'Full board'],
            ['Hell’s Gate & Naivasha Sports Club', 'Hell’s Gate hiking or biking. Afternoon tee-off at Naivasha Sports Club.', 'Full board'],
            ['Return to Nairobi', 'Return to Nairobi. Evening farewell dinner option at Carnivore Restaurant.', 'Half board'],
            ['Departure', 'Transfer to JKIA for departure.', 'Breakfast'],
        ]);

        $this->upsertPackage('10-days-9-nights-kenya-on-wheels-golf-safari-coastal-bliss', [
            'title' => '10 Days 9 Nights Kenya on Wheels: Golf, Safari & Coastal Bliss',
            'summary' => 'Road safari from Nairobi golf to Amboseli, Tsavo and Diani Beach coastal golf — from USD 4,500.',
            'duration_days' => 10,
            'country' => 'Kenya',
            'region' => 'Multi-destination',
            'price_from' => 4500,
            'featured' => true,
            'inclusions' => ['Accommodation', 'Park fees', 'Private vehicle', 'Green fees as listed'],
            'exclusions' => ['International flights', 'Visa fees', 'Tips', 'Optional activities'],
            'notes' => '<p>Flagship Kenya on Wheels golf and safari circuit.</p>',
            'images' => ['images/itineraries/kenya-family-cover.webp', 'images/itineraries/kenya-coast-day.webp'],
        ], [
            ['Arrival Nairobi – Windsor Golf', 'Check into Windsor Golf Hotel & Country Club. Afternoon championship round and clubhouse evening.', 'Half board'],
            ['Drive to Amboseli', 'Road transfer to Amboseli (4–5 hours). Afternoon game drive with Kilimanjaro views.', 'Full board'],
            ['Full-day Amboseli safari', 'Morning and afternoon game drives. Optional Maasai village visit. Overnight luxury tented camp.', 'Full board'],
            ['Drive to Tsavo East', 'Transfer to Tsavo East with game drive en route. Afternoon exploring Galana River / Mudanda Rock.', 'Full board'],
            ['Tsavo West safari & relaxation', 'Morning Tsavo West game drive and Mzima Springs. Afternoon at leisure.', 'Full board'],
            ['Continue Tsavo / coastal transfer staging', 'Further Tsavo exploration or begin coastal transfer staging toward Diani.', 'Full board'],
            ['Arrive Diani Beach', 'Reach the south coast. Beach time and coastal lodge check-in.', 'Full board'],
            ['Coastal golf & ocean leisure', 'Round at a south coast course and beach relaxation.', 'Full board'],
            ['Diani marine or leisure day', 'Optional marine park, spa or beach day.', 'Full board'],
            ['Departure', 'Transfer for departure flight.', 'Breakfast'],
        ]);
    }

    private function seedTanzania(): void
    {
        $this->upsertPackage('7-day-tanzania-northern-circuit-classic', [
            'title' => '7-Day Tanzania Northern Circuit Classic',
            'summary' => 'Tarangire, Ngorongoro Crater and Serengeti — the classic northern Tanzania safari circuit with private pacing.',
            'duration_days' => 7,
            'country' => 'Tanzania',
            'region' => 'Northern Circuit',
            'price_from' => 4290,
            'featured' => true,
            'inclusions' => ['Park fees', 'Private guiding', 'Game drives', 'Accommodation'],
            'exclusions' => ['International flights', 'Balloon safari', 'Tips'],
            'notes' => '<p>Core Tanzania northern circuit aligned with Shishi Footsteps demo and FAQ destinations.</p>',
            'images' => ['images/itineraries/tanzania-classic-cover.webp', 'images/itineraries/tanzania-crater-day.webp'],
        ], [
            ['Arrive Arusha', 'Meet and greet. Overnight Arusha / nearby lodge.', 'Half board'],
            ['Tarangire National Park', 'Game drives among elephant herds and baobabs.', 'Full board'],
            ['Transfer toward Ngorongoro', 'Scenic transfer and crater rim overnight.', 'Full board'],
            ['Ngorongoro Crater floor', 'Full-day crater exploration for dense Big Five viewing.', 'Full board'],
            ['Serengeti National Park', 'Enter the Serengeti plains. Afternoon game drive.', 'Full board'],
            ['Full-day Serengeti', 'Morning and afternoon game drives. Optional balloon safari.', 'Full board'],
            ['Return / depart', 'Transfer to airstrip or Arusha for departure.', 'Breakfast'],
        ]);

        $this->upsertPackage('tanzania-serengeti-migration-focus', [
            'title' => 'Serengeti Migration Focus Safari',
            'summary' => 'A Tanzania safari shaped around seasonal Great Migration movements across the Serengeti-Mara ecosystem.',
            'duration_days' => 6,
            'country' => 'Tanzania',
            'region' => 'Serengeti',
            'price_from' => null,
            'featured' => false,
            'inclusions' => ['Park fees', 'Private guiding', 'Game drives'],
            'exclusions' => ['International flights', 'Tips'],
            'notes' => '<p>Migration timing varies yearly; we place camps according to rainfall and herd movement.</p>',
            'images' => ['images/itineraries/tanzania-classic-cover.webp'],
        ], [
            ['Arrive northern Tanzania', 'Arrival and transfer toward Serengeti region.', 'Half board'],
            ['Central / south Serengeti', 'Game drives shaped around current wildlife concentrations.', 'Full board'],
            ['Migration plains', 'Full days following wildebeest, zebra and predator action.', 'Full board'],
            ['River country (seasonal)', 'Focus on river corridors when crossings are active (typically Jul–Oct in the north).', 'Full board'],
            ['Optional Ngorongoro add-on day', 'Optional crater day or continued Serengeti tracking.', 'Full board'],
            ['Departure', 'Fly or drive out for international connections.', 'Breakfast'],
        ]);
    }

    private function seedUganda(): void
    {
        $this->upsertPackage('gorilla-trekking-golf-safari-in-western-uganda', [
            'title' => 'Gorilla Trekking & Golf Safari in Western Uganda',
            'summary' => 'Lake Victoria golf, Bwindi gorilla trekking, Lake Mburo safari and wildlife fairways — from USD 2,900.',
            'duration_days' => 6,
            'country' => 'Uganda',
            'region' => 'Bwindi · Lake Mburo',
            'price_from' => 2900,
            'featured' => true,
            'inclusions' => ['2 rounds of golf', 'Gorilla permit', 'Park fees', 'Private transport', 'Domestic flights as listed'],
            'exclusions' => ['International flights', 'Visa', 'Tips'],
            'notes' => '<p>Live Shishi Footsteps Western Uganda golf and gorilla itinerary.</p>',
            'images' => ['https://images.unsplash.com/photo-1559592413-7cec4d0cae2b?auto=format&fit=crop&w=1600&q=84&fm=webp'],
        ], [
            ['Arrival Entebbe – Lake Victoria golf', 'Transfer to Lake Victoria Serena Golf Resort. Evening round of golf.', 'Half board'],
            ['Fly to Bwindi', 'Scenic flight to Bwindi. Afternoon nature walk or Batwa community visit.', 'Full board'],
            ['Gorilla trekking in Bwindi', 'Early morning gorilla trek (permit included). Afternoon birding or leisure.', 'Full board'],
            ['Transfer to Lake Mburo', 'Drive to Lake Mburo via Igongo Cultural Centre. Evening game drive.', 'Full board'],
            ['Golf and boat safari at Lake Mburo', 'Morning 9-hole golf with wildlife on the fairways. Afternoon boat safari.', 'Full board'],
            ['Return to Entebbe', 'Walking safari then drive to Entebbe with equator stop.', 'Breakfast'],
        ]);

        $this->upsertPackage('murchison-falls-golf-safari-experience', [
            'title' => 'Murchison Falls & Golf Safari Experience',
            'summary' => 'Kampala golf, Ziwa rhinos, Murchison Falls game drives and Nile cruise, then Fort Portal / Tooro golf — from USD 3,200.',
            'duration_days' => 7,
            'country' => 'Uganda',
            'region' => 'Murchison · Fort Portal',
            'price_from' => 3200,
            'featured' => true,
            'inclusions' => ['2 rounds of golf', 'Game drives', 'Boat safari', 'Park fees', 'Cultural tours'],
            'exclusions' => ['International flights', 'Tips'],
            'notes' => '<p>Uganda Murchison Falls golf safari from WordPress content.</p>',
            'images' => ['https://images.unsplash.com/photo-1540573133985-87b6da6d54a9?auto=format&fit=crop&w=1600&q=84&fm=webp'],
        ], [
            ['Arrive Entebbe – Kampala golf', 'Transfer to Uganda Golf Club in Kampala for an introductory 9-hole round.', 'Half board'],
            ['Transfer to Murchison Falls', 'Drive to Murchison (approx 5 hours) via Ziwa Rhino Sanctuary tracking.', 'Full board'],
            ['Game drive and Nile boat cruise', 'Morning game drive. Afternoon Nile boat to the base of the falls; hike to the top.', 'Full board'],
            ['Golf at Karuma Falls', 'Round at Karuma Falls Golf Course on the park edge. Afternoon lodge leisure.', 'Full board'],
            ['Transfer to Fort Portal', 'Scenic drive with crater lake views and cultural stops.', 'Full board'],
            ['Tooro Golf & crater lakes', 'Morning golf at Tooro Golf Club. Afternoon crater lakes and Amabere Caves.', 'Full board'],
            ['Return to Entebbe', 'Flight from Kasese airstrip to Entebbe for departure.', 'Breakfast'],
        ]);

        $this->upsertPackage('source-of-the-nile-golf-and-adventure-safari', [
            'title' => 'Source of the Nile Golf and Adventure Safari',
            'summary' => 'Kampala and Jinja golf with Mabira Forest, white-water rafting and Nile adventures — from USD 1,800.',
            'duration_days' => 5,
            'country' => 'Uganda',
            'region' => 'Jinja',
            'price_from' => 1800,
            'featured' => false,
            'inclusions' => ['Golf fees', 'Adventure activities', 'Park fees', 'Private transport'],
            'exclusions' => ['International flights', 'Tips'],
            'notes' => '<p>Jinja / Source of the Nile adventure and golf safari.</p>',
            'images' => ['https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=1600&q=84&fm=webp'],
        ], [
            ['Arrive Kampala – intro golf', 'Meet at Entebbe. Transfer for 9-hole round at Uganda Golf Club.', 'Half board'],
            ['Transfer to Jinja', 'Mabira Forest zip-lining and birdwatching. Afternoon golf at Jinja Golf Club.', 'Full board'],
            ['White-water rafting', 'Full day Grade 3–5 Nile rafting. Evening sundowner cruise.', 'Full board'],
            ['Quad biking & Source of the Nile', 'Morning quad biking. Afternoon Source of the Nile visit; optional kayak or horseback.', 'Full board'],
            ['Return to Entebbe', 'Morning lodge leisure. Transfer to Entebbe for departure.', 'Breakfast'],
        ]);

        $this->upsertPackage('queen-elizabeth-tooro-golf-safari', [
            'title' => 'Queen Elizabeth & Tooro Golf Safari',
            'summary' => 'Tooro highland golf, Queen Elizabeth plains, Ishasha tree-climbing lions and Kazinga Channel boat safari — from USD 2,600.',
            'duration_days' => 6,
            'country' => 'Uganda',
            'region' => 'Queen Elizabeth · Fort Portal',
            'price_from' => 2600,
            'featured' => true,
            'inclusions' => ['2 rounds of golf', 'Game drives', 'Boat safaris', 'Cultural tours'],
            'exclusions' => ['International flights', 'Tips'],
            'notes' => '<p>Queen Elizabeth and Tooro golf safari from live/WP Shishi content.</p>',
            'images' => ['https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?auto=format&fit=crop&w=1600&q=84&fm=webp'],
        ], [
            ['Arrive Entebbe – Lake Victoria golf', 'Transfer to Lake Victoria Serena Golf Resort for an evening round.', 'Half board'],
            ['Transfer to Fort Portal', 'Drive to Fort Portal. Afternoon round at Tooro Golf Club.', 'Full board'],
            ['Queen Elizabeth National Park', 'Transfer to Queen Elizabeth. Afternoon Kasenyi plains game drive.', 'Full board'],
            ['Ishasha & Kazinga Channel', 'Morning Ishasha for tree-climbing lions. Afternoon Kazinga Channel boat safari.', 'Full board'],
            ['Garuga Golf & Bakonzo culture', 'Morning Garuga Golf Course. Afternoon Bakonzo community visit.', 'Full board'],
            ['Return to Entebbe', 'Drive to Entebbe with equator stop.', 'Breakfast'],
        ]);

        $this->upsertPackage('4-days-bwindi-gorilla-trekking-safari-in-uganda', [
            'title' => '4 Days Bwindi Gorilla Trekking Safari in Uganda',
            'summary' => 'A focused Bwindi gorilla trek with domestic flights, community visit and lodge stays.',
            'duration_days' => 4,
            'country' => 'Uganda',
            'region' => 'Bwindi',
            'price_from' => null,
            'featured' => false,
            'inclusions' => ['Gorilla permit', 'Domestic flights', 'Transfers', 'Meals as specified', 'Community visit'],
            'exclusions' => ['International flights', 'Visa', 'Insurance', 'Tips'],
            'notes' => '<p>Short Bwindi gorilla safari from WordPress content.</p>',
            'images' => ['https://images.unsplash.com/photo-1559592413-7cec4d0cae2b?auto=format&fit=crop&w=1600&q=84&fm=webp'],
        ], [
            ['Arrive Entebbe', 'Arrival and overnight staging for the following day’s flight to Bwindi.', 'Half board'],
            ['Fly to Bwindi', 'Flight to Kihihi / Bwindi region. Community visit. Overnight lodge.', 'Full board'],
            ['Gorilla trekking', 'Briefing and trek to a gorilla family. One hour with the gorillas. Overnight lodge.', 'Full board'],
            ['Return to Entebbe', 'Flight back to Entebbe for international connections.', 'Breakfast'],
        ]);
    }

    private function seedSouthAfrica(): void
    {
        $this->upsertPackage('7-days-6-nights-south-africa-golf-travel', [
            'title' => '7 Days 6 Nights South Africa Golf & Travel',
            'summary' => 'Cape Town golf and city highlights, Stellenbosch wine country, Durban coast and Kruger safari with Skukuza golf — from USD 3,800.',
            'duration_days' => 7,
            'country' => 'South Africa',
            'region' => 'Cape · Durban · Kruger',
            'price_from' => 3800,
            'featured' => true,
            'inclusions' => ['Golf as listed', 'Selected sightseeing', 'Safari game drives', 'Transfers / domestic flights as arranged'],
            'exclusions' => ['International flights', 'Tips', 'Optional extras'],
            'notes' => '<p>Flagship South Africa golf and travel itinerary from live WP content.</p>',
            'images' => ['https://images.unsplash.com/photo-1484318571209-661cf29a69c3?auto=format&fit=crop&w=1600&q=84&fm=webp', 'https://images.unsplash.com/photo-1580060839134-75a5edca2e99?auto=format&fit=crop&w=1600&q=84&fm=webp'],
        ], [
            ['Arrive Cape Town – Westlake Golf', 'Transfer to hotel. Afternoon round at Westlake Golf Club. Evening V&A Waterfront.', 'Bed & breakfast'],
            ['Table Mountain & Mowbray Golf', 'Table Mountain cable car and Bo-Kaap. Round at Mowbray Golf Club. Dinner in Camps Bay.', 'Bed & breakfast'],
            ['Stellenbosch wine & golf', 'Wine tasting then round at Stellenbosch Golf Club. Farm-to-table dinner.', 'Bed & breakfast'],
            ['Fly to Durban – Beachwood Golf', 'Fly to Durban. Umhlanga beaches. Tee off at Durban Country Club Beachwood Course.', 'Bed & breakfast'],
            ['uShaka & Windsor Park Golf', 'uShaka Marine World. Round at Windsor Park Golf Course. Florida Road evening.', 'Bed & breakfast'],
            ['Kruger National Park safari', 'Fly to Nelspruit. Afternoon Big Five game drive. Bush dinner.', 'Full board'],
            ['Skukuza Golf & safari', 'Morning game drive. Round at unfenced Skukuza Golf Club inside Kruger. Farewell dinner.', 'Full board'],
        ]);

        $this->upsertPackage('6-days-in-south-africa-cape-town-whales-wine-routes', [
            'title' => '6 Days in South Africa: Cape Town, Whales & Wine Routes',
            'summary' => 'Cape Town, whale coast and Stellenbosch wine routes — a polished Western Cape circuit.',
            'duration_days' => 6,
            'country' => 'South Africa',
            'region' => 'Western Cape',
            'price_from' => null,
            'featured' => true,
            'inclusions' => ['Private guiding', 'Selected tastings', 'Scenic transfers'],
            'exclusions' => ['International flights', 'Tips'],
            'notes' => '<p>Cape Town whales and wine route from WordPress package content.</p>',
            'images' => ['https://images.unsplash.com/photo-1484318571209-661cf29a69c3?auto=format&fit=crop&w=1600&q=84&fm=webp', 'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?auto=format&fit=crop&w=1600&q=84&fm=webp'],
        ], [
            ['Arrive Cape Town', 'City orientation and Waterfront evening.', 'Bed & breakfast'],
            ['Table Mountain & Peninsula', 'Table Mountain and scenic peninsula highlights.', 'Bed & breakfast'],
            ['Whale coast', 'Coastal drive with seasonal whale watching opportunities.', 'Bed & breakfast'],
            ['Stellenbosch wine routes', 'Wine estates and Cape Winelands scenery.', 'Bed & breakfast'],
            ['Franschhoek / Paarl leisure', 'Further wine country or Cape cuisine experiences.', 'Bed & breakfast'],
            ['Departure', 'Transfer for departure flight.', 'Breakfast'],
        ]);

        $this->upsertPackage('10-day-south-africa-garden-route-road-trip', [
            'title' => '10-Day South Africa Garden Route Road Trip',
            'summary' => 'Extended Garden Route coastal scenery with optional public courses such as Fancourt Montagu and George Golf Club.',
            'duration_days' => 10,
            'country' => 'South Africa',
            'region' => 'Garden Route',
            'price_from' => null,
            'featured' => false,
            'inclusions' => ['Private or self-drive support', 'Handpicked stays'],
            'exclusions' => ['International flights', 'Green fees unless requested'],
            'notes' => '<p>Garden Route extension recommended in Shishi SA golf content.</p>',
            'images' => ['https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1600&q=84&fm=webp', 'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?auto=format&fit=crop&w=1600&q=84&fm=webp'],
        ], [
            ['Cape Town start', 'Begin the coastal journey from Cape Town.', 'Bed & breakfast'],
            ['Hermanus / whale coast', 'Coastal towns and seasonal whale watching.', 'Bed & breakfast'],
            ['Mossel Bay / route staging', 'Continue along the Garden Route.', 'Bed & breakfast'],
            ['Knysna lagoons', 'Knysna lagoon and forest scenery.', 'Bed & breakfast'],
            ['Plettenberg Bay', 'Beaches and optional marine activities.', 'Bed & breakfast'],
            ['Tsitsikamma', 'Tsitsikamma forests and coastline.', 'Bed & breakfast'],
            ['George golf option', 'Optional round at George Golf Club or Fancourt area.', 'Bed & breakfast'],
            ['Wilderness / lakes', 'Lakes and leisurely coastal days.', 'Bed & breakfast'],
            ['Return west or fly out staging', 'Flexible routing toward Cape Town or Port Elizabeth.', 'Bed & breakfast'],
            ['Departure', 'Departure transfer.', 'Breakfast'],
        ]);

        $this->upsertPackage('the-road-to-kruger', [
            'title' => 'The Road to Kruger',
            'summary' => 'A South Africa safari approach focused on Kruger and Greater Kruger private reserves.',
            'duration_days' => 5,
            'country' => 'South Africa',
            'region' => 'Kruger',
            'price_from' => null,
            'featured' => true,
            'inclusions' => ['Game drives', 'Park fees', 'Lodge stays as arranged'],
            'exclusions' => ['International flights', 'Tips'],
            'notes' => '<p>Kruger-focused South Africa safari product.</p>',
            'images' => ['https://images.unsplash.com/photo-1534177616072-ef7dc120449d?auto=format&fit=crop&w=1600&q=84&fm=webp'],
        ], [
            ['Arrive Johannesburg / Nelspruit', 'Arrival and transfer toward Greater Kruger.', 'Half board'],
            ['Private reserve / Kruger', 'Afternoon game drive.', 'Full board'],
            ['Full safari day', 'Morning and afternoon drives for Big Five viewing.', 'Full board'],
            ['Safari & lodge leisure', 'Further drives or photographic focus.', 'Full board'],
            ['Departure', 'Transfer to airport.', 'Breakfast'],
        ]);

        $this->upsertPackage('fairways-the-mother-city-cape-town-golf-wine-itinerary-6-days', [
            'title' => 'Fairways & The Mother City – Cape Town Golf & Wine',
            'summary' => 'Six days of Cape Town golf, city icons and Winelands — the Mother City fairway itinerary.',
            'duration_days' => 6,
            'country' => 'South Africa',
            'region' => 'Cape Town',
            'price_from' => null,
            'featured' => false,
            'inclusions' => ['Selected green fees', 'Wine experiences', 'Private transfers'],
            'exclusions' => ['International flights', 'Tips'],
            'notes' => '<p>Cape Town golf and wine itinerary from WordPress.</p>',
            'images' => ['https://images.unsplash.com/photo-1484318571209-661cf29a69c3?auto=format&fit=crop&w=1600&q=84&fm=webp'],
        ], [
            ['Arrive Cape Town', 'Settle in with city and mountain views.', 'Bed & breakfast'],
            ['Peninsula & first round', 'Scenic Cape exploration and golf.', 'Bed & breakfast'],
            ['City icons & golf', 'Table Mountain area highlights and another championship-style round.', 'Bed & breakfast'],
            ['Stellenbosch fairways & wine', 'Winelands golf and tasting.', 'Bed & breakfast'],
            ['Leisure / second wine valley', 'Flexible Cape cuisine and wine day.', 'Bed & breakfast'],
            ['Departure', 'Airport transfer.', 'Breakfast'],
        ]);
    }

    private function seedNamibia(): void
    {
        $this->upsertPackage('namibia-etosha-sossusvlei-circuit', [
            'title' => 'Namibia Etosha & Sossusvlei Circuit',
            'summary' => 'Desert dunes, Etosha waterholes and Atlantic desert golf options — a classic privately guided Namibia journey.',
            'duration_days' => 8,
            'country' => 'Namibia',
            'region' => 'Etosha · Sossusvlei · Coast',
            'price_from' => null,
            'featured' => true,
            'inclusions' => ['Private guiding / vehicle', 'Park fees', 'Handpicked lodges'],
            'exclusions' => ['International flights', 'Optional desert golf green fees', 'Tips'],
            'notes' => '<p>Namibia circuit reflecting Shishi destination themes and desert golf mentions (e.g. Rossmund).</p>',
            'images' => ['https://images.unsplash.com/photo-1509316785289-025f5b846b35?auto=format&fit=crop&w=1600&q=84&fm=webp', 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=1600&q=84&fm=webp'],
        ], [
            ['Arrive Windhoek', 'Arrival and city overnight.', 'Bed & breakfast'],
            ['Drive to Sossusvlei region', 'Transfer toward Namib dunes.', 'Full board'],
            ['Sossusvlei & Deadvlei', 'Dune landscapes and desert photography.', 'Full board'],
            ['Toward Swakopmund / coast', 'Atlantic coast staging; optional Rossmund desert golf.', 'Bed & breakfast'],
            ['Coastal leisure / Skeleton Coast intro', 'Coastal scenery and optional marine or desert activities.', 'Bed & breakfast'],
            ['Transfer to Etosha', 'Drive or fly toward Etosha National Park.', 'Full board'],
            ['Etosha game viewing', 'Waterhole and plains wildlife viewing.', 'Full board'],
            ['Return Windhoek / depart', 'Return for departure.', 'Breakfast'],
        ]);
    }

    private function seedBotswana(): void
    {
        $this->upsertPackage('5-day-luxury-botswana-safari', [
            'title' => '5-Day Luxury Botswana Safari',
            'summary' => 'A compact luxury Botswana safari with Chobe riverfront wildlife and Delta-style immersion.',
            'duration_days' => 5,
            'country' => 'Botswana',
            'region' => 'Chobe · Delta',
            'price_from' => 7200,
            'featured' => true,
            'inclusions' => ['Game drives', 'Boat / mokoro activities as arranged', 'Luxury camp stays'],
            'exclusions' => ['International flights', 'Tips'],
            'notes' => '<p>Luxury Botswana safari package retained and day structure enriched.</p>',
            'images' => ['images/itineraries/botswana-luxury-cover.webp', 'images/itineraries/botswana-chobe-day.webp'],
        ], [
            ['Arrive Kasane / Chobe', 'Arrival and afternoon Chobe river or game activity.', 'Full board'],
            ['Chobe elephants & river', 'Morning drive and afternoon river cruise among elephant herds.', 'Full board'],
            ['Fly to Okavango Delta', 'Light aircraft transfer to a private Delta camp.', 'Full board'],
            ['Delta mokoro & drives', 'Mokoro trails and game drives in watery wilderness.', 'Full board'],
            ['Departure', 'Fly out for international connections.', 'Breakfast'],
        ]);

        $this->upsertPackage('botswana-okavango-moremi-explorer', [
            'title' => 'Okavango & Moremi Explorer',
            'summary' => 'Deeper Okavango and Moremi wilderness with private concession guiding and water-based safari days.',
            'duration_days' => 6,
            'country' => 'Botswana',
            'region' => 'Okavango · Moremi',
            'price_from' => null,
            'featured' => false,
            'inclusions' => ['Light aircraft transfers as arranged', 'Game drives', 'Boat/mokoro activities'],
            'exclusions' => ['International flights', 'Tips'],
            'notes' => '<p>Additional Botswana wilderness circuit for destination depth.</p>',
            'images' => ['images/itineraries/botswana-chobe-day.webp'],
        ], [
            ['Arrive Maun / Delta', 'Arrival and transfer into the Delta system.', 'Full board'],
            ['Private concession drives', 'Morning and afternoon wildlife viewing.', 'Full board'],
            ['Mokoro & channels', 'Water-based exploration of lagoons and channels.', 'Full board'],
            ['Moremi / predator country', 'Focus on Moremi habitats and predators.', 'Full board'],
            ['Photographic / leisure safari day', 'Flexible pacing for photography or longer drives.', 'Full board'],
            ['Departure', 'Fly to Maun for onward travel.', 'Breakfast'],
        ]);
    }

    private function seedNyungwe(): void
    {
        $this->upsertPackage('nyungwe-forest-chimpanzee-canopy-extension', [
            'title' => 'Nyungwe Forest Chimpanzee & Canopy Extension',
            'summary' => 'Add Nyungwe Forest National Park to any Rwanda journey — chimpanzee tracking, canopy walk and misty highland forests.',
            'duration_days' => 3,
            'country' => 'Rwanda',
            'region' => 'Nyungwe',
            'price_from' => null,
            'featured' => true,
            'inclusions' => ['Park fees', 'Chimpanzee permit / tracking as arranged', 'Canopy walk', 'Transfers from/to Volcanoes or Kigali as designed'],
            'exclusions' => ['International flights', 'Gorilla permits (separate)', 'Tips'],
            'notes' => '<p>Nyungwe extension requested to complete Rwanda’s Volcanoes–Akagera–Nyungwe triangle featured in Shishi FAQ content.</p>',
            'images' => ['https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=1600&q=84&fm=webp', 'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?auto=format&fit=crop&w=1600&q=84&fm=webp'],
        ], [
            ['Transfer to Nyungwe', 'Scenic transfer from Kigali or Volcanoes region into Nyungwe’s montane forest.', 'Full board'],
            ['Chimpanzee tracking & canopy walk', 'Morning chimpanzee tracking. Afternoon Nyungwe canopy walk above the forest.', 'Full board'],
            ['Forest trails or depart', 'Optional nature trails / birding, then continue to Akagera or return to Kigali.', 'Breakfast'],
        ]);
    }

    private function activityImageFor(string $country): string
    {
        return match ($country) {
            'Kenya' => 'images/itineraries/kenya-family-cover.webp',
            'Tanzania' => 'images/itineraries/tanzania-classic-cover.webp',
            'Botswana' => 'images/itineraries/botswana-luxury-cover.webp',
            'Uganda', 'Rwanda' => 'https://images.unsplash.com/photo-1559592413-7cec4d0cae2b?auto=format&fit=crop&w=1600&q=84&fm=webp',
            'Namibia' => 'https://images.unsplash.com/photo-1509316785289-025f5b846b35?auto=format&fit=crop&w=1600&q=84&fm=webp',
            'South Africa' => 'https://images.unsplash.com/photo-1484318571209-661cf29a69c3?auto=format&fit=crop&w=1600&q=84&fm=webp',
            default => 'images/itineraries/kenya-family-cover.webp',
        };
    }

    private function seedActivities(): void
    {
        $items = [
            ['Maasai Mara Game Drive Experience', 'Kenya', 'Maasai Mara', 'Private or shared game drives in the Mara plains and conservancies.'],
            ['Amboseli Elephant Safari', 'Kenya', 'Amboseli', 'Elephant herds and Kilimanjaro views in Amboseli National Park.'],
            ['Vipingo Ridge Championship Round', 'Kenya', 'Kilifi', 'Ocean-view round on the Vipingo Ridge PGA Baobab Course.'],
            ['Serengeti Game Drive', 'Tanzania', 'Serengeti', 'Classic Serengeti plains game drives with predator and Migration potential.'],
            ['Ngorongoro Crater Descent', 'Tanzania', 'Ngorongoro', 'Full-day crater floor exploration for dense Big Five viewing.'],
            ['Tarangire Elephant & Baobab Safari', 'Tanzania', 'Tarangire', 'Elephant herds among ancient baobabs along the Tarangire River.'],
            ['Serengeti Balloon Safari', 'Tanzania', 'Serengeti', 'Sunrise hot-air balloon flight over the Serengeti with bush breakfast.'],
            ['Bwindi Gorilla Trekking', 'Uganda', 'Bwindi', 'Mountain gorilla trek in Bwindi Impenetrable National Park with permit coordination.'],
            ['Kazinga Channel Boat Safari', 'Uganda', 'Queen Elizabeth', 'Boat safari for hippos, crocodiles and birdlife on the Kazinga Channel.'],
            ['Murchison Falls Nile Cruise', 'Uganda', 'Murchison Falls', 'Boat cruise to the base of Murchison Falls with optional hike to the top.'],
            ['Jinja White-Water Rafting', 'Uganda', 'Jinja', 'Grade 3–5 white-water rafting on the Source of the Nile.'],
            ['Tooro Golf Club Round', 'Uganda', 'Fort Portal', 'Highland golf overlooking the Rwenzori Mountains.'],
            ['Kruger Big Five Game Drive', 'South Africa', 'Kruger', 'Morning or afternoon Big Five game drives in Kruger or private reserves.'],
            ['Stellenbosch Wine Tasting', 'South Africa', 'Stellenbosch', 'Guided tasting through premier Stellenbosch wine estates.'],
            ['Table Mountain Cable Car Experience', 'South Africa', 'Cape Town', 'Cable car ascent for panoramic views of Cape Town and the coast.'],
            ['Skukuza Golf Club Round', 'South Africa', 'Kruger', 'Unique unfenced 9-hole golf experience inside Kruger National Park.'],
            ['Cape Whale Watching', 'South Africa', 'Western Cape', 'Seasonal whale watching along the Western Cape coast.'],
            ['Sossusvlei Dune Walk', 'Namibia', 'Sossusvlei', 'Guided exploration of Sossusvlei and Deadvlei dune landscapes.'],
            ['Etosha Waterhole Safari', 'Namibia', 'Etosha', 'Game viewing around Etosha’s legendary waterholes.'],
            ['Rossmund Desert Golf Round', 'Namibia', 'Swakopmund', 'Rare oasis golf near the Namib Desert dunes on the Atlantic coast.'],
            ['Okavango Mokoro Excursion', 'Botswana', 'Okavango Delta', 'Traditional mokoro trails through Delta channels and lagoons.'],
            ['Chobe River Sunset Cruise', 'Botswana', 'Chobe', 'Sunset river cruise among elephants, hippos and water birds.'],
            ['Nyungwe Canopy Walk', 'Rwanda', 'Nyungwe', 'Walk the Nyungwe canopy bridge above one of Africa’s oldest rainforests.'],
        ];

        foreach ($items as [$name, $country, $location, $description]) {
            Activity::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'country' => $country,
                    'region' => $location,
                    'location' => $location,
                    'description' => $description,
                    'published_on_website' => true,
                    'show_on_mobile_app' => true,
                    'activity_status' => 'active',
                    'currency' => 'USD',
                    'min_pax' => 1,
                    'images' => [$this->activityImageFor($country)],
                ]
            );
        }
    }
}
