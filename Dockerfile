# Use an official PHP image with the version compatible with your Symfony application
FROM php:8.2-fpm-alpine

# Install dependencies required by Symfony
RUN apk update && apk add --no-cache \
    git \
    curl \
    libpng \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    zlib-dev \
    libxpm-dev \
    libxml2-dev \
    zip \
    unzip

# Install PHP extensions required by Symfony
RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-xpm \
    && docker-php-ext-install pdo pdo_mysql gd exif pcntl bcmath opcache

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory to /var/www
WORKDIR /var/www

# Copy the application code to the container
COPY . /var/www

# Optional: if you use Symfony Flex, you might need to copy the SSL certificates
# COPY --from=composer:latest /etc/ssl/certs/ca-certificates.crt /etc/ssl/certs/

# Expose port 9000
EXPOSE 9000

# Start PHP-FPM server
CMD ["php-fpm"]