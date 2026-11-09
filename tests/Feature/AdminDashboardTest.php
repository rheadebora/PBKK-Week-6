<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_authenticated_user_without_permission_is_forbidden(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_admin_with_permission_sees_dynamic_dashboard_widgets(): void
    {
        Permission::create(['name' => 'view dashboard', 'guard_name' => 'web']);
        Permission::create(['name' => 'manage users', 'guard_name' => 'web']);
        Permission::create(['name' => 'manage roles', 'guard_name' => 'web']);

        $role = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $role->syncPermissions(['view dashboard', 'manage users', 'manage roles']);

        $admin = User::factory()->create([
            'name' => 'Rhea Debora Sianturi',
            'email' => 'rheadebora@gmail.com',
        ]);
        $admin->assignRole($role);

        User::factory()->count(4)->create();

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertOk();
        $response->assertSee('Dashboard Admin');
        $response->assertSee('Total Pengguna');
        $response->assertSee('Total Role');
        $response->assertSee('Total Permission');
        $response->assertSee('AdminLTE');
        $response->assertSee('5');
        $response->assertSee('1');
        $response->assertSee('3');
    }
}
