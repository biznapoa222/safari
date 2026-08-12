<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SafariFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_homepage_renders_seeded_safari_content(): void
    {
        $this->seed();

        $this->get('/')
            ->assertOk()
            ->assertSee('Shishi Footsteps')
            ->assertSee('10-Day Kenya Family Safari & Indian Ocean');
    }

    public function test_admin_dashboard_and_translation_manager_render(): void
    {
        $this->seed();
        $this->signInAsAdministrator();

        $this->get('/admin')
            ->assertOk()
            ->assertSee('Confirmed revenue')
            ->assertSee('Recent proposals');

        $this->get('/admin/translations')
            ->assertOk()
            ->assertSee('Translation Manager')
            ->assertSee('10-Day Kenya Family Safari & Indian Ocean');
    }

    public function test_locale_switcher_stores_a_supported_locale(): void
    {
        $this->get('/language/fr')
            ->assertRedirect()
            ->assertSessionHas('locale', 'fr');

        $this->withSession(['locale' => 'fr'])
            ->get('/')
            ->assertOk()
            ->assertSee('Planifier votre safari');
    }

    public function test_public_enquiry_is_validated_and_stored(): void
    {
        $this->post('/enquire', [
            'name' => 'Amina Kamau',
            'email' => 'amina@example.com',
            'destination' => 'Kenya',
            'travel_date' => now()->addMonths(3)->toDateString(),
            'travelers' => 3,
            'message' => 'A private family safari.',
        ])->assertRedirect();

        $this->assertDatabaseHas('leads', [
            'email' => 'amina@example.com',
            'destination' => 'Kenya',
            'status' => 'new',
        ]);
    }
}
