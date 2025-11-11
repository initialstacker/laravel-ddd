# Use official PHP 8.4 FPM image as base
FROM php:8.4-fpm-bullseye

# Install system dependencies and PHP extensions required for the application
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip curl supervisor netcat-openbsd libpq-dev libicu-dev libzip-dev libonig-dev \
    libfreetype6-dev libjpeg-dev libpng-dev zlib1g-dev libxml2-dev pkg-config ca-certificates \
    autoconf build-essential redis-tools libc-client-dev libkrb5-dev librabbitmq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_pgsql mbstring intl zip exif sockets opcache pcntl

# Install Redis PHP extension via PECL and enable it
RUN pecl install -f redis \
    && docker-php-ext-enable redis

# Install IMAP PHP extension via PECL and enable it
RUN pecl install imap \
    && docker-php-ext-enable imap

# Install AMQP PHP extension (RabbitMQ) via PECL and enable it
RUN pecl install amqp \
    && docker-php-ext-enable amqp

# Manually install APCu extension from source and enable it
RUN pecl install apcu \
    && docker-php-ext-enable apcu \
    && rm -rf /tmp/pear

# Set timezone environment variable
ENV TZ=Europe/Moscow

# Define build arguments for user and group IDs to match host user 'anonim'
ARG USER_ID=1000
ARG GROUP_ID=1000

# Create or modify group 'anonim' with specified GID
# Create or modify user 'anonim' with specified UID and GID, without login shell
RUN groupmod -g ${GROUP_ID} anonim 2>/dev/null || groupadd -g ${GROUP_ID} anonim \
    && usermod -u ${USER_ID} -g ${GROUP_ID} anonim 2>/dev/null || useradd -u ${USER_ID} -g ${GROUP_ID} -M -s /usr/sbin/nologin anonim

# Fix ownership of any files previously created with UID/GID 1000 to new UID/GID to avoid permission issues
RUN find / -user 1000 -exec chown -h ${USER_ID} {} \; || true \
    && find / -group 1000 -exec chgrp -h ${GROUP_ID} {} \; || true

# Create home directory and composer cache directory with correct ownership
RUN mkdir -p /home/anonim/.composer/cache \
    && chown -R anonim:anonim /home/anonim

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php

# Set working directory to backend source code folder
WORKDIR /srv/ddd/src

# Copy backend source code with correct ownership
COPY --chown=anonim:anonim ./src /srv/ddd/src

# Install PHP dependencies with optimized autoloader for production
# RUN composer install --prefer-dist --optimize-autoloader

# Generate optimized autoload files (optional but recommended)
# RUN composer dump-autoload --optimize

# Copy custom PHP and PHP-FPM configuration files
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini
COPY ./docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

# Create storage/logs directory and set permissions for writing logs
RUN mkdir -p storage/logs \
    && chown -R anonim:anonim storage \
    && chmod -R 775 storage

# Copy SSL certificates and set secure file permissions
COPY ./certs /var/www/html/certs
RUN find /var/www/html/certs -type f -exec chmod 644 {} \;

# Copy wait-for-rabbitmq.sh script and make it executable
COPY ./docker/supervisord/wait-for-rabbitmq.sh /usr/local/bin/wait-for-rabbitmq.sh
RUN chmod +x /usr/local/bin/wait-for-rabbitmq.sh

# Switch to non-root user 'anonim' for security
USER anonim

# Expose PHP-FPM port
EXPOSE 9000

# Copy supervisord configuration and set it as the container entrypoint
COPY ./docker/supervisord/supervisord.conf /etc/supervisord/supervisord.conf

# Start supervisord to manage PHP-FPM and other processes
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord/supervisord.conf"]
