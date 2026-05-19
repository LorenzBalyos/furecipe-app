FROM php:8.2-apache

# Install system dependencies and PHP extensions needed for Laravel & PostgreSQL
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy the entire project into the container
COPY . .

# Install dependencies without dev packages
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Fix permissions for Laravel storage and bootstrap cache folders
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Change Apache Document Root to Laravel's Public folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# ---- START OF ADDED ENTRYPOINT PROCESS ----
# Give the entrypoint script execution permissions
RUN chmod +x /var/www/html/entrypoint.sh

# Force the container to use a shell execution wrapper for the script
ENTRYPOINT ["/bin/sh", "/var/www/html/entrypoint.sh"]
# ---- END OF ADDED ENTRYPOINT PROCESS ----

EXPOSE 80
