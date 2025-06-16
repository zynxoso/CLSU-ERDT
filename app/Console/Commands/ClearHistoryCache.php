<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearHistoryCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'history:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all history-related cache entries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cacheKeys = [
            'history_timeline',
            'history_achievements',
            'history_hero_content',
            'history_intro_content',
            'history_vision_content',
            'history_contact_content'
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        $this->info('History cache cleared successfully!');

        return Command::SUCCESS;
    }
}
