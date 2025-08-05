<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class WarmCacheCommand extends Command
{
    /**
     * Pangalan at signature ng console command.
     *
     * @var string
     */
    protected $signature = 'app:warm-cache-command';

    /**
     * Description ng console command.
     *
     * @var string
     */
    protected $description = 'Command para sa pag-warm ng cache';

    /**
     * I-execute ang console command.
     */
    public function handle()
    {
        
    }
}
