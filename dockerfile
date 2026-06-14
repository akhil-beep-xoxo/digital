FROM php:8.2-apache

# Disable conflicting MPM modules
RUN a2dismod mpm_worker mpm_event 2>/dev/null || true

COPY . /var/www/html/

RUN mkdir -p /var/www/html/data \
    && chown -R www-data:www-data /var/www/html/data

EXPOSE 80

CMD ["apache2-foreground"]
