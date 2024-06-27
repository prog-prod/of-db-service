<?php

namespace App\Console\Commands;

use App\Models\NginxRequestFrequency;
use Illuminate\Console\Command;

class FlushSiteLoadData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nginx:flush-load-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flushes the data about the site load.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Flushing the data about the site load...');
        NginxRequestFrequency::query()->where('created_at', '<', now()->subDays(30))->delete();

        $this->info('Data has been successfully flushed.');
        return Command::SUCCESS;
    }
}
