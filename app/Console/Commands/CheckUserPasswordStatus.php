<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckUserPasswordStatus extends Command
{
    /**
     * pangalan at signature ng console command
     *
     * @var string
     */
    protected $signature = 'user:password-status {email} {--reset : Reset password flags}';

    /**
     * paglalarawan ng console command
     *
     * @var string
     */
    protected $description = 'Check and optionally reset a user\'s password status';

    /**
     * pagpapatupad ng console command
     */
    public function handle()
    {
        $email = $this->argument('email');
        $reset = $this->option('reset');

        // paghahanap ng user gamit ang email
        $user = User::where('email', $email)->first();

        // validation kung may nahanap na user
        if (!$user) {
            $this->error("User with email '{$email}' not found.");
            return 1;
        }

        // pagpapakita ng infomation ng user
        $this->info("User Password Status for: {$user->name} ({$user->email})");
        $this->info("Role: {$user->role}");
        $this->line("─────────────────────────────────────");

        // pagpapakita ng password status fields
        $this->line("must_change_password: " . ($user->must_change_password ? 'TRUE' : 'FALSE'));
        $this->line("is_default_password: " . ($user->is_default_password ? 'TRUE' : 'FALSE'));
        $this->line("password_expires_at: " . ($user->password_expires_at ? $user->password_expires_at->format('Y-m-d H:i:s') : 'NULL'));
        $this->line("password_changed_at: " . ($user->password_changed_at ? $user->password_changed_at->format('Y-m-d H:i:s') : 'NULL'));

        // pagkalkula ng mga araw bago mag-expire ang password
        if ($user->password_expires_at) {
            $daysUntilExpiry = now()->diffInDays($user->password_expires_at, false);
            if ($daysUntilExpiry < 0) {
                $this->warn("Password EXPIRED " . abs($daysUntilExpiry) . " days ago");
            } else {
                $this->info("Password expires in {$daysUntilExpiry} days");
            }
        }

        // pag-reset ng password flags kung may --reset option
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
