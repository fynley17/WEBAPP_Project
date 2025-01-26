# Use an official Apache PHP image
FROM php:8.1-apache

# Install necessary PHP extensions
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install mysqli pdo pdo_mysql gd


# Enable mod_rewrite for Apache
RUN a2enmod rewrite

# Copy backend files to Apache's default directory
COPY . /var/www/html/

# Expose Apache port
EXPOSE 80
