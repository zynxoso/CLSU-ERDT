<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class CheckTableColumns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-table-columns {table=fund_requests}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $table = $this->argument('table');
        $columns = Schema::getColumnListing($table);
        
        $this->info("Columns in {$table} table:");
        foreach ($columns as $column) {
            $this->line("- {$column}");
        }
    }
}
