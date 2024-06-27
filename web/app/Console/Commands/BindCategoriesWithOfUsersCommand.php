<?php

namespace App\Console\Commands;

use App\Contracts\OfTagRepositoryInterface;
use App\Contracts\OfUserRepositoryInterface;
use App\Models\OfTag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BindCategoriesWithOfUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bind-categories-with-of-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bind Categories with OF users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(OfUserRepositoryInterface $ofUserRepository, OfTagRepositoryInterface $ofTagRepository)
    {
        $tags = $ofTagRepository->getAllTags();
        $progressBar = $this->output->createProgressBar($tags->count());
        $progressBar->start();

        DB::table('of_tag_of_user')->truncate();
        OfTag::query()->unsearchable();

        foreach ($tags as $tag) {
            $users = $ofUserRepository->searchOfUsersByTag($tag, 50);
            $this->info("\nUsers to add: ". count($users->getData()));
            foreach ($users->getData() as $user) {
                $user->tags()->attach($tag->id);
            }
            $progressBar->advance();
        }
        $progressBar->finish();
        return Command::SUCCESS;
    }
}
