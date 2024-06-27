<?php

namespace App\Console\Commands;

use App\Contracts\OfUserRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Console\Command\Command as CommandAlias;

class RegenerateModelPageText extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-file {username? :  OfUser username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear file cache';

    /**
     * Execute the console command.
     *
     * @param OfUserRepositoryInterface $ofUserRepository
     * @return int
     */
    public function handle(OfUserRepositoryInterface $ofUserRepository): int
    {
        $username = $this->argument('username');
        if($username) {
            $ofUser = $ofUserRepository->getOfUserByUsername($username);
            if(!$ofUser) {
                $this->error("User $username is not in the index.");
                return CommandAlias::FAILURE;
            }
            Cache::store('file')->forget('ofUserPage.'.$ofUser->id.'.trial_access');
            $this->info("File cache of $username cleared successfully.");

        } else {
            Cache::store('file')->flush();
            $this->info('File cache cleared successfully.');
        }

        return CommandAlias::SUCCESS;
    }
}
