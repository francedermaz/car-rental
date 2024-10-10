FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpq-dev \
    libonig-dev \
    unzip \
    git \
    wget \
    && docker-php-ext-install intl pdo pdo_pgsql opcache

RUN a2enmod rewrite

# Example: Disable unnecessary modules (adjust as needed)
RUN a2dismod autoindex headers ...

# Example: Custom Apache configuration (optional)
COPY apache.conf /etc/apache2/apache2.conf

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-scripts

EXPOSE 80

# Use supervisord for production (optional)
# CMD ["supervisord", "-n"]
CMD ["apache2", "-f", "/etc/apache2/apache2.conf"]