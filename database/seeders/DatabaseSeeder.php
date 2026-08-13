<?php

namespace Database\Seeders;

use App\Models\ActivityCategory;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['code' => 'KEN', 'name' => 'Kenya', 'regions' => ['Maasai Mara', 'Nairobi', 'Amboseli', 'Samburu', 'Tsavo East', 'Tsavo West', 'Naivasha', 'Diani', 'Laikipia', 'Meru', 'Aberdare', 'Lake Nakuru']],
            ['code' => 'TZA', 'name' => 'Tanzania', 'regions' => ['Serengeti', 'Ngorongoro', 'Tarangire', 'Manyara', 'Arusha', 'Zanzibar', 'Selous', 'Ruaha', 'Mahale', 'Katavi']],
            ['code' => 'UGA', 'name' => 'Uganda', 'regions' => ['Bwindi', 'Kibale', 'Queen Elizabeth', 'Murchison Falls', 'Kampala', 'Jinja']],
            ['code' => 'ZAF', 'name' => 'South Africa', 'regions' => ['Kruger', 'Cape Town', 'Johannesburg', 'Durban', 'Garden Route', 'Sabi Sand']],
            ['code' => 'NAM', 'name' => 'Namibia', 'regions' => ['Etosha', 'Swakopmund', 'Windhoek', 'Sossusvlei', 'Damaraland']],
            ['code' => 'BWA', 'name' => 'Botswana', 'regions' => ['Okavango Delta', 'Chobe', 'Moremi', 'Savuti', 'Kasane']],
        ];

        foreach ($countries as $c) {
            $country = Country::create(['code' => $c['code'], 'name' => $c['name'], 'slug' => Str::slug($c['name'])]);
            foreach ($c['regions'] as $r) {
                Region::create(['country_id' => $country->id, 'name' => $r, 'slug' => Str::slug($r)]);
            }
        }

        $categories = [
            'Safari', 'Excursion', 'National Park', 'Cultural Tour', 'Adventure',
            'Walking Safari', 'Marine Activity', 'Helicopter Tour', 'Balloon Safari', 'Conservation Experience',
        ];

        foreach ($categories as $cat) {
            ActivityCategory::create(['name' => $cat, 'slug' => Str::slug($cat)]);
        }

        // Create default admin user (password aligned with UserAccessSeeder / auth tests)
        \App\Models\User::firstOrCreate(
            ['email' => 'erp@biznapoa.com'],
            ['name' => 'Super Admin', 'password' => bcrypt('shishi2026'), 'role' => 'administrator', 'is_active' => true]
        );

        $this->call(UserAccessSeeder::class);
        $this->call(V2DemoSeeder::class);
        $this->call(KenyaWheelsSafariTemplateSeeder::class);
        $this->call(WordPressContentSeeder::class);
        $this->call(RwandaContentSeeder::class);
        $this->call(DestinationCountriesSeeder::class);
    }
}
