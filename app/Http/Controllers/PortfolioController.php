<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    /**
     * Display the public portfolio page (RPG HUD).
     */
    public function index()
    {
        // Get demo user with eager loaded relationships to prevent N+1 queries
        $user = User::where('email', 'demo@jobhunter.test')
            ->public()
            ->with([
                'projects' => function($query) {
                    $query->where('is_featured', true)
                          ->orderBy('xp_gained', 'desc')
                          ->select('id', 'user_id', 'title', 'description', 'difficulty', 'tech_stack', 'repo_link', 'xp_gained');
                },
                'skills_data' => function($query) {
                    $query->select('id', 'user_id', 'name', 'score', 'category');
                }
            ])
            ->firstOrFail();

        // Calculate RPG stats (uses cached relationships)
        $totalXp = $user->total_xp;
        $level = $user->level;
        $xpToNextLevel = $user->xp_to_next_level;
        $currentLevelXp = $totalXp % 1000;
        $xpProgress = ($currentLevelXp / 1000) * 100;

        // Get data from already loaded relationships
        $featuredQuests = $user->projects;
        $skills = $user->skills_data;

        return view('welcome', compact('user', 'totalXp', 'level', 'xpToNextLevel', 'xpProgress', 'featuredQuests', 'skills'));
    }

    /**
     * Show a specific user's portfolio.
     */
    public function show($id)
    {
        // Eager load relationships with select statements to minimize data transfer
        $user = User::where('id', $id)
            ->public()
            ->with([
                'projects' => function($query) {
                    $query->where('is_featured', true)
                          ->orderBy('xp_gained', 'desc')
                          ->select('id', 'user_id', 'title', 'description', 'difficulty', 'tech_stack', 'repo_link', 'xp_gained');
                },
                'skills_data' => function($query) {
                    $query->select('id', 'user_id', 'name', 'score', 'category');
                }
            ])
            ->firstOrFail();

        // Calculate RPG stats
        $totalXp = $user->total_xp;
        $level = $user->level;
        $xpToNextLevel = $user->xp_to_next_level;
        $currentLevelXp = $totalXp % 1000;
        $xpProgress = ($currentLevelXp / 1000) * 100;

        // Get data from already loaded relationships
        $featuredQuests = $user->projects;
        $skills = $user->skills_data;

        return view('welcome', compact('user', 'totalXp', 'level', 'xpToNextLevel', 'xpProgress', 'featuredQuests', 'skills'));
    }
}
