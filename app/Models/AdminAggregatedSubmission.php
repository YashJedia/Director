<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminAggregatedSubmission extends Model
{
    protected $fillable = [
        'admin_id',
        'quarter',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
