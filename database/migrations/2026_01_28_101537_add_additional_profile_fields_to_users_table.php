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
        Schema::table('users', function (Blueprint $table) {
            // Resume/CV
            $table->string('resume_path')->nullable()->after('portfolio_url');
            
            // Additional Social Media
            $table->string('twitter_username')->nullable()->after('resume_path');
            $table->string('instagram_username')->nullable()->after('twitter_username');
            $table->string('youtube_url')->nullable()->after('instagram_username');
            $table->string('twitch_username')->nullable()->after('youtube_url');
            $table->string('discord_username')->nullable()->after('twitch_username');
            $table->string('stackoverflow_id')->nullable()->after('discord_username');
            $table->string('devto_username')->nullable()->after('stackoverflow_id');
            $table->string('medium_username')->nullable()->after('devto_username');
            $table->string('behance_username')->nullable()->after('medium_username');
            $table->string('dribbble_username')->nullable()->after('behance_username');
            
            // Professional Info
            $table->string('job_title')->nullable()->after('dribbble_username');
            $table->string('location')->nullable()->after('job_title');
            $table->text('tagline')->nullable()->after('location'); // Short catchy phrase
            $table->boolean('available_for_hire')->default(false)->after('tagline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'resume_path',
                'twitter_username',
                'instagram_username',
                'youtube_url',
                'twitch_username',
                'discord_username',
                'stackoverflow_id',
                'devto_username',
                'medium_username',
                'behance_username',
                'dribbble_username',
                'job_title',
                'location',
                'tagline',
                'available_for_hire',
            ]);
        });
    }
};
