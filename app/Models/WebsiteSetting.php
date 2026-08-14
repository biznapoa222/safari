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

    /**
     * One unique photo per mega-menu tile (never reused inside the same country grid).
     * Order: safaris, discover, parks, accommodation, highlights, activities, wildlife, golf, journal, reviews.
     */
    public function menuTilesFor(string $slug): array
    {
        $u = fn (string $id) => "https://images.unsplash.com/photo-{$id}?auto=format&fit=crop&w=900&q=84&fm=webp";

        $tiles = [
            'kenya' => [
                $u('1516426122078-c23e76319801'),
                asset('images/itineraries/kenya-family-cover.webp'),
                $u('1534177616072-ef7dc120449d'),
                $u('1568084680786-a84f91d1153c'),
                asset('images/itineraries/kenya-coast-day.webp'),
                $u('1500530855697-b586d89ba3ee'),
                $u('1547471080-7cc2caa01a7e'),
                $u('1535131749006-b7f58c99034b'),
                $u('1489392191049-fc10c97e64b6'),
                $u('1469474968028-56623f02e42e'),
            ],
            'tanzania' => [
                asset('images/itineraries/tanzania-classic-cover.webp'),
                asset('images/itineraries/tanzania-crater-day.webp'),
                $u('1523805009345-7448845a9e53'),
                $u('1568084680786-a84f91d1153c'),
                $u('1547471080-7cc2caa01a7e'),
                $u('1500530855697-b586d89ba3ee'),
                $u('1516426122078-c23e76319801'),
                $u('1592919505780-303950717480'),
                $u('1493246507139-91e8fad9978e'),
                $u('1418065460487-3e41a6c84dc5'),
            ],
            'uganda' => [
                $u('1559592413-7cec4d0cae2b'),
                $u('1540573133985-87b6da6d54a9'),
                $u('1441974231531-c6227db76b6e'),
                $u('1568084680786-a84f91d1153c'),
                $u('1500534314209-a25ddb2bd429'),
                $u('1470071459604-3b5ec3a7fe05'),
                $u('1516026672322-bc52d61a55d5'),
                $u('1593111774240-d529f12cf4bb'),
                $u('1504432842672-1a79f78e4084'),
                $u('1546182990-dffeafbe841d'),
            ],
            'rwanda' => [
                $u('1559592413-7cec4d0cae2b'),
                $u('1469854523086-cc02fe5d8800'),
                $u('1441974231531-c6227db76b6e'),
                $u('1568084680786-a84f91d1153c'),
                $u('1500534314209-a25ddb2bd429'),
                $u('1470071459604-3b5ec3a7fe05'),
                $u('1540573133985-87b6da6d54a9'),
                $u('1593111774240-d529f12cf4bb'),
                $u('1493246507139-91e8fad9978e'),
                $u('1474044159687-1ee9f3a51722'),
            ],
            'south-africa' => [
                $u('1484318571209-661cf29a69c3'),
                $u('1516483638261-f4dbaf036963'),
                $u('1534177616072-ef7dc120449d'),
                $u('1568084680786-a84f91d1153c'),
                $u('1507525428034-b723cf961d3e'),
                $u('1470071459604-3b5ec3a7fe05'),
                $u('1516426122078-c23e76319801'),
                $u('1580060839134-75a5edca2e99'),
                $u('1469854523086-cc02fe5d8800'),
                $u('1504280390367-361c6d9f38f4'),
            ],
            'namibia' => [
                $u('1509316785289-025f5b846b35'),
                $u('1469854523086-cc02fe5d8800'),
                $u('1547471080-7cc2caa01a7e'),
                $u('1568084680786-a84f91d1153c'),
                $u('1470071459604-3b5ec3a7fe05'),
                $u('1500530855697-b586d89ba3ee'),
                $u('1534177616072-ef7dc120449d'),
                $u('1592919505780-303950717480'),
                $u('1523805009345-7448845a9e53'),
                $u('1549366021-9f761d450615'),
            ],
            'botswana' => [
                asset('images/itineraries/botswana-luxury-cover.webp'),
                asset('images/itineraries/botswana-chobe-day.webp'),
                $u('1523805009345-7448845a9e53'),
                $u('1568084680786-a84f91d1153c'),
                $u('1500534314209-a25ddb2bd429'),
                $u('1470071459604-3b5ec3a7fe05'),
                $u('1534177616072-ef7dc120449d'),
                $u('1592919505780-303950717480'),
                $u('1516426122078-c23e76319801'),
                $u('1551632811-561732d1e306'),
            ],
        ];

        return $tiles[$slug] ?? $tiles['kenya'];
    }

    public static function destinationMediaDefaults(): array
    {
        $image = fn (string $id) => "https://images.unsplash.com/photo-{$id}?auto=format&fit=crop&w=1400&q=84&fm=webp";

        return [
            // Kenya — Mara/wildlife + local coast/family covers
            'kenya' => [
                'hero' => $image('1534177616072-ef7dc120449d'),
                'gallery' => [
                    $image('1516426122078-c23e76319801'),
                    asset('images/itineraries/kenya-family-cover.webp'),
                    asset('images/itineraries/kenya-coast-day.webp'),
                    $image('1559564472-71484b1070bb'),
                    $image('1547471080-7cc2caa01a7e'),
                ],
            ],
            // Tanzania — Serengeti / crater + local covers
            'tanzania' => [
                'hero' => $image('1523805009345-7448845a9e53'),
                'gallery' => [
                    asset('images/itineraries/tanzania-classic-cover.webp'),
                    asset('images/itineraries/tanzania-crater-day.webp'),
                    $image('1516426122078-c23e76319801'),
                    $image('1547471080-7cc2caa01a7e'),
                    $image('1500530855697-b586d89ba3ee'),
                ],
            ],
            // Uganda — gorillas / Nile / forests (no Kenya/Tanzania covers)
            'uganda' => [
                'hero' => $image('1540573133985-87b6da6d54a9'),
                'gallery' => [
                    $image('1559592413-7cec4d0cae2b'),
                    $image('1540573133985-87b6da6d54a9'),
                    $image('1441974231531-c6227db76b6e'),
                    $image('1500534314209-a25ddb2bd429'),
                    $image('1470071459604-3b5ec3a7fe05'),
                ],
            ],
            // Rwanda — gorillas / highlands / forest
            'rwanda' => [
                'hero' => $image('1559592413-7cec4d0cae2b'),
                'gallery' => [
                    $image('1517853782856-d7cc5de7a7fc'),
                    $image('1559592413-7cec4d0cae2b'),
                    $image('1441974231531-c6227db76b6e'),
                    $image('1500534314209-a25ddb2bd429'),
                    $image('1470071459604-3b5ec3a7fe05'),
                ],
            ],
            // South Africa — Cape Town / coast / bush (no Kenya/Botswana mix)
            'south-africa' => [
                'hero' => $image('1484318571209-661cf29a69c3'),
                'gallery' => [
                    $image('1484318571209-661cf29a69c3'),
                    $image('1516483638261-f4dbaf036963'),
                    $image('1534177616072-ef7dc120449d'),
                    $image('1580060839134-75a5edca2e99'),
                    $image('1507525428034-b723cf961d3e'),
                ],
            ],
            // Namibia — dunes / desert (no Botswana covers)
            'namibia' => [
                'hero' => $image('1509316785289-025f5b846b35'),
                'gallery' => [
                    $image('1509316785289-025f5b846b35'),
                    $image('1469854523086-cc02fe5d8800'),
                    $image('1547471080-7cc2caa01a7e'),
                    $image('1470071459604-3b5ec3a7fe05'),
                    $image('1500530855697-b586d89ba3ee'),
                ],
            ],
            // Botswana — Delta / Chobe + local covers
            'botswana' => [
                'hero' => asset('images/itineraries/botswana-luxury-cover.webp'),
                'gallery' => [
                    asset('images/itineraries/botswana-chobe-day.webp'),
                    asset('images/itineraries/botswana-luxury-cover.webp'),
                    $image('1516426122078-c23e76319801'),
                    $image('1523805009345-7448845a9e53'),
                    $image('1534177616072-ef7dc120449d'),
                ],
            ],
        ];
    }
}
