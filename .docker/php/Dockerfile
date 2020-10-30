FROM php:7.4-cli

MAINTAINER docoli

COPY . /srv/app
COPY .docker/php/php.ini /usr/local/etc/php
RUN chown -R www-data:www-data /srv/app