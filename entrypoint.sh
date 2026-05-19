#!/bin/sh

# Clear any cached configuration files so Laravel reads Render Env Variables freshly
php artisan config:clear
php artisan cache:clear

# Run migrations automatically
php artisan migrate --force

# Keep the default Apache container running process active
exec apache2-foreground
