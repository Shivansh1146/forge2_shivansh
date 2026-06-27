<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organization;
use App\Models\Ticket;
use App\Models\SlaPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class HackathonVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_authentication_edge_cases()
    {
        $org = Organization::create(['name' => 'Org A', 'slug' => 'org-a']);
        $user = User::create([
            'name' => 'User', 'email' => 'test@test.com', 'password' => Hash::make('password'),
            'organization_id' => $org->id, 'role' => 'admin'
        ]);

        // Register with email that already exists
        $response = $this->postJson('/api/register', [
            'name' => 'User 2', 'email' => 'test@test.com', 'password' => 'password', 'organization_name' => 'Org B'
        ]);
        $response->assertStatus(422);

        // Login with incorrect password
        $response = $this->postJson('/api/login', [
            'email' => 'test@test.com', 'password' => 'wrong'
        ]);
        $response->assertStatus(422);

        // Access API without token
        $response = $this->getJson('/api/tickets');
        $response->assertStatus(401);
    }

    public function test_multi_tenancy_isolation()
    {
        $orgA = Organization::create(['name' => 'Org A', 'slug' => 'org-a']);
        $orgB = Organization::create(['name' => 'Org B', 'slug' => 'org-b']);

        $userA = User::create(['name' => 'A', 'email' => 'a@test.com', 'password' => 'pass', 'organization_id' => $orgA->id, 'role' => 'agent']);
        $userB = User::create(['name' => 'B', 'email' => 'b@test.com', 'password' => 'pass', 'organization_id' => $orgB->id, 'role' => 'agent']);

        $ticketB = Ticket::create(['organization_id' => $orgB->id, 'requester_id' => $userB->id, 'subject' => 'T2', 'description' => 'D2', 'priority' => 'low', 'status' => 'open']);

        // User from Org A cannot see Org B tickets
        $response = $this->actingAs($userA)->getJson('/api/tickets');
        $this->assertCount(0, $response->json('data'));

        // User from Org A cannot update Org B tickets
        $response = $this->actingAs($userA)->putJson('/api/tickets/' . $ticketB->id, ['status' => 'resolved']);
        $response->assertStatus(404);

        // Dashboard stats are org specific
        $response = $this->actingAs($userA)->getJson('/api/dashboard');
        $this->assertCount(0, $response->json('byStatus'));
    }

    public function test_role_testing()
    {
        $org = Organization::create(['name' => 'Org A', 'slug' => 'org-a']);
        $customer = User::create(['name' => 'C', 'email' => 'c@test.com', 'password' => 'pass', 'organization_id' => $org->id, 'role' => 'customer']);
        $agent = User::create(['name' => 'A', 'email' => 'a@test.com', 'password' => 'pass', 'organization_id' => $org->id, 'role' => 'agent']);
        
        $ticket = Ticket::create(['organization_id' => $org->id, 'requester_id' => $customer->id, 'subject' => 'T1', 'description' => 'D1', 'priority' => 'low', 'status' => 'open']);

        // Customer should NOT assign, delete, change priority
        $response = $this->actingAs($customer)->putJson('/api/tickets/' . $ticket->id, ['status' => 'resolved']);
        $response->assertStatus(403);
        $response = $this->actingAs($customer)->deleteJson('/api/tickets/' . $ticket->id);
        $response->assertStatus(403);

        // Agent should update status
        $response = $this->actingAs($agent)->putJson('/api/tickets/' . $ticket->id, ['status' => 'resolved']);
        $response->assertStatus(200);

        // Agent should NOT delete ticket
        $response = $this->actingAs($agent)->deleteJson('/api/tickets/' . $ticket->id);
        $response->assertStatus(403);
    }

    public function test_sla_breach_detection()
    {
        $org = Organization::create(['name' => 'Org A', 'slug' => 'org-a']);
        SlaPolicy::create(['organization_id' => $org->id, 'priority' => 'high', 'response_time_minutes' => 1, 'resolution_time_minutes' => 1]);
        $user = User::create(['name' => 'C', 'email' => 'c@test.com', 'password' => 'pass', 'organization_id' => $org->id, 'role' => 'agent']);
        
        $ticket = clone Ticket::create(['organization_id' => $org->id, 'requester_id' => $user->id, 'subject' => 'T1', 'description' => 'D1', 'priority' => 'high', 'status' => 'open']);
        
        // Wait 2 minutes by travelling in time
        Carbon::setTestNow(Carbon::now()->addMinutes(2));

        $response = $this->actingAs($user)->getJson('/api/dashboard');
        $this->assertEquals(1, $response->json('slaBreached'));

        $response = $this->actingAs($user)->getJson('/api/tickets?breached=true');
        $this->assertCount(1, $response->json('data'));
        $this->assertTrue($response->json('data.0.is_breached'));
    }
}
