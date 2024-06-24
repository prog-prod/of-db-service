<?php

namespace App\Contracts;

use Carbon\Carbon;

interface ParserServiceInterface
{
    public function getDefaultStartDate();
    public function getDefaultEndDate();
    public function startParsingModels();
    public function stopParsingModels();
    public function startUpdatingModels();
    public function stopUpdatingModels();
    public function startCheckingRegulars();
    public function stopCheckingRegulars();
    public function syncParserStatuses();
    public function getParserUpdatingStatusesForThePeriod(Carbon $from, Carbon $to);
    public function getParserStatusesForThePeriod(Carbon $from, Carbon $to);

    public function getParserCheckingRegularsStatusesForThePeriod(Carbon $from, Carbon $to);

}
