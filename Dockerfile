# Use the custom serversideup/php Docker image tagged as 'beta-8.3-unit'.
# This image will be referred to as 'base' in the following steps.
FROM serversideup/php:beta-8.3-unit as base

# Create a new Docker image named 'development' based on the 'base' image.
FROM base as development

ENV AUTORUN_LARAVEL_MIGRATION=true

# Provide two arguments for the build process: USER_ID and GROUP_ID
ARG USER_ID
ARG GROUP_ID

# Run a custom script inside the Docker image to match the 'www-data' user and group ID
# to the USER_ID and GROUP_ID provided as arguments.
# This resolves permission issues in a development environment.
RUN docker-php-serversideup-set-id www-data ${USER_ID} ${GROUP_ID}

# Create a new Docker image named 'deploy' based on the 'base' image.
FROM base as deploy

# Copy all files from the current directory to the /var/www/html directory in Docker image.
# Also, change the ownership of the files to the 'www-data' user and group.
COPY --chown=www-data:www-data . /var/www/html

# Run composer, a PHP dependency management tool, to install project dependencies.
# Options are provided to run composer quietly, without any scripts, dev-dependencies, and cache.
# Also, autoloader is optimized after vendor packages are installed.
RUN composer install --no-cache --no-dev --no-scripts --no-autoloader --ansi --no-interaction \
    && composer dump-autoload -o

EXPOSE 80
