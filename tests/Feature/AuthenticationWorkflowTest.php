<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_pages_require_authentication_and_login_screen_is_branded(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/login')
            ->assertOk()
            ->assertSee('Every journey')
            ->assertSee('Shishi Footsteps');
    }

    public function test_requested_administrator_can_sign_in_and_sign_out(): void
    {
        $admin = User::query()->where('email', 'erp@biznapoa.com')->firstOrFail();
        $this->assertSame('administrator', $admin->role);
        $this->assertTrue(Hash::check('shishi2026', $admin->password));

        $this->post('/login', [
            'email' => 'erp@biznapoa.com',
            'password' => 'shishi2026',
        ])->assertRedirect('/admin');

        $this->assertAuthenticatedAs($admin);
        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_inactive_user_cannot_sign_in(): void
    {
        $user = User::query()->where('role', 'sales')->firstOrFail();
        $user->update(['is_active' => false]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_administrator_can_create_and_update_role_based_users(): void
    {
        $this->signInAsAdministrator();

        $this->get('/admin/users')->assertOk()->assertSee('Users & Roles');
        $this->post('/admin/users', [
            'name' => 'Amina Sales',
            'email' => 'amina.sales@example.com',
            'role' => 'sales',
            'department' => 'Sales',
            'phone' => '+254700000100',
            'password' => 'temporary2026',
            'is_active' => 1,
        ])->assertSessionHasNoErrors();

        $user = User::query()->where('email', 'amina.sales@example.com')->firstOrFail();
        $this->put("/admin/users/{$user->id}", [
            'name' => 'Amina Sales',
            'email' => 'amina.sales@example.com',
            'role' => 'reservations',
            'department' => 'Reservations',
            'phone' => '+254700000100',
            'is_active' => 1,
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'email' => 'amina.sales@example.com',
            'role' => 'reservations',
            'is_active' => true,
        ]);
    }

    public function test_non_administrator_cannot_manage_users(): void
    {
        $this->actingAs(User::query()->where('role', 'sales')->firstOrFail());
        $this->get('/admin/users')->assertForbidden();
    }
}
