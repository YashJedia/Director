<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'quarter',
        'user_id',
        'language_id',
        'status',
        'score',
        'admin_feedback',
        'admin_remarks',
        'reviewed_at',
        'reviewed_by',
        'review_status',
        'revision_requested',
        'revision_requested_at',
        'revision_reason',
        'submitted_to_super_admin',
        'submitted_to_super_admin_at',
        'submitted_to_super_admin_by',
        'languages_previous_year',
        'languages_goal_2025',
        'languages_goal_q1', 'languages_achieved_q1',
        'languages_goal_q2', 'languages_achieved_q2',
        'languages_goal_q3', 'languages_achieved_q3',
        'languages_goal_q4', 'languages_achieved_q4',
        'volunteers_previous_year',
        'volunteers_goal_year',
        'volunteers_goal_2025',
        'volunteers_goal_q1', 'volunteers_achieved_q1',
        'volunteers_goal_q2', 'volunteers_achieved_q2',
        'volunteers_goal_q3', 'volunteers_achieved_q3',
        'volunteers_goal_q4', 'volunteers_achieved_q4',
        'volunteers_mentors_goal_year',
        'volunteers_mentors_goal_q1', 'volunteers_mentors_achieved_q1',
        'volunteers_mentors_goal_q2', 'volunteers_mentors_achieved_q2',
        'volunteers_mentors_goal_q3', 'volunteers_mentors_achieved_q3',
        'volunteers_mentors_goal_q4', 'volunteers_mentors_achieved_q4',
        'volunteers_chatters_goal_year',
        'volunteers_chatters_goal_q1', 'volunteers_chatters_achieved_q1',
        'volunteers_chatters_goal_q2', 'volunteers_chatters_achieved_q2',
        'volunteers_chatters_goal_q3', 'volunteers_chatters_achieved_q3',
        'volunteers_chatters_goal_q4', 'volunteers_chatters_achieved_q4',
        'volunteers_creators_goal_year',
        'volunteers_creators_goal_q1', 'volunteers_creators_achieved_q1',
        'volunteers_creators_goal_q2', 'volunteers_creators_achieved_q2',
        'volunteers_creators_goal_q3', 'volunteers_creators_achieved_q3',
        'volunteers_creators_goal_q4', 'volunteers_creators_achieved_q4',
        'evangelistic_students_goal_year',
        'evangelistic_students_goal_q1', 'evangelistic_students_achieved_q1',
        'evangelistic_students_goal_q2', 'evangelistic_students_achieved_q2',
        'evangelistic_students_goal_q3', 'evangelistic_students_achieved_q3',
        'evangelistic_students_goal_q4', 'evangelistic_students_achieved_q4',
        'discipleship_students_goal_year',
        'discipleship_students_goal_q1', 'discipleship_students_achieved_q1',
        'discipleship_students_goal_q2', 'discipleship_students_achieved_q2',
        'discipleship_students_goal_q3', 'discipleship_students_achieved_q3',
        'discipleship_students_goal_q4', 'discipleship_students_achieved_q4',
        'leadership_students_goal_year',
        'leadership_students_goal_q1', 'leadership_students_achieved_q1',
        'leadership_students_goal_q2', 'leadership_students_achieved_q2',
        'leadership_students_goal_q3', 'leadership_students_achieved_q3',
        'leadership_students_goal_q4', 'leadership_students_achieved_q4',
        'evangelistic_conversations_goal_year',
        'evangelistic_conversations_goal_q1', 'evangelistic_conversations_achieved_q1',
        'evangelistic_conversations_goal_q2', 'evangelistic_conversations_achieved_q2',
        'evangelistic_conversations_goal_q3', 'evangelistic_conversations_achieved_q3',
        'evangelistic_conversations_goal_q4', 'evangelistic_conversations_achieved_q4',
        'pastoral_connections_goal_year',
        'pastoral_connections_goal_q1', 'pastoral_connections_achieved_q1',
        'pastoral_connections_goal_q2', 'pastoral_connections_achieved_q2',
        'pastoral_connections_goal_q3', 'pastoral_connections_achieved_q3',
        'pastoral_connections_goal_q4', 'pastoral_connections_achieved_q4',
        'facebook_reach_goal_year',
        'facebook_reach_goal_q1', 'facebook_reach_achieved_q1',
        'facebook_reach_goal_q2', 'facebook_reach_achieved_q2',
        'facebook_reach_goal_q3', 'facebook_reach_achieved_q3',
        'facebook_reach_goal_q4', 'facebook_reach_achieved_q4',
        'instagram_reach_goal_year',
        'instagram_reach_goal_q1', 'instagram_reach_achieved_q1',
        'instagram_reach_goal_q2', 'instagram_reach_achieved_q2',
        'instagram_reach_goal_q3', 'instagram_reach_achieved_q3',
        'instagram_reach_goal_q4', 'instagram_reach_achieved_q4',
        'youtube_reach_goal_year',
        'youtube_reach_goal_q1', 'youtube_reach_achieved_q1',
        'youtube_reach_goal_q2', 'youtube_reach_achieved_q2',
        'youtube_reach_goal_q3', 'youtube_reach_achieved_q3',
        'youtube_reach_goal_q4', 'youtube_reach_achieved_q4',
        'website_reach_goal_year',
        'website_reach_goal_q1', 'website_reach_achieved_q1',
        'website_reach_goal_q2', 'website_reach_achieved_q2',
        'website_reach_goal_q3', 'website_reach_achieved_q3',
        'website_reach_goal_q4', 'website_reach_achieved_q4',
        'income_euros_goal_year',
        'income_euros_goal_q1', 'income_euros_achieved_q1',
        'income_euros_goal_q2', 'income_euros_achieved_q2',
        'income_euros_goal_q3', 'income_euros_achieved_q3',
        'income_euros_goal_q4', 'income_euros_achieved_q4',
        'expenditure_euros_goal_year',
        'expenditure_euros_goal_q1', 'expenditure_euros_achieved_q1',
        'expenditure_euros_goal_q2', 'expenditure_euros_achieved_q2',
        'expenditure_euros_goal_q3', 'expenditure_euros_achieved_q3',
        'expenditure_euros_goal_q4', 'expenditure_euros_achieved_q4',
        'pr_total_organic_reach_goal_year',
        'pr_total_organic_reach_goal_q1', 'pr_total_organic_reach_achieved_q1',
        'pr_total_organic_reach_goal_q2', 'pr_total_organic_reach_achieved_q2',
        'pr_total_organic_reach_goal_q3', 'pr_total_organic_reach_achieved_q3',
        'pr_total_organic_reach_goal_q4', 'pr_total_organic_reach_achieved_q4',
        'personal_fte_goal_year',
        'personal_fte_goal_q1', 'personal_fte_achieved_q1',
        'personal_fte_goal_q2', 'personal_fte_achieved_q2',
        'personal_fte_goal_q3', 'personal_fte_achieved_q3',
        'personal_fte_goal_q4', 'personal_fte_achieved_q4',
        'volunteers_chatters',
        'volunteers_mentors',
        'volunteers_content_creators',
        'volunteers_others',
        'facebook_reach',
        'instagram_reach',
        'youtube_reach',
        'website_reach',
        'evangelistic_students',
        'discipleship_students',
        'leadership_students',
        'evangelistic_conversations',
        'pastoral_connections',
        'income_euros',
        'expenditure_euros',
        'income_from_fundraising_euros',
        'number_of_supporters',
        'pr_total_organic_reach',
        'personal_fte',
        'new_activity',
        'organizational_highlight',
        'organizational_concern',
        'organizational_issues',
    ];

    protected $casts = [
        'score' => 'integer',
        'reviewed_at' => 'datetime',
        'revision_requested' => 'boolean',
        'revision_requested_at' => 'datetime',
        'submitted_to_super_admin' => 'boolean',
        'submitted_to_super_admin_at' => 'datetime',
        'languages_previous_year' => 'integer',
        'languages_goal_year' => 'integer',
        'languages_goal_2025' => 'integer',
        'languages_goal_q1' => 'integer', 'languages_achieved_q1' => 'integer',
        'languages_goal_q2' => 'integer', 'languages_achieved_q2' => 'integer',
        'languages_goal_q3' => 'integer', 'languages_achieved_q3' => 'integer',
        'languages_goal_q4' => 'integer', 'languages_achieved_q4' => 'integer',
        'volunteers_previous_year' => 'integer',
        'volunteers_goal_year' => 'integer',
        'volunteers_goal_2025' => 'integer',
        'volunteers_goal_q1' => 'integer', 'volunteers_achieved_q1' => 'integer',
        'volunteers_goal_q2' => 'integer', 'volunteers_achieved_q2' => 'integer',
        'volunteers_goal_q3' => 'integer', 'volunteers_achieved_q3' => 'integer',
        'volunteers_goal_q4' => 'integer', 'volunteers_achieved_q4' => 'integer',
        'volunteers_mentors_goal_year' => 'integer',
        'volunteers_mentors_goal_q1' => 'integer', 'volunteers_mentors_achieved_q1' => 'integer',
        'volunteers_mentors_goal_q2' => 'integer', 'volunteers_mentors_achieved_q2' => 'integer',
        'volunteers_mentors_goal_q3' => 'integer', 'volunteers_mentors_achieved_q3' => 'integer',
        'volunteers_mentors_goal_q4' => 'integer', 'volunteers_mentors_achieved_q4' => 'integer',
        'volunteers_chatters_goal_year' => 'integer',
        'volunteers_chatters_goal_q1' => 'integer', 'volunteers_chatters_achieved_q1' => 'integer',
        'volunteers_chatters_goal_q2' => 'integer', 'volunteers_chatters_achieved_q2' => 'integer',
        'volunteers_chatters_goal_q3' => 'integer', 'volunteers_chatters_achieved_q3' => 'integer',
        'volunteers_chatters_goal_q4' => 'integer', 'volunteers_chatters_achieved_q4' => 'integer',
        'volunteers_creators_goal_year' => 'integer',
        'volunteers_creators_goal_q1' => 'integer', 'volunteers_creators_achieved_q1' => 'integer',
        'volunteers_creators_goal_q2' => 'integer', 'volunteers_creators_achieved_q2' => 'integer',
        'volunteers_creators_goal_q3' => 'integer', 'volunteers_creators_achieved_q3' => 'integer',
        'volunteers_creators_goal_q4' => 'integer', 'volunteers_creators_achieved_q4' => 'integer',
        'evangelistic_students_goal_year' => 'integer',
        'evangelistic_students_goal_q1' => 'integer', 'evangelistic_students_achieved_q1' => 'integer',
        'evangelistic_students_goal_q2' => 'integer', 'evangelistic_students_achieved_q2' => 'integer',
        'evangelistic_students_goal_q3' => 'integer', 'evangelistic_students_achieved_q3' => 'integer',
        'evangelistic_students_goal_q4' => 'integer', 'evangelistic_students_achieved_q4' => 'integer',
        'discipleship_students_goal_year' => 'integer',
        'discipleship_students_goal_q1' => 'integer', 'discipleship_students_achieved_q1' => 'integer',
        'discipleship_students_goal_q2' => 'integer', 'discipleship_students_achieved_q2' => 'integer',
        'discipleship_students_goal_q3' => 'integer', 'discipleship_students_achieved_q3' => 'integer',
        'discipleship_students_goal_q4' => 'integer', 'discipleship_students_achieved_q4' => 'integer',
        'leadership_students_goal_year' => 'integer',
        'leadership_students_goal_q1' => 'integer', 'leadership_students_achieved_q1' => 'integer',
        'leadership_students_goal_q2' => 'integer', 'leadership_students_achieved_q2' => 'integer',
        'leadership_students_goal_q3' => 'integer', 'leadership_students_achieved_q3' => 'integer',
        'leadership_students_goal_q4' => 'integer', 'leadership_students_achieved_q4' => 'integer',
        'evangelistic_conversations_goal_year' => 'integer',
        'evangelistic_conversations_goal_q1' => 'integer', 'evangelistic_conversations_achieved_q1' => 'integer',
        'evangelistic_conversations_goal_q2' => 'integer', 'evangelistic_conversations_achieved_q2' => 'integer',
        'evangelistic_conversations_goal_q3' => 'integer', 'evangelistic_conversations_achieved_q3' => 'integer',
        'evangelistic_conversations_goal_q4' => 'integer', 'evangelistic_conversations_achieved_q4' => 'integer',
        'pastoral_connections_goal_year' => 'integer',
        'pastoral_connections_goal_q1' => 'integer', 'pastoral_connections_achieved_q1' => 'integer',
        'pastoral_connections_goal_q2' => 'integer', 'pastoral_connections_achieved_q2' => 'integer',
        'pastoral_connections_goal_q3' => 'integer', 'pastoral_connections_achieved_q3' => 'integer',
        'pastoral_connections_goal_q4' => 'integer', 'pastoral_connections_achieved_q4' => 'integer',
        'facebook_reach_goal_year' => 'integer',
        'facebook_reach_goal_q1' => 'integer', 'facebook_reach_achieved_q1' => 'integer',
        'facebook_reach_goal_q2' => 'integer', 'facebook_reach_achieved_q2' => 'integer',
        'facebook_reach_goal_q3' => 'integer', 'facebook_reach_achieved_q3' => 'integer',
        'facebook_reach_goal_q4' => 'integer', 'facebook_reach_achieved_q4' => 'integer',
        'instagram_reach_goal_year' => 'integer',
        'instagram_reach_goal_q1' => 'integer', 'instagram_reach_achieved_q1' => 'integer',
        'instagram_reach_goal_q2' => 'integer', 'instagram_reach_achieved_q2' => 'integer',
        'instagram_reach_goal_q3' => 'integer', 'instagram_reach_achieved_q3' => 'integer',
        'instagram_reach_goal_q4' => 'integer', 'instagram_reach_achieved_q4' => 'integer',
        'youtube_reach_goal_year' => 'integer',
        'youtube_reach_goal_q1' => 'integer', 'youtube_reach_achieved_q1' => 'integer',
        'youtube_reach_goal_q2' => 'integer', 'youtube_reach_achieved_q2' => 'integer',
        'youtube_reach_goal_q3' => 'integer', 'youtube_reach_achieved_q3' => 'integer',
        'youtube_reach_goal_q4' => 'integer', 'youtube_reach_achieved_q4' => 'integer',
        'website_reach_goal_year' => 'integer',
        'website_reach_goal_q1' => 'integer', 'website_reach_achieved_q1' => 'integer',
        'website_reach_goal_q2' => 'integer', 'website_reach_achieved_q2' => 'integer',
        'website_reach_goal_q3' => 'integer', 'website_reach_achieved_q3' => 'integer',
        'website_reach_goal_q4' => 'integer', 'website_reach_achieved_q4' => 'integer',
        'income_euros_goal_year' => 'decimal:2',
        'income_euros_goal_q1' => 'decimal:2', 'income_euros_achieved_q1' => 'decimal:2',
        'income_euros_goal_q2' => 'decimal:2', 'income_euros_achieved_q2' => 'decimal:2',
        'income_euros_goal_q3' => 'decimal:2', 'income_euros_achieved_q3' => 'decimal:2',
        'income_euros_goal_q4' => 'decimal:2', 'income_euros_achieved_q4' => 'decimal:2',
        'expenditure_euros_goal_year' => 'decimal:2',
        'expenditure_euros_goal_q1' => 'decimal:2', 'expenditure_euros_achieved_q1' => 'decimal:2',
        'expenditure_euros_goal_q2' => 'decimal:2', 'expenditure_euros_achieved_q2' => 'decimal:2',
        'expenditure_euros_goal_q3' => 'decimal:2', 'expenditure_euros_achieved_q3' => 'decimal:2',
        'expenditure_euros_goal_q4' => 'decimal:2', 'expenditure_euros_achieved_q4' => 'decimal:2',
        'pr_total_organic_reach_goal_year' => 'integer',
        'pr_total_organic_reach_goal_q1' => 'integer', 'pr_total_organic_reach_achieved_q1' => 'integer',
        'pr_total_organic_reach_goal_q2' => 'integer', 'pr_total_organic_reach_achieved_q2' => 'integer',
        'pr_total_organic_reach_goal_q3' => 'integer', 'pr_total_organic_reach_achieved_q3' => 'integer',
        'pr_total_organic_reach_goal_q4' => 'integer', 'pr_total_organic_reach_achieved_q4' => 'integer',
        'personal_fte_goal_year' => 'decimal:2',
        'personal_fte_goal_q1' => 'decimal:2', 'personal_fte_achieved_q1' => 'decimal:2',
        'personal_fte_goal_q2' => 'decimal:2', 'personal_fte_achieved_q2' => 'decimal:2',
        'personal_fte_goal_q3' => 'decimal:2', 'personal_fte_achieved_q3' => 'decimal:2',
        'personal_fte_goal_q4' => 'decimal:2', 'personal_fte_achieved_q4' => 'decimal:2',
        'volunteers_chatters' => 'integer',
        'volunteers_mentors' => 'integer',
        'volunteers_content_creators' => 'integer',
        'volunteers_others' => 'integer',
        'facebook_reach' => 'integer',
        'instagram_reach' => 'integer',
        'youtube_reach' => 'integer',
        'website_reach' => 'integer',
        'evangelistic_students' => 'integer',
        'discipleship_students' => 'integer',
        'leadership_students' => 'integer',
        'evangelistic_conversations' => 'integer',
        'pastoral_connections' => 'integer',
        'income_euros' => 'decimal:2',
        'expenditure_euros' => 'decimal:2',
        'income_from_fundraising_euros' => 'decimal:2',
        'number_of_supporters' => 'integer',
        'pr_total_organic_reach' => 'integer',
        'personal_fte' => 'decimal:1',
    ];

    /**
     * Get the user that owns the report
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the language associated with the report
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * Scope to get reports by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get reports by language
     */
    public function scopeByLanguage($query, $languageId)
    {
        return $query->where('language_id', $languageId);
    }

    /**
     * Scope to get reports by quarter
     */
    public function scopeByQuarter($query, $quarter)
    {
        return $query->where('quarter', $quarter);
    }

    /**
     * Scope to get reports by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get the admin who reviewed this report
     */
    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    /**
     * Get the admin who submitted this report to super admin
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'submitted_to_super_admin_by');
    }

    /**
     * Get comments for this report
     */
    public function comments()
    {
        return $this->hasMany(ReportComment::class);
    }

    /**
     * Scope to get reports by review status
     */
    public function scopeByReviewStatus($query, $status)
    {
        return $query->where('review_status', $status);
    }
}
