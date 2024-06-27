<?php

namespace App\Console\Commands;

use App\Contracts\OfUserRepositoryInterface;
use App\Models\OfUser;
use Illuminate\Console\Command;

class ReindexOfUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scout:reindex {username? : The username of the user to reindex} {--ads : ads users to reindex}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reindex a user in the search index based on username';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(OfUserRepositoryInterface $ofUserRepository)
    {
        $username = $this->argument('username');
        $ads = $this->option('ads');
        $user = OfUser::query()->where('username', $username)->first();
        if(!$username) {
            $users = OfUser::query();

            if($ads){
                $this->info("Reindexing ads users...");
                $users = $users->whereNotNull('free_trial_link');
            } else {
                $this->info('Starting reindex all users...');
            }

            $progressBar = $this->output->createProgressBar($users->count());

            $this->info('Got '. $users->count().' users.');
            $progressBar->start();
            foreach ($users->get() as $user) {
                $user->searchable();
                $progressBar->advance();
            }
            $progressBar->finish();
        } else {
            if (!$user) {
                $this->error('User not found.');
                return Command::FAILURE;
            }

            $user->searchable();
            $this->info('The user has been reindexed.');
        }

        return Command::SUCCESS;
    }
}
