<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_access_admin_dashboard(): void
    {
        $email = config('auth.superadmin_email', 'admin@hel.ink');
        $admin = User::factory()->create([
            'email' => $email,
        ]);
        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Admin Control Panel');
    }
}
