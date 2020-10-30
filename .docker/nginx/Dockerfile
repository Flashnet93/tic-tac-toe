FROM nginx:latest

MAINTAINER docoli

COPY ./public /srv/app
COPY .docker/nginx/nginx.conf /etc/nginx/conf.d/default.conf
RUN chown -R www-data:www-data /srv/app