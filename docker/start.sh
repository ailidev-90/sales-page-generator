#!/usr/bin/env sh
set -e

PORT="${PORT:-8080}"

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

php artisan config:clear
php artisan route:clear

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    php artisan migrate --force
fi

if [ "${RUN_SEEDER:-false}" = "true" ]; then
    php artisan db:seed --force
fi

php artisan config:cache
php artisan route:cache

exec php artisan serve --host=0.0.0.0 --port="${PORT}"
