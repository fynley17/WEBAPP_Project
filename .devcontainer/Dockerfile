# Use the official PHP 8.3 image as a base image
FROM php:8.3-apache

# Install necessary PHP extensions and tools
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    curl \
    vim \
    nodejs \
    npm \
    && docker-php-ext-install mysqli zip pdo pdo_mysql

# Enable Apache rewrite module
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy backend code to Apache's document root
COPY backend /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Update the Apache configuration to use the public directory as the document root
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Update the Apache configuration to allow .htaccess overrides
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80
EXPOSE 80
