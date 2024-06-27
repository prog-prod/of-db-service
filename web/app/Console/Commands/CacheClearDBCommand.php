<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CacheClearDBCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the cache of the database.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Clearing the cache of the database...');
        Cache::driver('database')->flush();
        $this->info('The cache of the database has been cleared.');
        return Command::SUCCESS;
    }
}
