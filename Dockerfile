FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Clear any cached package discovery from local dev
RUN rm -f bootstrap/cache/*.php

# Install PHP dependencies without dev packages
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Install Node dependencies and build assets
RUN npm install && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Create entrypoint script to fix permissions at runtime
RUN echo '#!/bin/sh\n\
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache\n\
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache\n\
    # Create storage link if it does not exist\n\
    if [ ! -L /var/www/html/public/storage ]; then\n\
    php artisan storage:link\n\
    fi\n\
    # Clear and cache config for production\n\
    php artisan config:cache\n\
    php artisan route:cache\n\
    php artisan view:cache\n\
    exec php-fpm' > /usr/local/bin/docker-entrypoint.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

CMD ["/usr/local/bin/docker-entrypoint.sh"]
