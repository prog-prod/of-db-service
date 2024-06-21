
## Requirements

- Docker & Docker Compose.
- Composer
- Node.js & npm

## Installation & Setup
1. Install composer dependencies: ``` composer install ```
2. Start the Docker services: ``` ./vendor/bin/sail up -d ```
3. Install JavaScript dependencies: ``` ./vendor/bin/sail npm install ```
4. Create & setup .env config:  ``` cp .env.example .env ```
5. Set the application key:  ``` ./vendor/bin/sail artisan key:generate ```
6. Import of_users table to MySQL Database: ``` ./vendor/bin/sail sail mysql --default-character-set=utf8 --comments --database={DB_NAME}  < "of_performer.sql" ```
7. Run migrations: ``` ./vendor/bin/sail artisan migrate ```
8. Build project: ``` ./vendor/bin/sail npm run build ```
9. Import the OfUser model into the search index ``` ./vendor/bin/sail artisan scout:import "App\Models\OfUser" ```
10. Run SSR server: ``` ./vendor/bin/sail artisan inertia:start-ssr```

## Elasticsearch
Indexing:       
``` ./vendor/bin/sail artisan scout:import "App\Models\OfUser" ```        
Reindexing:     
``` ./vendor/bin/sail artisan scout:flush "App\Models\OfUser" ```       
``` ./vendor/bin/sail artisan scout:import "App\Models\OfUser" ```

## Development
Run ``` npm run dev ```

## Deployment
- To run app in production mode: ``` ./run-prod.sh up -d ```
- To run app in dev mode:``` ./run-dev.sh up -d ```
- To stop app:``` ./stop.sh ```
- To build app:``` ./vite-build.sh ```
- To open terminal inside web service:``` ./exec.sh ```
- To deploy changes from repo and run app in production mode:``` ./deploy.sh ```

## Typical errors resolving:
1. 500 bulk operation(s) did not complete successfully. Catch the exception and use the Elastic\Adapter\Exceptions\BulkOperationException::rawResult() method to get more details.
Fix: Run curl -X DELETE "localhost:9200/of_users" to delete index
Reindex Table: ./vendor/bin/sail artisan scout:import "App\Models\OfUser"
2. When mysql container is full Run: FLUSH LOGS;
3. bootstrap check failure [1] of [1]: max virtual memory areas vm.max_map_count [65530] is too low, increase to at least [262144]:
Increase vm.max_map_count sudo sysctl -w vm.max_map_count=262144
## Hints
1. To export DB with correct encoding use --default-character-set=utf8mb4 flag in mysqldump command:
    <br>  mysqldump "only_girls" --result-file="/dumps/{data_source}-{timestamp}-dump.sql" --skip-add-drop-table --skip-disable-keys --skip-add-locks "of_users" --lock-tables --default-character-set=utf8mb4
