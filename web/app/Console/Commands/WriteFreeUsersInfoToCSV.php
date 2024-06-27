<?php

namespace App\Console\Commands;

use App\Contracts\OfUserRepositoryInterface;
use Illuminate\Console\Command;

class WriteFreeUsersInfoToCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'write-to-csv-free-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bind free trials links with OF users';

    public function handle(OfUserRepositoryInterface $ofUserRepository)
    {

        $null_users = [];

        // Prepare a new CSV file for writing updated information
        $newCsvPath = storage_path('app/free_users_data.csv');
        $writer = \League\Csv\Writer::createFromPath($newCsvPath, 'w+');
        $writer->insertOne(['№','id', 'username', 'name', 'description', 'price', 'avatar', 'cover']); // Add headers for new columns

        $users = $ofUserRepository->searchFreeOfUsersOrderByLikes(200);
        foreach ($users->getData() as $key => $user) {
            $user = $user->resource;
            $username = $user->username;

            if (is_null($user)) {
                $null_users[] = $username;
                continue;
            }

            // Now, write to the new CSV
            $writer->insertOne([
                $key + 1,
                $user->id,
                $user->username,
                $user->name,
                $user->about,
                $user->subscribe_price,
                $user->avatar,
                $user->cover,
            ]);

            $this->info("Writing data for user: $username...");
        }

        $this->info('All users data have been written to updated_ftls.csv.');
        $this->info('Not found users: ' . join(', ', $null_users));

        return Command::SUCCESS;
    }
}
