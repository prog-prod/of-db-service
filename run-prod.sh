#!/bin/bash
set -e

. .env
echo "Запуск в режиме продакшна..."
docker compose -f docker-compose.yml -f docker-compose.prod.yml $@