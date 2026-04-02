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
        Schema::table('reports', function (Blueprint $table) {
            // Add annual goal_year columns for all metrics
            
            // Ministry (Languages, Volunteers)
            $table->integer('languages_goal_year')->default(0)->after('languages_achieved_q4');
            $table->integer('volunteers_goal_year')->default(0)->after('volunteers_achieved_q4');
            $table->integer('volunteers_mentors_goal_year')->default(0)->after('volunteers_mentors_achieved_q4');
            $table->integer('volunteers_chatters_goal_year')->default(0)->after('volunteers_chatters_achieved_q4');
            $table->integer('volunteers_creators_goal_year')->default(0)->after('volunteers_creators_achieved_q4');
            
            // Outreach & Engagement
            $table->integer('evangelistic_students_goal_year')->default(0)->after('evangelistic_students_achieved_q4');
            $table->integer('discipleship_students_goal_year')->default(0)->after('discipleship_students_achieved_q4');
            $table->integer('leadership_students_goal_year')->default(0)->after('leadership_students_achieved_q4');
            $table->integer('evangelistic_conversations_goal_year')->default(0)->after('evangelistic_conversations_achieved_q4');
            $table->integer('pastoral_connections_goal_year')->default(0)->after('pastoral_connections_achieved_q4');
            
            // Social Media Reach
            $table->integer('facebook_reach_goal_year')->default(0)->after('facebook_reach_achieved_q4');
            $table->integer('instagram_reach_goal_year')->default(0)->after('instagram_reach_achieved_q4');
            $table->integer('youtube_reach_goal_year')->default(0)->after('youtube_reach_achieved_q4');
            $table->integer('website_reach_goal_year')->default(0)->after('website_reach_achieved_q4');
            
            // Financial & Operations
            $table->decimal('income_euros_goal_year', 15, 2)->default(0.00)->after('income_euros_achieved_q4');
            $table->decimal('expenditure_euros_goal_year', 15, 2)->default(0.00)->after('expenditure_euros_achieved_q4');
            $table->integer('pr_total_organic_reach_goal_year')->default(0)->after('pr_total_organic_reach_achieved_q4');
            $table->decimal('personal_fte_goal_year', 8, 2)->default(0.00)->after('personal_fte_achieved_q4');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn([
                'languages_goal_year',
                'volunteers_goal_year',
                'volunteers_mentors_goal_year',
                'volunteers_chatters_goal_year',
                'volunteers_creators_goal_year',
                'evangelistic_students_goal_year',
                'discipleship_students_goal_year',
                'leadership_students_goal_year',
                'evangelistic_conversations_goal_year',
                'pastoral_connections_goal_year',
                'facebook_reach_goal_year',
                'instagram_reach_goal_year',
                'youtube_reach_goal_year',
                'website_reach_goal_year',
                'income_euros_goal_year',
                'expenditure_euros_goal_year',
                'pr_total_organic_reach_goal_year',
                'personal_fte_goal_year',
            ]);
        });
    }
};
