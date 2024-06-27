<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LogAvgReqsToTheSite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nginx:calc-load-on-server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates the average number of requests to the site per second and logs it to the db.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $command = "cat /var/log/nginx/access.log | awk '{print $4}' | cut -d: -f1,2,3,4 | uniq -c | awk '{total += $1; count++} END {print \"Average requests per second: \", total/count}'";
        $output = shell_exec($command);

        // Извлечение значения из строки
        if (preg_match('/Average requests per second:\s+(\d+\.?\d*)/', $output, $matches)) {
            $averageRequestsPerSecond = $matches[1];

            // Запись результата в базу данных
            DB::table('nginx_request_frequencies')->insert([
                'average_requests_per_second' => $averageRequestsPerSecond,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->info('Logged Nginx access statistics successfully.');
        } else {
            $this->error('Failed to parse the script output.');
        }
        return Command::SUCCESS;
    }
}
