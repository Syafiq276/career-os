<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates the applications table with all required fields for job tracking.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('job_title');
            $table->string('job_link')->nullable();
            $table->string('location')->nullable();
            $table->string('salary_range')->nullable();
            $table->enum('status', [
                'applied',
                'screening',
                'interview',
                'offer',
                'rejected',
                'ghosted'
            ])->default('applied');
            $table->date('applied_at');
            $table->date('interview_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Index for filtering by status (improves query performance)
            $table->index('status');
            // Index for sorting by application date
            $table->index('applied_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
