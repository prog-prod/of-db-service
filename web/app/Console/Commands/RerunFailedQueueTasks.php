<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class RerunFailedQueueTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:rerun-failed {limit=20} {exceptionFilter=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rerun failed jobs with limit';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $failedJobs = DB::table('failed_jobs')->limit($this->argument('limit'));
        if($this->argument('exceptionFilter')){
            $this->info('apply exception filter...');
            $failedJobs->where('exception', 'like', 'ErrorException: Array to%');
        }
        $failedJobs = $failedJobs->get();
        $count = $failedJobs->count();
        foreach ($failedJobs as $key => $failedJob) {
            if ($failedJob) {
                $this->info('Run '.($key+1).'/'.$count.' bunch of tasks...');
                Artisan::call('queue:retry', ['id' => $failedJob->uuid]);
            }
        }

        if($count === 0) {
            $this->info('There are no failed tasks.');
        }
        return Command::SUCCESS;
    }
}
