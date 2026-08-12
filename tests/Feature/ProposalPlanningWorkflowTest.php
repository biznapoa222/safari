<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WebsiteSetting;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProposalPlanningWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow('2026-07-03 09:00:00');
        $this->actingAs(User::factory()->create([
            'role' => 'administrator', 'department' => 'Administration', 'is_active' => true,
        ]));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_destination_images_are_editable_in_admin_and_render_individually(): void
    {
        $settings = WebsiteSetting::home();
        $this->put('/admin/cms/home-settings', [
            'hero_title' => $settings->hero_title,
            'hero_subtitle' => $settings->hero_subtitle,
            'destination_media' => [
                'kenya' => [
                    'hero' => 'https://images.example.com/kenya-hero.jpg',
                    'gallery' => [
                        'https://images.example.com/kenya-lion.jpg',
                        'https://images.example.com/kenya-elephant.jpg',
                        'https://images.example.com/kenya-giraffe.jpg',
                    ],
                ],
            ],
        ])->assertSessionHasNoErrors();

        $this->get('/destinations/kenya')
            ->assertOk()
            ->assertSee('https://images.example.com/kenya-hero.jpg', false)
            ->assertSee('https://images.example.com/kenya-lion.jpg', false)
            ->assertSee('https://images.example.com/kenya-elephant.jpg', false)
            ->assertSee('https://images.example.com/kenya-giraffe.jpg', false);
    }

    public function test_golf_page_is_golf_only_and_images_link_to_matching_actions(): void
    {
        $this->get('/golf')
            ->assertOk()
            ->assertSee('Rwanda Championship Golf Week')
            ->assertSee('Vipingo Ridge PGA Baobab Course')
            ->assertSee('golf-package-image-link', false)
            ->assertSee('course-image-link', false)
            ->assertSee('safari_type=Golf%20safari', false)
            ->assertDontSee('Gorilla Safari Adventure')
            ->assertDontSee('Water Sports');

        $this->get('/')
            ->assertOk()
            ->assertSee('home-golf-grid', false)
            ->assertSee('/golf#golf-courses', false)
            ->assertSee('/golf#golf-packages', false)
            ->assertSee('safari_type=Golf%20safari', false);
    }

    public function test_planning_board_renders_the_complete_ordered_workflow(): void
    {
        $quotation = $this->quotation(['status' => 'confirmed', 'start_date' => '2026-07-08']);
        DB::table('quotation_payments')->insert([
            'quotation_id' => $quotation, 'reference' => 'PAY-FULL', 'amount' => 5000,
            'currency' => 'USD', 'paid_at' => '2026-07-01', 'method' => 'Bank',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->get('/admin/proposal-planning?stage=confirmed&trip=upcoming')
            ->assertOk()
            ->assertSeeInOrder(['Planning', 'Pre-confirmed', 'Confirmed'])
            ->assertSeeInOrder(['Upcoming trips', 'Trips in operation', 'Operated trips', 'Evaluated trips'])
            ->assertSee('QT-WORKFLOW-1')
            ->assertSee('payment-row--paid', false)
            ->assertSee('All payments paid');
    }

    public function test_dates_and_completed_itinerary_automatically_advance_the_trip(): void
    {
        $quotation = $this->quotation(['status' => 'confirmed', 'start_date' => '2026-06-25', 'duration_days' => 4]);
        foreach (range(1, 4) as $day) {
            DB::table('quotation_days')->insert([
                'quotation_id' => $quotation, 'day_number' => $day,
                'travel_date' => Carbon::parse('2026-06-25')->addDays($day - 1)->toDateString(),
                'description' => 'Complete day '.$day, 'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        $this->get('/admin/proposal-planning?stage=confirmed&trip=operated')->assertOk()->assertSee('QT-WORKFLOW-1');
        $this->assertDatabaseHas('quotations', ['id' => $quotation, 'status' => 'completed']);
        $this->assertNotNull(DB::table('proposal_workflows')->where('quotation_id', $quotation)->value('itinerary_completed_at'));
    }

    public function test_manual_planning_stage_can_advance_to_the_next_step(): void
    {
        $quotation = $this->quotation(['status' => 'draft', 'start_date' => '2026-08-01']);
        $this->get('/admin/proposal-planning?stage=planning&step=in-planning')->assertOk()->assertSee('QT-WORKFLOW-1');
        $this->post("/admin/proposal-planning/{$quotation}/advance")->assertSessionHasNoErrors();
        $this->assertDatabaseHas('quotations', ['id' => $quotation, 'status' => 'active']);
    }

    public function test_operational_checks_notes_and_export_are_working(): void
    {
        $quotation = $this->quotation(['status' => 'confirmed', 'start_date' => '2026-07-08']);
        $this->get('/admin/proposal-planning?stage=confirmed&trip=upcoming')->assertOk();

        $this->post("/admin/proposal-planning/{$quotation}/toggle", ['field' => 'jeeps_planned_at'])->assertSessionHasNoErrors();
        $this->put("/admin/proposal-planning/{$quotation}/note", [
            'planning_note' => 'Driver briefing confirmed', 'whatsapp_status' => 'Sent',
        ])->assertSessionHasNoErrors();

        $workflow = DB::table('proposal_workflows')->where('quotation_id', $quotation)->first();
        $this->assertNotNull($workflow->jeeps_planned_at);
        $this->assertSame('Driver briefing confirmed', $workflow->planning_note);
        $this->get('/admin/proposal-planning/export?stage=confirmed&trip=upcoming')
            ->assertOk()->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_secure_client_link_matches_the_proposal_without_exposing_internal_costs(): void
    {
        $quotation = $this->quotation(['status' => 'accepted', 'start_date' => '2026-08-08']);
        DB::table('quotation_days')->insert([
            'quotation_id' => $quotation, 'day_number' => 1, 'travel_date' => '2026-08-08',
            'from_location' => 'Nairobi', 'to_location' => 'Naivasha',
            'description' => 'Private transfer and lake safari.', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->get('/admin/proposal-planning?stage=pre-confirmed')->assertOk();
        $token = DB::table('proposal_workflows')->where('quotation_id', $quotation)->value('client_token');

        $this->get('/proposal/'.$token)
            ->assertOk()
            ->assertSee('Information of Travel Request')
            ->assertSee('Workflow Guest')
            ->assertSee('Private transfer and lake safari.')
            ->assertDontSee('Supplier buy-in')
            ->assertDontSee('Office markup');
    }

    public function test_proposal_tabs_snapshot_and_file_changes_are_comparable(): void
    {
        Storage::fake('local');
        $quotation = $this->quotation(['status'=>'accepted']);
        $this->get("/admin/quotations/{$quotation}?tab=persons")->assertOk()->assertSee('Add person');
        $this->post("/admin/quotations/{$quotation}/travelers", ['salutation'=>'Mrs','first_name'=>'Amina','surname'=>'Kamau','date_of_birth'=>'1990-01-01'])->assertSessionHasNoErrors();
        $this->post("/admin/quotations/{$quotation}/snapshots")->assertSessionHasNoErrors();
        $this->post("/admin/quotations/{$quotation}/documents", ['category'=>'customer_briefing','document'=>UploadedFile::fake()->create('briefing.pdf',120,'application/pdf')])->assertSessionHasNoErrors();
        $this->post("/admin/quotations/{$quotation}/snapshots")->assertSessionHasNoErrors();
        $allSnapshots = DB::table('proposal_snapshots')->where('quotation_id',$quotation)->orderBy('id')->pluck('id')->all();
        $snapshots = [$allSnapshots[0],$allSnapshots[array_key_last($allSnapshots)]];

        $this->get("/admin/quotations/{$quotation}?tab=snapshots&snapshots[]={$snapshots[0]}&snapshots[]={$snapshots[1]}")
            ->assertOk()->assertSee('Changes between selected snapshots')->assertSee('Documents changed')->assertSee('briefing.pdf');
        $token = DB::table('proposal_workflows')->where('quotation_id',$quotation)->value('client_token');
        $this->get('/proposal/'.$token)->assertOk()->assertSee('briefing.pdf');
    }

    public function test_reservation_request_email_is_sent_and_audited(): void
    {
        Mail::fake();
        $quotation = $this->quotation(['status'=>'confirmed']);
        $reservation = DB::table('reservations')->insertGetId([
            'quotation_id'=>$quotation,'quotation_item_id'=>null,'reservation_type'=>'room','resource_id'=>null,
            'starts_at'=>'2026-07-08 14:00:00','ends_at'=>'2026-07-10 10:00:00','quantity'=>1,'supplier'=>'Test Lodge',
            'amount_due'=>500,'actual_cost'=>500,'paid_amount'=>0,'status'=>'pending','created_at'=>now(),'updated_at'=>now(),
        ]);
        $this->post("/admin/reservations/{$reservation}/email", [
            'recipient'=>'reservations@testlodge.example','subject'=>'Reservation request QT-WORKFLOW-1','message'=>'Please confirm this accommodation booking.',
        ])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('reservations',['id'=>$reservation,'status'=>'requested']);
        $this->assertDatabaseHas('reservation_emails',['reservation_id'=>$reservation,'recipient'=>'reservations@testlodge.example','status'=>'sent']);
        $this->post("/admin/reservations/{$reservation}/email", ['recipient'=>'reservations@testlodge.example','subject'=>'Duplicate','message'=>'Duplicate'])->assertSessionHasErrors('email');
        $this->assertSame(1,DB::table('reservation_emails')->where('reservation_id',$reservation)->where('status','sent')->count());
    }

    public function test_batch_reservation_mail_builds_all_services_and_never_resends(): void
    {
        Mail::fake(); $quotation=$this->quotation(['status'=>'accepted']);
        $day=DB::table('quotation_days')->insertGetId(['quotation_id'=>$quotation,'day_number'=>1,'travel_date'=>'2026-07-08','description'=>'Arrival day','created_at'=>now(),'updated_at'=>now()]);
        $hotel=DB::table('hotels')->insertGetId(['name'=>'Mail Test Lodge','country'=>'Kenya','location'=>'Nairobi','reservation_email'=>'bookings@mailtest.example','currency'=>'USD','created_at'=>now(),'updated_at'=>now()]);
        $room=DB::table('room_types')->insertGetId(['hotel_id'=>$hotel,'name'=>'Double Room','inventory'=>5,'active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        $supplier=DB::table('suppliers')->insertGetId(['type'=>'activity','name'=>'Mail Test Adventures','country'=>'Kenya','email'=>'reserve@adventures.example','is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        foreach ([['room',$room,'Mail Test Lodge','Double room'],['activity',null,'Mail Test Adventures','City tour']] as [$type,$sourceId,$source,$title]) DB::table('quotation_items')->insert(['quotation_day_id'=>$day,'item_type'=>$type,'source_id'=>$sourceId,'title'=>$title,'source'=>$source,'calculation_type'=>'per_item','quantity'=>2,'buy_unit_price'=>100,'markup_percent'=>20,'sell_unit_price'=>120,'buy_total'=>200,'sell_total'=>240,'currency'=>'USD','created_at'=>now(),'updated_at'=>now()]);

        $this->post("/admin/quotations/{$quotation}/reservation-mails")->assertSessionHasNoErrors();
        $this->assertSame(2,DB::table('reservations')->where('quotation_id',$quotation)->count());
        $this->assertSame(2,DB::table('reservation_emails')->where('status','sent')->count());
        $this->post("/admin/quotations/{$quotation}/reservation-mails")->assertSessionHasNoErrors();
        $this->assertSame(2,DB::table('reservation_emails')->where('status','sent')->count());
    }

    public function test_travel_request_information_is_editable_and_snapshotted(): void
    {
        $quotation=$this->quotation(['status'=>'accepted']);
        $this->get("/admin/quotations/{$quotation}")->assertOk()->assertSee('Information of Travel Request');
        $this->put("/admin/quotations/{$quotation}/travel-information",['customer_message'=>'Family safari with dietary needs.','arrival_time'=>'22:05','arrival_location'=>'JKIA','arrival_flight'=>'KQ101','departure_time'=>'21:35','departure_location'=>'JKIA','departure_flight'=>'KQ102','dietary_requests'=>'Gluten free','announcements'=>'Birthday','customer_notes'=>'Window seats'])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('proposal_workflows',['quotation_id'=>$quotation,'arrival_flight'=>'KQ101','dietary_requests'=>'Gluten free']);
        $this->assertDatabaseHas('proposal_snapshots',['quotation_id'=>$quotation,'label'=>'Automatic · travel request information updated']);
    }

    public function test_every_proposal_mini_tab_renders(): void
    {
        $quotation = $this->quotation(['status'=>'confirmed']);
        foreach (['settings','persons','program','supplements','surcharges','discounts','overview','pdfs','snapshots','reservations','evaluation','predeparture','movements','deadlines'] as $tab) {
            $this->get("/admin/quotations/{$quotation}?tab={$tab}")->assertOk();
        }
    }

    public function test_activity_catalogue_switches_both_interface_and_entered_translation(): void
    {
        $activity=DB::table('activities')->insertGetId(['name'=>'Balloon Safari','slug'=>'balloon-safari-test','country'=>'Kenya','location'=>'Maasai Mara','currency'=>'USD','activity_status'=>'active','created_at'=>now(),'updated_at'=>now()]);
        DB::table('activity_translations')->insert(['activity_id'=>$activity,'locale'=>'es','title'=>'Safari en globo','description'=>'Vuelo al amanecer','location'=>'Masái Mara','region'=>'Kenia']);
        $this->withSession(['locale'=>'es'])->get('/admin/activities')->assertOk()->assertSee('Catálogo de experiencias')->assertSee('Safari en globo')->assertSee('Vuelo al amanecer')->assertSee('Ubicación');
    }

    public function test_reference_frontend_country_pages_and_live_chat_work(): void
    {
        $this->get('/')->assertOk()->assertSee('Travel Africa')->assertSee('Kenya')->assertSee('Tanzania')->assertSee('Golf')->assertSee('Plan your trip')->assertSee('trip-planner-dialog', false);
        $this->get('/destinations/kenya')->assertOk()->assertSee('KENYA TOURS')->assertSee('Kenya safari ideas');

        $start=$this->postJson('/chat/start',['name'=>'Website Guest','email'=>'guest@example.test','message'=>'Can you plan Kenya and golf?'])->assertOk()->json();
        $this->postJson('/chat/'.$start['token'],['message'=>'We travel in August.'])->assertOk();
        $conversation=DB::table('chat_conversations')->where('token',$start['token'])->value('id');
        $this->post('/admin/chat/'.$conversation.'/reply',['message'=>'Absolutely—we can combine both.'])->assertSessionHasNoErrors();
        $this->getJson('/chat/'.$start['token'])->assertOk()->assertJsonFragment(['body'=>'Absolutely—we can combine both.','sender'=>'admin']);
        $this->get('/admin/chat')->assertOk()->assertSee('Website Guest')->assertSee('Can you plan Kenya and golf?');
    }

    private function quotation(array $overrides = []): int
    {
        $client = DB::table('clients')->insertGetId([
            'name' => 'Workflow Guest', 'email' => uniqid('guest').'@example.test', 'country' => 'Kenya',
            'preferred_language' => 'en', 'status' => 'active', 'created_at' => now(), 'updated_at' => now(),
        ]);

        return DB::table('quotations')->insertGetId(array_merge([
            'client_id' => $client, 'reference' => 'QT-WORKFLOW-1', 'title' => 'Kenya Complete Safari',
            'start_date' => '2026-07-08', 'duration_days' => 7, 'guest_count' => 2,
            'start_location' => 'Nairobi', 'currency' => 'USD', 'office_markup_percent' => 20,
            'misc_markup_percent' => 5, 'exchange_rate' => 1, 'buy_total' => 4000,
            'sell_total' => 5000, 'margin_total' => 1000, 'status' => 'confirmed', 'frozen' => false,
            'created_at' => now(), 'updated_at' => now(),
        ], $overrides));
    }
}
