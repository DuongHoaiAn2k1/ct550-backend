#!/bin/sh

/usr/bin/docker exec ct550-app php artisan app:update-status-promotion >> /home/hoaian/CT550/backend/docker/cron/logs/promotion-update-status.log 2>&1
