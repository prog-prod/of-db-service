<?php

use Database\Seeders\BindOfTagsWithGroupsSeeder;
use Database\Seeders\OfTagsGroupSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $seeder = new OfTagsGroupSeeder();
        $seeder->run();

        $seeder = new BindOfTagsWithGroupsSeeder();
        $seeder->run();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
