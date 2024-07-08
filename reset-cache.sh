#!/bin/bash

echo "Очистка кеша..."
./run-prod.sh exec -T api php artisan cache:clear;

echo "Очистка кеша бд..."

./run-prod.sh exec -T api php artisan cache:clear-db;

echo "Перезапускаем SSR..."
./restart-ssr.sh exec -T api php artisan inertia:stop-ssr;

echo "Кеш очищен и SSR перезапущен."