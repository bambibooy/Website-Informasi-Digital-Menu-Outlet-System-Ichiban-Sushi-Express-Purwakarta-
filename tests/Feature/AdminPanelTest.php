<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\OperatingHour;
use App\Models\User;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Selamat Datang');
    }

    public function test_register_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Daftar Akun Baru');
    }

    public function test_customer_user_can_register(): void
    {
        $email = 'user_' . time() . '@test.com';
        $response = $this->post('/register', [
            'name' => 'Test Pelanggan',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertDatabaseHas('users', [
            'email' => $email,
            'role' => 'user',
        ]);
    }

    public function test_admin_user_can_authenticate_and_access_dashboard(): void
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin Test',
                'email' => 'admin_test@ichiban.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]);
        }

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard Restoran');
    }

    public function test_authenticated_user_can_access_homepage(): void
    {
        $user = User::where('role', 'user')->first() ?? User::create([
            'name' => 'User Biasa',
            'email' => 'biasa@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
        $response->assertSee('Ichiban Sushi');
    }

    public function test_admin_can_access_categories_page(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/admin/categories');
        $response->assertStatus(200);
        $response->assertSee('Kelola Kategori Menu');
    }

    public function test_admin_can_access_menu_items_page(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/admin/menu-items');
        $response->assertStatus(200);
        $response->assertSee('Kelola Menu Restoran');
    }

    public function test_admin_can_toggle_sold_status(): void
    {
        $admin = User::where('role', 'admin')->first();
        $item = MenuItem::first();
        $originalSold = $item->is_sold;

        $response = $this->actingAs($admin)->post("/admin/menu-items/{$item->id}/toggle-sold");
        $response->assertRedirect();

        $this->assertEquals(!$originalSold, (bool)$item->fresh()->is_sold);
    }

    public function test_admin_can_access_operating_hours_page(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/admin/operating-hours');
        $response->assertStatus(200);
        $response->assertSee('Kelola Jam Operasional');
    }
}
