<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class OperationsWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->signInAsAdministrator();
    }

    public function test_sidebar_labels_and_specialized_pages_render(): void
    {
        $this->get('/admin')
            ->assertOk()
            ->assertSee('Fees & Fares')
            ->assertSee('Website CMS')
            ->assertDontSee('ui.nav.');

        $this->get('/admin/accommodations')->assertOk()->assertSee('Four Points by Sheraton');
        $this->get('/admin/activities')->assertOk()->assertSee('Mount Longonot Guided Hike');
        $this->get('/admin/quotations')->assertOk()->assertSee('Proposals');
        $this->get('/admin/leads')->assertOk()->assertSee('Leads & Enquiries');
        $this->get('/admin/flights')->assertOk()->assertSee('KQ 482')->assertDontSee('Savanna Africa');
    }

    public function test_hotel_room_and_rate_crud_calculates_markup(): void
    {
        $response = $this->post('/admin/accommodations', [
            'name' => 'Test Safari Lodge', 'country' => 'Kenya', 'location' => 'Nanyuki',
            'supplier_type' => 'preferred', 'luxury_level' => 'premium',
            'reservation_email' => 'book@test.test', 'currency' => 'USD',
            'default_markup_percent' => 25, 'status' => 'active', 'published' => 1,
        ]);
        $hotelId = DB::table('hotels')->where('name', 'Test Safari Lodge')->value('id');
        $response->assertRedirect("/admin/accommodations/{$hotelId}/edit");

        $this->post("/admin/accommodations/{$hotelId}/rooms", [
            'name' => 'Interconnecting Family Room', 'max_adults' => 4,
            'max_children' => 4, 'inventory' => 2, 'is_family_room' => 1, 'is_interconnecting' => 1,
        ])->assertSessionHasNoErrors();
        $roomId = DB::table('room_types')->where('hotel_id', $hotelId)->value('id');

        $this->post("/admin/accommodations/{$hotelId}/rooms/{$roomId}/rates", [
            'season_name' => 'Peak', 'valid_from' => '2026-07-01', 'valid_to' => '2026-09-30',
            'meal_plan' => 'Full Board', 'occupancy_basis' => 'per_room',
            'buy_rate' => 400, 'markup_percent' => 25, 'currency' => 'USD',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('hotel_rates', ['room_type_id' => $roomId, 'buy_rate' => 400, 'sell_rate' => 500]);
    }

    public function test_activity_markup_and_generic_module_crud_work(): void
    {
        $this->post('/admin/activities', [
            'name' => 'Private Forest Hike', 'country' => 'Kenya', 'region' => 'Aberdares',
            'location' => 'Aberdares', 'min_pax' => 2, 'currency' => 'USD',
            'duration_hours' => 5, 'description' => 'A private guided forest experience.',
        ])->assertSessionHasNoErrors();
        $activity = DB::table('activities')->where('name', 'Private Forest Hike')->first();
        $this->assertNotNull($activity);

        $this->post("/admin/activities/{$activity->id}/prices", [
            'type' => 'standard', 'season' => 'high', 'year' => 2026,
            'price' => 130, 'currency' => 'USD',
        ])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('activity_prices', ['activity_id' => $activity->id, 'price' => 130]);

        $this->post('/admin/records/park-fees', [
            'title' => 'Maasai Mara adult fee', 'reference' => 'FEE-01',
            'status' => 'active', 'effective_date' => '2026-07-01', 'amount' => 200,
        ])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('module_records', ['module_slug' => 'park-fees', 'amount' => 200]);
    }

    public function test_flight_booking_crud_calculates_total_fare(): void
    {
        $this->post('/admin/flights', [
            'passenger_name' => 'Flight Test Guest', 'passenger_type' => 'adult',
            'airline' => 'Kenya Airways', 'flight_number' => 'KQ 100',
            'flight_type' => 'international', 'cabin_class' => 'business',
            'origin_code' => 'NBO', 'destination_code' => 'LHR',
            'departure_at' => '2026-10-10 08:00:00', 'arrival_at' => '2026-10-10 15:00:00',
            'baggage_allowance' => '2 x 32kg', 'supplier' => 'Kenya Airways',
            'base_fare' => 1000, 'taxes' => 200, 'markup_percent' => 10,
            'currency' => 'USD', 'payment_status' => 'unpaid', 'booking_status' => 'requested',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('flight_bookings', [
            'passenger_name' => 'Flight Test Guest', 'selling_total' => 1320,
        ]);
    }

    public function test_website_request_can_be_assigned_and_converted_to_a_quotation(): void
    {
        $lead = DB::table('leads')->where('status', '!=', 'confirmed')->first();
        $consultant = DB::table('users')->where('is_active', true)->whereIn('role', ['sales', 'administrator'])->first();

        $this->post("/admin/leads/{$lead->id}/assign", [
            'assigned_consultant_id' => $consultant->id,
        ])->assertSessionHasNoErrors();

        $this->put("/admin/leads/{$lead->id}", [
            'name' => $lead->name, 'email' => $lead->email, 'phone' => $lead->phone,
            'country' => $lead->country, 'source' => $lead->source, 'status' => 'contacted',
            'estimated_value' => 12000, 'currency' => $lead->currency,
            'travel_date' => $lead->travel_date, 'travelers' => $lead->travelers,
            'destination' => $lead->destination, 'notes' => $lead->notes,
        ])->assertSessionHasNoErrors();

        $this->post("/admin/leads/{$lead->id}/convert")->assertRedirect();
        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'assigned_consultant_id' => $consultant->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_unrealistic_same_day_distance_is_rejected(): void
    {
        $quote = DB::table('quotations')->first();
        $day = DB::table('quotation_days')->where('quotation_id', $quote->id)->first();

        $this->put("/admin/quotations/{$quote->id}/days/{$day->id}", [
            'from_location' => 'Nairobi', 'to_location' => 'Bwindi',
            'description' => 'Attempt an impossible same-day drive.',
        ])->assertSessionHasErrors('route');
    }

    public function test_fully_booked_room_is_rejected(): void
    {
        $day = DB::table('quotation_days')->first();
        $quote = DB::table('quotations')->find($day->quotation_id);
        $room = DB::table('room_types')->first();
        DB::table('room_types')->where('id', $room->id)->update(['inventory' => 1]);
        $itemId = DB::table('quotation_items')->insertGetId([
            'quotation_day_id' => $day->id, 'item_type' => 'room', 'source_id' => $room->id,
            'title' => 'Presidential Suite', 'source' => 'Test Hotel', 'calculation_type' => 'per_room',
            'quantity' => 1, 'buy_unit_price' => 600, 'markup_percent' => 20,
            'sell_unit_price' => 720, 'buy_total' => 600, 'sell_total' => 720,
            'currency' => 'USD', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $payload = [
            'quotation_id' => $quote->id, 'quotation_item_id' => $itemId,
            'starts_at' => '2030-08-10 14:00:00', 'ends_at' => '2030-08-12 10:00:00',
            'quantity' => 1, 'supplier' => 'Test Hotel',
        ];

        $this->post('/admin/reservations', $payload)->assertSessionHasNoErrors();
        $this->post('/admin/reservations', $payload)->assertSessionHasErrors('availability');
    }

    public function test_public_enquiry_enters_new_sales_pipeline(): void
    {
        $this->post('/enquire', [
            'name' => 'New Safari Guest', 'email' => 'newguest@example.com',
            'destination' => 'Kenya', 'travel_date' => '2026-10-10',
            'travelers' => 4, 'message' => 'Golf, safari and beach.',
        ])->assertRedirect();

        $this->assertDatabaseHas('leads', [
            'email' => 'newguest@example.com', 'source' => 'website', 'status' => 'new',
        ]);
    }
}
