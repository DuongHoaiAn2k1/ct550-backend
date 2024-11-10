#!/bin/sh

/usr/bin/docker exec ct550-app php artisan app:update-order-status >> /var/www/html/ct550/docker/cron/logs/order-update-status.log 2>&1
