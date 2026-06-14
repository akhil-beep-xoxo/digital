FROM php:8.2-fpm-alpine

# Install nginx, supervisord, and SQLite PDO extension
RUN apk add --no-cache nginx supervisor sqlite-libs \
    && docker-php-ext-install pdo pdo_sqlite

# Copy nginx configuration
COPY nginx.conf /etc/nginx/nginx.conf

# Copy supervisord configuration
COPY supervisord.conf /etc/supervisord.conf

# Copy application source
COPY . /app/

RUN mkdir -p /app/data \
    && chown -R www-data:www-data /app/data \
    && chown -R www-data:www-data /app

# nginx needs /run/nginx for its PID file on Alpine
RUN mkdir -p /run/nginx

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
