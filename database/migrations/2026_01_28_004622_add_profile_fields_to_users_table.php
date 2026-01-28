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
            $table->text('bio')->nullable()->after('email');
            $table->json('skills')->nullable()->after('bio');
            $table->string('github_username')->nullable()->after('skills');
            $table->string('github_token')->nullable()->after('github_username');
            $table->string('linkedin_url')->nullable()->after('github_token');
            $table->string('portfolio_url')->nullable()->after('linkedin_url');
            $table->boolean('is_profile_public')->default(false)->after('portfolio_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'bio',
                'skills',
                'github_username',
                'github_token',
                'linkedin_url',
                'portfolio_url',
                'is_profile_public',
            ]);
        });
    }
};
