<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'assigned_user_id',
        'assigned_admin_id',
        'description'
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'assigned_admin_id');
    }

    /**
     * Scope to get active languages
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get inactive languages
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
} 