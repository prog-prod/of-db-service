<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class TestPerformanceService
{
    private array $startArray = [];

    public function __construct()
    {
        $this->start('overall');
    }

    public function start(string $name): void
    {
        $this->startArray[$name] = microtime(true);
    }

    public function getExecutionTime(?string $name = null): float
    {
        if($name === null) {
            $name = 'overall';
        }

        return microtime(true) - $this->startArray[$name];
    }

    public function writeExecutionTime(?string $name = null): void
    {
        if($name === null) {
            $name = 'overall';
        }

        $executionTime = $this->getExecutionTime($name);

        if(!app()->environment('production')){
            Log::driver('debug')->info("Execution time for $name: $executionTime\n");
        }
    }
}
