#!/bin/sh

/usr/bin/docker exec ct550-app php artisan app:update-order-status >> /home/hoaian/CT550/backend/docker/cron/logs/order-update-status.log 2>&1
