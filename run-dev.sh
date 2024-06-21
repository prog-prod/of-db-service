#!/bin/bash
set -e

. .env

echo "Запуск в режиме разработки..."
docker-compose -f docker-compose.yml -f docker-compose.dev.yml $@

