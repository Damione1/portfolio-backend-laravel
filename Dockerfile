FROM php:8.3.2-fpm

# Install necessary libraries for postgresql and git
RUN apt-get update && apt-get install -y libpq-dev git zip unzip

# Install pdo_pgsql and pgsql PHP extensions
RUN docker-php-ext-install pdo_pgsql pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . /var/www/html/

# Expose port 8000
EXPOSE 8000

# Install dependencies using composer image
RUN composer install

# Generate application key
RUN php artisan key:generate

# Write permissions for Laravel storage and cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Run server
CMD php artisan serve --host=0.0.0.0 --port=8000
