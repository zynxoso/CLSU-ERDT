<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\Auditable;
use App\Services\SystemSettingsService;
use Carbon\Carbon;

class User extends Authenticatable
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
        'email_notifications',
        'fund_request_notifications',
        'document_notifications',
        'manuscript_notifications',
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
        'password' => 'hashed',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'password_expires_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'must_change_password' => 'boolean',
        'is_default_password' => 'boolean',
        'email_notifications' => 'boolean',
        'fund_request_notifications' => 'boolean',
        'document_notifications' => 'boolean',
        'manuscript_notifications' => 'boolean',
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
        return $this->hasMany(CustomNotification::class);
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
    public function isPasswordExpired(?int $days = null): bool
    {
        if (!$this->password_changed_at) {
            return true;
        }

        $expiryDays = $days ?? SystemSettingsService::getPasswordExpiryDays();
        return Carbon::parse($this->password_changed_at)->addDays($expiryDays)->isPast();
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
     * Set password expiration date using dynamic settings.
     *
     * @param int|null $days Optional override for expiry days
     * @return void
     */
    public function setPasswordExpiration($days = null)
    {
        // Use provided days or get from system settings
        $expiryDays = $days ?? \App\Models\SiteSetting::get('password_expiry_days', 90);

        $this->password_expires_at = now()->addDays($expiryDays);
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

    /**
     * Get the active status (accessor for is_active).
     *
     * @return bool
     */
    public function getActiveAttribute()
    {
        return $this->is_active;
    }

    /**
     * Safely check if user has fund request notifications enabled
     * Falls back to true if the column doesn't exist yet
     */
    public function hasFundRequestNotifications(): bool
    {
        // If the attribute doesn't exist or user is admin, return true
        return $this->fund_request_notifications ?? ($this->role === 'admin');
    }

    /**
     * Safely check if user has email notifications enabled
     * Falls back to true if the column doesn't exist yet
     */
    public function hasEmailNotifications(): bool
    {
        // If the attribute doesn't exist or user is admin with email, return true
        return $this->email_notifications ?? ($this->role === 'admin' && !empty($this->email));
    }

    /**
     * Safely check if user has manuscript notifications enabled
     * Falls back to true if the column doesn't exist yet
     */
    public function hasManuscriptNotifications(): bool
    {
        // If the attribute doesn't exist, default to true
        return $this->manuscript_notifications ?? true;
    }

    /**
     * Get the user's unread notifications count.
     *
     * @return int
     */
    public function getUnreadNotificationsCount()
    {
        return $this->customNotifications()
            ->where('is_read', false)
            ->count();
    }

    /**
     * Get the user's recent notifications.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentNotifications($limit = 5)
    {
        return $this->customNotifications()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
