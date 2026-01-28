#!/usr/bin/env bash
# Exit on error
set -o errexit

# Install Composer dependencies
composer install --no-dev --optimize-autoloader --no-interaction

# Clear and cache configuration
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Run database migrations
php artisan migrate --force

# Seed database (optional - remove if you don't want demo data in production)
# php artisan db:seed --force

# Cache configuration and routes for better performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chmod -R 775 storage bootstrap/cache
