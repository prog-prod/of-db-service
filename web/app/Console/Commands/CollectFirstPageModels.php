<?php

namespace App\Console\Commands;

use App\Contracts\OfUserRepositoryInterface;
use App\Enums\SpecialCategoriesEnum;
use App\Exports\ArrayToCsvExport;
use App\Models\OfTag;
use App\Models\OfUser;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class CollectFirstPageModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collect:firstpage-models';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collects model IDs appearing on the first page in Elasticsearch';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $models = [["ID", "Username", "Keys count", "Keys"]];
        $tags = OfTag::all();
        $bar = $this->output->createProgressBar(count($tags) + 3);

        foreach ($tags as $tag) {
            $users = OfUser::searchPaginate($tag->name);
            $this->fillModels($users->getPaginator()->items(), $tag->name, $models);
            $bar->advance();
        }

        $users = app(OfUserRepositoryInterface::class)->searchNewestOfUsers();
        $this->fillModels($users->getPaginator()->items(), SpecialCategoriesEnum::NEW, $models);
        $bar->advance();

        $users = app(OfUserRepositoryInterface::class)->searchFreeOfUsers();
        $this->fillModels($users->getPaginator()->items(), SpecialCategoriesEnum::FREE, $models);
        $bar->advance();

        $users = app(OfUserRepositoryInterface::class)->searchTopOfUsers();
        $this->fillModels($users->getPaginator()->items(), SpecialCategoriesEnum::TOP, $models);
        $bar->advance();

        $bar->finish();

        $outputFileName = 'first_page_models_collection.csv';
        Excel::store(new ArrayToCsvExport(array_values($models)), $outputFileName, 'local');
    }

    protected function fillModels(array $users, string $keyword, &$models): void
    {
        foreach ($users as $user) {
            if (isset($models[$user->username])) {
                $models[$user->username][2]++;
                $models[$user->username][3] .= ', ' . $keyword;
            } else {
                $models[$user->username] = [
                    0 => $user->id,
                    1 => $user->username,
                    2 => 1,
                    3 => $keyword
                ];
            }
        }
    }
}
