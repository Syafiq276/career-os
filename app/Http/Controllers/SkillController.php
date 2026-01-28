<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillController extends Controller
{
    /**
     * Store a new skill.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'score' => ['required', 'integer', 'min:0', 'max:100'],
            'category' => ['required', 'string', 'in:Frontend,Backend,Database,DevOps,Tools,Other'],
        ]);

        Auth::user()->skills_data()->create($validated);

        return redirect()->route('profile.edit')
            ->with('skill-added', true);
    }

    /**
     * Delete a skill.
     */
    public function destroy(Skill $skill): RedirectResponse
    {
        // Ensure user can only delete their own skills
        if ($skill->user_id !== Auth::id()) {
            abort(403);
        }

        $skill->delete();

        return redirect()->route('profile.edit')
            ->with('skill-deleted', true);
    }
}
