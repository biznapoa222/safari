<?php

namespace Tests\Feature;

use App\Models\Itinerary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ItineraryWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->signInAsAdministrator();
    }

    public function test_detailed_itinerary_list_editor_and_preview_render(): void
    {
        $itinerary = Itinerary::query()->with('days')->orderBy('id')->firstOrFail();

        $this->get('/admin/itineraries')
            ->assertOk()
            ->assertSee($itinerary->title)
            ->assertSee('planned days');

        $this->get("/admin/itineraries/{$itinerary->id}/edit")
            ->assertOk()
            ->assertSee('Day-by-day program')
            ->assertSee($itinerary->days->first()->title);

        $this->get("/admin/itineraries/{$itinerary->id}")
            ->assertOk()
            ->assertSee('Your safari program')
            ->assertSee($itinerary->days->last()->title);
    }

    public function test_itinerary_can_be_created_with_cover_and_detailed_day_images(): void
    {
        Storage::fake('public');

        $response = $this->post('/admin/itineraries', [
            'title' => 'Test Image Safari',
            'countries' => 'Kenya',
            'summary' => 'A complete image upload workflow test.',
            'duration_days' => 2,
            'nights' => 1,
            'minimum_guests' => 2,
            'maximum_guests' => 6,
            'price_from' => 1800,
            'currency' => 'USD',
            'travel_style' => 'Private safari',
            'difficulty' => 'Easy',
            'status' => 'draft',
            'inclusions_text' => "Private guide\nPark fees",
            'exclusions_text' => "Flights\nTips",
            'cover_image_upload' => UploadedFile::fake()->image('cover.jpg', 1400, 800),
        ]);

        $response->assertSessionHasNoErrors();
        $itinerary = Itinerary::query()->where('title', 'Test Image Safari')->firstOrFail();
        $response->assertRedirect("/admin/itineraries/{$itinerary->id}/edit");
        Storage::disk('public')->assertExists($itinerary->cover_image);

        $this->post("/admin/itineraries/{$itinerary->id}/days", [
            'day_number' => 1,
            'title' => 'Arrival and briefing',
            'location' => 'Nairobi',
            'summary' => 'Meet the operations team.',
            'description' => 'Airport welcome and private transfer.',
            'activities_text' => "Meet and assist\nTrip briefing",
            'primary_image_upload' => UploadedFile::fake()->image('day.jpg', 1400, 800),
            'images' => [
                UploadedFile::fake()->image('gallery-one.jpg', 1000, 700),
                UploadedFile::fake()->image('gallery-two.jpg', 1000, 700),
            ],
            'caption' => 'Arrival in Nairobi',
        ])->assertSessionHasNoErrors();

        $day = $itinerary->days()->firstOrFail();
        Storage::disk('public')->assertExists($day->primary_image);
        $this->assertCount(2, $day->images);
        $this->assertSame(['Meet and assist', 'Trip briefing'], $day->activities);
    }

    public function test_itinerary_can_be_duplicated_and_downloaded_as_pdf(): void
    {
        $itinerary = Itinerary::query()->with('days')->orderBy('id')->firstOrFail();

        $this->post("/admin/itineraries/{$itinerary->id}/duplicate")
            ->assertRedirect();

        $copy = Itinerary::query()->where('title', $itinerary->title.' - Copy')->firstOrFail();
        $this->assertSame('draft', $copy->status);
        $this->assertSame($itinerary->days()->count(), $copy->days()->count());

        $response = $this->get("/admin/itineraries/{$itinerary->id}/pdf");
        $response->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }
}
