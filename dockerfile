FROM php:8.2-apache

RUN docker-php-ext-install pdo_mysql mysqli

RUN php -m | grep -E "pdo_mysql|mysqli"

COPY . /var/www/html/
