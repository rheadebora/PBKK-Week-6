<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class AdminUserSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin/users')->assertRedirect('/login');
    }

    public function test_authenticated_user_without_permission_is_forbidden(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin/users')
            ->assertForbidden();
    }

    public function test_admin_sees_all_users_without_a_filter(): void
    {
        $admin = $this->admin();

        $budi = User::factory()->create(['name' => 'Budi Santoso', 'email' => 'budi@example.com']);
        $siti = User::factory()->create(['name' => 'Siti Aminah', 'email' => 'siti@example.com']);

        $this->actingAs($admin)
            ->get('/admin/users')
            ->assertOk()
            ->assertSee('Kelola Pengguna')
            ->assertSee($admin->name)
            ->assertSee($budi->name)
            ->assertSee($siti->name)
            ->assertSee('3 data');
    }

    public function test_search_filters_users_by_name_using_when_condition(): void
    {
        $admin = $this->admin();

        $budi = User::factory()->create(['name' => 'Budi Santoso', 'email' => 'budi@example.com']);
        $siti = User::factory()->create(['name' => 'Siti Aminah', 'email' => 'siti@example.com']);

        $this->actingAs($admin)
            ->get('/admin/users?q=Budi')
            ->assertOk()
            ->assertSee($budi->name)
            ->assertDontSee($siti->name)
            ->assertSee('1 data');
    }

    public function test_search_filters_users_by_email_using_when_condition(): void
    {
        $admin = $this->admin();

        $budi = User::factory()->create(['name' => 'Budi Santoso', 'email' => 'budi@example.com']);
        $siti = User::factory()->create(['name' => 'Siti Aminah', 'email' => 'siti@example.com']);

        $this->actingAs($admin)
            ->get('/admin/users?q=siti@example.com')
            ->assertOk()
            ->assertSee($siti->name)
            ->assertDontSee($budi->name)
            ->assertSee('1 data');
    }

    public function test_role_filter_uses_a_second_when_condition(): void
    {
        $admin = $this->admin();

        $operator = Role::create(['name' => 'operator', 'guard_name' => 'web']);

        $budi = User::factory()->create(['name' => 'Budi Santoso']);
        $siti = User::factory()->create(['name' => 'Siti Aminah']);
        $siti->assignRole($operator);

        $this->actingAs($admin)
            ->get('/admin/users?role=operator')
            ->assertOk()
            ->assertSee($siti->name)
            ->assertDontSee($budi->name)
            ->assertSee('1 data');
    }

    public function test_filter_without_results_shows_empty_state(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get('/admin/users?q=tidak-ada-yang-cocok')
            ->assertOk()
            ->assertSee('Tidak ada pengguna yang cocok dengan filter.')
            ->assertSee('0 data');
    }

    private function admin(): User
    {
        Permission::create(['name' => 'view dashboard', 'guard_name' => 'web']);

        $role = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $role->givePermissionTo('view dashboard');

        $admin = User::factory()->create([
            'name' => 'Rhea Debora Sianturi',
            'email' => 'rheadebora@gmail.com',
        ]);
        $admin->assignRole($role);

        return $admin;
    }
}
