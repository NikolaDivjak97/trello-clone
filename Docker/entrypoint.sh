#!/bin/bash

composer install --ignore-platform-req=ext-gd
composer update

php artisan migrate --seed
php artisan key:generate

php artisan serve --port=8000 --host=0.0.0.0 --env=.env
exec docker-php-entrypoint "$@"
