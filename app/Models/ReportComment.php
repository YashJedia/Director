<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'admin_id',
        'section',
        'field',
        'comment',
        'is_reply',
        'parent_comment_id',
    ];

    protected $casts = [
        'is_reply' => 'boolean',
    ];

    /**
     * Get the report that owns the comment
     */
    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Get the admin who made the comment
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Get the parent comment if this is a reply
     */
    public function parentComment()
    {
        return $this->belongsTo(ReportComment::class, 'parent_comment_id');
    }

    /**
     * Get replies to this comment
     */
    public function replies()
    {
        return $this->hasMany(ReportComment::class, 'parent_comment_id');
    }
}