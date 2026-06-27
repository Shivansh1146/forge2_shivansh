<?php
namespace Tests\Feature;
use Tests\TestCase;
use App\Models\User;
use App\Models\Organization;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MultiTenancyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_see_other_organization_tickets()
    {
        $org1 = Organization::create(['name' => 'Org 1', 'slug' => 'org-1']);
        $org2 = Organization::create(['name' => 'Org 2', 'slug' => 'org-2']);
        
        $user1 = User::create(['name' => 'User 1', 'email' => 'u1@test.com', 'password' => 'pw', 'organization_id' => $org1->id, 'role' => 'agent']);
        $user2 = User::create(['name' => 'User 2', 'email' => 'u2@test.com', 'password' => 'pw', 'organization_id' => $org2->id, 'role' => 'agent']);

        $ticket1 = Ticket::create(['organization_id' => $org1->id, 'requester_id' => $user1->id, 'subject' => 'T1', 'description' => 'D1', 'priority' => 'low']);
        $ticket2 = Ticket::create(['organization_id' => $org2->id, 'requester_id' => $user2->id, 'subject' => 'T2', 'description' => 'D2', 'priority' => 'low']);

        $response = $this->actingAs($user1)->getJson('/api/tickets');
        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($ticket1->id, $response->json('data.0.id'));
    }

    public function test_cross_tenant_access_returns_404()
    {
        $org1 = Organization::create(['name' => 'Org 1', 'slug' => 'org-1']);
        $org2 = Organization::create(['name' => 'Org 2', 'slug' => 'org-2']);
        
        $user1 = User::create(['name' => 'User 1', 'email' => 'u1@test.com', 'password' => 'pw', 'organization_id' => $org1->id, 'role' => 'agent']);
        $user2 = User::create(['name' => 'User 2', 'email' => 'u2@test.com', 'password' => 'pw', 'organization_id' => $org2->id, 'role' => 'agent']);

        $ticket2 = Ticket::create(['organization_id' => $org2->id, 'requester_id' => $user2->id, 'subject' => 'T2', 'description' => 'D2', 'priority' => 'low']);

        $response = $this->actingAs($user1)->getJson("/api/tickets/{$ticket2->id}");
        $response->assertStatus(404);
    }
}
