<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->applyImages(
            '10-Day Kenya Family Safari & Indian Ocean',
            'images/itineraries/kenya-family-cover.webp',
            'images/itineraries/kenya-coast-day.webp',
            fn (int $day) => $day >= 9,
            'A private family safari in Kenya',
            'A quiet Indian Ocean beach extension',
        );
        $this->applyImages(
            '7-Day Tanzania Northern Circuit Classic',
            'images/itineraries/tanzania-classic-cover.webp',
            'images/itineraries/tanzania-crater-day.webp',
            fn (int $day) => in_array($day, [4, 5], true),
            'Wildlife on Tanzania northern circuit',
            'Ngorongoro Crater landscape',
        );
        $this->applyImages(
            '5-Day Luxury Botswana Safari',
            'images/itineraries/botswana-luxury-cover.webp',
            'images/itineraries/botswana-chobe-day.webp',
            fn (int $day) => $day >= 3,
            'A luxury safari in the Okavango Delta',
            'Elephants crossing the Chobe River',
        );
    }

    public function down(): void
    {
        $paths = [
            'images/itineraries/kenya-family-cover.webp',
            'images/itineraries/kenya-coast-day.webp',
            'images/itineraries/tanzania-classic-cover.webp',
            'images/itineraries/tanzania-crater-day.webp',
            'images/itineraries/botswana-luxury-cover.webp',
            'images/itineraries/botswana-chobe-day.webp',
        ];

        DB::table('itineraries')->whereIn('cover_image', $paths)->update(['cover_image' => null]);
        DB::table('itinerary_days')->whereIn('primary_image', $paths)->update(['primary_image' => null]);
        DB::table('itinerary_images')->whereIn('path', $paths)->delete();
    }

    private function applyImages(
        string $title,
        string $cover,
        string $supporting,
        callable $useSupporting,
        string $coverAlt,
        string $supportingAlt,
    ): void {
        $itinerary = DB::table('itineraries')->where('title', $title)->first();
        if (! $itinerary) {
            return;
        }

        DB::table('itineraries')->where('id', $itinerary->id)->update(['cover_image' => $cover]);
        DB::table('itinerary_images')->where('itinerary_id', $itinerary->id)->update(['is_cover' => false]);
        DB::table('itinerary_images')->updateOrInsert(
            ['itinerary_id' => $itinerary->id, 'path' => $cover],
            [
                'itinerary_day_id' => null,
                'caption' => $coverAlt,
                'alt_text' => $coverAlt,
                'credit' => 'Shishi Footsteps original editorial image',
                'sort_order' => 1,
                'is_cover' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );
        DB::table('itinerary_images')->updateOrInsert(
            ['itinerary_id' => $itinerary->id, 'path' => $supporting],
            [
                'itinerary_day_id' => null,
                'caption' => $supportingAlt,
                'alt_text' => $supportingAlt,
                'credit' => 'Shishi Footsteps original editorial image',
                'sort_order' => 2,
                'is_cover' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );

        foreach (DB::table('itinerary_days')->where('itinerary_id', $itinerary->id)->get() as $day) {
            DB::table('itinerary_days')->where('id', $day->id)->update([
                'primary_image' => $useSupporting((int) $day->day_number) ? $supporting : $cover,
            ]);
        }
    }
};
