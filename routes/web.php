<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\GitHubController;
use App\Http\Controllers\GitHubSyncController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public portfolio (CareerOS RPG HUD)
Route::get('/', [PortfolioController::class, 'index'])->name('home');

// Public portfolio by username
Route::get('/portfolio/{username}', [PortfolioController::class, 'show'])->name('portfolio.show');

// Social Authentication (GitHub, Google)
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('auth.social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('auth.social.callback');

// Redirect dashboard to applications
Route::get('/dashboard', function () {
    return redirect()->route('applications.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// Protected application routes (require authentication)
Route::middleware(['auth', 'verified'])->group(function () {
    // Resource route for applications
    Route::resource('applications', ApplicationController::class);
});

// Profile management routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // GitHub Integration
    Route::get('/github/redirect', [GitHubController::class, 'redirect'])->name('github.redirect');
    Route::get('/github/callback', [GitHubController::class, 'callback'])->name('github.callback');
    Route::post('/github/sync', [GitHubController::class, 'sync'])->name('github.sync');
    Route::post('/github/disconnect', [GitHubController::class, 'disconnect'])->name('github.disconnect');
    
    // GitHub Repository Sync
    Route::get('/github/sync-repos', [GitHubSyncController::class, 'index'])->name('github.sync-repos');
    Route::post('/github/sync-selected', [GitHubSyncController::class, 'sync'])->name('github.sync');
    Route::post('/github/sync-all', [GitHubSyncController::class, 'syncAll'])->name('github.sync-all');
    Route::get('/github/preview', [GitHubSyncController::class, 'preview'])->name('github.preview');
});

require __DIR__.'/auth.php';
