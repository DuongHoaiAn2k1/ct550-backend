#!/bin/sh

/usr/bin/docker exec ct550-app php artisan user:update-user-points >> /home/hoaian/CT550/backend/docker/cron/logs/update-user-point.log 2>&1
