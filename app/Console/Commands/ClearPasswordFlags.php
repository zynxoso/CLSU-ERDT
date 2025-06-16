<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ClearPasswordFlags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password:clear-flags {--user= : Clear flags for specific user email} {--all : Clear flags for all users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear password flags (is_default_password and must_change_password) for users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('all')) {
            $users = User::where('is_default_password', true)
                        ->orWhere('must_change_password', true)
                        ->get();

            if ($users->isEmpty()) {
                $this->info('No users found with password flags set.');
                return;
            }

            foreach ($users as $user) {
                $user->is_default_password = false;
                $user->must_change_password = false;
                $user->save();
            }

            $this->info("Cleared password flags for {$users->count()} users.");
        } elseif ($this->option('user')) {
            $email = $this->option('user');
            $user = User::where('email', $email)->first();

            if (!$user) {
                $this->error("User with email '{$email}' not found.");
                return;
            }

            $user->is_default_password = false;
            $user->must_change_password = false;
            $user->save();

            $this->info("Cleared password flags for user: {$user->email}");
        } else {
            $this->error('Please specify either --user=email or --all option.');
            $this->line('Examples:');
            $this->line('  php artisan password:clear-flags --user=scholar@example.com');
            $this->line('  php artisan password:clear-flags --all');
        }
    }
}
