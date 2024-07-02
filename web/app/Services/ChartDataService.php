<?php

namespace App\Services;

use App\Contracts\ChartDataServiceInterface;
use App\Contracts\NginxRequestFrequencyRepositoryInterface;
use App\DTO\ChartDataDTO;
use Carbon\Carbon;

class ChartDataService implements ChartDataServiceInterface
{

    private NginxRequestFrequencyRepositoryInterface $nginxRequestFrequencyRepository;

    public function __construct()
    {
        $this->nginxRequestFrequencyRepository = app(NginxRequestFrequencyRepositoryInterface::class);
    }

    public function getDefaultStartDate(): Carbon
    {
        return Carbon::now()->subWeek();
    }

    public function getDefaultEndDate(): Carbon
    {
        return Carbon::now();
    }


    /**
     * @param Carbon $from
     * @param Carbon $to
     * @return ChartDataDTO[]
     */
    public function getRequestsPerSecondForThePeriod(Carbon $from, Carbon $to): array
    {
        $clicks = $this->nginxRequestFrequencyRepository->getNginxRequestFrequencyForThePeriod($from, $to);

        return collect($clicks)->map(function ($item) {
            return new ChartDataDTO(
                date: Carbon::parse($item->date)->format('Y-m-d'),
                value: $item->requestsPerSecond
            );
        })->toArray();
    }
}
