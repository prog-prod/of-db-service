<?php

namespace App\Providers;

use App\Contracts\ElasticSearchServiceInterface;
use App\Contracts\NginxRequestFrequencyRepositoryInterface;
use App\Contracts\OfTagRepositoryInterface;
use App\Contracts\OfTagsGroupRepositoryInterface;
use App\Contracts\OfUserRepositoryInterface;
use App\Contracts\ParserStatusRepositoryInterface;
use App\Contracts\PublicFreeTrialSubscriberRepositoryInterface;
use App\Contracts\UserRepositoryInterface;
use App\Repositories\NginxRequestFrequencyRepository;
use App\Repositories\OfTagRepository;
use App\Repositories\OfTagsGroupRepository;
use App\Repositories\OfUserRepository;
use App\Repositories\ParserStatusRepository;
use App\Repositories\PublicFreeTrialSubscriberRepository;
use App\Repositories\UserRepository;
use App\Services\ElasticSearchService;
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

        // Repositories
        $this->app->bind(ParserStatusRepositoryInterface::class, ParserStatusRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(OfUserRepositoryInterface::class, OfUserRepository::class);
        $this->app->bind(OfTagRepositoryInterface::class, OfTagRepository::class);
        $this->app->bind(PublicFreeTrialSubscriberRepositoryInterface::class, PublicFreeTrialSubscriberRepository::class);
        $this->app->bind(NginxRequestFrequencyRepositoryInterface::class, NginxRequestFrequencyRepository::class);
        $this->app->bind(OfTagsGroupRepositoryInterface::class, OfTagsGroupRepository::class);

        // Services
        $this->app->bind(ElasticSearchServiceInterface::class, ElasticSearchService::class);

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
