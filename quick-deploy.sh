#!/bin/bash
echo "Pulling changes from repository..."
git pull origin master

echo "Pausing server..."
./run-prod.sh exec -T api php artisan down

echo "Building project..."
./run-prod.sh exec -T api yarn install
./run-prod.sh exec -T api yarn run build

echo "Optimizing project..."
./run-prod.sh exec -T api php artisan config:cache
./run-prod.sh exec -T api php artisan event:cache
./run-prod.sh exec -T api php artisan route:cache
./run-prod.sh exec -T api php artisan view:cache
./run-prod.sh exec -T api php artisan optimize

echo "Starting server..."
./run-prod.sh exec -T api php artisan up
