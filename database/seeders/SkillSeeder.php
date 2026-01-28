<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get demo user
        $demoUser = User::where('email', 'demo@jobhunter.test')->first();
        
        if (!$demoUser) {
            $this->command->error('Demo user not found. Run ApplicationSeeder first.');
            return;
        }

        $skills = [
            // Backend
            ['name' => 'PHP', 'score' => 85, 'category' => 'Backend'],
            ['name' => 'Laravel', 'score' => 90, 'category' => 'Backend'],
            ['name' => 'Node.js', 'score' => 70, 'category' => 'Backend'],
            ['name' => 'Python', 'score' => 65, 'category' => 'Backend'],
            
            // Frontend
            ['name' => 'JavaScript', 'score' => 80, 'category' => 'Frontend'],
            ['name' => 'React', 'score' => 75, 'category' => 'Frontend'],
            ['name' => 'Vue.js', 'score' => 70, 'category' => 'Frontend'],
            ['name' => 'TailwindCSS', 'score' => 85, 'category' => 'Frontend'],
            
            // Database
            ['name' => 'MySQL', 'score' => 80, 'category' => 'Database'],
            ['name' => 'PostgreSQL', 'score' => 70, 'category' => 'Database'],
            ['name' => 'Redis', 'score' => 65, 'category' => 'Database'],
            
            // DevOps & Tools
            ['name' => 'Git', 'score' => 85, 'category' => 'Tools'],
            ['name' => 'Docker', 'score' => 60, 'category' => 'DevOps'],
            ['name' => 'Linux', 'score' => 70, 'category' => 'DevOps'],
        ];

        foreach ($skills as $skillData) {
            $demoUser->skills_data()->create($skillData);
        }

        $this->command->info('✓ Seeded ' . count($skills) . ' skills for skill tree');
    }
}
