<?php

namespace App\Contracts;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

interface ParserStatusRepositoryInterface
{
    public function getParserStatusesForThePeriod(Carbon $from, Carbon $to): Collection;
    public function getParserUpdatingStatusesForThePeriod(Carbon $from, Carbon $to): Collection;
    public function getParserCheckingRegularsStatusesForThePeriod(Carbon $from, Carbon $to): Collection;
    public function getActiveParsers();
}
