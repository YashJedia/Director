<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AdminInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'token',
        'invited_by',
        'status',
        'expires_at',
        'accepted_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invitation) {
            if (empty($invitation->token)) {
                $invitation->token = Str::random(64);
            }
            if (empty($invitation->expires_at)) {
                $invitation->expires_at = now()->addDays(7); // 7 days expiry
            }
        });
    }

    /**
     * Get the super admin who sent the invitation
     */
    public function inviter()
    {
        return $this->belongsTo(Admin::class, 'invited_by');
    }

    /**
     * Check if invitation is expired
     */
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if invitation is valid
     */
    public function isValid()
    {
        return $this->status === 'pending' && !$this->isExpired();
    }

    /**
     * Scope to get valid invitations
     */
    public function scopeValid($query)
    {
        return $query->where('status', 'pending')
                    ->where('expires_at', '>', now());
    }
}
