<?php
namespace Tests\Feature;
use Tests\TestCase;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_register()
    {
        $org = Organization::create(['name' => 'Test Org', 'slug' => 'test-org']);
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'password' => 'password123',
            'organization_name' => 'Acme Corp',
            'role' => 'agent'
        ]);
        $response->assertStatus(201)->assertJsonStructure(['user', 'token']);
    }

    public function test_can_login()
    {
        $org = Organization::create(['name' => 'Test Org', 'slug' => 'test-org']);
        $user = User::create([
            'name' => 'Jane', 'email' => 'jane@test.com', 
            'password' => bcrypt('password123'), 'organization_id' => $org->id, 'role' => 'customer'
        ]);
        $response = $this->postJson('/api/login', [
            'email' => 'jane@test.com',
            'password' => 'password123'
        ]);
        $response->assertStatus(200)->assertJsonStructure(['user', 'token']);
    }

    public function test_can_get_me()
    {
        $org = Organization::create(['name' => 'Test Org', 'slug' => 'test-org']);
        $user = User::create([
            'name' => 'Jane', 'email' => 'jane@test.com', 
            'password' => bcrypt('password123'), 'organization_id' => $org->id, 'role' => 'customer'
        ]);
        $response = $this->actingAs($user)->getJson('/api/me');
        $response->assertStatus(200)->assertJsonPath('email', 'jane@test.com');
    }

    public function test_can_logout()
    {
        $org = Organization::create(['name' => 'Test Org', 'slug' => 'test-org']);
        $user = User::create([
            'name' => 'Jane', 'email' => 'jane@test.com', 
            'password' => bcrypt('password123'), 'organization_id' => $org->id, 'role' => 'customer'
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->postJson('/api/logout');
        $response->assertStatus(200);
    }
}
