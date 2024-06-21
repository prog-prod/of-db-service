#!/bin/bash
echo "Pulling changes from repository..."
git pull origin master

echo "Pausing server..."
./run-prod.sh exec -T web php artisan down

echo "Updating composer packages..."
./run-prod.sh exec -T web composer update --no-dev --optimize-autoloader --no-interaction

echo "Building project..."
./run-prod.sh exec -T web yarn install
./run-prod.sh exec -T web yarn run build

echo "Running migrate command..."
./run-prod.sh exec -T web php artisan migrate --force

echo "Optimizing project..."
./run-prod.sh exec -T web php artisan config:cache
./run-prod.sh exec -T web php artisan event:cache
./run-prod.sh exec -T web php artisan route:cache
./run-prod.sh exec -T web php artisan view:cache
./run-prod.sh exec -T web php artisan optimize

echo "Starting server..."
./run-prod.sh exec -T web php artisan up
