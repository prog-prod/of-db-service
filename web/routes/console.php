<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

if (config('app.is-server-for-indexation')) {
    Schedule::command('indexing-api:run')->hourly();
    Schedule::command('indexing-api:om-run')->hourly();

}

Schedule::command('check:proxies')->hourly();
Schedule::command('check:parser-status')->everyFiveMinutes();
Schedule::command('clear:parser-status-data')->daily();
Schedule::command('parser:restart-all')->daily()->at('00:00:00');
Schedule::command('telescope:prune --hours=2')->everyTwoHours();
