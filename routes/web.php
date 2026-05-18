<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Explore is public
Route::get('/explore', [\App\Http\Controllers\ExploreController::class, 'index'])->name('explore.index');
Route::get('/explore/map', [\App\Http\Controllers\ExploreController::class, 'map'])->name('explore.map');
Route::get('/explore/campaigns/{campaign}', [\App\Http\Controllers\ExploreController::class, 'showCampaign'])->name('explore.campaigns.show');
Route::get('/explore/solutions/{solution}', [\App\Http\Controllers\ExploreController::class, 'showSolution'])->name('explore.solutions.show');

Route::middleware('auth')->group(function () {
    
    // Feedback/reviews
    Route::post('/explore/campaigns/{campaign}/reviews', [\App\Http\Controllers\ExploreController::class, 'submitCampaignReview'])->name('explore.campaigns.reviews.store');
    Route::post('/explore/solutions/{solution}/reviews', [\App\Http\Controllers\ExploreController::class, 'submitSolutionReview'])->name('explore.solutions.reviews.store');

    // Notifications
    Route::post('/notifications/{notification}/read', function (\App\Models\Notification $notification) {
        $notification->update(['is_read' => true]);
        return redirect()->back()->with('success', 'Notification marked as read.');
    })->name('notifications.read');

    // Proposer, Recycling Agency, Awareness Organization
    Route::middleware('role:Proposer,Recycling Agency,Awareness Organization')->prefix('proposer')->name('proposer.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\ProposerController::class, 'dashboard'])->name('dashboard');
        Route::post('/campaigns', [\App\Http\Controllers\ProposerController::class, 'createCampaign'])->name('campaigns.store');
        Route::post('/solutions', [\App\Http\Controllers\ProposerController::class, 'createSolution'])->name('solutions.store');
    });

    // Admin
    Route::middleware('role:Admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/export', [\App\Http\Controllers\AdminController::class, 'exportReport'])->name('export');
        
        Route::post('/users/{user}/approve', [\App\Http\Controllers\AdminController::class, 'approveUser'])->name('users.approve');
        Route::post('/users/{user}/reject', [\App\Http\Controllers\AdminController::class, 'rejectUser'])->name('users.reject');
        
        Route::post('/campaigns/{campaign}/approve', [\App\Http\Controllers\AdminController::class, 'approveCampaign'])->name('campaigns.approve');
        Route::post('/campaigns/{campaign}/reject', [\App\Http\Controllers\AdminController::class, 'rejectCampaign'])->name('campaigns.reject');
        
        Route::post('/solutions/{solution}/approve', [\App\Http\Controllers\AdminController::class, 'approveSolution'])->name('solutions.approve');
        Route::post('/solutions/{solution}/reject', [\App\Http\Controllers\AdminController::class, 'rejectSolution'])->name('solutions.reject');
    });
});
