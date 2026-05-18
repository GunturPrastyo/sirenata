# Stage 1: Build Dependencies (Composer)
FROM composer:2.7 AS vendor
WORKDIR /app
COPY . .
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-reqs --no-scripts

# Stage 2: Build Frontend Assets (Node)
FROM node:20 AS frontend
WORKDIR /app
COPY package.json package-lock.json vite.config.js ./
RUN npm install
COPY . .
RUN npm run build

# Stage 3: Production PHP Image (App)
FROM php:8.3-fpm-alpine AS app

RUN apk add --no-cache \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    zlib-dev \
    libxml2-dev \
    oniguruma-dev \
    zip \
    unzip \
    git

RUN docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd xml

WORKDIR /var/www/html

COPY . .
COPY --from=vendor /app/vendor/ /var/www/html/vendor/
COPY --from=frontend /app/public/build/ /var/www/html/public/build/

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]

# Stage 4: Production Nginx Image (Web)
FROM nginx:alpine AS web

# Copy Nginx config
COPY docker/nginx/nginx.conf /etc/nginx/conf.d/default.conf

# Copy ONLY the public folder (including built assets) to Nginx
WORKDIR /var/www/html
COPY ./public ./public
COPY --from=frontend /app/public/build ./public/build
