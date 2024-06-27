<?php

namespace App\Console\Commands;

use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Illuminate\Console\Command;

class ElasticsearchUpdateSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scout:update_max_result_window';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Increasing max_result_window for of_users index';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws AuthenticationException
     */
    public function handle()
    {
        $index = 'of_users';
        $maxResultWindow = 10000;

        $client = ClientBuilder::create()->setHosts(config('scout.elasticsearch.hosts'))->build();

        $params = [
            'index' => $index,
            'body' => [
                'settings' => [
                    'index' => [
                        'max_result_window' => $maxResultWindow,
                    ]
                ]
            ]
        ];

        try {
            $response = $client->indices()->putSettings($params);
            $this->info("Updated max_result_window for index {$index} to {$maxResultWindow}");
        } catch (\Exception $e) {
            $this->error("Error updating settings: " . $e->getMessage());
        }
        return Command::SUCCESS;
    }
}
