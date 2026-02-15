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
        'languages_previous_year',
        'languages_goal_2025',
        'languages_goal_q1',
        'languages_achieved_q1',
        'volunteers_previous_year',
        'volunteers_goal_2025',
        'volunteers_goal_q1',
        'volunteers_achieved_q1',
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
        'languages_previous_year' => 'integer',
        'languages_goal_2025' => 'integer',
        'languages_goal_q1' => 'integer',
        'languages_achieved_q1' => 'integer',
        'volunteers_previous_year' => 'integer',
        'volunteers_goal_2025' => 'integer',
        'volunteers_goal_q1' => 'integer',
        'volunteers_achieved_q1' => 'integer',
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
