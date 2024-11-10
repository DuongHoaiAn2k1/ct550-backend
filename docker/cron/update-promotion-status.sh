#!/bin/sh

/usr/bin/docker exec ct550-app php artisan app:update-status-promotion >> /var/www/html/ct550/docker/cron/logs/promotion-update-status.log 2>&1
