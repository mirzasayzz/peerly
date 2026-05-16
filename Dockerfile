FROM php:8.4-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Install PHP dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-req=php

# Install NPM dependencies and build assets
RUN npm install && npm run build

# Change ownership of our applications
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose port 8000 and start php-fpm server
EXPOSE 8000

# Allow multiple concurrent requests to fix the extreme slowness
ENV PHP_CLI_SERVER_WORKERS=5

CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
