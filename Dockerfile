# Use FrankenPHP official image with PHP 8.3
FROM dunglas/frankenphp:1-php8.3

# Set working directory
WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libpq-dev \
    default-mysql-client \
    postgresql-client \
    supervisor \
    && pecl install redis \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql mysqli mbstring exif pcntl bcmath gd zip opcache\
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Configure PHP for production
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=20000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Copy application code
COPY . .

# Set proper permissions for Laravel
RUN chown -R www-data:www-data /app \
    && chmod -R 755 /app/storage \
    && chmod -R 755 /app/bootstrap/cache \
    && chmod +x /app/artisan

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy FrankenPHP configuration
COPY docker/frankenphp/Caddyfile /etc/caddy/Caddyfile

# Copy supervisor configuration
COPY docker/supervisor/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf

# Set up environment file and database
RUN cp .env.example .env
RUN touch database/database.sqlite

# Generate Laravel application key if not set
RUN php artisan key:generate --force

# Cache Laravel configuration and routes
RUN php artisan package:discover --ansi \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Run database migrations and seeders
RUN php artisan migrate --force --seed

# Install Node.js and build frontend assets
RUN apt update && apt install -y nodejs npm
RUN npm install
RUN npm run build

# Expose port 80
EXPOSE 80

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

# Create startup script
RUN echo '#!/bin/bash' > /usr/local/bin/start.sh \
    && echo 'supervisord -c /etc/supervisor/supervisord.conf &' >> /usr/local/bin/start.sh \
    && echo 'exec frankenphp run --config /etc/caddy/Caddyfile' >> /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

# Start services
CMD ["/usr/local/bin/start.sh"]
