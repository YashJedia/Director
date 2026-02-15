<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
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
        'phone',
        'department',
        'job_title',
        'location',
        'bio',
        'avatar',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'is_invited',
        'invited_at',
        'password_set_at',
        'last_login_at',
        'status',
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_enabled' => 'boolean',
            'is_invited' => 'boolean',
            'invited_at' => 'datetime',
            'password_set_at' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Get the languages assigned to this user
     */
    public function assignedLanguages()
    {
        return $this->hasMany(Language::class, 'assigned_user_id');
    }

    /**
     * Get the reports created by this user
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Get the full avatar URL
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset($this->avatar);
        }
        return null;
    }

    /**
     * Check if user was invited
     */
    public function isInvited()
    {
        return $this->is_invited;
    }

    /**
     * Check if user has set their password
     */
    public function hasSetPassword()
    {
        return !is_null($this->password_set_at);
    }
}
