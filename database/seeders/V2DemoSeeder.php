<?php

namespace Database\Seeders;

use App\Models\Accommodation;
use App\Models\AccommodationRoom;
use App\Models\Activity;
use App\Models\ContentItem;
use App\Models\Itinerary;
use App\Models\ItineraryDayV2;
use App\Models\ItineraryV2;
use App\Models\Lead;
use App\Models\User;
use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class V2DemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedOperationalClients();
        $this->call([OperationsSeeder::class, ItinerarySeeder::class]);
        DB::table('quotations')->where('reference', 'QT-2026-0108')->update(['status' => 'confirmed']);
        $this->localizeLegacyItineraries();
        $this->seedAccommodations();
        $this->seedActivities();
        $this->seedLeads();
        $this->seedSafaris();
        $this->seedWebsite();
        $this->seedBlogPosts();
        $this->seedEvaluationDemo();
    }

    private function seedOperationalClients(): void
    {
        foreach ([
            ['Sophie Martin', 'sophie@example.com', 'France'],
            ['Lukas Weber', 'lukas@example.de', 'Germany'],
            ['Olivia Bennett', 'olivia@bennettfamily.com', 'United Kingdom'],
        ] as [$name, $email, $country]) {
            DB::table('clients')->insert([
                'name' => $name,
                'email' => $email,
                'phone' => '+254 700 000 000',
                'country' => $country,
                'preferred_language' => 'en',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedAccommodations(): void
    {
        foreach ([
            ['Mara Serena Safari Lodge', 'Kenya', 'Maasai Mara', 'images/itineraries/kenya-family-cover.webp'],
            ['Serengeti Serena Safari Lodge', 'Tanzania', 'Serengeti', 'images/itineraries/tanzania-classic-cover.webp'],
            ['Chobe Game Lodge', 'Botswana', 'Chobe', 'images/itineraries/botswana-luxury-cover.webp'],
        ] as $index => [$name, $country, $region, $image]) {
            $stay = Accommodation::create([
                'name' => $name, 'type' => 'lodge', 'country' => $country, 'region' => $region,
                'category' => 'luxury', 'luxury_level' => 'luxury', 'currency' => 'USD',
                'published' => true, 'featured' => $index === 0, 'status' => 'active',
                'images' => [$image],
                'description' => "A carefully selected {$region} safari stay with warm service and a strong sense of place.",
            ]);
            AccommodationRoom::create([
                'accommodation_id' => $stay->id, 'name' => 'Luxury Safari Room',
                'capacity' => 2, 'max_adults' => 2, 'max_children' => 1,
                'inventory' => 10, 'is_active' => true,
            ]);
        }
    }

    private function seedActivities(): void
    {
        $categories = DB::table('activity_categories')->pluck('id', 'name');
        foreach ([
            ['Maasai Mara Balloon Safari', 'Balloon Safari', 'Kenya', 'Maasai Mara', 'images/itineraries/kenya-family-cover.webp'],
            ['Mount Longonot Guided Hike', 'Adventure', 'Kenya', 'Naivasha', 'images/itineraries/tanzania-crater-day.webp'],
            ['Nairobi National Park Day Trip', 'Safari', 'Kenya', 'Nairobi', 'images/itineraries/kenya-family-cover.webp'],
            ['Gorilla Trekking Permit', 'Walking Safari', 'Uganda', 'Bwindi', 'images/itineraries/tanzania-crater-day.webp'],
            ['Cultural Maasai Village Visit', 'Cultural Tour', 'Kenya', 'Maasai Mara', 'images/itineraries/kenya-coast-day.webp'],
            ['Chobe River Safari', 'Safari', 'Botswana', 'Chobe', 'images/itineraries/botswana-chobe-day.webp'],
        ] as [$name, $category, $country, $location, $image]) {
            Activity::create([
                'name' => $name, 'slug' => Str::slug($name),
                'activity_category_id' => $categories[$category] ?? null,
                'country' => $country, 'region' => $location, 'location' => $location,
                'min_pax' => 1, 'duration_hours' => 4, 'currency' => 'USD',
                'published_on_website' => true, 'show_on_mobile_app' => true,
                'activity_status' => 'active', 'images' => [$image],
                'description' => "A privately guided {$name} experience planned around guest pace and current conditions.",
            ]);
        }
    }

    private function seedLeads(): void
    {
        $consultant = User::where('role', 'sales')->value('id');
        foreach ([
            ['Sophie Martin', 'sophie@example.com', 'website', 'new', 'Kenya', 4],
            ['Lukas Weber', 'lukas@example.de', 'referral', 'proposal_sent', 'Tanzania', 2],
            ['Olivia Bennett', 'olivia@bennettfamily.com', 'website', 'negotiating', 'Botswana', 6],
        ] as [$name, $email, $source, $status, $destination, $travelers]) {
            Lead::create([
                'name' => $name, 'email' => $email, 'phone' => '+254 700 000 000',
                'country' => 'Kenya', 'source' => $source, 'status' => $status,
                'assigned_consultant_id' => $consultant, 'destination' => $destination,
                'travel_date' => '2026-10-10', 'travelers' => $travelers,
                'estimated_value' => 9000, 'currency' => 'USD',
            ]);
        }
    }

    private function seedSafaris(): void
    {
        foreach ([
            ['10-Day Kenya Family Safari & Indian Ocean', 'Kenya', 10, 5480, 'images/itineraries/kenya-family-cover.webp', 'images/itineraries/kenya-coast-day.webp'],
            ['7-Day Tanzania Northern Circuit Classic', 'Tanzania', 7, 4290, 'images/itineraries/tanzania-classic-cover.webp', 'images/itineraries/tanzania-crater-day.webp'],
            ['5-Day Luxury Botswana Safari', 'Botswana', 5, 7200, 'images/itineraries/botswana-luxury-cover.webp', 'images/itineraries/botswana-chobe-day.webp'],
        ] as $index => [$title, $country, $duration, $price, $cover, $supporting]) {
            $safari = ItineraryV2::create([
                'title' => $title, 'slug' => Str::slug($title),
                'summary' => "A private {$country} journey with thoughtful pacing, excellent guiding and handpicked stays.",
                'duration_days' => $duration, 'country' => $country, 'region' => 'Multi-destination',
                'price_from' => $price, 'currency' => 'USD',
                'inclusions' => ['Private guide', 'Accommodation', 'Park fees'],
                'exclusions' => ['International flights', 'Travel insurance', 'Tips'],
                'published' => true, 'featured' => $index < 2, 'images' => [$cover, $supporting],
            ]);
            foreach (range(1, min($duration, 4)) as $day) {
                ItineraryDayV2::create([
                    'itinerary_v2_id' => $safari->id, 'day_number' => $day,
                    'title' => $day === 1 ? "Welcome to {$country}" : "{$country} safari discovery",
                    'location' => $country, 'activities' => 'Private guided safari activities.',
                    'meal_plan' => 'Full Board', 'sort_order' => $day - 1,
                ]);
            }
            ContentItem::create([
                'type' => 'safari_package', 'name' => $title, 'country' => $country,
                'status' => 'published', 'price_from' => $price, 'rating' => 4.8,
                'duration_days' => $duration, 'featured' => $index < 2, 'published_at' => now(),
            ]);
        }
    }

    private function seedWebsite(): void
    {
        WebsiteSetting::updateOrCreate(['key' => 'home'], [
            'hero_image' => 'images/itineraries/kenya-family-cover.webp',
            'hero_title' => 'Luxury Safaris Crafted Around You',
            'hero_subtitle' => 'Private safari journeys across East and Southern Africa, designed around your pace and sense of wonder.',
            'show_published_accommodation' => true,
            'seo_title' => 'Shishi Footsteps | Luxury Tailor-Made African Safaris',
            'seo_description' => 'Plan a private luxury safari across East and Southern Africa with Shishi Footsteps.',
            'open_graph_image' => 'images/itineraries/kenya-family-cover.webp',
        ]);
    }

    private function seedBlogPosts(): void
    {
        if (DB::table('cms_pages')->where('type', 'blog')->exists()) {
            return;
        }

        $posts = [
            [
                'title' => 'The Ultimate Guide to Planning a Luxury Safari in East Africa',
                'slug' => 'ultimate-guide-luxury-safari-east-africa',
                'content' => '<p>East Africa is home to some of the most transformative travel experiences in the world. From the vast plains of the Serengeti to the volcanic landscapes of Ngorongoro, from Maasai Mara river crossings to gorilla trekking in lush mountain forests — each journey is extraordinary.</p><h2>When to Go</h2><p>The dry season (June to October) offers the best wildlife viewing across most parks, with the Great Migration peaking between July and September. The green season (November to May) brings lush landscapes, fewer crowds and excellent birding.</p><h2>Choosing Your Destinations</h2><p>Kenya offers the Maasai Mara, private conservancies and a warm coastal ending. Tanzania delivers the Serengeti, Ngorongoro Crater and wild southern parks. Uganda and Rwanda are the world\'s premier gorilla trekking destinations, while South Africa combines Big Five reserves with wine country and Cape Town.</p><h2>Lodge Selection</h2><p>We select properties based on service quality, location, guiding standards and conservation ethos. From luxury lodges with volcano views to intimate tented camps and eco-resorts, every stay is matched to your journey.</p>',
                'cover_image' => 'https://images.unsplash.com/photo-1534177616072-ef7dc120449d?auto=format&fit=crop&w=1800&q=82&fm=webp',
                'seo_title' => 'Luxury Safari Planning Guide | Shishi Footsteps',
                'seo_description' => 'A complete guide to planning a luxury safari in East Africa — when to go, where to stay and how to choose the right destinations.',
                'published' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Kenya Through Your Senses: A Safari Beyond the Photographs',
                'slug' => 'kenya-through-your-senses',
                'content' => '<p>Kenya is a country that stays with you. Not just in photographs, but in the way the air changes at dawn over the Maasai Mara, the scent of wild sage after rain, the weight of silence in the northern deserts.</p><h2>The Sound of the Wild</h2><p>Wake to the call of a fish eagle across a Rift Valley lake. Fall asleep to the distant cough of a leopard. Between them, the hum of a land still deeply alive.</p><h2>Moments That Stay</h2><p>A sundowner on a private conservancy, gin and tonic in hand, as the horizon turns amber. A Maasai guide pointing out tracks you would never have seen. The feeling of being small in a landscape that has been here for millions of years.</p><p>Kenya is not just a destination. It is a feeling you carry home.</p>',
                'cover_image' => 'https://images.unsplash.com/photo-1504432842672-1a79f78e4084?auto=format&fit=crop&w=1800&q=82&fm=webp',
                'seo_title' => 'Kenya Safari Experience | Shishi Footsteps',
                'seo_description' => 'A sensory journey through Kenya — from the Maasai Mara to the Rift Valley, captured through moments that stay with you.',
                'published' => true,
                'published_at' => now()->subDay(),
            ],
        ];

        foreach ($posts as $post) {
            DB::table('cms_pages')->insert(array_merge($post, [
                'type' => 'blog',
                'content' => $post['content'],
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    private function seedEvaluationDemo(): void
    {
        $quotations = DB::table('quotations')->where('status', 'confirmed')->get();
        foreach ($quotations as $quotation) {
            $items = DB::table('quotation_items')
                ->join('quotation_days', 'quotation_days.id', '=', 'quotation_items.quotation_day_id')
                ->where('quotation_days.quotation_id', $quotation->id)
                ->select('quotation_items.*', 'quotation_days.travel_date')
                ->get();

            foreach ($items as $item) {
                DB::table('evaluation_entries')->insert([
                    'quotation_id' => $quotation->id,
                    'quotation_day_id' => $item->quotation_day_id,
                    'quotation_item_id' => $item->id,
                    'item_type' => $item->item_type,
                    'title' => $item->title,
                    'supplier' => $item->source,
                    'service_date' => $item->travel_date,
                    'service_end_date' => $item->travel_date,
                    'system_rate' => $item->buy_total,
                    'quantity' => $item->quantity,
                    'status' => 'missing_invoice',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('proposal_evaluations')->insert([
                'quotation_id' => $quotation->id,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('supplier_invoices')->insert([
                'quotation_id' => $quotation->id,
                'uploaded_by' => User::where('role', 'reservations')->first()?->id ?? 1,
                'invoice_date' => now()->subDays(5)->toDateString(),
                'invoice_number' => 'INV-DEMO-' . $quotation->id . '-001',
                'company_name' => 'Demo Supplier Ltd',
                'amount' => 1500.00,
                'currency' => 'USD',
                'invoice_type' => 'normal',
                'invoice_category' => 'accommodation',
                'status' => 'uploaded',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('evaluation_audit_logs')->insert([
                'quotation_id' => $quotation->id,
                'user_id' => User::where('role', 'administrator')->first()?->id ?? 1,
                'action' => 'evaluation_created',
                'description' => "Evaluation auto-created for quotation #{$quotation->id}",
                'module' => 'evaluation',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function localizeLegacyItineraries(): void
    {
        foreach ([
            '10-Day Kenya Family Safari & Indian Ocean' => ['images/itineraries/kenya-family-cover.webp', 'images/itineraries/kenya-coast-day.webp', 9],
            '7-Day Tanzania Northern Circuit' => ['images/itineraries/tanzania-classic-cover.webp', 'images/itineraries/tanzania-crater-day.webp', 4],
        ] as $title => [$cover, $supporting, $switchDay]) {
            $itinerary = Itinerary::where('title', $title)->first();
            if (! $itinerary) {
                continue;
            }
            $itinerary->update(['cover_image' => $cover]);
            $itinerary->images()->delete();
            foreach ([$cover, $supporting] as $index => $path) {
                $itinerary->images()->create([
                    'path' => $path, 'caption' => $title.' editorial photography',
                    'alt_text' => $title, 'credit' => 'Shishi Footsteps original editorial image',
                    'sort_order' => $index + 1, 'is_cover' => $index === 0,
                ]);
            }
            foreach ($itinerary->days as $day) {
                $day->update(['primary_image' => $day->day_number >= $switchDay ? $supporting : $cover]);
            }
        }
    }
}
