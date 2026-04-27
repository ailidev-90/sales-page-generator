#!/usr/bin/env sh
set -e

PORT="${PORT:-8080}"

export APP_NAME="${APP_NAME:-AI Sales Page Generator}"
export APP_ENV="${APP_ENV:-production}"
export APP_DEBUG="${APP_DEBUG:-false}"
export LOG_CHANNEL="${LOG_CHANNEL:-stderr}"
export LOG_LEVEL="${LOG_LEVEL:-debug}"
export SESSION_DRIVER="${SESSION_DRIVER:-file}"
export CACHE_STORE="${CACHE_STORE:-file}"
export QUEUE_CONNECTION="${QUEUE_CONNECTION:-sync}"

if [ -n "${RAILWAY_PUBLIC_DOMAIN:-}" ]; then
    export APP_URL="https://${RAILWAY_PUBLIC_DOMAIN}"
fi

if [ -z "${APP_KEY:-}" ]; then
    echo "WARNING: APP_KEY is not set. Generating a temporary runtime key. Set APP_KEY in Railway for stable sessions." >&2
    APP_KEY="$(php -r 'echo "base64:".base64_encode(random_bytes(32));')"
    export APP_KEY
fi

if [ -n "${MYSQLHOST:-}" ] || [ -n "${MYSQL_URL:-}" ]; then
    if [ "${DB_CONNECTION:-}" != "mysql" ]; then
        echo "Railway MySQL variables detected. Overriding DB_CONNECTION=${DB_CONNECTION:-unset} to mysql." >&2
    fi

    export DB_CONNECTION=mysql
    export DB_HOST="${DB_HOST:-${MYSQLHOST:-}}"
    export DB_PORT="${DB_PORT:-${MYSQLPORT:-3306}}"
    export DB_DATABASE="${MYSQLDATABASE:-${DB_DATABASE:-railway}}"
    export DB_USERNAME="${MYSQLUSER:-${DB_USERNAME:-root}}"
    export DB_PASSWORD="${MYSQLPASSWORD:-${DB_PASSWORD:-}}"
fi

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache
chmod -R 0777 storage bootstrap/cache

echo "Starting Laravel on port ${PORT}" >&2
echo "APP_NAME=${APP_NAME}, APP_ENV=${APP_ENV}, APP_DEBUG=${APP_DEBUG}, DB_CONNECTION=${DB_CONNECTION:-sqlite}" >&2

php artisan config:clear
php artisan route:clear

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "Running migrations..." >&2
    php artisan migrate --force
fi

if [ "${RUN_SEEDER:-true}" = "true" ]; then
    echo "Running seeders..." >&2
    php artisan db:seed --force
fi

php artisan config:cache
php artisan route:cache

exec php artisan serve --host=0.0.0.0 --port="${PORT}"
