<?php

namespace App\Repositories;

use App\Contracts\NginxRequestFrequencyRepositoryInterface;
use App\Models\NginxRequestFrequency;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class NginxRequestFrequencyRepository implements NginxRequestFrequencyRepositoryInterface
{

    public function getNginxRequestFrequencyForThePeriod(Carbon $from, Carbon $to): Collection
    {
        $from = $from->startOfDay();
        $to = $to->endOfDay();
        return NginxRequestFrequency::query()
            ->selectRaw('
                      AVG(average_requests_per_second) as requestsPerSecond,
                      DATE(created_at) as date
                ')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
}
