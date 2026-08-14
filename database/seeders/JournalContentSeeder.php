<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class JournalContentSeeder extends Seeder
{
    public function run(): void
    {
        $image = fn (string $id) => "https://images.unsplash.com/photo-{$id}?auto=format&fit=crop&w=1600&q=84&fm=webp";
        $days = 1;

        foreach ($this->posts($image) as $post) {
            $page = CmsPage::firstOrNew(['slug' => $post['slug'], 'type' => 'blog']);
            $page->fill([
                'title' => $post['title'],
                'content' => $post['content'],
                'seo_title' => $post['title'].' | Shishi Footsteps Journal',
                'seo_description' => $post['seo'],
                'cover_image' => $post['cover'],
                'published' => true,
                'published_at' => $page->published_at ?: now()->subDays($days),
            ]);
            $page->save();
            $days++;
        }

        $this->command?->info('Journal stories seeded for Kenya, Tanzania, Uganda, Rwanda, South Africa, Namibia and Botswana.');
    }

    private function posts(callable $image): array
    {
        return [
            [
                'slug' => 'kenya-when-to-visit-masai-mara',
                'title' => 'When to visit Kenya: Mara seasons, conservancies and the coast',
                'seo' => 'A practical Kenya safari calendar covering the Great Migration, green-season wildlife, Laikipia conservancies and Indian Ocean endings.',
                'cover' => $image('1516426122078-c23e76319801'),
                'content' => $this->html([
                    'Kenya works in more than one season, which is why the first planning question is never simply “when is the migration?” It is what you want the days to feel like.',
                    'July to October is the classic dry window for the Masai Mara: short grass, concentrated wildlife and, in the right weeks, river-crossing drama. Private conservancies around the Mara add night drives, walking and quieter vehicle numbers.',
                    'November to May is greener, beautiful for photography, and often gentler on rates. Amboseli elephants, Samburu’s dry-country specialists and Laikipia’s rhino and wild-dog work remain strong reasons to travel outside peak migration months.',
                    'Many travellers finish with Diani, Vipingo or Watamu. A Kenya route can hold safari, culture and a coastal exhale without feeling rushed, provided the flying or road days are honest.',
                ]),
            ],
            [
                'slug' => 'kenya-amboseli-elephants-and-kilimanjaro',
                'title' => 'Amboseli journal: elephants under Kilimanjaro',
                'seo' => 'Why Amboseli remains one of Kenya’s most photogenic safari chapters, from elephant herds to swamp edges and Kilimanjaro views.',
                'cover' => $image('1547471080-7cc2caa01a7e'),
                'content' => $this->html([
                    'Amboseli is Kenya at its most theatrical when the mountain is out: elephants moving through swamp and dust, Kilimanjaro holding the horizon.',
                    'The park is compact, which makes it excellent early or late in a longer Kenya itinerary. Observation Hill, the swamps and the open plains give very different photographs in a single day.',
                    'We often pair Amboseli with a private conservancy stay, then continue to Tsavo or the coast. The point is not to collect parks, but to give the elephants enough time to become the story rather than a drive-by.',
                ]),
            ],
            [
                'slug' => 'kenya-private-conservancy-safari-notes',
                'title' => 'Why Kenya’s private conservancies change the safari',
                'seo' => 'How Mara and Laikipia conservancies add walking, night drives and quieter guiding to a Kenya safari.',
                'cover' => 'images/itineraries/kenya-family-cover.webp',
                'content' => $this->html([
                    'National parks set the wildlife stage. Conservancies change how you move through it.',
                    'In the Mara conservancies and across Laikipia, off-road access, night drives and guided walks are often possible. Vehicle numbers are managed, and guiding can be more personal.',
                    'That is why many of our Kenya itineraries mix a headline park with at least one conservancy stay. Families, photographers and first-time safari travellers all benefit from the extra space.',
                ]),
            ],
            [
                'slug' => 'tanzania-serengeti-migration-journal',
                'title' => 'Following the Serengeti: how we time a Tanzania migration safari',
                'seo' => 'A clear guide to Tanzania’s Great Migration months, from calving on the southern plains to northern river crossings.',
                'cover' => 'images/itineraries/tanzania-classic-cover.webp',
                'content' => $this->html([
                    'The migration is a moving river of wildebeest and zebra, not a single viewpoint you can book by name. Timing and camp location matter more than any postcard image.',
                    'December to March is the southern calving season: short-grass plains, predators and vast herds. Mid-year, the herds push north. River crossings in the northern Serengeti are famous, and never guaranteed on a given morning.',
                    'We build Tanzania routes with a Serengeti chapter, then add Ngorongoro, Tarangire or a beach finale depending on season and how much moving you want to do.',
                ]),
            ],
            [
                'slug' => 'tanzania-ngorongoro-crater-days',
                'title' => 'Ngorongoro crater days: wildlife density, pacing and where to stay',
                'seo' => 'How to plan Ngorongoro Crater as a Tanzania safari highlight without rushing the descent, the wildlife or the rim stay.',
                'cover' => 'images/itineraries/tanzania-crater-day.webp',
                'content' => $this->html([
                    'Ngorongoro is a half-day or full-day of extraordinary density: lion, elephant, buffalo, flamingo-lined lakes and, with luck, rhino, all inside a collapsed caldera.',
                    'The descent is early, the floor can be busy, and the light is best before the day warms. A rim lodge the night before keeps the morning unhurried.',
                    'We rarely make the crater the whole Tanzania story. It is a spectacular chapter beside Serengeti space or Tarangire’s baobabs and elephants.',
                ]),
            ],
            [
                'slug' => 'tanzania-when-to-visit',
                'title' => 'When to visit Tanzania: dry season, green season and Zanzibar endings',
                'seo' => 'Tanzania safari seasons explained, including migration timing, Tarangire elephants and when a Zanzibar beach ending makes sense.',
                'cover' => $image('1523805009345-7448845a9e53'),
                'content' => $this->html([
                    'June to October is the dry classic: easy game viewing, comfortable lodge weather and strong predator sightings. It is also the busiest window, so camp choice matters.',
                    'Green season brings dramatic skies, fewer vehicles and excellent birding. Southern parks such as Ruaha and Nyerere reward travellers who want wilderness over famous names.',
                    'A Zanzibar or coastal ending still works after safari, provided you leave enough recovery time between the last game drive and the first beach breakfast.',
                ]),
            ],
            [
                'slug' => 'uganda-gorilla-trekking-journal',
                'title' => 'Gorilla trekking in Uganda: Bwindi notes from the forest floor',
                'seo' => 'What gorilla trekking in Bwindi Impenetrable National Park actually feels like, from permits and pacing to the hour with the family.',
                'cover' => $image('1559592413-7cec4d0cae2b'),
                'content' => $this->html([
                    'Bwindi is steep, green and quieter than the photographs suggest until the trackers pause and the forest suddenly holds a family of mountain gorillas.',
                    'Permits are limited. Fitness helps, but the walk is not a race. Guides, porters and a sensible lodge the night before make the day feel considered rather than athletic.',
                    'We usually keep gorilla trekking as the emotional centre of a Uganda journey, then add chimpanzees, savannah or the Nile so the trip has more than one register.',
                ]),
            ],
            [
                'slug' => 'uganda-chimpanzee-kibale',
                'title' => 'Kibale chimpanzees and the rest of a Uganda safari',
                'seo' => 'How chimpanzee tracking in Kibale sits beside Queen Elizabeth, Murchison Falls and gorilla trekking on a Uganda itinerary.',
                'cover' => $image('1540573133985-87b6da6d54a9'),
                'content' => $this->html([
                    'Chimpanzee tracking in Kibale is faster, noisier and more mischievous than gorilla trekking. You hear them before you see them.',
                    'Queen Elizabeth adds tree-climbing lions and boat time on the Kazinga Channel. Murchison Falls brings river, delta and savannah. Lake Mburo is a gentler close, with walking and, for some travellers, highland golf nearby.',
                    'Uganda rewards travellers who like variety in a compact country, as long as the driving days are respected.',
                ]),
            ],
            [
                'slug' => 'uganda-when-to-visit',
                'title' => 'When to visit Uganda: dry tracks, gorilla permits and green-season forests',
                'seo' => 'The best months for gorilla trekking, chimpanzee tracking and savannah safari in Uganda, plus how we sequence the parks.',
                'cover' => $image('1441974231531-c6227db76b6e'),
                'content' => $this->html([
                    'June to August and December to February are the drier windows, when forest trails are kinder and savannah parks are easier to read.',
                    'Gorilla permits should be secured early regardless of month. Rain does not cancel trekking; it changes the mud and the photography.',
                    'We plan Uganda around permit dates first, then build the rest of the country around those forest mornings.',
                ]),
            ],
            [
                'slug' => 'rwanda-volcanoes-gorilla-trekking',
                'title' => 'Rwanda journal: gorilla trekking in Volcanoes National Park',
                'seo' => 'How gorilla trekking in Rwanda’s Volcanoes National Park works, from Kigali arrival to the hour with a mountain gorilla family.',
                'cover' => $image('1559592413-7cec4d0cae2b'),
                'content' => $this->html([
                    'Rwanda makes gorilla trekking feel remarkably close to a polished city stay. Kigali to Volcanoes National Park is a scenic drive, not an expedition.',
                    'The Virunga slopes are steep. The briefing is calm. The hour with the family is tightly guided and unforgettable.',
                    'Golden monkeys, Twin Lakes and a thoughtful Kigali day give the journey more than a single summit morning.',
                ]),
            ],
            [
                'slug' => 'rwanda-akagera-and-nyungwe',
                'title' => 'Beyond the gorillas: Akagera savannah and Nyungwe forest',
                'seo' => 'Why Akagera’s Big Five savannah and Nyungwe’s chimpanzees and canopy walk belong on a Rwanda safari, not only gorilla trekking.',
                'cover' => $image('1469854523086-cc02fe5d8800'),
                'content' => $this->html([
                    'Akagera is Rwanda’s savannah chapter: lions, elephants, giraffes, boat time on Lake Ihema and a growing Big Five story.',
                    'Nyungwe, in the southwest, is older forest: chimpanzees, colobus, a canopy walk and cool highland air.',
                    'A well-paced Rwanda itinerary can hold gorillas, savannah and forest without feeling like three separate holidays, especially with a night in Kigali to breathe.',
                ]),
            ],
            [
                'slug' => 'rwanda-when-to-visit',
                'title' => 'When to visit Rwanda: dry months, permits and Kigali golf add-ons',
                'seo' => 'Rwanda travel seasons for gorilla trekking, Akagera game drives and Nyungwe forest, plus optional golf in Kigali.',
                'cover' => $image('1500534314209-a25ddb2bd429'),
                'content' => $this->html([
                    'June to September and December to February are the drier, more popular windows. Trails are firmer and views from the volcanoes are often clearer.',
                    'Gorilla permits remain the planning anchor. Akagera wildlife viewing is strong in the dry months; Nyungwe is a forest in every season.',
                    'Travellers who want a different tempo can add championship golf around Kigali before or after the parks.',
                ]),
            ],
            [
                'slug' => 'south-africa-cape-town-and-kruger',
                'title' => 'South Africa journal: Cape Town, wine country and Greater Kruger',
                'seo' => 'How we combine Cape Town, the Winelands and a private Greater Kruger safari into one South Africa journey.',
                'cover' => $image('1484318571209-661cf29a69c3'),
                'content' => $this->html([
                    'South Africa is one of the few safari countries where a city, a vineyard and a Big Five reserve can sit in the same itinerary without strain.',
                    'Cape Town needs time: mountain, Atlantic light, neighbourhood restaurants. The Winelands add slower days. Then a flight to Greater Kruger or Sabi Sand changes the register completely.',
                    'Private reserves here are excellent for first-time safari travellers: guiding standards are high, road times are short, and rhino conservation is part of the story.',
                ]),
            ],
            [
                'slug' => 'south-africa-winelands-and-golf',
                'title' => 'Fairways and bush: golf on a South Africa safari',
                'seo' => 'Where championship golf fits a South Africa safari, from Cape courses and Winelands to Kruger-edge stays.',
                'cover' => $image('1516483638261-f4dbaf036963'),
                'content' => $this->html([
                    'South Africa is the easiest place in our collection to take golf seriously without losing the safari.',
                    'Cape Town and the Garden Route hold coastal and mountain courses. Sun City and Kruger-edge properties let you keep wildlife in the same week as a championship round.',
                    'We schedule tee times around light, transfers and the rest of the table: wine, coastline, and enough unhurried evenings.',
                ]),
            ],
            [
                'slug' => 'south-africa-when-to-visit',
                'title' => 'When to visit South Africa: safari dry season, whales and Cape summer',
                'seo' => 'South Africa travel seasons for Kruger safari, Cape Town weather, whale coast months and Winelands timing.',
                'cover' => $image('1507525428034-b723cf961d3e'),
                'content' => $this->html([
                    'May to September is the dry safari window in Kruger and the private reserves: thinner bush, strong game viewing, cooler mornings.',
                    'Cape Town’s brightest coastal months run roughly November to March. Whale watching along the coast peaks in the southern winter.',
                    'The art is matching those calendars. Many travellers safari in the dry months, then add Cape Town in a separate season; others accept a compromise and still have a superb trip.',
                ]),
            ],
            [
                'slug' => 'namibia-sossusvlei-and-etosha',
                'title' => 'Namibia journal: Sossusvlei dunes and Etosha’s white pans',
                'seo' => 'How a Namibia safari moves from the dunes of Sossusvlei to desert-adapted wildlife and Etosha’s waterholes.',
                'cover' => $image('1509316785289-025f5b846b35'),
                'content' => $this->html([
                    'Namibia is a landscape country first. The dunes at Sossusvlei and Deadvlei are sculptural, silent and worth a dawn start.',
                    'Etosha is the wildlife counterpoint: pale pans, busy waterholes, rhino and lion in a very different light from East African savannah.',
                    'Between them sit Damaraland’s desert-adapted elephants and the Skeleton Coast’s raw emptiness. Distances are real, so we fly some legs and keep road days honest.',
                ]),
            ],
            [
                'slug' => 'namibia-desert-adapted-wildlife',
                'title' => 'Desert-adapted wildlife: what makes a Namibia safari different',
                'seo' => 'Desert elephants, oryx, rhino and the spare beauty of Namibia’s wildlife, and how we plan around it.',
                'cover' => $image('1469854523086-cc02fe5d8800'),
                'content' => $this->html([
                    'Namibia’s animals are not less abundant so much as more revealed. An oryx on a gravel plain, an elephant in a dry riverbed, a rhino tracked on foot with a specialist guide.',
                    'This is not a migration safari. It is space, geology and animals that have learned the desert.',
                    'Photography, stargazing and remote camps are often the reason travellers choose Namibia over a classic East African circuit.',
                ]),
            ],
            [
                'slug' => 'namibia-when-to-visit',
                'title' => 'When to visit Namibia: cool dry months, dunes and desert golf',
                'seo' => 'The best months for Etosha, Sossusvlei and Damaraland, plus how weather shapes a Namibia road or fly-in safari.',
                'cover' => $image('1547471080-7cc2caa01a7e'),
                'content' => $this->html([
                    'May to October is the classic dry, cooler window. Etosha waterholes are at their most magnetic, and dune climbing is kinder in the morning.',
                    'Summer heat is intense inland. Coastal Swakopmund stays milder. Rain, when it comes, can transform the desert briefly and beautifully.',
                    'A round at Rossmund, near the dunes, is a rare desert-golf footnote for travellers who want the fairway as well as the wilderness.',
                ]),
            ],
            [
                'slug' => 'botswana-okavango-delta-journal',
                'title' => 'Okavango journal: water, mokoro time and private concessions',
                'seo' => 'What an Okavango Delta safari feels like, from mokoro channels and private concessions to seasonal flood timing.',
                'cover' => 'images/itineraries/botswana-luxury-cover.webp',
                'content' => $this->html([
                    'The Okavango is a safari of water and silence: channels, floodplains, and camps that feel a long way from anywhere.',
                    'Mokoro outings, boat time and game drives share the same day when the water is in. Private concessions keep the experience uncrowded.',
                    'We match camps to the flood, not the brochure. Some years the water arrives earlier; some later. That is why Botswana planning is specialist work.',
                ]),
            ],
            [
                'slug' => 'botswana-chobe-elephants',
                'title' => 'Chobe elephants and the dry-country side of Botswana',
                'seo' => 'Chobe’s elephant herds, river safari days and how they sit beside the Okavango and the Makgadikgadi on a Botswana journey.',
                'cover' => 'images/itineraries/botswana-chobe-day.webp',
                'content' => $this->html([
                    'Chobe is elephant country on a different scale: riverfront gatherings, boat cruises and dry-season concentrations that stay with you.',
                    'Savuti and Moremi add predator stories. The Makgadikgadi pans are another Botswana entirely: salt, space and, in season, zebra migration.',
                    'A Botswana itinerary is usually two or three camps, not seven. The luxury is staying long enough for the place to settle.',
                ]),
            ],
            [
                'slug' => 'botswana-when-to-visit',
                'title' => 'When to visit Botswana: flood season, dry rivers and quiet luxury camps',
                'seo' => 'Botswana safari seasons explained, from Okavango flood months to Chobe’s dry-season elephants.',
                'cover' => $image('1500534314209-a25ddb2bd429'),
                'content' => $this->html([
                    'The Okavango’s peak water months are typically the middle of the year, even though rain fell months earlier upstream. That lag is the Delta’s secret calendar.',
                    'Late dry season, roughly August to October, is superb for Chobe elephants and predator viewing, with heat as the trade-off.',
                    'Green season is quieter, beautiful for birding and often better value. We choose camps that still work when the channels drop.',
                ]),
            ],
        ];
    }

    private function html(array $paragraphs): string
    {
        return collect($paragraphs)
            ->map(fn (string $p) => '<p>'.e($p).'</p>')
            ->implode('');
    }
}
