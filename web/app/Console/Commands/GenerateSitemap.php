<?php

namespace App\Console\Commands;

use App\Contracts\OfUserRepositoryInterface;
use App\Models\OfTag;
use App\Models\OfUser;
use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate {models? : sitemap for 40k models}';
    protected $description = 'Generate the sitemap';

    private OfUserRepositoryInterface $ofUserRepository;

    public function __construct()
    {
        parent::__construct();
        $this->ofUserRepository = app(OfUserRepositoryInterface::class);
    }

    public function handle(): int
    {
        $models = $this->argument('models');

        if ($models) {
            $this->info('Generating models-sitemap.xml...');
            $this->generateModelsSitemap();
            return Command::SUCCESS;
        }
        $this->info('Generating sitemap.xml...');

        $sitemap = Sitemap::create();
        $mainLastModified = OfUser::search('')
            ->orderBy('updated_at', 'desc')
            ->first()->updated_at ?? now();

        $sitemap->add(Url::create('/')
            ->setPriority(0.8)
            ->setLastModificationDate($mainLastModified));

        $categories = [
            'top' => $this->ofUserRepository->searchTopOfUsers(),
            'new' => $this->ofUserRepository->searchNewestOfUsers(),
            'free' => $this->ofUserRepository->searchFreeOfUsers()
        ];

        foreach ($categories as $category => $users) {
            $categoryLastModified = $users
                ->first()->updated_at ?? now();
            $sitemap->add(Url::create("/category/{$category}")
                ->setPriority(0.6)
                ->setLastModificationDate($categoryLastModified));
        }
        $categories = OfTag::all();
        foreach ($categories as $category) {
            $users = $this->ofUserRepository->searchOfUsersByTag($category);
            $categoryLastModified = $users->first()->updated_at ?? now();
            $sitemap->add(Url::create("/category/{$category->key}")
                ->setPriority(0.6)
                ->setLastModificationDate($categoryLastModified));
        }
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated!');
        return Command::SUCCESS;
    }

    private function generateModelsSitemap(): void
    {
        $sitemap = Sitemap::create();
        $categories = OfTag::all();
        $bar = $this->output->createProgressBar($categories->count());
        foreach ($categories as $category) {
            $users = $this->ofUserRepository->searchOfUsersByTag($category);
            $bar->advance();
            foreach($users->getData() as $user) {
                $categoryLastModified = $user->updated_at ?? now();
                $sitemap->add(Url::create(route('users-of.show', $user))
                    ->setPriority(0.6)
                    ->setLastModificationDate($categoryLastModified));
            }
        }
        $bar->finish();

        $sitemap->writeToFile(public_path('models-sitemap.xml'));

        $this->info('Sitemap generated!');
    }
}
