<?php

namespace App\Console\Commands;

use App\Models\OfTag;
use App\Models\OfUser;
use Illuminate\Console\Command;

class HideTagsWithoutModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:tags';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hide Tags without models';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tags = OfTag::all();
        $bar = $this->output->createProgressBar(count($tags));
        $hidden = 0;
        foreach ($tags as $tag) {
            $users = OfUser::searchPaginate($tag->name);
            if($users->getPaginator()->total() === 0) {
                $tag->hidden = 1;
                $tag->save();
                $hidden++;
            }
            $bar->advance();
        }
        $this->info("Hide $hidden tags.");

        $bar->finish();

        return Command::SUCCESS;
    }
}
