# syntax=docker/dockerfile:1

FROM php:8.3-fpm-alpine AS vendor
RUN apk add --no-cache unzip
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts --no-autoloader
COPY . .
RUN composer dump-autoload --no-dev --optimize

FROM node:20-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json* ./
RUN if [ -f package-lock.json ]; then npm ci; else echo "No package-lock.json: skipping npm ci"; fi
COPY resources ./resources
COPY public ./public
COPY vite.config.js* postcss.config.js* tailwind.config.js* ./
RUN mkdir -p public/build && if [ -f package-lock.json ]; then npm run build; fi

FROM php:8.3-fpm-alpine AS app
RUN docker-php-ext-install pdo_mysql
RUN printf '[www]\nclear_env = no\n' > /usr/local/etc/php-fpm.d/zz-env.conf
WORKDIR /var/www/html
COPY --from=vendor --chown=www-data:www-data /app/ ./
COPY --from=frontend --chown=www-data:www-data /app/public/build ./public/build
RUN mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache && chown -R www-data:www-data storage bootstrap/cache && chmod -R ug+rwX storage bootstrap/cache
USER www-data
EXPOSE 9000
CMD ["php-fpm"]

FROM nginxinc/nginx-unprivileged:1.27-alpine AS web
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY --from=app /var/www/html/public /var/www/html/public