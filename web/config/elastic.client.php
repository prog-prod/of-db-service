<?php declare(strict_types=1);

return [
    'default' => env('ELASTIC_CONNECTION', 'default'),
    'connections' => [
        'default' => [
            'hosts' => [
                env('ELASTIC_HOST1', 'localhost:9200'),
                env('ELASTIC_HOST2', 'localhost:9200'),
            ],
        ],
    ],
];
