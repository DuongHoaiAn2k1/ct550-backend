#!/bin/sh
cd /home/hoaian/CT550/backend

# Chạy lệnh php artisan schedule:run trong container
# /usr/bin/docker exec ct550-app php artisan schedule:run >> /home/hoaian/CT550/backend/docker/cron/logs/cron.log 2>&1

# Chạy lệnh php artisan batch:update-status trong container
# /usr/bin/docker exec ct550-app php artisan batch:update-status >> /home/hoaian/CT550/backend/docker/cron/logs/batch-update-status.log 2>&1

# echo "Cron ran at $(date)" >> /home/hoaian/CT550/backend/docker/cron/logs/cron-test.log
