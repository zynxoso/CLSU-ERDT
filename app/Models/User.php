<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\Auditable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'last_login_at',
        'last_login_ip',
        'password_expires_at',
        'password_changed_at',
        'must_change_password',
        'is_default_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'password_expires_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'must_change_password' => 'boolean',
        'is_default_password' => 'boolean',
    ];

    /**
     * Get the scholar profile associated with the user.
     */
    public function scholarProfile()
    {
        return $this->hasOne(ScholarProfile::class);
    }

    /**
     * Get the custom notifications for the user.
     */
    public function customNotifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the audit logs for the user.
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin' || $this->isSuperAdmin();
    }

    /**
     * Check if the user is a super admin.
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if the user is a scholar.
     *
     * @return bool
     */
    public function isScholar()
    {
        // return $this->role === 'scholar';
        return $this->scholarProfile !== null;
    }

    /**
     * Get the unread notifications count for the user.
     *
     * @return int
     */
    public function getUnreadNotificationsCountAttribute()
    {
        return $this->customNotifications()->where('is_read', false)->count();
    }

    /**
     * Check if the user's password has expired.
     *
     * @return bool
     */
    public function isPasswordExpired()
    {
        if (!$this->password_expires_at) {
            return false;
        }

        return now()->isAfter($this->password_expires_at);
    }

    /**
     * Check if the user's password will expire soon (within 7 days).
     *
     * @return bool
     */
    public function isPasswordExpiringSoon()
    {
        if (!$this->password_expires_at) {
            return false;
        }

        return now()->addDays(7)->isAfter($this->password_expires_at) && !$this->isPasswordExpired();
    }

    /**
     * Set password expiration date (90 days from now by default).
     *
     * @param int $days
     * @return void
     */
    public function setPasswordExpiration($days = 90)
    {
        $this->password_expires_at = now()->addDays($days);
        $this->password_changed_at = now();
        $this->must_change_password = false;
        $this->is_default_password = false;
        $this->save();
    }

    /**
     * Mark password as requiring change.
     *
     * @return void
     */
    public function requirePasswordChange()
    {
        $this->must_change_password = true;
        $this->save();
    }

    /**
     * Get days until password expires.
     *
     * @return int|null
     */
    public function getDaysUntilPasswordExpires()
    {
        if (!$this->password_expires_at) {
            return null;
        }

        return now()->diffInDays($this->password_expires_at, false);
    }

}
