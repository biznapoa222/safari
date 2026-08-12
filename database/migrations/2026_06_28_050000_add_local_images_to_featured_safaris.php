<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach ([
            '10-Day Kenya Family Safari & Indian Ocean' => ['images/itineraries/kenya-family-cover.webp', 'images/itineraries/kenya-coast-day.webp'],
            '7-Day Tanzania Northern Circuit Classic' => ['images/itineraries/tanzania-classic-cover.webp', 'images/itineraries/tanzania-crater-day.webp'],
            '5-Day Luxury Botswana Safari' => ['images/itineraries/botswana-luxury-cover.webp', 'images/itineraries/botswana-chobe-day.webp'],
        ] as $title => $images) {
            DB::table('itineraries_v2')->where('title', $title)->update(['images' => json_encode($images)]);
        }

        DB::table('website_settings')->where('key', 'home')->update([
            'hero_image' => 'images/itineraries/kenya-family-cover.webp',
            'open_graph_image' => 'images/itineraries/kenya-family-cover.webp',
        ]);
    }

    public function down(): void
    {
        DB::table('itineraries_v2')->whereIn('title', [
            '10-Day Kenya Family Safari & Indian Ocean',
            '7-Day Tanzania Northern Circuit Classic',
            '5-Day Luxury Botswana Safari',
        ])->update(['images' => json_encode([])]);
    }
};
