FROM php:8.3.2-fpm

# Install necessary libraries for postgresql
RUN apt-get update && apt-get install -y libpq-dev

# Install pdo_pgsql and pgsql PHP extensions
RUN docker-php-ext-install pdo_pgsql pgsql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . /var/www/html/

# Install dependencies
RUN composer install
