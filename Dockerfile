FROM php:8.2-fpm-alpine

# System dependencies + build tools
RUN apk add --no-cache \
    nginx \
    supervisor \
    libpng-dev \
    libzip-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    oniguruma-dev \
    zip unzip git curl bash \
    $PHPIZE_DEPS

# PHP extensions (including Redis for caching/sessions)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip opcache \
 && pecl install redis && docker-php-ext-enable redis

# Composer
RUN curl -sS https://getcomposer.org/download/latest-stable/composer.phar -o /usr/bin/composer \
 && chmod +x /usr/bin/composer

WORKDIR /var/www/html

# Install PHP deps first (cache layer)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy app
COPY . .

# Post-install
RUN composer run-script post-autoload-dump --no-interaction || true

# Permissions
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 755 storage bootstrap/cache

# Docker configs
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
