<?php
namespace Tests\Feature;
use Tests\TestCase;
use App\Models\User;
use App\Models\Organization;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $org;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = Organization::create(['name' => 'Test', 'slug' => 'test']);
        $this->user = User::create([
            'name' => 'Cust', 'email' => 'c@test.com', 'password' => 'pw', 
            'organization_id' => $this->org->id, 'role' => 'customer'
        ]);
    }

    public function test_can_create_ticket()
    {
        $response = $this->actingAs($this->user)->postJson('/api/tickets', [
            'subject' => 'Help',
            'description' => 'Not working',
            'priority' => 'high'
        ]);
        $response->assertStatus(201)->assertJsonPath('subject', 'Help');
    }

    public function test_can_list_tickets()
    {
        Ticket::create([
            'organization_id' => $this->org->id,
            'requester_id' => $this->user->id,
            'subject' => 'Help me',
            'description' => 'Issue',
            'status' => 'open',
            'priority' => 'low'
        ]);
        
        $response = $this->actingAs($this->user)->getJson('/api/tickets');
        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }

    public function test_can_update_ticket()
    {
        $ticket = Ticket::create([
            'organization_id' => $this->org->id,
            'requester_id' => $this->user->id,
            'subject' => 'Help me',
            'description' => 'Issue',
            'status' => 'open',
            'priority' => 'low'
        ]);
        
        $response = $this->actingAs($this->user)->putJson("/api/tickets/{$ticket->id}", [
            'status' => 'resolved'
        ]);
        $response->assertStatus(200)->assertJsonPath('status', 'resolved');
    }
}
