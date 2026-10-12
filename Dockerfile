FROM php:8.2-apache

# Disable all Apache MPM modules first
RUN set -eux; \
    for mpm in mpm_event mpm_worker mpm_prefork; do \
        a2dismod "$mpm" 2>/dev/null || true; \
    done

# Enable only prefork MPM and URL rewriting
RUN a2enmod mpm_prefork rewrite

# Install the MySQL extension for PHP
RUN docker-php-ext-install mysqli

# Verify Apache configuration during the build
RUN apache2ctl -t && apache2ctl -M

# Copy the project into Apache's web directory
COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80