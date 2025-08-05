<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class CheckTableColumns extends Command
{
    /**
     * pangalan at signature ng console command
     *
     * @var string
     */
    protected $signature = 'app:check-table-columns {table=fund_requests}';

    /**
     * paglalarawan ng console command
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * pagpapatupad ng console command
     */
    public function handle(): void
    {
        // pagkuha ng table name mula sa argument
        $table = $this->argument('table');
        
        // pagkuha ng lahat ng columns sa table
        $columns = Schema::getColumnListing($table);
        
        // pagpapakita ng header message
        $this->info("Columns in {$table} table:");
        
        // pag-loop sa lahat ng columns at pagpapakita nito
        foreach ($columns as $column) {
            $this->line("- {$column}");
        }
    }
}
