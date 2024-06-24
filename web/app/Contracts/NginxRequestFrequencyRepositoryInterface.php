<?php

namespace App\Contracts;

use Carbon\Carbon;

interface NginxRequestFrequencyRepositoryInterface
{
    public function getNginxRequestFrequencyForThePeriod(Carbon $from, Carbon $to);
}
