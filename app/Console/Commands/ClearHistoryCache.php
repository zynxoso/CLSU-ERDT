<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearHistoryCache extends Command
{
    /**
     * Pangalan at signature ng console command
     *
     * @var string
     */
    protected $signature = 'history:clear-cache';

    /**
     * Paglalarawan ng console command
     *
     * @var string
     */
    protected $description = 'Clear lahat ng history-related cache entries';

    /**
     * I-execute ang console command
     */
    public function handle()
    {
        // mga cache keys na kailangan i-clear
        $cacheKeys = [
            'history_achievements',
            'history_hero_content',
            'history_intro_content',
            'history_vision_content',
            'history_contact_content'
        ];

        // i-loop at i-clear ang bawat cache key
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        // ipakita ang success message
        $this->info('History cache cleared successfully!');

        return Command::SUCCESS;
    }
}
