<?php
namespace Tests\Feature;
use Tests\TestCase;
use App\Models\User;
use App\Models\Organization;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConversationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_add_reply()
    {
        $org = Organization::create(['name' => 'Test', 'slug' => 'test']);
        $user = User::create([
            'name' => 'Agent', 'email' => 'a@test.com', 'password' => 'pw', 
            'organization_id' => $org->id, 'role' => 'agent'
        ]);
        $ticket = Ticket::create([
            'organization_id' => $org->id,
            'requester_id' => $user->id,
            'subject' => 'Help',
            'description' => 'Issue',
            'status' => 'open',
            'priority' => 'high'
        ]);

        $response = $this->actingAs($user)->postJson("/api/tickets/{$ticket->id}/conversations", [
            'body' => 'I am looking into this.',
            'type' => 'public_reply'
        ]);
        
        $response->assertStatus(201)->assertJsonPath('body', 'I am looking into this.');
    }
    
    public function test_can_list_replies()
    {
        $org = Organization::create(['name' => 'Test', 'slug' => 'test']);
        $user = User::create([
            'name' => 'Agent', 'email' => 'a@test.com', 'password' => 'pw', 
            'organization_id' => $org->id, 'role' => 'agent'
        ]);
        $ticket = Ticket::create([
            'organization_id' => $org->id,
            'requester_id' => $user->id,
            'subject' => 'Help',
            'description' => 'Issue',
            'status' => 'open',
            'priority' => 'high'
        ]);
        
        $this->actingAs($user)->postJson("/api/tickets/{$ticket->id}/conversations", [
            'body' => 'I am looking into this.',
            'type' => 'public_reply'
        ]);

        $response = $this->actingAs($user)->getJson("/api/tickets/{$ticket->id}/conversations");
        $response->assertStatus(200);
        $this->assertCount(1, $response->json());
    }
}
