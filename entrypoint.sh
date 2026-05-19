#!/bin/sh

# Run migrations automatically before the server turns on
php artisan migrate --force

# Keep the default Apache container running process active
exec apache2-foreground
