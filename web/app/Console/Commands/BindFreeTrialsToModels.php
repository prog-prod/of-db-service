<?php

namespace App\Console\Commands;

use App\Models\OfUser;
use Illuminate\Console\Command;
use League\Csv\Reader;

class BindFreeTrialsToModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bind-free-trials-with-models {clear? : Clear all free trials links}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bind free trials links with OF users';

    public function handle()
    {
        if ($this->argument('clear')) {
            $users = OfUser::query()->whereNotNull('free_trial_link')->get();
            $progressBar = $this->output->createProgressBar($users->count());
            $progressBar->start();
            foreach ($users as $user) {
                $user->free_trial_link = null;
                $user->save();
                $progressBar->advance();
            }
            $progressBar->finish();
            $this->info('All free trials links have been cleared.');
        } else {

            $csvPath = storage_path('app/ftls.csv');
            if (!file_exists($csvPath)) {
                $this->info('No file ftls.csv in next path ' . storage_path('app/'));
                return Command::FAILURE;
            }
            $csv = Reader::createFromPath($csvPath, 'r');
            $records = $csv->getRecords();
            $usernames = collect($records)->map(function ($record) {
                $data = explode(';', $record[0]);
                return $data[0] ?? null;
            });
            $users = OfUser::query()->whereIn('username', $usernames)->get();
            $null_users = [];
            foreach ($records as $record) {
                $data = explode(';', $record[0]);
                $username = $data[0];
                $user = $users->where('username', $username)->first();
                if (is_null($user)) {
                    $null_users[] = $username;
                    continue;
                }
                $this->info("Updating link for user: $username...");
                $link = $data[1];
                $user->free_trial_link = $link;
                $user->save();
                $this->info("Updated link for user: $username");
            }

            $this->info('All user links have been updated.');
            $this->info('Not found users:' . join(', ', $null_users));
        }
        return Command::SUCCESS;
    }
}
