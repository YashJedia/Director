<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'two_factor_enabled' => 'boolean',
        ];
    }

    /**
     * Get the reports reviewed by this admin
     */
    public function reviewedReports()
    {
        return $this->hasMany(Report::class, 'reviewed_by');
    }

    /**
     * Get the comments made by this admin
     */
    public function comments()
    {
        return $this->hasMany(ReportComment::class);
    }

    /**
     * Get the user invitations sent by this admin
     */
    public function invitations()
    {
        return $this->hasMany(UserInvitation::class, 'invited_by');
    }

    /**
     * Get the admin invitations sent by this super admin
     */
    public function adminInvitations()
    {
        return $this->hasMany(AdminInvitation::class, 'invited_by');
    }

    /**
     * Get the languages assigned to this admin
     */
    public function assignedLanguages()
    {
        return $this->hasMany(Language::class, 'assigned_admin_id');
    }

    /**
     * Check if admin is super admin
     */
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if admin is regular admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if admin can delete accounts
     */
    public function canDeleteAccounts()
    {
        return $this->role !== 'super_admin';
    }
}
