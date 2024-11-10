#!/bin/sh

/usr/bin/docker exec ct550-app php artisan batch:update-status >> /var/www/html/ct550/docker/cron/logs/batch-update-status.log 2>&1
