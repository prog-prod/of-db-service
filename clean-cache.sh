#!/bin/bash

echo "Clearing cache..."
./run-prod.sh exec -T api php artisan config:clear
./run-prod.sh exec -T api php artisan route:clear
./run-prod.sh exec -T api php artisan view:clear
./run-prod.sh exec -T api php artisan cache:clear

