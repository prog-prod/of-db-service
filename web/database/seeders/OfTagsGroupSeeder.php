<?php

namespace Database\Seeders;

use App\Contracts\CategoryServiceInterface;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfTagsGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = app(CategoryServiceInterface::class)->getGroupsArray();
        foreach ($groups as $group) {
            $time = now();
            DB::table('of_tags_groups')->insert([
                ...$group,
                'created_at' => $time,
                'updated_at' => $time,
            ]);
        }
    }
}
