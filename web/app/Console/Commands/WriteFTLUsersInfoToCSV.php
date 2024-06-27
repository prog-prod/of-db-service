<?php

namespace App\Console\Commands;

use App\Contracts\OfUserRepositoryInterface;
use Illuminate\Console\Command;
use League\Csv\Reader;

class WriteFTLUsersInfoToCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'write-to-csv-ftl-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bind free trials links with OF users';

    public function handle(OfUserRepositoryInterface $ofUserRepository)
    {
        $csvPath = storage_path('app/ftls.csv');
        if(!file_exists($csvPath)) {
            $this->info('No file ftls.csv in next path '.storage_path('app/'));
            return Command::FAILURE;
        }
        $csv = Reader::createFromPath($csvPath, 'r');
        $records = $csv->getRecords();

        $null_users = [];

        // Prepare a new CSV file for writing updated information
        $newCsvPath = storage_path('app/updated_ftls.csv');
        $writer = \League\Csv\Writer::createFromPath($newCsvPath, 'w+');
        $writer->insertOne(['№','id', 'username', 'name', 'ftl', 'description', 'price', 'avatar', 'cover']);

        foreach ($records as $key => $record) {
            $data = explode(';', $record[0]);
            $username = $data[0];
            $user = $ofUserRepository->getOfUserByUsername($username);
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
                $user->free_trial_link,
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
