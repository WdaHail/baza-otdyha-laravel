<?php
namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_is_accessible(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_booking_requires_login(): void
    {
        $this->get('/booking')->assertRedirect('/login');
    }

    public function test_regular_user_cannot_access_admin_panel(): void
    {
        $user = User::create(['name'=>'Тест', 'email'=>'test2@example.test', 'password'=>'secretPassword123', 'role'=>'user']);
        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_booking_overlaps_are_rejected(): void
    {
        $user = User::create(['name' => 'Тест', 'email' => 'test@example.test', 'password' => 'secretPassword123', 'role' => 'user']);
        $service = Service::create([
            'name' => 'Тестовый домик', 'slug' => 'test-home', 'category' => 'Дом',
            'short_description' => 'Демо', 'description' => 'Тестовая услуга',
            'price_per_hour' => 1000, 'capacity' => 4, 'is_active' => true, 'sort_order' => 1,
        ]);
        $day = now()->addDays(3)->startOfDay();
        Booking::create([
            'user_id' => $user->id, 'service_id' => $service->id,
            'start_at' => $day->copy()->setTime(12,0), 'end_at' => $day->copy()->setTime(14,0),
            'guest_count' => 2, 'total_price' => 2000, 'status' => 'confirmed',
        ]);
        $this->actingAs($user)->post('/booking', [
            'service_id' => $service->id, 'date' => $day->format('Y-m-d'),
            'time' => '13:00', 'hours' => 2, 'guest_count' => 2,
        ])->assertSessionHasErrors('time');
        $this->assertDatabaseCount('bookings', 1);
    }
}
