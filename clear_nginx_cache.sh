#!/bin/bash
CACHE_PATH="/var/cache/nginx"
echo "Clearing Nginx cache at $CACHE_PATH"
sudo rm -rf ${CACHE_PATH}/*
echo "Restarting Nginx"
sudo systemctl restart nginx
echo "Cache cleared and Nginx restarted"