#!/bin/bash

# Переменные
ES_HOST="localhost"
ES_PORT="9200"

# Проверка состояния кластера
curl -X GET "$ES_HOST:$ES_PORT/_cluster/health?pretty"

# Проверка состояния индексов
curl -X GET "$ES_HOST:$ES_PORT/_cat/indices?v"

# Проверка статистики узлов
curl -X GET "$ES_HOST:$ES_PORT/_nodes/stats?pretty"
