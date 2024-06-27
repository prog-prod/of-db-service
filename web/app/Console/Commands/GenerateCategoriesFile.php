<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateCategoriesFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:categories-file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generating result_keys.csv with all categories';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $f = fopen(storage_path('app/result_keys.csv'), 'r');
        if (file_exists(storage_path('result.csv'))) {
            unlink(storage_path('result.csv'));
        }
        $f2 = fopen(storage_path('result.csv'), 'w');

        fgetcsv($f);

        while ($r = fgetcsv($f)) {
            if (empty($r[1]) || ($r[1] < 10)) {
                continue;
            }

            fputs($f2, "{$r[0]};{$r[2]}\n");
        }

        return Command::SUCCESS;
    }
}
