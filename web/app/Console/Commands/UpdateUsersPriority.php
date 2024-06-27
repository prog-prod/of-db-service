<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateUsersPriority extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:priority';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating OF users priority';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $bar = $this->output->createProgressBar(12);

        DB::statement('ALTER TABLE of_users DROP COLUMN priority');
        $bar->advance();

        DB::statement('ALTER TABLE of_users ADD COLUMN priority INT UNSIGNED DEFAULT 100 NOT NULL;');
        $bar->advance();

        DB::statement("UPDATE of_users SET priority = priority - 20 WHERE about LIKE '??? ?' OR name LIKE '??? ?'");
        $bar->advance();

        DB::statement("UPDATE of_users SET priority = priority - 20 WHERE about LIKE '%??? ?%' OR name LIKE '%??? ?%';");
        $bar->advance();

        DB::statement("UPDATE of_users SET priority = priority - 5 WHERE LENGTH(about) < 100;");
        $bar->advance();

        DB::statement("UPDATE of_users SET priority = priority - 15 WHERE LENGTH(about) < 20;");
        $bar->advance();

        DB::statement("UPDATE of_users SET priority = priority - 2 WHERE favorites_count < 1000;");
        $bar->advance();

        DB::statement("UPDATE of_users SET priority = priority - 8 WHERE favorites_count < 20;");
        $bar->advance();

        DB::statement("UPDATE of_users SET priority = priority - 10 WHERE photos_count < 2;");
        $bar->advance();

        DB::statement("UPDATE of_users SET priority = priority - 10 WHERE videos_count < 2;");
        $bar->advance();

        DB::statement("UPDATE of_users SET priority = priority - 50 WHERE name LIKE '%male%' OR name LIKE '%guy%';"); # 6,5K Среди результатов есть с Guy в фамилии. Переделать проверку на точное совпадение слова (окружено пробелами или началом/концом строки)
        $bar->advance();

        DB::statement("UPDATE of_users SET priority = priority - 30 WHERE about LIKE '%male%' OR about LIKE '%guy%';"); # 95 K, есть шанс попасть на что-то типа Hey guys! или #female. При парсинге надо будет чуть переделать.');
        $bar->advance();
        $bar->finish();
        return Command::SUCCESS;
    }
}
