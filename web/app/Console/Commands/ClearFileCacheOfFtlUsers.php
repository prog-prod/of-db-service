<?php

namespace App\Console\Commands;

use App\Contracts\OfUserRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ClearFileCacheOfFtlUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear-cache-ftl-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear cache of OfUsers that have free trial links';

    /**
     * Execute the console command.
     *
     * @param OfUserRepositoryInterface $ofUserRepository
     * @return int
     */
    public function handle(OfUserRepositoryInterface $ofUserRepository): int
    {
        $ofUsers = $ofUserRepository->getOfUsersWithTrialLinks();
        $progressBar = $this->output->createProgressBar($ofUsers->count());
        $progressBar->start();

        foreach ($ofUsers as $ofUser) {
            $progressBar->advance();
            $this->info('Clearing '.$ofUser->username.' cache...');
            $result = Artisan::call('cache:clear-file', ['username' => $ofUser->username]);
            if($result === CommandAlias::FAILURE) {
                $this->error("User $ofUser->username is not in the index.");
            }
        }
        $progressBar->finish();
        $this->info('Cache cleared successfully.');

        return CommandAlias::SUCCESS;
    }
}
