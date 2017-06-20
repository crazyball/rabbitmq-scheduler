FROM php:7.1.6-alpine
LABEL maintainer "loic@1001pharmacies.com"

RUN mkdir -p /var/www

WORKDIR /var/www

COPY ./ /var/www/

COPY docker/docker-entrypoint.sh /

ENTRYPOINT ["/docker-entrypoint.sh"]