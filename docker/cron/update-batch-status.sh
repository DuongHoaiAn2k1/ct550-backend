#!/bin/sh

/usr/bin/docker exec ct550-app php artisan batch:update-status >> /home/hoaian/CT550/backend/docker/cron/logs/batch-update-status.log 2>&1
