<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Campaign;
use App\Models\Solution;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProposerDashboardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        User::truncate();
        Campaign::truncate();
        Solution::truncate();
    }

    public function test_proposer_can_create_campaign(): void
    {
        $proposer = User::create([
            'name' => 'Proposer Test User',
            'email' => 'proposer_test_' . uniqid() . '@econexus.com',
            'password' => Hash::make('password'),
            'role' => 'Proposer',
            'is_validated' => true,
            'status' => 'active',
        ]);

        $this->actingAs($proposer);

        $campaignData = [
            'title' => 'Seaweed Wrapping Development',
            'category' => 'Packaging',
            'goal' => 5000,
            'start_date' => '2026-06-01',
            'end_date' => '2026-06-30',
            'description' => 'Developing seaweed wrapping materials.',
        ];

        $response = $this->post(route('proposer.campaigns.store'), $campaignData);

        // Check for redirection (usually redirects back or to dashboard)
        $response->assertRedirect(route('proposer.dashboard'));

        // Verify the database has the campaign
        $campaign = Campaign::where('title', 'Seaweed Wrapping Development')->first();
        $this->assertNotNull($campaign, 'Campaign was not created in the database');
        $this->assertEquals($proposer->id, $campaign->proposer_id);
    }

    public function test_proposer_can_create_solution(): void
    {
        $proposer = User::create([
            'name' => 'Proposer Test User 2',
            'email' => 'proposer_test_' . uniqid() . '@econexus.com',
            'password' => Hash::make('password'),
            'role' => 'Proposer',
            'is_validated' => true,
            'status' => 'active',
        ]);

        $this->actingAs($proposer);

        $solutionData = [
            'name' => 'Downtown Bottle Recycling Depot',
            'type' => 'recycling point',
            'address' => '456 Greenway Blvd',
            'latitude' => 45.67,
            'longitude' => -122.34,
            'description' => 'A automated bottle recycling facility.',
        ];

        $response = $this->post(route('proposer.solutions.store'), $solutionData);

        $response->assertRedirect(route('proposer.dashboard'));

        // Verify the database has the solution
        $solution = Solution::where('name', 'Downtown Bottle Recycling Depot')->first();
        $this->assertNotNull($solution, 'Solution was not created in the database');
        $this->assertEquals($proposer->id, $solution->proposer_id);
    }

    public function test_proposer_creating_solution_with_empty_coordinates(): void
    {
        $proposer = User::create([
            'name' => 'Proposer Test User 3',
            'email' => 'proposer_test_' . uniqid() . '@econexus.com',
            'password' => Hash::make('password'),
            'role' => 'Proposer',
            'is_validated' => true,
            'status' => 'active',
        ]);

        $this->actingAs($proposer);

        $solutionData = [
            'name' => 'East Side Recycling Hub',
            'type' => 'recycling point',
            'address' => '789 East Blvd',
            'latitude' => '',
            'longitude' => '',
            'description' => 'A recycling point with no coordinates.',
        ];

        $response = $this->post(route('proposer.solutions.store'), $solutionData);

        // If validation fails, it will redirect back (to '/' in test environment unless we set from)
        // Let's assert it redirects to dashboard if it passes, or check for errors if it fails
        $response->assertRedirect(route('proposer.dashboard'));
        $response->assertSessionHasNoErrors();
    }
}
