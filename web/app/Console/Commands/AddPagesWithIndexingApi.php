<?php

namespace App\Console\Commands;

use App\Contracts\OfUserRepositoryInterface;
use Exception;
use Google_Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Command\Command as CommandAlias;

class AddPagesWithIndexingApi extends Command
{
    const maxIndexedUsersForADay = 200;
    const indexUsersForAnHourNumber= 18;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'indexing-api:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index pages with Indexing API';

    /**
     * Execute the console command.
     *
     * @param OfUserRepositoryInterface $ofUserRepository
     * @return int
     * @throws GuzzleException
     * @throws \Google\Exception
     */
    public function handle(OfUserRepositoryInterface $ofUserRepository): int
    {
        $client = new Google_Client();

        // service_account_file.json is the private key that you created for your service account.
        $client->setAuthConfig(storage_path('app/service_account_file.json'));
        $client->addScope('https://www.googleapis.com/auth/indexing');

        // Get a Guzzle HTTP Client
        $httpClient = $client->authorize();
        $endpoint = 'https://indexing.googleapis.com/v3/urlNotifications:publish';

        $update_users = [];
        $updated_users_url = [];
        $indexedUsers = $ofUserRepository->getOfUsersThatWereIndexedToday();
        if($indexedUsers->count() >= self::maxIndexedUsersForADay) {
            $this->info('Indexed users for today reached the limit '. $indexedUsers->count());
            return CommandAlias::SUCCESS;
        }
        $limit = min(self::maxIndexedUsersForADay - $indexedUsers->count(), self::indexUsersForAnHourNumber);
        $users = $ofUserRepository->getSpecificOfUsersForIndexation(limit: $limit);
        if($users->isEmpty()){
            $users = $ofUserRepository->getOfUsersForIndexation(limit: $limit);
        }

        foreach ($users as $key => $user) {
            $full_url = route('users-of.show', $user);
            $updated_users_url[] = $full_url;
            $this->info('Indexing user '.($key + 1). ": ". $user->username. "...");

            try {

                echo $full_url, "\t";
                $response = $httpClient->post($endpoint, ['body' => json_encode([
                    "url" => $full_url,
                    "type" => "URL_UPDATED"
                ])]);
                $status_code = $response->getStatusCode();
                echo $response->getBody()->getContents(), "\n";

                if ($status_code == 429) {
                    $this->info('The indexing google api return reaching the limit.');
                    break;
                }else if($status_code === 200) {
                    $update_users[] = $user->id;
                    $this->info('Indexed successfully user '.($key + 1). ": ". $user->username. "...");
                }

            } catch (Exception $e) {
                echo 'error ', $e->getMessage(), "\n";
                break;
            }

            sleep(1);
        }

        if(!empty($update_users)) {
            $ofUserRepository->updateOfUsersIndexDate(usersIds: $update_users, index_date: now());
        }

        Log::driver('cron')->info('[CRON indexing-api:run] Updated users ('.count($update_users).' count): '.json_encode($updated_users_url));
        return CommandAlias::SUCCESS;
    }
}
