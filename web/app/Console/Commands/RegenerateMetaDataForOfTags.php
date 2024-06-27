<?php

namespace App\Console\Commands;

use App\Contracts\MetaTagGeneratorServiceInterface;
use App\Models\OfTag;
use Illuminate\Console\Command;

class RegenerateMetaDataForOfTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'regenerate:categories-meta';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerating categories meta tags: h1, description, title';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $serviceMetaTagsGenerator = app(MetaTagGeneratorServiceInterface::class);
        $ofTags = OfTag::all();
        $bar = $this->output->createProgressBar($ofTags->count());
        foreach ($ofTags as $ofTag) {

            $metaTags = $serviceMetaTagsGenerator->generateMetaTags($ofTag->name);

            $results = [
                'title' => $metaTags->title,
                'description' => $metaTags->description,
                'h1' => $metaTags->h1
            ];

            $ofTag->update($results);
            $bar->advance();
        }
        $bar->finish();

        return Command::SUCCESS;
    }
}
