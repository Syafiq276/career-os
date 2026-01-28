<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
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

        $projects = [
            [
                'title' => 'CareerOS - Job Hunter Dashboard',
                'description' => 'A gamified portfolio and job tracking system built with Laravel 11. Features include XP/Level system, skill radar charts, quest management, and cyberpunk-inspired UI. Demonstrates full-stack development skills with authentication, CRUD operations, and dynamic data visualization.',
                'difficulty' => 'Legendary',
                'tech_stack' => ['Laravel 11', 'PHP 8.2', 'TailwindCSS', 'Chart.js', 'SQLite', 'Blade Templates'],
                'repo_link' => 'https://github.com/demo-developer/career-os',
                'is_featured' => true,
                'xp_gained' => 1500,
            ],
            [
                'title' => 'E-Commerce API',
                'description' => 'RESTful API for an e-commerce platform with product management, cart functionality, and payment integration. Includes JWT authentication, rate limiting, and comprehensive API documentation using Swagger.',
                'difficulty' => 'Hardcore',
                'tech_stack' => ['Laravel', 'MySQL', 'Redis', 'JWT', 'Swagger', 'Stripe API'],
                'repo_link' => 'https://github.com/demo-developer/ecommerce-api',
                'is_featured' => true,
                'xp_gained' => 1200,
            ],
            [
                'title' => 'Real-Time Chat Application',
                'description' => 'WebSocket-based chat application with private messaging, group chats, and file sharing. Built with Laravel broadcasting and Vue.js frontend for real-time updates.',
                'difficulty' => 'Hardcore',
                'tech_stack' => ['Laravel', 'Vue.js', 'Pusher', 'WebSockets', 'Redis', 'MySQL'],
                'repo_link' => 'https://github.com/demo-developer/realtime-chat',
                'is_featured' => true,
                'xp_gained' => 1000,
            ],
            [
                'title' => 'Task Management System',
                'description' => 'Kanban-style task board with drag-and-drop functionality, team collaboration, and deadline tracking. Features include sprint planning, burndown charts, and activity logs.',
                'difficulty' => 'Normal',
                'tech_stack' => ['Laravel', 'Livewire', 'AlpineJS', 'TailwindCSS', 'MySQL'],
                'repo_link' => 'https://github.com/demo-developer/task-manager',
                'is_featured' => true,
                'xp_gained' => 800,
            ],
            [
                'title' => 'Weather Dashboard',
                'description' => 'Interactive weather dashboard that fetches real-time data from OpenWeather API. Displays 5-day forecasts, weather maps, and location-based alerts.',
                'difficulty' => 'Normal',
                'tech_stack' => ['JavaScript', 'React', 'OpenWeather API', 'Chart.js', 'Tailwind'],
                'repo_link' => 'https://github.com/demo-developer/weather-dashboard',
                'is_featured' => false,
                'xp_gained' => 500,
            ],
            [
                'title' => 'Portfolio Website Generator',
                'description' => 'CLI tool to generate static portfolio websites from markdown files. Supports multiple themes, syntax highlighting, and automatic deployment to GitHub Pages.',
                'difficulty' => 'Tutorial',
                'tech_stack' => ['Node.js', 'Markdown', 'EJS', 'GitHub Actions'],
                'repo_link' => 'https://github.com/demo-developer/portfolio-gen',
                'is_featured' => false,
                'xp_gained' => 300,
            ],
        ];

        foreach ($projects as $projectData) {
            $demoUser->projects()->create($projectData);
        }

        $this->command->info('✓ Seeded ' . count($projects) . ' quests (Total XP: ' . array_sum(array_column($projects, 'xp_gained')) . ')');
    }
}
