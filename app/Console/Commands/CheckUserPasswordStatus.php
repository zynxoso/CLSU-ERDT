<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckUserPasswordStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:password-status {email} {--reset : Reset password flags}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and optionally reset a user\'s password status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $reset = $this->option('reset');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email '{$email}' not found.");
            return 1;
        }

        $this->info("User Password Status for: {$user->name} ({$user->email})");
        $this->info("Role: {$user->role}");
        $this->line("─────────────────────────────────────");

        $this->line("must_change_password: " . ($user->must_change_password ? 'TRUE' : 'FALSE'));
        $this->line("is_default_password: " . ($user->is_default_password ? 'TRUE' : 'FALSE'));
        $this->line("password_expires_at: " . ($user->password_expires_at ? $user->password_expires_at->format('Y-m-d H:i:s') : 'NULL'));
        $this->line("password_changed_at: " . ($user->password_changed_at ? $user->password_changed_at->format('Y-m-d H:i:s') : 'NULL'));

        if ($user->password_expires_at) {
            $daysUntilExpiry = now()->diffInDays($user->password_expires_at, false);
            if ($daysUntilExpiry < 0) {
                $this->warn("Password EXPIRED " . abs($daysUntilExpiry) . " days ago");
            } else {
                $this->info("Password expires in {$daysUntilExpiry} days");
            }
        }

        if ($reset) {
            $user->must_change_password = false;
            $user->is_default_password = false;
            $user->password_expires_at = now()->addDays(90);
            $user->password_changed_at = now();
            $user->save();

            $this->success("Password flags reset successfully!");
            $this->info("New expiration date: " . $user->password_expires_at->format('Y-m-d H:i:s'));
        }

        return 0;
    }
}
