<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // 1 Organization
        $orgId = DB::table('organizations')->insertGetId([
            'name' => 'Acme Corp',
            'slug' => 'acme',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 1 Admin
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin User',
            'email' => 'admin@acme.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'organization_id' => $orgId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 2 Agents
        $agent1Id = DB::table('users')->insertGetId([
            'name' => 'Agent One',
            'email' => 'agent1@acme.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'organization_id' => $orgId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $agent2Id = DB::table('users')->insertGetId([
            'name' => 'Agent Two',
            'email' => 'agent2@acme.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'organization_id' => $orgId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 2 Customers
        $customer1Id = DB::table('users')->insertGetId([
            'name' => 'Customer One',
            'email' => 'customer1@acme.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'organization_id' => $orgId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $customer2Id = DB::table('users')->insertGetId([
            'name' => 'Customer Two',
            'email' => 'customer2@acme.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'organization_id' => $orgId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // SLA Policies
        $slas = [
            ['priority' => 'low', 'response' => 480, 'resolution' => 2880],
            ['priority' => 'medium', 'response' => 240, 'resolution' => 1440],
            ['priority' => 'high', 'response' => 60, 'resolution' => 480],
            ['priority' => 'urgent', 'response' => 15, 'resolution' => 60],
        ];

        foreach ($slas as $sla) {
            DB::table('sla_policies')->insert([
                'organization_id' => $orgId,
                'priority' => $sla['priority'],
                'response_time_minutes' => $sla['response'],
                'resolution_time_minutes' => $sla['resolution'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 12 Tickets
        $statuses = ['open', 'pending', 'resolved', 'closed'];
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $agents = [$agent1Id, $agent2Id];
        $customers = [$customer1Id, $customer2Id];

        for ($i = 1; $i <= 12; $i++) {
            DB::table('tickets')->insert([
                'organization_id' => $orgId,
                'subject' => 'Sample Ticket ' . $i,
                'description' => 'This is a sample ticket description for ticket ' . $i,
                'status' => $statuses[array_rand($statuses)],
                'priority' => $priorities[array_rand($priorities)],
                'requester_id' => $customers[array_rand($customers)],
                'assignee_id' => $agents[array_rand($agents)],
                'tags' => json_encode(['demo', 'test']),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
