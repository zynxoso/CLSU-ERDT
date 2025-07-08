<?php

namespace App\Services;

use App\Models\SiteSetting;

class SystemSettingsService
{
    /**
     * Get password expiry days
     */
    public static function getPasswordExpiryDays(): int
    {
        return 90; // Default password expiry of 90 days
    }

    /**
     * Get session lifetime
     */
    public static function getSessionLifetime(): int
    {
        return env('SESSION_LIFETIME', 120); // Use environment variable with default of 120 minutes
    }

    /**
     * Check if HTTPS is forced
     */
    public static function isHttpsForced(): bool
    {
        return env('FORCE_HTTPS', true); // Use environment variable with default of true
    }

    /**
     * Get email configuration
     */
    public static function getEmailConfig(): array
    {
        return [
            'driver' => env('MAIL_MAILER', 'smtp'),
            'host' => env('MAIL_HOST', 'smtp.mailtrap.io'),
            'port' => env('MAIL_PORT', 2525),
            'username' => env('MAIL_USERNAME', ''),
            'password' => env('MAIL_PASSWORD', ''),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'from_address' => env('MAIL_FROM_ADDRESS', 'noreply@clsu.edu.ph'),
            'from_name' => env('MAIL_FROM_NAME', 'CLSU-ERDT System'),
        ];
    }

    /**
     * Apply email settings to the mail configuration
     */
    public static function applyEmailSettings(): void
    {
        $settings = self::getEmailConfig();

        config([
            'mail.default' => $settings['driver'],
            'mail.mailers.smtp.host' => $settings['host'],
            'mail.mailers.smtp.port' => $settings['port'],
            'mail.mailers.smtp.username' => $settings['username'],
            'mail.mailers.smtp.password' => $settings['password'],
            'mail.mailers.smtp.encryption' => $settings['encryption'],
            'mail.from.address' => $settings['from_address'],
            'mail.from.name' => $settings['from_name'],
        ]);
    }

    /**
     * Get password changed success message
     */
    public static function getPasswordChangedMessage(): string
    {
        $expiryDays = self::getPasswordExpiryDays();
        return "Password updated successfully. Your password will expire in {$expiryDays} days.";
    }
}
