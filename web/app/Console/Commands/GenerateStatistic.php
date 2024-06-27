<?php

namespace App\Console\Commands;

use App\Exports\ArrayToCsvExport;
use App\Models\OfUser;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class GenerateStatistic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse CSV file and search records by each key';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $files = [
            'models_keys.csv',
            'onlyfans-creators_keys.csv',
            'search-location_keys.csv',
            'search_keys.csv',
        ];
        $results = [["Key", "Results", "Traffic"]];

        foreach ($files as $file) {

            $this->info("Processing {$file}...");
            $path = storage_path($file);
            $data = array_map('str_getcsv', file($path));
            $bar = $this->output->createProgressBar(count($data));
            foreach ($data as $row => $strings) {
                if($row == 0) continue;
                if(count($strings) > 1){
                    $string = implode(',', $strings);
                } else {
                    $string = $strings[0];
                }
                $columns = explode(';', $string);
                $key = str_replace(['/', '\\', '/'], '', $columns[0]);
                $count = OfUser::search($key)->paginate();
                $results[] = [$key, (string)$count->total(), $columns[3]];
                $bar->advance();
            }
            $bar->finish();
        }

        $outputFileName = 'result_keys.csv';
        Excel::store(new ArrayToCsvExport($results), $outputFileName, 'local');
        $this->info("\nSaved to {$outputFileName}");
    }
}
