FROM php:7.1.6-alpine
LABEL maintainer "loic@1001pharmacies.com"

COPY /docker/* /

COPY ./ /var/www/

RUN chmod +x /docker-entry.sh

ENTRYPOINT ["/docker-entry.sh"]