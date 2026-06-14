FROM php:8.2-apache

# Disable all MPM modules and enable only mpm_prefork
RUN a2dismod mpm_worker mpm_event mpm_prefork 2>/dev/null || true && \
    a2enmod mpm_prefork

COPY . /var/www/html/

RUN mkdir -p /var/www/html/data \
    && chown -R www-data:www-data /var/www/html/data

EXPOSE 80

CMD ["apache2-foreground"]
