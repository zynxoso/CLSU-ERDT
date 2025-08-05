<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Mga Artisan commands na provided ng application.
     *
     * @var array
     */
    protected $commands = [
        // Debug commands na-remove na
    ];

    /**
     * Define ng command schedule ng application.
     *
     * Mga schedule na ito ay tumatakbo sa console.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire:daily')->hourly();
    }

    /**
     * Register ng mga commands para sa application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
