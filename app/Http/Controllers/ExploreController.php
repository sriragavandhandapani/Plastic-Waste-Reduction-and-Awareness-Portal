<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Solution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category');
        $search = $request->get('search');

        $campaignsQuery = Campaign::where('status', 'active');
        $solutionsQuery = Solution::where('status', 'active');

        if ($category) {
            $campaignsQuery->where('category', $category);
            $solutionsQuery->where('type', $category);
        }

        if ($search) {
            $campaignsQuery->where('title', 'like', "%{$search}%");
            $solutionsQuery->where('name', 'like', "%{$search}%");
        }

        $campaigns = $campaignsQuery->get();
        $solutions = $solutionsQuery->get();

        return view('explore.index', compact('campaigns', 'solutions', 'category', 'search'));
    }

    public function map()
    {
        $recyclingPoints = Solution::where('type', 'recycling point')->where('status', 'active')->get();
        return view('explore.map', compact('recyclingPoints'));
    }

    public function showCampaign(Campaign $campaign)
    {
        if ($campaign->status !== 'active' && 
            (!Auth::check() || (Auth::user()->role !== 'Admin' && Auth::id() !== $campaign->proposer_id))) {
            abort(403, 'Unauthorized access.');
        }

        $campaign->load('proposer');
        return view('explore.show_campaign', compact('campaign'));
    }

    public function showSolution(Solution $solution)
    {
        if ($solution->status !== 'active' && 
            (!Auth::check() || (Auth::user()->role !== 'Admin' && Auth::id() !== $solution->proposer_id))) {
            abort(403, 'Unauthorized access.');
        }

        $solution->load('proposer');
        return view('explore.show_solution', compact('solution'));
    }

    public function submitCampaignReview(Request $request, Campaign $campaign)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $review = [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'rating' => (int)$request->rating,
            'comment' => $request->comment,
            'created_at' => now()->toDateTimeString(),
        ];

        $reviews = $campaign->reviews ?? [];
        $reviews[] = $review;
        $campaign->update(['reviews' => $reviews]);

        return redirect()->back()->with('success', 'Thank you for your feedback!');
    }

    public function submitSolutionReview(Request $request, Solution $solution)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $review = [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'rating' => (int)$request->rating,
            'comment' => $request->comment,
            'created_at' => now()->toDateTimeString(),
        ];

        $reviews = $solution->reviews ?? [];
        $reviews[] = $review;
        $avgRating = collect($reviews)->avg('rating');

        $solution->update([
            'reviews' => $reviews,
            'rating' => $avgRating
        ]);

        return redirect()->back()->with('success', 'Thank you for your rating and review!');
    }
}
