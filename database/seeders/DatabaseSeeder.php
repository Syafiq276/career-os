<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run seeders in correct order
        $this->call([
            ApplicationSeeder::class,
            ProjectSeeder::class,
            SkillSeeder::class,
        ]);
    }
}
