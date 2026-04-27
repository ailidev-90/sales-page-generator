FROM node:20-alpine AS assets

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY postcss.config.js tailwind.config.js vite.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm run build

FROM php:8.4-cli

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip \
    && docker-php-ext-install pdo_mysql opcache \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .
COPY --from=assets /app/public/build ./public/build
COPY docker/start.sh /usr/local/bin/start

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress \
    && chmod +x /usr/local/bin/start \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache

EXPOSE 8080

CMD ["start"]
