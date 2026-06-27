<?php
namespace Tests\Feature;
use Tests\TestCase;
use App\Models\User;
use App\Models\Organization;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_dashboard_stats()
    {
        $org = Organization::create(['name' => 'Test', 'slug' => 'test']);
        $user = User::create([
            'name' => 'Agent', 'email' => 'a@test.com', 'password' => 'pw', 
            'organization_id' => $org->id, 'role' => 'agent'
        ]);

        Ticket::create([
            'organization_id' => $org->id,
            'requester_id' => $user->id,
            'subject' => 'Help',
            'description' => 'Issue',
            'status' => 'open',
            'priority' => 'high'
        ]);

        $response = $this->actingAs($user)->getJson('/api/dashboard');
        $response->assertStatus(200)
                 ->assertJsonStructure(['byStatus', 'byPriority', 'recentTickets']);
    }
}
