<?php

use App\Models\OfUser;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $startOfYear = now()->startOfYear()->toDateString();
        $currentDate = now()->subMonth()->toDateString();

        OfUser::query()->update([
            'date_published' => DB::raw("DATE(FROM_UNIXTIME(UNIX_TIMESTAMP('{$startOfYear}') + RAND() * (UNIX_TIMESTAMP('{$currentDate}') - UNIX_TIMESTAMP('{$startOfYear}'))))")
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
