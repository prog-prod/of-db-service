<?php

namespace App\Console\Commands;

use App\Models\OfUser;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UpdateShortAboutAndAboutFields extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:short-about-and-about-fields';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update short_about and about fields in of_users table.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Отключаем лимит времени выполнения скрипта
        ini_set('max_execution_time', 0);
        set_time_limit(0);

        $chunkSize = 1000;
        $query = OfUser::query()->whereNull('short_about')->whereNotNull('about')->where('about', '!=', "");
        $total = $query->count();
        $bar = $this->output->createProgressBar($total);

        $query->select('id', 'about')->chunk($chunkSize, function ($users) use ($bar) {
            $updates = [];

            foreach ($users as &$user) {
                $aboutText = $user->about;
                $transformedText = rm_tags($this->transformAboutText($aboutText));
                $shortAbout = $this->shortAbout($transformedText);
                $updates[] = [
                    'id' => $user->id,
                    'short_about' => $shortAbout,
                    'raw_about' => $transformedText,
                ];
                $bar->advance();
            }

            $this->batchUpdate('of_users', $updates, 'id');
        });

        $bar->finish();
        $this->info('All users updated.');

        return Command::SUCCESS;
    }

    private function shortAbout($aboutText): string
    {
        $maxLength = OfUser::SHORT_ABOUT_LENGTH;

        if (mb_strlen($aboutText) > $maxLength) {

            $shortenedText = mb_substr($aboutText, 0, $maxLength);
            $lastSpacePosition = mb_strrpos($shortenedText, ' ');

            if ($lastSpacePosition === false) {
                $lastSpacePosition = mb_strrpos($shortenedText, "\n");
            }
            return mb_substr($shortenedText, 0, $lastSpacePosition, 'UTF-8') . '...';
        }


        return $aboutText;
    }

    private function transformAboutText(string $aboutText): string
    {

        return transform_about_text($aboutText);
    }


    private function batchUpdate(string $table, array $values, string $index)
    {
        $ids = implode(',', array_column($values, $index));
        $cases = [];
        $params = [];

        foreach ($values as $value) {
            foreach ($value as $key => $val) {
                if ($key !== $index) {
                    $cases[$key][] = "WHEN {$index} = '{$value[$index]}' THEN ?";
                    $params[$key][] = $val;
                }
            }
        }

        $sql = "UPDATE {$table} SET ";
        foreach ($cases as $key => $case) {
            $sql .= "{$key} = CASE " . implode(' ', $case) . " ELSE {$key} END, ";
        }

        $sql = rtrim($sql, ', ') . " WHERE {$index} IN ({$ids})";

        DB::update($sql, Arr::flatten($params));
    }
}
