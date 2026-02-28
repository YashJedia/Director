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
            // Add Q2, Q3, Q4 columns for Languages
            $table->integer('languages_goal_q2')->default(0)->after('languages_achieved_q1');
            $table->integer('languages_achieved_q2')->default(0)->after('languages_goal_q2');
            $table->integer('languages_goal_q3')->default(0)->after('languages_achieved_q2');
            $table->integer('languages_achieved_q3')->default(0)->after('languages_goal_q3');
            $table->integer('languages_goal_q4')->default(0)->after('languages_achieved_q3');
            $table->integer('languages_achieved_q4')->default(0)->after('languages_goal_q4');

            // Add Q2, Q3, Q4 columns for Volunteers
            $table->integer('volunteers_goal_q2')->default(0)->after('volunteers_achieved_q1');
            $table->integer('volunteers_achieved_q2')->default(0)->after('volunteers_goal_q2');
            $table->integer('volunteers_goal_q3')->default(0)->after('volunteers_achieved_q2');
            $table->integer('volunteers_achieved_q3')->default(0)->after('volunteers_goal_q3');
            $table->integer('volunteers_goal_q4')->default(0)->after('volunteers_achieved_q3');
            $table->integer('volunteers_achieved_q4')->default(0)->after('volunteers_goal_q4');

            // Add Volunteer subcategories (mentors, chatters, creators) for all quarters - both goal and achieved
            // Bible Mentors
            $table->integer('volunteers_mentors_goal_q1')->default(0)->after('volunteers_achieved_q4');
            $table->integer('volunteers_mentors_achieved_q1')->default(0)->after('volunteers_mentors_goal_q1');
            $table->integer('volunteers_mentors_goal_q2')->default(0)->after('volunteers_mentors_achieved_q1');
            $table->integer('volunteers_mentors_achieved_q2')->default(0)->after('volunteers_mentors_goal_q2');
            $table->integer('volunteers_mentors_goal_q3')->default(0)->after('volunteers_mentors_achieved_q2');
            $table->integer('volunteers_mentors_achieved_q3')->default(0)->after('volunteers_mentors_goal_q3');
            $table->integer('volunteers_mentors_goal_q4')->default(0)->after('volunteers_mentors_achieved_q3');
            $table->integer('volunteers_mentors_achieved_q4')->default(0)->after('volunteers_mentors_goal_q4');

            // Chat Volunteers
            $table->integer('volunteers_chatters_goal_q1')->default(0)->after('volunteers_mentors_achieved_q4');
            $table->integer('volunteers_chatters_achieved_q1')->default(0)->after('volunteers_chatters_goal_q1');
            $table->integer('volunteers_chatters_goal_q2')->default(0)->after('volunteers_chatters_achieved_q1');
            $table->integer('volunteers_chatters_achieved_q2')->default(0)->after('volunteers_chatters_goal_q2');
            $table->integer('volunteers_chatters_goal_q3')->default(0)->after('volunteers_chatters_achieved_q2');
            $table->integer('volunteers_chatters_achieved_q3')->default(0)->after('volunteers_chatters_goal_q3');
            $table->integer('volunteers_chatters_goal_q4')->default(0)->after('volunteers_chatters_achieved_q3');
            $table->integer('volunteers_chatters_achieved_q4')->default(0)->after('volunteers_chatters_goal_q4');

            // Content Creators
            $table->integer('volunteers_creators_goal_q1')->default(0)->after('volunteers_chatters_achieved_q4');
            $table->integer('volunteers_creators_achieved_q1')->default(0)->after('volunteers_creators_goal_q1');
            $table->integer('volunteers_creators_goal_q2')->default(0)->after('volunteers_creators_achieved_q1');
            $table->integer('volunteers_creators_achieved_q2')->default(0)->after('volunteers_creators_goal_q2');
            $table->integer('volunteers_creators_goal_q3')->default(0)->after('volunteers_creators_achieved_q2');
            $table->integer('volunteers_creators_achieved_q3')->default(0)->after('volunteers_creators_goal_q3');
            $table->integer('volunteers_creators_goal_q4')->default(0)->after('volunteers_creators_achieved_q3');
            $table->integer('volunteers_creators_achieved_q4')->default(0)->after('volunteers_creators_goal_q4');

            // Add quarterly columns for Outreach & Engagement
            $table->integer('evangelistic_students_goal_q1')->default(0)->after('volunteers_creators_achieved_q4');
            $table->integer('evangelistic_students_achieved_q1')->default(0)->after('evangelistic_students_goal_q1');
            $table->integer('evangelistic_students_goal_q2')->default(0)->after('evangelistic_students_achieved_q1');
            $table->integer('evangelistic_students_achieved_q2')->default(0)->after('evangelistic_students_goal_q2');
            $table->integer('evangelistic_students_goal_q3')->default(0)->after('evangelistic_students_achieved_q2');
            $table->integer('evangelistic_students_achieved_q3')->default(0)->after('evangelistic_students_goal_q3');
            $table->integer('evangelistic_students_goal_q4')->default(0)->after('evangelistic_students_achieved_q3');
            $table->integer('evangelistic_students_achieved_q4')->default(0)->after('evangelistic_students_goal_q4');

            $table->integer('discipleship_students_goal_q1')->default(0)->after('evangelistic_students_achieved_q4');
            $table->integer('discipleship_students_achieved_q1')->default(0)->after('discipleship_students_goal_q1');
            $table->integer('discipleship_students_goal_q2')->default(0)->after('discipleship_students_achieved_q1');
            $table->integer('discipleship_students_achieved_q2')->default(0)->after('discipleship_students_goal_q2');
            $table->integer('discipleship_students_goal_q3')->default(0)->after('discipleship_students_achieved_q2');
            $table->integer('discipleship_students_achieved_q3')->default(0)->after('discipleship_students_goal_q3');
            $table->integer('discipleship_students_goal_q4')->default(0)->after('discipleship_students_achieved_q3');
            $table->integer('discipleship_students_achieved_q4')->default(0)->after('discipleship_students_goal_q4');

            $table->integer('leadership_students_goal_q1')->default(0)->after('discipleship_students_achieved_q4');
            $table->integer('leadership_students_achieved_q1')->default(0)->after('leadership_students_goal_q1');
            $table->integer('leadership_students_goal_q2')->default(0)->after('leadership_students_achieved_q1');
            $table->integer('leadership_students_achieved_q2')->default(0)->after('leadership_students_goal_q2');
            $table->integer('leadership_students_goal_q3')->default(0)->after('leadership_students_achieved_q2');
            $table->integer('leadership_students_achieved_q3')->default(0)->after('leadership_students_goal_q3');
            $table->integer('leadership_students_goal_q4')->default(0)->after('leadership_students_achieved_q3');
            $table->integer('leadership_students_achieved_q4')->default(0)->after('leadership_students_goal_q4');

            $table->integer('evangelistic_conversations_goal_q1')->default(0)->after('leadership_students_achieved_q4');
            $table->integer('evangelistic_conversations_achieved_q1')->default(0)->after('evangelistic_conversations_goal_q1');
            $table->integer('evangelistic_conversations_goal_q2')->default(0)->after('evangelistic_conversations_achieved_q1');
            $table->integer('evangelistic_conversations_achieved_q2')->default(0)->after('evangelistic_conversations_goal_q2');
            $table->integer('evangelistic_conversations_goal_q3')->default(0)->after('evangelistic_conversations_achieved_q2');
            $table->integer('evangelistic_conversations_achieved_q3')->default(0)->after('evangelistic_conversations_goal_q3');
            $table->integer('evangelistic_conversations_goal_q4')->default(0)->after('evangelistic_conversations_achieved_q3');
            $table->integer('evangelistic_conversations_achieved_q4')->default(0)->after('evangelistic_conversations_goal_q4');

            $table->integer('pastoral_connections_goal_q1')->default(0)->after('evangelistic_conversations_achieved_q4');
            $table->integer('pastoral_connections_achieved_q1')->default(0)->after('pastoral_connections_goal_q1');
            $table->integer('pastoral_connections_goal_q2')->default(0)->after('pastoral_connections_achieved_q1');
            $table->integer('pastoral_connections_achieved_q2')->default(0)->after('pastoral_connections_goal_q2');
            $table->integer('pastoral_connections_goal_q3')->default(0)->after('pastoral_connections_achieved_q2');
            $table->integer('pastoral_connections_achieved_q3')->default(0)->after('pastoral_connections_goal_q3');
            $table->integer('pastoral_connections_goal_q4')->default(0)->after('pastoral_connections_achieved_q3');
            $table->integer('pastoral_connections_achieved_q4')->default(0)->after('pastoral_connections_goal_q4');

            // Add quarterly columns for Social Media Reach
            $table->integer('facebook_reach_goal_q1')->default(0)->after('pastoral_connections_achieved_q4');
            $table->integer('facebook_reach_achieved_q1')->default(0)->after('facebook_reach_goal_q1');
            $table->integer('facebook_reach_goal_q2')->default(0)->after('facebook_reach_achieved_q1');
            $table->integer('facebook_reach_achieved_q2')->default(0)->after('facebook_reach_goal_q2');
            $table->integer('facebook_reach_goal_q3')->default(0)->after('facebook_reach_achieved_q2');
            $table->integer('facebook_reach_achieved_q3')->default(0)->after('facebook_reach_goal_q3');
            $table->integer('facebook_reach_goal_q4')->default(0)->after('facebook_reach_achieved_q3');
            $table->integer('facebook_reach_achieved_q4')->default(0)->after('facebook_reach_goal_q4');

            $table->integer('instagram_reach_goal_q1')->default(0)->after('facebook_reach_achieved_q4');
            $table->integer('instagram_reach_achieved_q1')->default(0)->after('instagram_reach_goal_q1');
            $table->integer('instagram_reach_goal_q2')->default(0)->after('instagram_reach_achieved_q1');
            $table->integer('instagram_reach_achieved_q2')->default(0)->after('instagram_reach_goal_q2');
            $table->integer('instagram_reach_goal_q3')->default(0)->after('instagram_reach_achieved_q2');
            $table->integer('instagram_reach_achieved_q3')->default(0)->after('instagram_reach_goal_q3');
            $table->integer('instagram_reach_goal_q4')->default(0)->after('instagram_reach_achieved_q3');
            $table->integer('instagram_reach_achieved_q4')->default(0)->after('instagram_reach_goal_q4');

            $table->integer('youtube_reach_goal_q1')->default(0)->after('instagram_reach_achieved_q4');
            $table->integer('youtube_reach_achieved_q1')->default(0)->after('youtube_reach_goal_q1');
            $table->integer('youtube_reach_goal_q2')->default(0)->after('youtube_reach_achieved_q1');
            $table->integer('youtube_reach_achieved_q2')->default(0)->after('youtube_reach_goal_q2');
            $table->integer('youtube_reach_goal_q3')->default(0)->after('youtube_reach_achieved_q2');
            $table->integer('youtube_reach_achieved_q3')->default(0)->after('youtube_reach_goal_q3');
            $table->integer('youtube_reach_goal_q4')->default(0)->after('youtube_reach_achieved_q3');
            $table->integer('youtube_reach_achieved_q4')->default(0)->after('youtube_reach_goal_q4');

            $table->integer('website_reach_goal_q1')->default(0)->after('youtube_reach_achieved_q4');
            $table->integer('website_reach_achieved_q1')->default(0)->after('website_reach_goal_q1');
            $table->integer('website_reach_goal_q2')->default(0)->after('website_reach_achieved_q1');
            $table->integer('website_reach_achieved_q2')->default(0)->after('website_reach_goal_q2');
            $table->integer('website_reach_goal_q3')->default(0)->after('website_reach_achieved_q2');
            $table->integer('website_reach_achieved_q3')->default(0)->after('website_reach_goal_q3');
            $table->integer('website_reach_goal_q4')->default(0)->after('website_reach_achieved_q3');
            $table->integer('website_reach_achieved_q4')->default(0)->after('website_reach_goal_q4');

            // Add quarterly columns for Financial & Operations
            $table->decimal('income_euros_goal_q1', 15, 2)->default(0.00)->after('website_reach_achieved_q4');
            $table->decimal('income_euros_achieved_q1', 15, 2)->default(0.00)->after('income_euros_goal_q1');
            $table->decimal('income_euros_goal_q2', 15, 2)->default(0.00)->after('income_euros_achieved_q1');
            $table->decimal('income_euros_achieved_q2', 15, 2)->default(0.00)->after('income_euros_goal_q2');
            $table->decimal('income_euros_goal_q3', 15, 2)->default(0.00)->after('income_euros_achieved_q2');
            $table->decimal('income_euros_achieved_q3', 15, 2)->default(0.00)->after('income_euros_goal_q3');
            $table->decimal('income_euros_goal_q4', 15, 2)->default(0.00)->after('income_euros_achieved_q3');
            $table->decimal('income_euros_achieved_q4', 15, 2)->default(0.00)->after('income_euros_goal_q4');

            $table->decimal('expenditure_euros_goal_q1', 15, 2)->default(0.00)->after('income_euros_achieved_q4');
            $table->decimal('expenditure_euros_achieved_q1', 15, 2)->default(0.00)->after('expenditure_euros_goal_q1');
            $table->decimal('expenditure_euros_goal_q2', 15, 2)->default(0.00)->after('expenditure_euros_achieved_q1');
            $table->decimal('expenditure_euros_achieved_q2', 15, 2)->default(0.00)->after('expenditure_euros_goal_q2');
            $table->decimal('expenditure_euros_goal_q3', 15, 2)->default(0.00)->after('expenditure_euros_achieved_q2');
            $table->decimal('expenditure_euros_achieved_q3', 15, 2)->default(0.00)->after('expenditure_euros_goal_q3');
            $table->decimal('expenditure_euros_goal_q4', 15, 2)->default(0.00)->after('expenditure_euros_achieved_q3');
            $table->decimal('expenditure_euros_achieved_q4', 15, 2)->default(0.00)->after('expenditure_euros_goal_q4');

            $table->integer('pr_total_organic_reach_goal_q1')->default(0)->after('expenditure_euros_achieved_q4');
            $table->integer('pr_total_organic_reach_achieved_q1')->default(0)->after('pr_total_organic_reach_goal_q1');
            $table->integer('pr_total_organic_reach_goal_q2')->default(0)->after('pr_total_organic_reach_achieved_q1');
            $table->integer('pr_total_organic_reach_achieved_q2')->default(0)->after('pr_total_organic_reach_goal_q2');
            $table->integer('pr_total_organic_reach_goal_q3')->default(0)->after('pr_total_organic_reach_achieved_q2');
            $table->integer('pr_total_organic_reach_achieved_q3')->default(0)->after('pr_total_organic_reach_goal_q3');
            $table->integer('pr_total_organic_reach_goal_q4')->default(0)->after('pr_total_organic_reach_achieved_q3');
            $table->integer('pr_total_organic_reach_achieved_q4')->default(0)->after('pr_total_organic_reach_goal_q4');

            $table->decimal('personal_fte_goal_q1', 8, 2)->default(0.00)->after('pr_total_organic_reach_achieved_q4');
            $table->decimal('personal_fte_achieved_q1', 8, 2)->default(0.00)->after('personal_fte_goal_q1');
            $table->decimal('personal_fte_goal_q2', 8, 2)->default(0.00)->after('personal_fte_achieved_q1');
            $table->decimal('personal_fte_achieved_q2', 8, 2)->default(0.00)->after('personal_fte_goal_q2');
            $table->decimal('personal_fte_goal_q3', 8, 2)->default(0.00)->after('personal_fte_achieved_q2');
            $table->decimal('personal_fte_achieved_q3', 8, 2)->default(0.00)->after('personal_fte_goal_q3');
            $table->decimal('personal_fte_goal_q4', 8, 2)->default(0.00)->after('personal_fte_achieved_q3');
            $table->decimal('personal_fte_achieved_q4', 8, 2)->default(0.00)->after('personal_fte_goal_q4');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Drop all Q2, Q3, Q4 columns
            $table->dropColumn([
                'languages_goal_q2', 'languages_achieved_q2',
                'languages_goal_q3', 'languages_achieved_q3',
                'languages_goal_q4', 'languages_achieved_q4',
                'volunteers_goal_q2', 'volunteers_achieved_q2',
                'volunteers_goal_q3', 'volunteers_achieved_q3',
                'volunteers_goal_q4', 'volunteers_achieved_q4',
                'volunteers_mentors_goal_q1', 'volunteers_mentors_achieved_q1',
                'volunteers_mentors_goal_q2', 'volunteers_mentors_achieved_q2',
                'volunteers_mentors_goal_q3', 'volunteers_mentors_achieved_q3',
                'volunteers_mentors_goal_q4', 'volunteers_mentors_achieved_q4',
                'volunteers_chatters_goal_q1', 'volunteers_chatters_achieved_q1',
                'volunteers_chatters_goal_q2', 'volunteers_chatters_achieved_q2',
                'volunteers_chatters_goal_q3', 'volunteers_chatters_achieved_q3',
                'volunteers_chatters_goal_q4', 'volunteers_chatters_achieved_q4',
                'volunteers_creators_goal_q1', 'volunteers_creators_achieved_q1',
                'volunteers_creators_goal_q2', 'volunteers_creators_achieved_q2',
                'volunteers_creators_goal_q3', 'volunteers_creators_achieved_q3',
                'volunteers_creators_goal_q4', 'volunteers_creators_achieved_q4',
                'evangelistic_students_goal_q1', 'evangelistic_students_achieved_q1',
                'evangelistic_students_goal_q2', 'evangelistic_students_achieved_q2',
                'evangelistic_students_goal_q3', 'evangelistic_students_achieved_q3',
                'evangelistic_students_goal_q4', 'evangelistic_students_achieved_q4',
                'discipleship_students_goal_q1', 'discipleship_students_achieved_q1',
                'discipleship_students_goal_q2', 'discipleship_students_achieved_q2',
                'discipleship_students_goal_q3', 'discipleship_students_achieved_q3',
                'discipleship_students_goal_q4', 'discipleship_students_achieved_q4',
                'leadership_students_goal_q1', 'leadership_students_achieved_q1',
                'leadership_students_goal_q2', 'leadership_students_achieved_q2',
                'leadership_students_goal_q3', 'leadership_students_achieved_q3',
                'leadership_students_goal_q4', 'leadership_students_achieved_q4',
                'evangelistic_conversations_goal_q1', 'evangelistic_conversations_achieved_q1',
                'evangelistic_conversations_goal_q2', 'evangelistic_conversations_achieved_q2',
                'evangelistic_conversations_goal_q3', 'evangelistic_conversations_achieved_q3',
                'evangelistic_conversations_goal_q4', 'evangelistic_conversations_achieved_q4',
                'pastoral_connections_goal_q1', 'pastoral_connections_achieved_q1',
                'pastoral_connections_goal_q2', 'pastoral_connections_achieved_q2',
                'pastoral_connections_goal_q3', 'pastoral_connections_achieved_q3',
                'pastoral_connections_goal_q4', 'pastoral_connections_achieved_q4',
                'facebook_reach_goal_q1', 'facebook_reach_achieved_q1',
                'facebook_reach_goal_q2', 'facebook_reach_achieved_q2',
                'facebook_reach_goal_q3', 'facebook_reach_achieved_q3',
                'facebook_reach_goal_q4', 'facebook_reach_achieved_q4',
                'instagram_reach_goal_q1', 'instagram_reach_achieved_q1',
                'instagram_reach_goal_q2', 'instagram_reach_achieved_q2',
                'instagram_reach_goal_q3', 'instagram_reach_achieved_q3',
                'instagram_reach_goal_q4', 'instagram_reach_achieved_q4',
                'youtube_reach_goal_q1', 'youtube_reach_achieved_q1',
                'youtube_reach_goal_q2', 'youtube_reach_achieved_q2',
                'youtube_reach_goal_q3', 'youtube_reach_achieved_q3',
                'youtube_reach_goal_q4', 'youtube_reach_achieved_q4',
                'website_reach_goal_q1', 'website_reach_achieved_q1',
                'website_reach_goal_q2', 'website_reach_achieved_q2',
                'website_reach_goal_q3', 'website_reach_achieved_q3',
                'website_reach_goal_q4', 'website_reach_achieved_q4',
                'income_euros_goal_q1', 'income_euros_achieved_q1',
                'income_euros_goal_q2', 'income_euros_achieved_q2',
                'income_euros_goal_q3', 'income_euros_achieved_q3',
                'income_euros_goal_q4', 'income_euros_achieved_q4',
                'expenditure_euros_goal_q1', 'expenditure_euros_achieved_q1',
                'expenditure_euros_goal_q2', 'expenditure_euros_achieved_q2',
                'expenditure_euros_goal_q3', 'expenditure_euros_achieved_q3',
                'expenditure_euros_goal_q4', 'expenditure_euros_achieved_q4',
                'pr_total_organic_reach_goal_q1', 'pr_total_organic_reach_achieved_q1',
                'pr_total_organic_reach_goal_q2', 'pr_total_organic_reach_achieved_q2',
                'pr_total_organic_reach_goal_q3', 'pr_total_organic_reach_achieved_q3',
                'pr_total_organic_reach_goal_q4', 'pr_total_organic_reach_achieved_q4',
                'personal_fte_goal_q1', 'personal_fte_achieved_q1',
                'personal_fte_goal_q2', 'personal_fte_achieved_q2',
                'personal_fte_goal_q3', 'personal_fte_achieved_q3',
                'personal_fte_goal_q4', 'personal_fte_achieved_q4',
            ]);
        });
    }
};
