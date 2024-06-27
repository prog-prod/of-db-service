<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Exception;
use Google_Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Command\Command as CommandAlias;

class AddOnlyModelsPagesWithIndexingApi extends Command
{
    const CATEGORY_PAGE_URL_TEMPLATE = '/category/{slug}';
    const MODEL_PAGE_URL_TEMPLATE = '/model/{slug}';
    const HOST_URL = 'https://onlymodels.com';
    const MAX_INDEXED_PAGES_COUNT = 70000;
    const maxIndexedUsersForADay = 200;
    const indexUsersForAnHourNumber= 18;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'indexing-api:om-run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index OM pages with Indexing API';
    private int $indexedPagesCount;

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Google\Exception
     * @throws GuzzleException
     */
    public function handle(): int
    {
        $this->indexedPagesCount = $this->getIndexedPagesCount();
        $pages = $this->generatePagesForIndexation();
        $client = new Google_Client();

        // service_account_file.json is the private key that you created for your service account.
        $client->setAuthConfig(storage_path('app/indexing-om-service-account.json'));
        $client->addScope('https://www.googleapis.com/auth/indexing');

        // Get a Guzzle HTTP Client
        $httpClient = $client->authorize();
        $endpoint = 'https://indexing.googleapis.com/v3/urlNotifications:publish';

        $updated_pages = [];
        $indexedPagesCount = $this->getPagesCountThatWereIndexedToday();
        if($indexedPagesCount >= self::maxIndexedUsersForADay) {
            $this->info('Indexed users for today reached the limit '. $indexedPagesCount);
            return CommandAlias::SUCCESS;
        }
        $pages = array_slice($pages, 0, self::indexUsersForAnHourNumber);
        foreach ($pages as $page) {
            $full_url = self::HOST_URL.$page;
            $this->info('Indexing page: '. $full_url. "...");

            try {
                $response = $httpClient->post($endpoint, ['body' => json_encode([
                    "url" => $full_url,
                    "type" => "URL_UPDATED"
                ])]);
                $status_code = $response->getStatusCode();
                echo $response->getBody()->getContents(), "\n";

                if ($status_code == 429) {
                    $this->info('The indexing google api return reaching the limit.');
                    break;
                } else if($status_code === 200) {
                    $updated_pages[] = $page;
                    $this->info('Indexed successfully page: '. $page);
                }

            } catch (Exception $e) {
                echo 'error ', $e->getMessage(), "\n";
                break;
            }

            sleep(1);
        }
        if(count($updated_pages) > 0) {
            $this->updateIndexedPage($updated_pages);
        }

        $progress = number_format($this->indexedPagesCount/self::MAX_INDEXED_PAGES_COUNT*100,2);
        Log::driver('cron')->info('[CRON indexing-api:om-run] Updated pages (Progress: '.$progress.'% ,'.count($updated_pages).' count): '.json_encode($updated_pages));
        return Command::SUCCESS;
    }

    private function generatePagesForIndexation(): array
    {
        $pages = [];
        $langPrefix = 'ru';
        $categories = DB::connection('aws-onlymodels')
            ->table('categories')
            ->leftJoin('indexed_pages', DB::raw("CONCAT('".str_replace('{slug}', '', self::CATEGORY_PAGE_URL_TEMPLATE)."', categories.slug)"), '=', 'indexed_pages.url')
            ->whereNull('indexed_pages.url')
            ->select('categories.*')
            ->get();


        foreach ($categories as $category) {
            $pages[] = $url = str_replace('{slug}', $category->slug, self::CATEGORY_PAGE_URL_TEMPLATE);
            $pages[] = '/'.$langPrefix.$url;
        }

        $bestModels = DB::connection('aws-onlymodels')
            ->table('models')
            ->select('models.*')
            ->leftJoin('indexed_pages', DB::raw("CONCAT('".str_replace('{slug}', '', self::MODEL_PAGE_URL_TEMPLATE)."', models.slug)"), '=', 'indexed_pages.url')
            ->whereNull('indexed_pages.url')
            ->where('likes_amount', '>', 1000)
            ->orderBy('likes_amount', 'desc')
            ->limit(self::MAX_INDEXED_PAGES_COUNT - $this->indexedPagesCount)
            ->get();
        foreach ($bestModels as $model) {
            $pages[] = $url = str_replace('{slug}', $model->slug, self::MODEL_PAGE_URL_TEMPLATE);
            $pages[] = '/'.$langPrefix.$url;
        }

        return $pages;
    }

    private function getPagesCountThatWereIndexedToday()
    {
        return DB::connection('aws-onlymodels')
            ->table('indexed_pages')
            ->selectRaw('COUNT(*) as count')
            ->whereBetween('created_at', [Carbon::today()->startOfDay()->timestamp, Carbon::today()->endOfDay()->timestamp])
            ->get()
            ->first()
            ->count;
    }

    private function updateIndexedPage(array $updated_pages): bool
    {
        $time = Carbon::now()->timestamp;
        return DB::connection('aws-onlymodels')
            ->table('indexed_pages')
            ->insert(array_map(function ($page) use ($time) {
                    return [
                        'url' => $page,
                        'sent' => true,
                        'created_at' => $time,
                        'updated_at' => $time
                    ];
            },$updated_pages));
    }

    private function getIndexedPagesCount()
    {
        return DB::connection('aws-onlymodels')
            ->table('indexed_pages')
            ->selectRaw('COUNT(*) as count')
            ->get()->first()->count;
    }
}
