<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Console\Helper\ProgressBar;

class ClearCachePageText extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:cache-page-text';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cachePath = storage_path('framework/cache/data'); // Путь к директории кеша
        $cleared = 0;
        $directory = new \RecursiveDirectoryIterator($cachePath, \RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new \RecursiveIteratorIterator($directory);
        $filesCount = 0;
        foreach ($iterator as $file) {
            if ($file->isFile() && str_contains(file_get_contents($file), 'Get Free OnlyFans')) {
                $filesCount++;
            }
        }
        $iterator->rewind();

        // Creating the progress bar
        $progressBar = new ProgressBar($this->output, $filesCount);
        $progressBar->start();
        foreach ($iterator as $file) {
            if ($file->isFile() && str_contains(file_get_contents($file), 'Get Free OnlyFans')) {
                $keyWithPrefix = $file->getBasename('.php'); // Получаем имя файла без расширения
                $key = str_replace(['file-', '.cache'], '', $keyWithPrefix); // Удаляем префикс и суффикс, если они есть
                $this->info('Key: ' . $key);
                $cleared++;
                Cache::store('file')->forget($key);
                @unlink($file->getRealPath());
            }
            $progressBar->advance();
        }
        $progressBar->finish();
        $this->info("\n".$cleared . ' cache files cleared.');

        return Command::SUCCESS;
    }
}
