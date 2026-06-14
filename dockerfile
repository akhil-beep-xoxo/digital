FROM php:8.2-apache

# Remove conflicting MPM modules and comment out their LoadModule directives
RUN rm -f /etc/apache2/mods-available/mpm_event.* /etc/apache2/mods-available/mpm_worker.* && \
    rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf && \
    find /etc/apache2 -type f -name "*.conf" -exec sed -i 's/^LoadModule mpm_event_module/#LoadModule mpm_event_module/g' {} \; && \
    find /etc/apache2 -type f -name "*.conf" -exec sed -i 's/^LoadModule mpm_worker_module/#LoadModule mpm_worker_module/g' {} \; && \
    a2enmod mpm_prefork && \
    apache2ctl configtest

COPY . /var/www/html/

RUN mkdir -p /var/www/html/data \
    && chown -R www-data:www-data /var/www/html/data

EXPOSE 80

CMD ["apache2-foreground"]
