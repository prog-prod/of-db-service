<?php

namespace App\Services;

use App\Contracts\ElasticSearchServiceInterface;
use App\Contracts\SearchableModel;
use App\Models\OfUser;
use App\Utilities\ElasticSearchResponseProcessor;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Response\Elasticsearch;
use Http\Promise\Promise;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class ElasticSearchService implements ElasticSearchServiceInterface
{
    public Client $client;
    private SearchableModel $model;
    private array $wheresIn;
    private array $wheres;
    private int $size = 10;
    private Promise|Elasticsearch $response;

    /**
     * @throws AuthenticationException
     */
    public function __construct()
    {
        if(!app()->environment('production')){
            $logger = new Logger('elastic_search_logger');
            $logger->pushHandler(new StreamHandler(storage_path('/logs/elastic_logs'), Logger::DEBUG));
        }

        $client = ClientBuilder::create()
            ->setHosts(config('scout.elasticsearch.hosts'));

        if(!app()->environment('production')) {
            $client->setLogger($logger);
        }

        $this->client = $client->build();

        $this->setModel(new OfUser());
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setModel(SearchableModel $model): self
    {
        $this->model = $model;
        return $this;
    }
    public function whereIn(string $field, array $values): self
    {
        $this->wheresIn[$field] = $values;
        return $this;
    }
    public function where(string $field, mixed $value): self
    {
        $this->wheres[$field] = $value;
        return $this;
    }


    public function search($params = [], $randomize = false): ?ElasticSearchResponseProcessor
    {
        $params = $this->buildParams($params, $randomize);
        try {
            $this->response = $this->client->search($params);
            return new ElasticSearchResponseProcessor($this->response);
        } catch (\Exception $e) {
//            dd($e->getMessage());
            return null;
        }
    }

    public function limit(int $limit): self
    {
        $this->size = $limit;
        return $this;
    }
    protected function buildParams(array $params, bool $randomize): array
    {
        $params = [
            'index' => $this->model->searchableAs(),
            'body'  => [
                'query' => [
                    // default values
                ]
            ],
            ...$params
        ];

        if($randomize) {
            $params['body']['query']['function_score']['random_score'] = new \stdClass();
        }

        if(!empty($this->wheresIn)){
            foreach ($this->wheresIn as $field => $values){
                $params['body']['query']['function_score']['query']['bool']['filter']['terms'][$field] = $values;
            }
        } else if(!empty($this->wheres)) {
            foreach ($this->wheres as $field => $values){
                $params['body']['query']['function_score']['query']['bool']['filter']['term'][$field] = $values;
            }
        }

        $params['body']['size'] = $this->size;

        return $params;
    }
}
