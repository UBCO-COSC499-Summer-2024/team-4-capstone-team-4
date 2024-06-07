FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/htm

# Install PostgreSQL client package
RUN apt-get update \
    && apt-get install -y postgresql-client \
    && rm -rf /var/lib/apt/lists/*

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libpq-dev

# Install PHP extensions
RUN docker-php-ext-install pgsql pdo_pgsql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . /var/www/html

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www/html

# Change current user to www-data
USER www-data

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]