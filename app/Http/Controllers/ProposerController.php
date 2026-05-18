<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Solution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProposerController extends Controller
{
    public function dashboard()
    {
        $campaigns = Campaign::where('proposer_id', Auth::id())->get();
        $solutions = Solution::where('proposer_id', Auth::id())->get();

        return view('proposer.dashboard', compact('campaigns', 'solutions'));
    }

    public function createCampaign(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'goal' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        Campaign::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'goal' => $request->goal,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'proposer_id' => Auth::id(),
            'status' => 'pending',
            'impact_metrics' => []
        ]);

        return redirect()->route('proposer.dashboard')->with('success', 'Campaign created and submitted for moderation!');
    }

    public function createSolution(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string', // e.g., 'recycling point', 'education program'
            'description' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        Solution::create([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'location' => [
                'address' => $request->address,
                'lat' => $request->latitude ?? 0,
                'lng' => $request->longitude ?? 0,
            ],
            'proposer_id' => Auth::id(),
            'status' => 'pending',
            'rating' => 0
        ]);

        return redirect()->route('proposer.dashboard')->with('success', 'Resource/Solution created and submitted for moderation!');
    }
}
