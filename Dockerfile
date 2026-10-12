FROM php:8.2-apache

# Fix "More than one MPM loaded": keep only mpm_prefork (required for PHP)
RUN a2dismod mpm_event mpm_worker || true \
 && a2enmod mpm_prefork

# PHP extensions for MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy your project
COPY . /var/www/html/

# Make Apache listen on Railway's PORT instead of 80
CMD sed -i "s/80/${PORT:-80}/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf \
 && apache2-foreground