<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Campaign;
use App\Models\Solution;
use App\Models\Validation;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $pendingUsers = User::where('status', 'pending')->get();
        
        $totalCampaigns = Campaign::count();
        $activeCampaigns = Campaign::where('status', 'active')->count();
        $pendingCampaigns = Campaign::where('status', 'pending')->with('proposer')->get();
        
        $totalSolutions = Solution::count();
        $activeSolutions = Solution::where('status', 'active')->count();
        $pendingSolutions = Solution::where('status', 'pending')->with('proposer')->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'pendingUsers', 'activeCampaigns', 'pendingCampaigns',
            'totalCampaigns', 'totalSolutions', 'activeSolutions', 'pendingSolutions'
        ));
    }

    public function approveUser(User $user)
    {
        $user->update([
            'status' => 'active',
            'is_validated' => true
        ]);

        Validation::create([
            'user_id' => $user->id,
            'admin_id' => Auth::id(),
            'status' => 'approved',
            'notes' => 'User account approved by administrator.'
        ]);

        Notification::create([
            'user_id' => $user->id,
            'message' => 'Congratulations! Your proposer account has been approved by the administrator. You can now publish campaigns and resources.',
            'type' => 'success',
            'is_read' => false
        ]);

        return redirect()->back()->with('success', 'User approved successfully.');
    }

    public function rejectUser(Request $request, User $user)
    {
        $notes = $request->input('notes', 'No reason provided.');

        $user->update([
            'status' => 'rejected',
            'is_validated' => false
        ]);

        Validation::create([
            'user_id' => $user->id,
            'admin_id' => Auth::id(),
            'status' => 'rejected',
            'notes' => $notes
        ]);

        Notification::create([
            'user_id' => $user->id,
            'message' => 'Your account registration was rejected by the administrator. Reason: ' . $notes,
            'type' => 'alert',
            'is_read' => false
        ]);

        return redirect()->back()->with('success', 'User rejected successfully.');
    }

    public function approveCampaign(Campaign $campaign)
    {
        $campaign->update([
            'status' => 'active'
        ]);

        Notification::create([
            'user_id' => $campaign->proposer_id,
            'message' => 'Your campaign "' . $campaign->title . '" has been approved and is now live on the portal.',
            'type' => 'success',
            'is_read' => false
        ]);

        return redirect()->back()->with('success', 'Campaign approved successfully.');
    }

    public function rejectCampaign(Request $request, Campaign $campaign)
    {
        $reason = $request->input('reject_reason', 'No reason provided.');

        $campaign->update([
            'status' => 'rejected',
            'reject_reason' => $reason
        ]);

        Notification::create([
            'user_id' => $campaign->proposer_id,
            'message' => 'Your campaign "' . $campaign->title . '" was rejected by the moderator. Reason: ' . $reason,
            'type' => 'alert',
            'is_read' => false
        ]);

        return redirect()->back()->with('success', 'Campaign rejected successfully.');
    }

    public function approveSolution(Solution $solution)
    {
        $solution->update([
            'status' => 'active'
        ]);

        Notification::create([
            'user_id' => $solution->proposer_id,
            'message' => 'Your resource/recycling point "' . $solution->name . '" has been approved and is now live on the map.',
            'type' => 'success',
            'is_read' => false
        ]);

        return redirect()->back()->with('success', 'Resource approved successfully.');
    }

    public function rejectSolution(Request $request, Solution $solution)
    {
        $reason = $request->input('reject_reason', 'No reason provided.');

        $solution->update([
            'status' => 'rejected',
            'reject_reason' => $reason
        ]);

        Notification::create([
            'user_id' => $solution->proposer_id,
            'message' => 'Your resource/recycling point "' . $solution->name . '" was rejected by the moderator. Reason: ' . $reason,
            'type' => 'alert',
            'is_read' => false
        ]);

        return redirect()->back()->with('success', 'Resource rejected successfully.');
    }

    public function exportReport()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=econexus_platform_report_" . date('Y-m-d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Header Info
            fputcsv($file, ['EcoNexus Plastic Waste Reduction Portal - Platform Analytics Report']);
            fputcsv($file, ['Generated At', date('Y-m-d H:i:s')]);
            fputcsv($file, []); // Blank line
            
            // Metrics Table
            fputcsv($file, ['Platform Metric', 'Value']);
            fputcsv($file, ['Total Registered Users', User::count()]);
            fputcsv($file, ['Pending User Registrations', User::where('status', 'pending')->count()]);
            fputcsv($file, ['Active Proposers/Agencies', User::where('role', '!=', 'End User')->where('status', 'active')->count()]);
            
            fputcsv($file, ['Total Campaigns', Campaign::count()]);
            fputcsv($file, ['Active/Live Campaigns', Campaign::where('status', 'active')->count()]);
            fputcsv($file, ['Pending Campaign Moderations', Campaign::where('status', 'pending')->count()]);
            fputcsv($file, ['Rejected Campaigns', Campaign::where('status', 'rejected')->count()]);
            
            fputcsv($file, ['Total Resources & Locations', Solution::count()]);
            fputcsv($file, ['Active/Live Resources & Locations', Solution::where('status', 'active')->count()]);
            fputcsv($file, ['Pending Resource Moderations', Solution::where('status', 'pending')->count()]);
            fputcsv($file, ['Rejected Resources & Locations', Solution::where('status', 'rejected')->count()]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
