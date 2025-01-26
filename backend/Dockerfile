# Use an official Apache PHP image
FROM php:8.1-apache

# Install necessary PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable mod_rewrite for Apache
RUN a2enmod rewrite

# Copy backend files to Apache's default directory
COPY . /var/www/html/

# Expose Apache port
EXPOSE 80
