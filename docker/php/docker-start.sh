#!/bin/bash
composer require predis/predis
composer require tymon/jwt-auth
composer install
php /var/www/artisan key:generate
php /var/www/artisan migrate
php /var/www/artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
php /var/www/artisan jwt:secret
#php /var/www/artisan queue:listen --timeout=0 &

php-fpm