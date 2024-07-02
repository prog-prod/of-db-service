<?php

namespace App\Contracts;

use Carbon\Carbon;

interface ChartDataServiceInterface
{
    public function getRequestsPerSecondForThePeriod(Carbon $from, Carbon $to);

    public function getDefaultStartDate();

    public function getDefaultEndDate();
}
