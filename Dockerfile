FROM php:7.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip opcache

# Get Composer (PHP package manager)
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Add our PHP config
COPY ./docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# Set working directory
WORKDIR /var/www/educa/

# What command to run
CMD ["php-fpm"]

# Document that we use port 9000
EXPOSE 9000