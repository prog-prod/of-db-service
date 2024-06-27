<?php

namespace App\Console\Commands;

use App\Contracts\ParserServiceInterface;
use Illuminate\Console\Command;

class RestartAllParsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parser:restart-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restart updating models, checking regulars, parsing new models';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ParserServiceInterface $parserService)
    {
        $parserService->stopParsingModels();
        $parserService->startParsingModels();
        $this->info('Restarted parsing new models parser successfully.');

        $parserService->stopUpdatingModels();
        $parserService->startUpdatingModels();
        $this->info('Restarted updating models parser successfully.');

        $parserService->stopCheckingRegulars();
        $parserService->startCheckingRegulars();

        $this->info('Restarted checking regulars parser successfully.');
    }
}
