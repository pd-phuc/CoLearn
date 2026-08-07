FROM php:8.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    icu-dev \
    postgresql-dev \
    linux-headers \
    oniguruma-dev \
    $PHPIZE_DEPS

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        pgsql \
        gd \
        zip \
        bcmath \
        intl \
        mbstring \
        opcache \
        pcntl

# Install Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js 20 (for Vite/Tailwind builds inside container if needed)
RUN apk add --no-cache nodejs npm

# Configure PHP-FPM
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Set upload limits for video/file uploads
RUN echo "upload_max_filesize=100M" >> "$PHP_INI_DIR/php.ini" \
    && echo "post_max_size=100M" >> "$PHP_INI_DIR/php.ini" \
    && echo "memory_limit=256M" >> "$PHP_INI_DIR/php.ini" \
    && echo "max_execution_time=120" >> "$PHP_INI_DIR/php.ini"

# Set working directory
WORKDIR /var/www/html

# Create system user for running Composer and Artisan
RUN addgroup -g 1000 -S www \
    && adduser -u 1000 -S www -G www

# Set permissions
RUN chown -R www:www /var/www/html

USER www

EXPOSE 9000
CMD ["php-fpm"]
