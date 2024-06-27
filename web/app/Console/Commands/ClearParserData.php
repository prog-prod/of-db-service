<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearParserData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:parser-status-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear parser statuses data for the previous period';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::table('parser_statuses')->where('created_at', '<', Carbon::now()->subMonth()->format('Y-m-d H:i:s'))->delete();
        DB::table('parser_updating_statuses')->where('created_at', '<', Carbon::now()->subMonth()->format('Y-m-d H:i:s'))->delete();
        DB::table('parser_checking_regulars_statuses')->where('created_at', '<', Carbon::now()->subMonth()->format('Y-m-d H:i:s'))->delete();
        $this->info('Parser data cleared successfully for the previous period.');
        return Command::SUCCESS;
    }
}
