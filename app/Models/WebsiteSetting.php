<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $fillable = [
        'key',
        'hero_image',
        'hero_title',
        'hero_subtitle',
        'featured_destinations',
        'featured_safaris',
        'featured_activities',
        'show_published_accommodation',
        'seo_title',
        'seo_description',
        'open_graph_image',
        'destination_media',
    ];

    protected function casts(): array
    {
        return [
            'featured_destinations' => 'array',
            'featured_safaris' => 'array',
            'featured_activities' => 'array',
            'show_published_accommodation' => 'boolean',
            'destination_media' => 'array',
        ];
    }

    public static function home(): self
    {
        return static::firstOrCreate(
            ['key' => 'home'],
            [
                'hero_image' => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=2200&q=86&fm=webp',
                'hero_title' => 'Luxury Safaris Crafted Around You',
                'hero_subtitle' => 'Private, tailor-made safari journeys across Kenya, Tanzania, Uganda, South Africa, Namibia and Botswana.',
                'seo_title' => 'Shishi Footsteps | Luxury Tailor-Made African Safaris',
                'seo_description' => 'Plan a private luxury safari across East and Southern Africa with Shishi Footsteps.',
                'open_graph_image' => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1600&q=82&fm=webp',
            ]
        );
    }

    public function mediaFor(string $slug): array
    {
        $saved = $this->destination_media[$slug] ?? [];
        $defaults = static::destinationMediaDefaults()[$slug] ?? static::destinationMediaDefaults()['kenya'];

        return [
            'hero' => $saved['hero'] ?? $defaults['hero'],
            'gallery' => array_values(array_filter($saved['gallery'] ?? $defaults['gallery'])),
        ];
    }

    public static function destinationMediaDefaults(): array
    {
        $image = fn (string $id) => "https://images.unsplash.com/photo-{$id}?auto=format&fit=crop&w=1400&q=84&fm=webp";

        return [
            'kenya' => ['hero' => $image('1534177616072-ef7dc120449d'), 'gallery' => [$image('1516426122078-c23e76319801'), $image('1523805009345-7448845a9e53'), $image('1516026672322-bc52d61a55d5'), asset('images/itineraries/kenya-family-cover.webp'), asset('images/itineraries/kenya-coast-day.webp')]],
            'tanzania' => ['hero' => $image('1523805009345-7448845a9e53'), 'gallery' => [$image('1516426122078-c23e76319801'), asset('images/itineraries/tanzania-classic-cover.webp'), asset('images/itineraries/tanzania-crater-day.webp'), $image('1534177616072-ef7dc120449d'), $image('1516026672322-bc52d61a55d5')]],
            'uganda' => ['hero' => $image('1540573133985-87b6da6d54a9'), 'gallery' => [$image('1516026672322-bc52d61a55d5'), $image('1534177616072-ef7dc120449d'), $image('1523805009345-7448845a9e53'), $image('1516426122078-c23e76319801'), asset('images/itineraries/tanzania-classic-cover.webp')]],
            'rwanda' => ['hero' => $image('1517853782856-d7cc5de7a7fc'), 'gallery' => [$image('1540573133985-87b6da6d54a9'), $image('1516026672322-bc52d61a55d5'), $image('1534177616072-ef7dc120449d'), $image('1523805009345-7448845a9e53'), asset('images/itineraries/kenya-family-cover.webp')]],
            'south-africa' => ['hero' => $image('1484318571209-661cf29a69c3'), 'gallery' => [$image('1516483638261-f4dbaf036963'), $image('1534177616072-ef7dc120449d'), $image('1516026672322-bc52d61a55d5'), $image('1516426122078-c23e76319801'), asset('images/itineraries/botswana-luxury-cover.webp')]],
            'namibia' => ['hero' => $image('1516483638261-f4dbaf036963'), 'gallery' => [$image('1484318571209-661cf29a69c3'), $image('1523805009345-7448845a9e53'), $image('1534177616072-ef7dc120449d'), asset('images/itineraries/botswana-luxury-cover.webp'), asset('images/itineraries/botswana-chobe-day.webp')]],
            'botswana' => ['hero' => asset('images/itineraries/botswana-luxury-cover.webp'), 'gallery' => [asset('images/itineraries/botswana-chobe-day.webp'), $image('1516426122078-c23e76319801'), $image('1534177616072-ef7dc120449d'), $image('1523805009345-7448845a9e53'), $image('1516483638261-f4dbaf036963')]],
        ];
    }
}
