<?php

namespace Tests\Feature\Admin;

use App\Enums\UserRole;
use App\Models\ContactMessage;
use App\Models\User;
use App\Services\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagesSettingsUsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_viewing_message_marks_it_read_and_bulk_actions_work(): void
    {
        $messages = ContactMessage::factory()->count(3)->create();

        $this->actingAs($this->editor())->get(route('admin.messages.show', $messages[0]))->assertOk()->assertSee($messages[0]->name);
        $this->assertTrue($messages[0]->fresh()->is_read);

        $this->actingAs($this->editor())->post(route('admin.messages.bulk'), ['action' => 'delete', 'ids' => [$messages[1]->id, $messages[2]->id]])->assertRedirect();
        $this->assertSame(1, ContactMessage::query()->count());
    }

    public function test_settings_can_be_saved_and_are_reflected_on_site(): void
    {
        $this->actingAs($this->admin())->put(route('admin.settings.update'), [
            'tab' => 'general',
            'site_name' => 'Yeni Firma A.Ş.',
            'primary_color' => '#ff0000',
            'nav_show_blog' => 0,
        ])->assertRedirect();

        $this->assertSame('Yeni Firma A.Ş.', app(Settings::class)->get('site_name'));
        $this->assertSame('0', app(Settings::class)->get('nav_show_blog'));
        $this->get(route('home'))->assertOk()->assertSee('Yeni Firma A.Ş.')->assertSee('#ff0000', false);
    }

    public function test_settings_validation(): void
    {
        $this->actingAs($this->admin())->put(route('admin.settings.update'), ['tab' => 'general', 'site_name' => '', 'primary_color' => 'red'])
            ->assertSessionHasErrors(['site_name', 'primary_color']);

        $this->actingAs($this->admin())->put(route('admin.settings.update'), ['tab' => 'contact', 'map_embed' => 'https://evil.com/x'])
            ->assertSessionHasErrors(['map_embed']);
    }

    public function test_super_admin_can_manage_users(): void
    {
        $super = $this->superAdmin();

        $this->actingAs($super)->post(route('admin.users.store'), [
            'name' => 'Editör Ayşe',
            'email' => 'ayse@example.com',
            'password' => 'GucluSifre123',
            'password_confirmation' => 'GucluSifre123',
            'role' => 'editor',
            'is_active' => 1,
        ])->assertRedirect(route('admin.users.index'));

        $user = User::query()->where('email', 'ayse@example.com')->firstOrFail();
        $this->assertSame(UserRole::Editor, $user->role);
        $this->assertNotSame('GucluSifre123', $user->password);
        $this->assertTrue(password_verify('GucluSifre123', $user->password));

        $this->actingAs($super)->put(route('admin.users.update', $user), ['name' => 'Ayşe', 'email' => $user->email, 'role' => 'admin', 'is_active' => 0])->assertRedirect();
        $this->assertSame(UserRole::Admin, $user->fresh()->role);
        $this->assertFalse($user->fresh()->is_active);

        $this->actingAs($super)->delete(route('admin.users.destroy', $user));
        $this->assertModelMissing($user);
    }

    public function test_user_cannot_delete_self_or_last_super_admin(): void
    {
        $super = $this->superAdmin();

        $this->actingAs($super)->delete(route('admin.users.destroy', $super));
        $this->assertModelExists($super);
    }

    public function test_user_cannot_change_own_role(): void
    {
        $super = $this->superAdmin();

        $this->actingAs($super)->put(route('admin.users.update', $super), ['name' => $super->name, 'email' => $super->email, 'role' => 'editor', 'is_active' => 0]);

        $this->assertSame(UserRole::SuperAdmin, $super->fresh()->role);
        $this->assertTrue($super->fresh()->is_active);
    }

    public function test_profile_password_change_requires_current_password(): void
    {
        $user = $this->editor();

        $this->actingAs($user)->put(route('admin.profile.password'), ['current_password' => 'wrong', 'password' => 'YeniSifre123', 'password_confirmation' => 'YeniSifre123'])
            ->assertSessionHasErrors('current_password');

        $this->actingAs($user)->put(route('admin.profile.password'), ['current_password' => 'password', 'password' => 'YeniSifre123', 'password_confirmation' => 'YeniSifre123'])
            ->assertSessionHasNoErrors();

        $this->assertTrue(password_verify('YeniSifre123', $user->fresh()->password));
    }
}
