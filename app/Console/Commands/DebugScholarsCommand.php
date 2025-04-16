<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\ScholarProfile;

class DebugScholarsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:scholars';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug scholar creation and database issues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Running Scholar Debug Command');

        // Create log file for diagnostic info
        $logPath = storage_path('logs/scholar_debug.log');
        $logMessage = "==== Scholar Debug Executed at " . now() . " ====\n";

        // Check critical tables existence
        $this->info('Checking database tables...');
        $logMessage .= "Database Tables Check:\n";

        $tables = ['users', 'scholar_profiles', 'password_reset_tokens', 'migrations'];

        foreach ($tables as $table) {
            $exists = Schema::hasTable($table);
            $this->line(" - Table '$table': " . ($exists ? 'Exists' : 'Missing'));
            $logMessage .= " - Table '$table': " . ($exists ? 'Exists' : 'Missing') . "\n";

            if ($exists) {
                // Count records
                $count = DB::table($table)->count();
                $this->line("   Record count: $count");
                $logMessage .= "   Record count: $count\n";
            }
        }

        // Check user roles
        $this->info('Checking user roles...');
        $logMessage .= "\nUser Roles Check:\n";

        $users = User::all();
        $adminCount = 0;
        $scholarCount = 0;
        $otherCount = 0;
        $userDetails = [];

        foreach ($users as $user) {
            if ($user->role === 'admin') {
                $adminCount++;
            } elseif ($user->role === 'scholar') {
                $scholarCount++;
            } else {
                $otherCount++;
            }

            $userDetails[] = [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'role' => $user->role,
                'created_at' => $user->created_at
            ];
        }

        $this->line(" - Admin users: $adminCount");
        $this->line(" - Scholar users: $scholarCount");
        $this->line(" - Other roles: $otherCount");

        $logMessage .= " - Admin users: $adminCount\n";
        $logMessage .= " - Scholar users: $scholarCount\n";
        $logMessage .= " - Other roles: $otherCount\n";
        $logMessage .= " - User details: " . json_encode($userDetails, JSON_PRETTY_PRINT) . "\n";

        // Check scholar profiles
        $this->info('Checking scholar profiles...');
        $logMessage .= "\nScholar Profiles Check:\n";

        $scholarProfiles = ScholarProfile::all();
        $this->line(" - Total scholar profiles: " . $scholarProfiles->count());
        $logMessage .= " - Total scholar profiles: " . $scholarProfiles->count() . "\n";

        // Check for orphaned profiles (no associated user)
        $orphanedProfiles = 0;
        foreach ($scholarProfiles as $profile) {
            if (!$profile->user) {
                $orphanedProfiles++;
                $this->warn(" - Orphaned profile found: ID {$profile->id}");
                $logMessage .= " - Orphaned profile found: ID {$profile->id}\n";
            }
        }

        $this->line(" - Orphaned profiles: $orphanedProfiles");
        $logMessage .= " - Orphaned profiles: $orphanedProfiles\n";

        // Check for users with 'scholar' role but no profile
        $scholarsWithoutProfiles = 0;
        foreach ($users as $user) {
            if ($user->role === 'scholar' && !$user->scholarProfile) {
                $scholarsWithoutProfiles++;
                $this->warn(" - Scholar without profile found: ID {$user->id}, Email: {$user->email}");
                $logMessage .= " - Scholar without profile found: ID {$user->id}, Email: {$user->email}\n";
            }
        }

        $this->line(" - Scholars without profiles: $scholarsWithoutProfiles");
        $logMessage .= " - Scholars without profiles: $scholarsWithoutProfiles\n";

        // Write log
        file_put_contents($logPath, $logMessage, FILE_APPEND);
        $this->info("Debug information logged to: $logPath");

        return Command::SUCCESS;
    }
}
