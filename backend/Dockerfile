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
    nano \
    npm \
    sudo \
    && docker-php-ext-install mysqli zip pdo pdo_mysql \
    && apt-get clean

# Enable Apache rewrite module
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create a custom user and group
RUN groupadd -g 1000 dev && \
    useradd -u 1000 -g dev -m -s /bin/bash dev && \
    usermod -aG sudo dev

# Set up sudo to allow no-password sudo for "dev"
RUN echo "dev ALL=(ALL) NOPASSWD:ALL" >> /etc/sudoers

# Copy custom .bashrc from .devcontainer directory to the dev user's home directory
COPY .bashrc /home/dev/.bashrc
RUN chown dev:dev /home/dev/.bashrc

# Set the working directory
WORKDIR /var/www/html

# Update the Apache configuration to use the public directory as the document root
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Update the Apache configuration to allow .htaccess overrides
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Change ownership of the working directory to the dev user
RUN chown -R dev:dev /var/www/html

# Switch to the dev user
USER dev

# Expose port 80
EXPOSE 80
