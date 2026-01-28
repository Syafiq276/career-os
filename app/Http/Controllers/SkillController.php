<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillController extends Controller
{
    /**
     * Auto-generate skills from GitHub projects.
     */
    public function generateFromProjects(): RedirectResponse
    {
        $user = Auth::user();
        $projects = $user->projects;

        if ($projects->isEmpty()) {
            return redirect()->route('profile.edit')
                ->with('skill-error', 'No projects found. Sync your GitHub repositories first.');
        }

        // Collect all tech stacks from projects
        $techCounts = [];
        foreach ($projects as $project) {
            if (is_array($project->tech_stack)) {
                foreach ($project->tech_stack as $tech) {
                    $tech = trim($tech);
                    if (!empty($tech)) {
                        $techCounts[$tech] = ($techCounts[$tech] ?? 0) + 1;
                    }
                }
            }
        }

        if (empty($techCounts)) {
            return redirect()->route('profile.edit')
                ->with('skill-error', 'No technologies found in your projects.');
        }

        // Map technologies to categories
        $categoryMap = [
            'Frontend' => ['JavaScript', 'TypeScript', 'React', 'Vue', 'Angular', 'Svelte', 'HTML', 'CSS', 'SCSS', 'TailwindCSS', 'Bootstrap'],
            'Backend' => ['PHP', 'Python', 'Java', 'C#', 'Ruby', 'Go', 'Rust', 'Node.js', 'Laravel', 'Django', 'Express', 'FastAPI'],
            'Database' => ['MySQL', 'PostgreSQL', 'MongoDB', 'Redis', 'SQLite', 'MariaDB', 'Cassandra', 'DynamoDB'],
            'DevOps' => ['Docker', 'Kubernetes', 'AWS', 'Azure', 'GCP', 'Terraform', 'Ansible', 'Jenkins', 'CI/CD'],
            'Tools' => ['Git', 'GitHub', 'GitLab', 'Webpack', 'Vite', 'npm', 'Composer', 'Maven', 'Gradle'],
        ];

        $skillsAdded = 0;
        $maxProjects = max($techCounts);

        foreach ($techCounts as $tech => $count) {
            // Skip if skill already exists
            if ($user->skills_data()->where('name', $tech)->exists()) {
                continue;
            }

            // Calculate score based on usage (20-95 range)
            $score = min(95, 20 + (($count / $maxProjects) * 75));
            $score = round($score);

            // Determine category
            $category = 'Other';
            foreach ($categoryMap as $cat => $techs) {
                foreach ($techs as $knownTech) {
                    if (stripos($tech, $knownTech) !== false || stripos($knownTech, $tech) !== false) {
                        $category = $cat;
                        break 2;
                    }
                }
            }

            $user->skills_data()->create([
                'name' => $tech,
                'score' => $score,
                'category' => $category,
            ]);

            $skillsAdded++;
        }

        return redirect()->route('profile.edit')
            ->with('skill-added', "Auto-generated {$skillsAdded} skills from your projects!");
    }

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
