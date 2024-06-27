<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CheckParserStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:parser-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update parser status data in DB';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $timeout = 10;
        Http::timeout($timeout)->get(config('app.parser-url').'/check'); // check ordinary parsing
        Http::timeout($timeout)->get(config('app.parser-url').'/check-updating'); // check parser of updating models
        Http::timeout($timeout)->get(config('app.parser-url').'/check-checking-regulars'); // check parser of checking regulars
        return Command::SUCCESS;
    }
}
