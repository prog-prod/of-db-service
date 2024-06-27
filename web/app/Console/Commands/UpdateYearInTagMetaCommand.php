<?php

namespace App\Console\Commands;

use App\Models\OfTag;
use Illuminate\Console\Command;

class UpdateYearInTagMetaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:year-in-tag';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualize year in meta title, description, h1';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tags = OfTag::all();
        $bar = $this->output->createProgressBar(count($tags));

        foreach ($tags as $tag) {
            $this->info("Processing tag $tag->id...\n");
            if($this->checkYear($tag)) {
                $tag->update([
                    'h1' => $this->replaceYear($tag->h1),
                    'title' => $this->replaceYear($tag->title),
                    'description' => $this->replaceYear($tag->description),
                ]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->info("FINISH. Tags updated successfully.");

        return Command::SUCCESS;
    }
    private function replaceYear(string $text): string {
        $currentYear = date('Y'); // Get the current year
        $lastYear = date('Y', strtotime('-1 year')); // Get the last year

        return str_replace($lastYear, $currentYear, $text);
    }
    private function checkYear($text): bool {
        $lastYear = date('Y', strtotime('-1 year'));
        return str_contains($text, $lastYear);
    }
}
