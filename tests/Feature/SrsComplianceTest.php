<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Campaign;
use App\Models\Solution;
use App\Models\Notification;
use App\Models\Validation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SrsComplianceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        User::truncate();
        Campaign::truncate();
        Solution::truncate();
        Notification::truncate();
        Validation::truncate();
    }

    public function test_new_creations_are_pending_by_default_and_require_moderation(): void
    {
        $proposer = User::create([
            'name' => 'Proposer Alice',
            'email' => 'alice@econexus.com',
            'password' => Hash::make('password'),
            'role' => 'Proposer',
            'is_validated' => true,
            'status' => 'active',
        ]);

        $this->actingAs($proposer);

        // 1. Create a Campaign
        $campaignData = [
            'title' => 'Seaweed Wrapping Development',
            'category' => 'packaging',
            'goal' => 5000,
            'start_date' => '2026-06-01',
            'end_date' => '2026-06-30',
            'description' => 'Developing seaweed wrapping materials.',
        ];
        $this->post(route('proposer.campaigns.store'), $campaignData);

        $campaign = Campaign::where('title', 'Seaweed Wrapping Development')->first();
        $this->assertNotNull($campaign);
        $this->assertEquals('pending', $campaign->status, 'Campaign must be pending by default');

        // 2. Create a Solution
        $solutionData = [
            'name' => 'Downtown Bottle Recycling Depot',
            'type' => 'recycling point',
            'address' => '456 Greenway Blvd',
            'latitude' => 45.67,
            'longitude' => -122.34,
            'description' => 'Automated bottle recycling facility.',
        ];
        $this->post(route('proposer.solutions.store'), $solutionData);

        $solution = Solution::where('name', 'Downtown Bottle Recycling Depot')->first();
        $this->assertNotNull($solution);
        $this->assertEquals('pending', $solution->status, 'Solution must be pending by default');
    }

    public function test_admin_can_moderate_campaigns_and_trigger_notifications(): void
    {
        $admin = User::create([
            'name' => 'Admin Bob',
            'email' => 'admin@econexus.com',
            'password' => Hash::make('password'),
            'role' => 'Admin',
            'is_validated' => true,
            'status' => 'active',
        ]);

        $proposer = User::create([
            'name' => 'Proposer Alice',
            'email' => 'alice@econexus.com',
            'password' => Hash::make('password'),
            'role' => 'Proposer',
            'is_validated' => true,
            'status' => 'active',
        ]);

        $campaign = Campaign::create([
            'title' => 'Unmoderated Campaign',
            'description' => 'Needs moderation.',
            'category' => 'packaging',
            'goal' => 1000,
            'start_date' => '2026-06-01',
            'end_date' => '2026-06-30',
            'proposer_id' => $proposer->id,
            'status' => 'pending',
        ]);

        $this->actingAs($admin);

        // Approve Campaign
        $response = $this->post(route('admin.campaigns.approve', $campaign));
        $response->assertRedirect();

        $campaign->refresh();
        $this->assertEquals('active', $campaign->status);

        // Verify in-app notification is created
        $notification = Notification::where('user_id', $proposer->id)->first();
        $this->assertNotNull($notification);
        $this->assertStringContainsString('approved', $notification->message);
    }

    public function test_admin_can_export_analytics_report_csv(): void
    {
        $admin = User::create([
            'name' => 'Admin Bob',
            'email' => 'admin@econexus.com',
            'password' => Hash::make('password'),
            'role' => 'Admin',
            'is_validated' => true,
            'status' => 'active',
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('admin.export'));
        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename=econexus_platform_report_' . date('Y-m-d') . '.csv');
    }

    public function test_proposer_can_mark_notifications_as_read(): void
    {
        $proposer = User::create([
            'name' => 'Proposer Alice',
            'email' => 'alice@econexus.com',
            'password' => Hash::make('password'),
            'role' => 'Proposer',
            'is_validated' => true,
            'status' => 'active',
        ]);

        $notification = Notification::create([
            'user_id' => $proposer->id,
            'message' => 'Test Notification',
            'type' => 'success',
            'is_read' => false,
        ]);

        $this->actingAs($proposer);

        $response = $this->post(route('notifications.read', $notification));
        $response->assertRedirect();

        $notification->refresh();
        $this->assertTrue($notification->is_read);
    }
}
