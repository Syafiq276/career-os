<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Generates 50 realistic job applications with varied statuses and logical date handling.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Create demo users
        $demoUser = User::create([
            'name' => 'demo',
            'email' => 'demo@jobhunter.test',
            'password' => Hash::make('password'),
            'bio' => 'Full-stack developer passionate about building scalable web applications.',
            'skills' => ['Laravel', 'PHP', 'JavaScript', 'React', 'TailwindCSS', 'MySQL'],
            'github_username' => 'demo-developer',
            'linkedin_url' => 'https://linkedin.com/in/demo',
            'portfolio_url' => 'https://demo.dev',
            'is_profile_public' => true,
            'email_verified_at' => now(),
        ]);

        $testUser = User::create([
            'name' => 'test',
            'email' => 'test@jobhunter.test',
            'password' => Hash::make('password'),
            'bio' => 'Junior developer looking for opportunities in web development.',
            'skills' => ['PHP', 'Laravel', 'Vue.js', 'TailwindCSS'],
            'email_verified_at' => now(),
        ]);

        $this->command->info('✓ Created demo users (demo@jobhunter.test / test@jobhunter.test - password: password)');

        // Realistic company names pool
        $companies = [
            'Tech Solutions Inc.',
            'Creative Digital Agency',
            'Global Innovations Ltd.',
            'NextGen Software',
            'DataMinds Analytics',
            'CloudScale Systems',
            'Pixel Perfect Design',
            'CodeCraft Studios',
            'Future Tech Corp',
            'Agile Development Group',
            'Smart Solutions LLC',
            'Digital Pioneers',
            'Innovation Labs',
            'WebWorks Co.',
            'Enterprise Systems Inc.',
        ];

        // Realistic job titles pool
        $jobTitles = [
            'Junior Web Developer',
            'Frontend Developer',
            'Backend Developer',
            'Full Stack Developer',
            'Data Analyst',
            'Software Engineer',
            'UI/UX Designer',
            'DevOps Engineer',
            'QA Tester',
            'Project Manager',
            'Business Analyst',
            'Marketing Specialist',
            'Content Writer',
            'Graphic Designer',
            'Sales Executive',
        ];

        // Status distribution (weights to create realistic mix)
        $statusWeights = [
            Application::STATUS_APPLIED => 30,    // 30% still in 'applied' status
            Application::STATUS_SCREENING => 15,  // 15% in screening
            Application::STATUS_INTERVIEW => 20,  // 20% reached interview
            Application::STATUS_OFFER => 5,       // 5% got offers
            Application::STATUS_REJECTED => 20,   // 20% rejected
            Application::STATUS_GHOSTED => 10,    // 10% ghosted
        ];

        // Create weighted status array
        $statuses = [];
        foreach ($statusWeights as $status => $weight) {
            $statuses = array_merge($statuses, array_fill(0, $weight, $status));
        }

        // Generate 50 applications distributed across users
        for ($i = 0; $i < 50; $i++) {
            // Random applied_at date within last 6 months
            $appliedAt = $faker->dateTimeBetween('-6 months', 'now');
            
            // Pick random status
            $status = $faker->randomElement($statuses);
            
            // Pick random user (more applications for demo user)
            $user = $faker->randomElement([$demoUser, $demoUser, $demoUser, $testUser]);
            
            // Logic: Determine interview_at based on status
            $interviewAt = null;
            if ($status === Application::STATUS_INTERVIEW || $status === Application::STATUS_OFFER) {
                // Interview date should be 1-4 weeks after applied_at
                $interviewAt = $faker->dateTimeBetween($appliedAt, '+4 weeks');
            }
            // For 'applied', 'screening', 'rejected', 'ghosted' -> interview_at remains null

            $user->applications()->create([
                'company_name' => $faker->randomElement($companies),
                'job_title' => $faker->randomElement($jobTitles),
                'job_link' => $faker->boolean(70) ? $faker->url() : null, // 70% have links
                'location' => $faker->boolean(80) ? $faker->city() . ', ' . $faker->stateAbbr() : 'Remote',
                'salary_range' => $faker->boolean(60) ? $this->generateSalaryRange($faker) : null,
                'status' => $status,
                'applied_at' => $appliedAt,
                'interview_at' => $interviewAt,
                'notes' => $faker->boolean(40) ? $faker->sentence(10) : null, // 40% have notes
            ]);
        }

        $this->command->info('✓ Seeded 50 job applications across users');
    }

    /**
     * Generate a realistic salary range.
     *
     * @param \Faker\Generator $faker
     * @return string
     */
    private function generateSalaryRange($faker): string
    {
        $minSalary = $faker->numberBetween(40, 90) * 1000;
        $maxSalary = $minSalary + $faker->numberBetween(10, 30) * 1000;

        return '$' . number_format($minSalary) . ' - $' . number_format($maxSalary);
    }
}
