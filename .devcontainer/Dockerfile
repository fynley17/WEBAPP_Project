FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

# Install necessary packages including wget for downloading files
RUN apt-get update && \
    apt-get install -y apache2 php libapache2-mod-php sudo git \
    php-mysql php-bcmath php-mbstring php-xml php-curl php-zip curl \
    wget apt-transport-https software-properties-common && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Install Node.js (via nvm)
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.1/install.sh | bash \
    && export NVM_DIR="/root/.nvm" \
    && [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh" \
    && nvm install v20.18.0  # This installs both Node.js and npm

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Enable detailed PHP errors
RUN sed -i "s/display_errors = Off/display_errors = On/" /etc/php/8.1/apache2/php.ini && \
    sed -i "s/error_reporting = .*/error_reporting = E_ALL/" /etc/php/8.1/apache2/php.ini

# Set up Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    a2enmod rewrite

# Remove default content from /var/www/html
RUN rm -rf /var/www/html/*

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Expose the web server ports
EXPOSE 80 5500

# Start Apache in the foreground
CMD ["apache2ctl", "-D", "FOREGROUND"]