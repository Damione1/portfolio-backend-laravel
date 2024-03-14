# Base Image
FROM php:8.3.2-fpm

# Install necessary libraries for postgresql and git
RUN apt-get update && apt-get install -y libpq-dev git zip unzip nginx

# Install pdo_pgsql and pgsql PHP extensions
RUN docker-php-ext-install pdo_pgsql pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory
COPY . /var/www/html/

# Copy Nginx configuration
COPY default.conf /etc/nginx/sites-available/default

# Expose port 80
EXPOSE 80

# Set environment variables for GitHub token
ARG GITHUB_TOKEN=""
ENV GITHUB_TOKEN=${GITHUB_TOKEN}

# Install dependencies
RUN if [ -n "${GITHUB_TOKEN}" ]; then \
        export COMPOSER_AUTH="{\"github-oauth\": {\"github.com\": \"${GITHUB_TOKEN}\"}}"; \
        COMPOSER_MEMORY_LIMIT=-1 composer install -n --no-dev --ansi --prefer-dist --optimize-autoloader; \
    else \
        COMPOSER_MEMORY_LIMIT=-1 composer install --no-interaction; \
    fi

# Run the command on container startup
CMD service nginx start && php-fpm
