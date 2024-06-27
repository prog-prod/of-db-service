<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE of_users MODIFY priority INT DEFAULT 100 NOT NULL;');
        DB::statement("UPDATE of_users SET priority = priority - 20 WHERE about LIKE '??? ?' OR name LIKE '??? ?'");
        DB::statement("UPDATE of_users SET priority = priority - 20 WHERE about LIKE '%??? ?%' OR name LIKE '%??? ?%';");
        DB::statement("UPDATE of_users SET priority = priority - 5 WHERE LENGTH(about) < 100;");
        DB::statement("UPDATE of_users SET priority = priority - 15 WHERE LENGTH(about) < 20;");
        DB::statement("UPDATE of_users SET priority = priority - 2 WHERE favorites_count < 1000;");
        DB::statement("UPDATE of_users SET priority = priority - 8 WHERE favorites_count < 20;");
        DB::statement("UPDATE of_users SET priority = priority - 10 WHERE photos_count < 2;");
        DB::statement("UPDATE of_users SET priority = priority - 10 WHERE videos_count < 2;");
        DB::statement("UPDATE of_users SET priority = priority - 50 WHERE name LIKE '%male%' OR name LIKE '%guy%';"); # 6,5K Среди результатов есть с Guy в фамилии. Переделать проверку на точное совпадение слова (окружено пробелами или началом/концом строки)
        DB::statement("UPDATE of_users SET priority = priority - 30 WHERE about LIKE '%male%' OR about LIKE '%guy%';"); # 95 K, есть шанс попасть на что-то типа Hey guys! или #female. При парсинге надо будет чуть переделать.');
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
