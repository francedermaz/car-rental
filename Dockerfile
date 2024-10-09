# Usa la imagen oficial de PHP
FROM php:8.1-apache

# Instala las dependencias necesarias para Symfony
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpq-dev \
    libonig-dev \
    unzip \
    git \
    wget \
    && docker-php-ext-install intl pdo pdo_pgsql opcache

# Habilita mod_rewrite para Apache
RUN a2enmod rewrite

# Instala Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash
ENV PATH="$HOME/.symfony*/bin:$PATH"

# Crea un nuevo usuario para evitar ejecutar como root
RUN useradd -m symfonyuser

# Cambia al nuevo usuario
USER symfonyuser

# Configura el directorio de trabajo
WORKDIR /var/www/html/public

# Copia los archivos del proyecto
COPY --chown=symfonyuser:symfonyuser . .

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instala las dependencias de Symfony sin ejecutar scripts
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Expone el puerto 80 para la aplicación
EXPOSE 80

# Establece el comando predeterminado para iniciar Apache
CMD ["apache2-foreground"]
