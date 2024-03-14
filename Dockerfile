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

# Install dependencies using composer image
RUN composer install

# Expose port 8000
EXPOSE 8000
