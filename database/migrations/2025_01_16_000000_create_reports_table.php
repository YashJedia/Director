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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            
            // Basic Report Information
            $table->string('title');
            $table->string('quarter'); // e.g., "Q3 2025"
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('language_id');
            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved', 'rejected'])->default('draft');
            $table->integer('score')->nullable(); // 1-10 score from admin
            $table->text('admin_feedback')->nullable();
            
            // Section I: Goal Progress
            $table->integer('languages_previous_year')->default(0);
            $table->integer('languages_goal_2025')->default(0);
            $table->integer('languages_goal_q1')->default(0);
            $table->integer('languages_achieved_q1')->default(0);
            $table->integer('volunteers_previous_year')->default(0);
            $table->integer('volunteers_goal_2025')->default(0);
            $table->integer('volunteers_goal_q1')->default(0);
            $table->integer('volunteers_achieved_q1')->default(0);
            
            // Section II: Organic Reach (Per Language & Platform)
            // Facebook metrics
            $table->integer('facebook_reach')->default(0);
            // Instagram metrics
            $table->integer('instagram_reach')->default(0);
            // YouTube metrics
            $table->integer('youtube_reach')->default(0);
            // Website metrics
            $table->integer('website_reach')->default(0);
            
            // Section III: Bible Course Students
            $table->integer('evangelistic_students')->default(0);
            $table->integer('discipleship_students')->default(0);
            $table->integer('leadership_students')->default(0);
            
            // Section IV: Chat Conversations
            $table->integer('evangelistic_conversations')->default(0);
            $table->integer('pastoral_connections')->default(0);
            
            // Section V: Organization
            $table->decimal('income_euros', 15, 2)->default(0.00);
            $table->decimal('expenditure_euros', 15, 2)->default(0.00);
            
            // Section VI: Public Relations & Staffing
            $table->integer('pr_total_organic_reach')->default(0);
            $table->decimal('personal_fte', 8, 2)->default(0.00); // Full-Time Equivalent - allows up to 999,999.99
            
            // Section VII: Descriptive Text Fields
            $table->text('new_activity')->nullable(); // max 100 words
            $table->text('organizational_highlight')->nullable(); // max 50 words
            $table->text('organizational_concern')->nullable(); // max 50 words
            $table->text('organizational_issues')->nullable(); // max 50 words
            
            // Timestamps
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            
            // Unique constraint to prevent duplicate reports for same user, language, and quarter
            $table->unique(['user_id', 'language_id', 'quarter'], 'unique_user_language_quarter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
