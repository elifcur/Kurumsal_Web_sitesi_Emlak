<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_accessible(): void
    {
        $this->get(route('admin.login'))->assertOk()->assertSee('Giriş Yap');
    }

    public function test_guests_are_redirected_from_admin(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = $this->superAdmin();

        $this->post(route('admin.login.store'), ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->fresh()->last_login_at);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = $this->superAdmin();

        $this->from(route('admin.login'))
            ->post(route('admin.login.store'), ['email' => $user->email, 'password' => 'wrong'])
            ->assertRedirect(route('admin.login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = User::factory()->inactive()->create();

        $this->post(route('admin.login.store'), ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_inactive_user_session_is_terminated(): void
    {
        $user = $this->superAdmin();
        $this->actingAs($user);
        $user->update(['is_active' => false]);

        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_login_is_rate_limited(): void
    {
        $user = $this->superAdmin();

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('admin.login.store'), ['email' => $user->email, 'password' => 'wrong']);
        }

        $this->post(route('admin.login.store'), ['email' => $user->email, 'password' => 'password'])
            ->assertTooManyRequests();

        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $this->actingAs($this->superAdmin())->post(route('admin.logout'))->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_dashboard_renders_for_admin(): void
    {
        $this->actingAs($this->superAdmin())->get(route('admin.dashboard'))->assertOk()->assertSee('Dashboard');
    }
}
