FROM php:8.2-apache

# Disable conflicting MPM modules and enable prefork
RUN a2dismod mpm_event mpm_worker 2>/dev/null || true \
    && a2enmod mpm_prefork

# Install PHP MySQL support
RUN docker-php-ext-install mysqli

# Enable URL rewriting
RUN a2enmod rewrite

# Verify Apache configuration during the image build
RUN apache2ctl -M \
    && apache2ctl configtest

# Copy application files
COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80