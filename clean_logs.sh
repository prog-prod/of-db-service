#!/bin/bash

# Загрузка переменных из файла .env
set -a
source .env
set +a

# Переменные
MYSQL_ROOT_PASSWORD=$DB_PASSWORD

# Очистка Laravel логов
sudo truncate -s 0 ./web/storage/logs/laravel.log

# Очистка Nginx логов
sudo truncate -s 0 /var/log/nginx/access.log
sudo truncate -s 0 /var/log/nginx/error.log

# Очистка Docker логов для всех контейнеров
for LOG_FILE in $(sudo find /var/lib/docker/containers/ -type f -name "*.log")
do
  sudo truncate -s 0 $LOG_FILE
done

# Удаление устаревших binlog файлов MySQL, оставляем последние 2 файла
docker exec onlygirls-db mysql -u root -p${MYSQL_ROOT_PASSWORD} -e "PURGE BINARY LOGS BEFORE DATE_SUB(NOW(), INTERVAL 2 DAY);"

echo "Очистка завершена"
