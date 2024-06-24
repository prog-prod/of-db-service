<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Telescope::night();

        $this->hideSensitiveRequestDetails();
        Telescope::tag(function (IncomingEntry $entry) {
            $tags = [];
            if ($entry->type === 'request') {
                $tags = [...$tags,
                    'status:' . $entry->content['response_status'],
                    'method:' . $entry->content['method'],
                ];
                if(isset($entry->content['headers']['x-real-ip'])) {
                    $tags = [...$tags,
                        'ip:' . $entry->content['headers']['x-real-ip'],
                    ];
                }
                if(isset($entry->content['headers']['user-agent'])) {
                    if(str_contains($entry->content['headers']['user-agent'], 'bot')){
                        $tags = [...$tags,
                            'bots',
                        ];
                    }
                    if(str_contains($entry->content['headers']['user-agent'], 'google')){
                        $tags = [...$tags,
                            'google-bot',
                        ];
                    }
                    if(str_contains($entry->content['headers']['user-agent'], 'bing')){
                        $tags = [...$tags,
                            'bing-bot',
                        ];
                    }
                    if(str_contains($entry->content['headers']['user-agent'], 'yandex')){
                        $tags = [...$tags,
                            'yandex-bot',
                        ];
                    }
                    if(!str_contains($entry->content['headers']['user-agent'], 'bot')){
                        $tags = [...$tags,
                            'real-user',
                        ];
                    }
                }
                if ($entry->isClientRequest()) {
                    $tags = [...$tags,
                        'visitor',
                    ];
                }
                if (isset($entry->content['uri'])) {
                    $tags = [...$tags,
                        'uri:' . $entry->content['uri'],
                    ];
                }
                if ($entry->isFailedRequest()) {
                    $tags = [...$tags,
                        'failed',
                    ];
                }
                if ($entry->user) {
                    $tags = [...$tags,
                        'user:' . $entry->user->id,
                    ];
                }
                if ($entry->content['duration'] > 1000) {
                    $tags = [...$tags,
                        'slow',
                    ];
                }
            }
            if ($entry->type === 'query') {
                if($entry->isSlowQuery()){
                    $tags = [...$tags,
                        'slow-query',
                    ];
                }
            }
            return $tags;
        });
        $isLocal = true;//$this->app->environment('local');

        Telescope::filter(function (IncomingEntry $entry) use ($isLocal) {
            return $isLocal ||
                $entry->isReportableException() ||
                $entry->isFailedRequest() ||
                $entry->isFailedJob() ||
                $entry->isScheduledTask() ||
                $entry->hasMonitoredTag();
        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     */
    protected function hideSensitiveRequestDetails(): void
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewTelescope', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }
}
