<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearExpiredSessions extends Command
{
    /**
     * Pangalan at signature ng console command.
     *
     * @var string
     */
    protected $signature = 'session:clear-expired {--force : Force clear lahat ng sessions}';

    /**
     * Description ng console command.
     *
     * @var string
     */
    protected $description = 'Clear expired sessions at optional na lahat ng sessions';

    /**
     * Execute ng console command.
     */
    public function handle()
    {
        if ($this->option('force')) {
            // Clear lahat ng session files
            $sessionPath = storage_path('framework/sessions');
            $files = glob($sessionPath . '/*');
            $cleared = 0;
            
            foreach ($files as $file) {
                if (is_file($file) && basename($file) !== '.gitignore') {
                    unlink($file);
                    $cleared++;
                }
            }
            
            $this->info("Na-clear na {$cleared} session files.");
        } else {
            // Clear expired sessions base sa session lifetime
            $sessionLifetime = config('session.lifetime') * 60; // Convert minutes to seconds
            $sessionPath = storage_path('framework/sessions');
            $files = glob($sessionPath . '/*');
            $cleared = 0;
            
            foreach ($files as $file) {
                if (is_file($file) && basename($file) !== '.gitignore') {
                    if (filemtime($file) < (time() - $sessionLifetime)) {
                        unlink($file);
                        $cleared++;
                    }
                }
            }
            
            $this->info("Na-clear na {$cleared} expired session files.");
        }
        
        return 0;
    }
}
