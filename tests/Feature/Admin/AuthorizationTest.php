<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_editor_cannot_access_users_or_settings(): void
    {
        $editor = $this->editor();

        $this->actingAs($editor)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($editor)->get(route('admin.settings.edit'))->assertForbidden();
    }

    public function test_editor_can_access_content_sections(): void
    {
        $editor = $this->editor();

        $routes = ['admin.pages.index', 'admin.media.index', 'admin.messages.index'];

        if (template()->supports('services')) {
            $routes[] = 'admin.services.index';
        }

        if (template()->supports('products')) {
            $routes[] = 'admin.products.index';
        }

        if (template()->supports('blog')) {
            $routes[] = 'admin.posts.index';
        }

        if (template()->supports('team')) {
            $routes[] = 'admin.team.index';
        }

        foreach ($routes as $route) {
            $this->actingAs($editor)->get(route($route))->assertOk();
        }
    }

    public function test_admin_can_manage_settings_but_not_users(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get(route('admin.settings.edit'))->assertOk();
        $this->actingAs($admin)->get(route('admin.users.index'))->assertForbidden();
    }

    public function test_super_admin_can_access_everything(): void
    {
        $this->actingAs($this->superAdmin())->get(route('admin.users.index'))->assertOk();
    }
}
