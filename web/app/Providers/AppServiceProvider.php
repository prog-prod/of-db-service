<?php

namespace App\Providers;

use App\Contracts\CacheServiceInterface;
use App\Contracts\ChartDataServiceInterface;
use App\Contracts\ElasticSearchServiceInterface;
use App\Contracts\NginxRequestFrequencyRepositoryInterface;
use App\Contracts\OfTagRepositoryInterface;
use App\Contracts\OfUserRepositoryInterface;
use App\Contracts\ParserServiceInterface;
use App\Contracts\ParserStatusRepositoryInterface;
use App\Contracts\TelegramServiceInterface;
use App\Repositories\NginxRequestFrequencyRepository;
use App\Repositories\OfTagRepository;
use App\Repositories\OfUserRepository;
use App\Repositories\ParserStatusRepository;
use App\Services\CacheService;
use App\Services\ChartDataService;
use App\Services\ElasticSearchService;
use App\Services\HelperService;
use App\Services\ParserService;
use App\Services\TelegramService;
use App\Services\TestPerformanceService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Facades
        $this->app->bind('helper', HelperService::class);
        $this->app->singleton('cacheService', function ($app) {
            return new CacheService();
        });
        $this->app->bind('testPerformanceService', TestPerformanceService::class);

        // Repositories
        $this->app->bind(ParserStatusRepositoryInterface::class, ParserStatusRepository::class);
        $this->app->bind(OfUserRepositoryInterface::class, OfUserRepository::class);
        $this->app->bind(OfTagRepositoryInterface::class, OfTagRepository::class);
        $this->app->bind(NginxRequestFrequencyRepositoryInterface::class, NginxRequestFrequencyRepository::class);

        // Services
        $this->app->bind(ElasticSearchServiceInterface::class, ElasticSearchService::class);
        $this->app->bind(TelegramServiceInterface::class, TelegramService::class);
        $this->app->bind(ParserServiceInterface::class, ParserService::class);

        $this->app->bind(ParserServiceInterface::class, ParserService::class);
        $this->app->bind(ElasticSearchServiceInterface::class, ElasticSearchService::class);
        $this->app->bind(CacheServiceInterface::class, CacheService::class);
        $this->app->bind(ChartDataServiceInterface::class, ChartDataService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        JsonResource::withoutWrapping();
    }
}
