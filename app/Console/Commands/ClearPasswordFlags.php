<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ClearPasswordFlags extends Command
{
    /**
     * Pangalan at signature ng console command.
     *
     * @var string
     */
    protected $signature = 'password:clear-flags {--user= : Clear flags para sa specific user email} {--all : Clear flags para sa lahat ng users}';

    /**
     * Description ng console command.
     *
     * @var string
     */
    protected $description = 'Clear ng password flags (is_default_password at must_change_password) para sa mga users';

    /**
     * Execute ng console command.
     */
    public function handle()
    {
        if ($this->option('all')) {
            // Kumuha ng lahat ng users na may password flags na naka-set
            $users = User::where('is_default_password', true)
                        ->orWhere('must_change_password', true)
                        ->get();

            if ($users->isEmpty()) {
                $this->info('Walang users na nakita na may password flags na naka-set.');
                return;
            }

            // Clear ng flags para sa bawat user
            foreach ($users as $user) {
                $user->is_default_password = false;
                $user->must_change_password = false;
                $user->save();
            }

            $this->info("Na-clear na ang password flags para sa {$users->count()} users.");
        } elseif ($this->option('user')) {
            $email = $this->option('user');
            // Hanapin ang user gamit ang email
            $user = User::where('email', $email)->first();

            if (!$user) {
                $this->error("Hindi nakita ang user na may email '{$email}'.");
                return;
            }

            // Clear ng password flags para sa specific user
            $user->is_default_password = false;
            $user->must_change_password = false;
            $user->save();

            $this->info("Na-clear na ang password flags para sa user: {$user->email}");
        } else {
            // Error message kung walang option na na-specify
            $this->error('Mag-specify ng --user=email o --all option.');
            $this->line('Mga halimbawa:');
            $this->line('  php artisan password:clear-flags --user=scholar@example.com');
            $this->line('  php artisan password:clear-flags --all');
        }
    }
}
