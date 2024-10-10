FROM php:8.1-apache

# Install dependencies for Symfony (modify as needed)
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpq-dev \
    libonig-dev \
    unzip \
    git \
    wget \
    && docker-php-ext-install intl pdo pdo_pgsql opcache

# Enable mod_rewrite for Apache
RUN a2enmod rewrite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

# Create a user for running the application (security best practice)
RUN adduser -D symfonyuser

# Set working directory within the container
WORKDIR /var/www/html

# Copy your application files here
COPY . .

# Install dependencies (adjust for your project)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Expose port 80
EXPOSE 80

# Command to start Apache in the foreground (good for development)
CMD ["apache2-foreground"]