#!/bin/bash
# Wait for database, then run migrations
php artisan migrate --force
# Start the web server
apache2-foreground
