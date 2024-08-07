
## Requirements

- Docker & Docker Compose.
- Composer
- Node.js & npm

## Installation & Setup
1. Start the Docker services and enter it: ``` ./run-prod.sh up -d && ./exec.sh```
2. Install composer dependencies: ``` composer install ```
3. Install JavaScript dependencies: ``` npm install ```
4. Create & setup .env config:  ``` cp .env.example .env ```
5. Set the application key:  ``` php artisan key:generate ```
6. Import of_users table to MySQL Database: ``` mysql --default-character-set=utf8 --comments --database={DB_NAME}  < "of_performer.sql" ```
7. Run migrations: ``` php artisan migrate ```
8. Build project: ``` npm run build ```
9. Import the OfUser model into the search index ``` php artisan scout:import "App\Models\OfUser" ```
10. Run SSR server: ``` php artisan inertia:start-ssr```

## Elasticsearch
Indexing:       
``` php artisan scout:import "App\Models\OfUser" ```        
Reindexing:     
``` php artisan scout:flush "App\Models\OfUser" ```       
``` php artisan scout:import "App\Models\OfUser" ```

## Development
Run ``` npm run dev ```

## Deployment
- To run app in production mode: ``` ./run-prod.sh up -d ```
- To run app in dev mode:``` ./run-dev.sh up -d ```
- To stop app:``` ./stop.sh ```
- To build app:``` ./vite-build.sh ```
- To open terminal inside web service:``` ./exec.sh ```
- To deploy changes from repo and run app in production mode:``` ./deploy.sh ```
- To remove all containers and images of docker:  [cleanup-docker.sh](cleanup-docker.sh) 

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
