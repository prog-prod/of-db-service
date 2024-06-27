<?php

namespace App\Console\Commands;

use App\Services\HelperService;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateRedirectsUrlsStatusCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:redirect-urls-status-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate file that contains models with 404 status';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(HelperService $helperService)
    {
        $client = new Client(['timeout' => 10]);
        $codes = [];
        foreach ($helperService->getRedirectedUrls() as $url) {
            try {
                $respose = $client->request('GET', url($url));
            } catch (\Exception $exception) {
                $codes[] = $url.  " - {$exception->getCode()};" ;
            }
        }
        Storage::put('404-models.txt', join( "\n", $codes));

        return Command::SUCCESS;
    }
}
