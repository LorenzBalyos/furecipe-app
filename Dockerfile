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

# --------------------------------------------------------------------------
# CRITICAL FIX: Build the actual firebase_credentials.json file inside the container.
# This forces the full key to be present without getting clipped by Render.
# --------------------------------------------------------------------------
RUN mkdir -p /var/www/html/storage/app && echo '{\n\
  "type": "service_account",\n\
  "project_id": "furecipe",\n\
  "private_key_id": "b0c5e9888d72c64c75e0dba512325a1417fcefa6",\n\
  "private_key": "-----BEGIN PRIVATE KEY-----\\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDCOH2gSZtRajEY\\ntOQsY0seyB6rFi7eZfdigKIUNfWlTP3kqHymlJ+p7RRzNGOs/lR8Nb7kmUDYbbHw\\nOzhmS7uuBTTtioFxsnBvc7kITJkEJnav5EBIaHtW+VBf7KXvuV6f1k1RRdzlpoJZ\\nhB7jhjmsmVvGSaQjOaWzqdcG7mqL6yf+7YlikQ25RsTeME+wUtO6fT5dp+acuKL1\\nT2eUWbql7Ol+DthG1KFZm+LYhxdqXVaNab6YEJDUw51XOhxGk/Qkwy28+Kw6i2wY\\nKO7fB1LGHJ6CyZ/AGaF31AJHSTVDG+ApVLOv6sLiQRInnb4peltzI2wNhfutxe1U\\n4WBFr009AgMBAAECggEAAzmdams2e0pcYNjmHg+TCIKRsc0XP6WpTzcsGdJQjyth\\nXunmUfzPTurLJ2OUESQTApIA5rpdv4pIxWCyXc59ohTfjV86qYjpQYyGIstk0hMS\\nbvbHE3y6qZXPvhwHoFyvY8+S7pnOoniamwJs4eHRdNqCEd+YrGYRaJOWKL0OQ17/\\nkjEtss+kqjCvnr5H0S2OZpF71eYbPQRK5gcmoBNSBoiXwxaLA254MgAZIw6vozRq\\nidjHwOvjPL/G1zKvir4tQgUeYf4EROI2U6JMq1vQO/un7GVUxKypNpjDIMBplmYJ\\n6/HzT1OCraSA8sAv/eSdMx7Ix5Gtjgoif/28FY86CQKBgQD5S5KvzJMHdrsvL4yf\\nsVLU0GmQGLiZKQs7qm6/vn95lBX8vQXq0FApKGfJ+LAXPrf458JTbwygt0jg/QVy\\ncqA0poK18ndyjT6daSA70ojiKGBLX8iGoRyIPCI9YjatgINjxhyU4cwSYHl9HM+b\\nIO7H8mVrGakkoMbJGAegm6XXdQKBgQDHcbkW3k6ZjzW1OJKAMX0f/r8TZC0yfOc7\\n0LewEwcaOwmbuufaPPJL+WHvydPljiPeJNgH5nfQra2k5KNnrvfORFpeAZMZ00uz\\nBThDoBxwyBrLr2H6es7IXhqiIoT0IfSu982sS7i2O/z116raRDZlwJ0X+1duBhI+\\n2Vj+7vKtqQKBgQCXy0VxF+66z7fgXmKQ5icagCCW5gaY893sIbW3uSXjgKD2uJ3+\\nwYLd3MV9vJh0PvNEctHnfanBvnh4znffnR4hkPXsFcxXituCe31uD57bnlwr6RGv\\nsDAwX/U1TTUf5i2atgm3OdnJosa2wLFEswPR6a2JTiGotOD1l1UlnUCY9QKBgCyR\\ncC/5C41rIDBX7Pzykih1L9Owfh0bJj0KnhGdnuewq6v+L1NobSinGMiRzGUuxzsb\\n9p6FVN7wAK6pXQ6NXdjdE2iQZ4PM4MynbPRHsjNNtBcyIO3tRYom5UK3/gfkEp6K\\nx44p+aPXceaqBAb0WUeRrEkwpE/00Vz0CtwlH1vJAoGALBIYF0ObXhdu0ewt5kNu\\nmkSGRcBzn1+tk3ytMNf4PJUNT3PZKNDEziQHBkRQDH7Z9J3uJBt4zJ2MLe11qszQ\\nVLG3l59DGx2lK+WREd0XfIbpuEqx5bW8GU9kobawT4+/S3on0iJskCLNgJO9Gp3W\\nyYEn1eqvbFJpoiKstN1NZ9I=\\n-----END PRIVATE KEY-----\\n",\n  "client_email": "firebase-adminsdk-fbsvc@furecipe.iam.gserviceaccount.com",\n  "client_id": "100422765697958537065",\n  "auth_uri": "https://accounts.google.com/o/oauth2/auth",\n  "token_uri": "https://oauth2.googleapis.com/token",\n  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",\n  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-fbsvc%40furecipe.iam.gserviceaccount.com",\n  "universe_domain": "googleapis.com"\n}' > /var/www/html/storage/app/firebase_credentials.json

# Install dependencies without dev packages
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Fix permissions for Laravel storage and bootstrap cache folders (and our new json file)
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
