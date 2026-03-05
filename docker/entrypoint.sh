#!/bin/bash
set -e

cd /var/www/html

# Generate .env from environment variables if not present
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Set APP_KEY if not configured
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Seed only if no users exist yet
php artisan db:seed --force

# Cache for production
echo "Caching config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Fix permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
