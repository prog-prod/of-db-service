<?php

namespace App\Providers;

use App\Contracts\CacheServiceInterface;
use App\Contracts\CategoryServiceInterface;
use App\Contracts\ChartDataServiceInterface;
use App\Contracts\ElasticSearchServiceInterface;
use App\Contracts\FeedbackRepositoryInterface;
use App\Contracts\JsonLdServiceInterface;
use App\Contracts\MetaTagGeneratorServiceInterface;
use App\Contracts\NginxRequestFrequencyRepositoryInterface;
use App\Contracts\OfTagRepositoryInterface;
use App\Contracts\OfTagsGroupRepositoryInterface;
use App\Contracts\OfUserRepositoryInterface;
use App\Contracts\ParserServiceInterface;
use App\Contracts\ParserStatusRepositoryInterface;
use App\Contracts\PublicFreeTrialSubscriberRepositoryInterface;
use App\Contracts\TagServiceInterface;
use App\Contracts\TelegramServiceInterface;
use App\Contracts\UserRepositoryInterface;
use App\Repositories\FeedbackRepository;
use App\Repositories\NginxRequestFrequencyRepository;
use App\Repositories\OfTagRepository;
use App\Repositories\OfTagsGroupRepository;
use App\Repositories\OfUserRepository;
use App\Repositories\ParserStatusRepository;
use App\Repositories\PublicFreeTrialSubscriberRepository;
use App\Repositories\UserRepository;
use App\Services\CacheService;
use App\Services\CategoryService;
use App\Services\ChartDataService;
use App\Services\ElasticSearchService;
use App\Services\HelperService;
use App\Services\JsonLdService;
use App\Services\MetaTagGeneratorService;
use App\Services\ParserService;
use App\Services\TagService;
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
        $this->app->bind(FeedbackRepositoryInterface::class, FeedbackRepository::class);
        $this->app->bind(ParserStatusRepositoryInterface::class, ParserStatusRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(OfUserRepositoryInterface::class, OfUserRepository::class);
        $this->app->bind(OfTagRepositoryInterface::class, OfTagRepository::class);
        $this->app->bind(PublicFreeTrialSubscriberRepositoryInterface::class, PublicFreeTrialSubscriberRepository::class);
        $this->app->bind(NginxRequestFrequencyRepositoryInterface::class, NginxRequestFrequencyRepository::class);
        $this->app->bind(OfTagsGroupRepositoryInterface::class, OfTagsGroupRepository::class);

        // Services
        $this->app->bind(ElasticSearchServiceInterface::class, ElasticSearchService::class);
        $this->app->bind(TelegramServiceInterface::class, TelegramService::class);
        $this->app->bind(ParserServiceInterface::class, ParserService::class);

        $this->app->bind(ParserServiceInterface::class, ParserService::class);
        $this->app->bind(ElasticSearchServiceInterface::class, ElasticSearchService::class);
        $this->app->bind(TagServiceInterface::class, TagService::class);
        $this->app->bind(MetaTagGeneratorServiceInterface::class, MetaTagGeneratorService::class);
        $this->app->bind(JsonLdServiceInterface::class, JsonLdService::class);
        $this->app->bind(CacheServiceInterface::class, CacheService::class);
        $this->app->bind(ChartDataServiceInterface::class, ChartDataService::class);
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
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
