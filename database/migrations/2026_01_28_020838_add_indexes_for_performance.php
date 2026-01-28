<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Applications table indexes
        Schema::table('applications', function (Blueprint $table) {
            $table->index(['user_id', 'status']); // Filter by user and status
            $table->index(['user_id', 'applied_at']); // Sort by application date
            $table->index('company_name'); // Search by company
            $table->index('job_title'); // Search by job title
        });

        // Projects table indexes
        Schema::table('projects', function (Blueprint $table) {
            $table->index(['user_id', 'is_featured']); // Get featured projects
            $table->index('difficulty'); // Filter by difficulty
        });

        // Skills table indexes
        Schema::table('skills', function (Blueprint $table) {
            $table->index(['user_id', 'category']); // Group by category
            $table->index('score'); // Filter by skill level
        });

        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            $table->index('name'); // Portfolio lookup by username
            $table->index('is_profile_public'); // Filter public profiles
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['user_id', 'applied_at']);
            $table->dropIndex(['company_name']);
            $table->dropIndex(['job_title']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'is_featured']);
            $table->dropIndex(['difficulty']);
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'category']);
            $table->dropIndex(['score']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['is_profile_public']);
        });
    }
};
