#!/bin/sh

/usr/bin/docker exec ct550-app php artisan user:update-user-points >> /var/www/html/ct550/docker/cron/logs/update-user-point.log 2>&1
