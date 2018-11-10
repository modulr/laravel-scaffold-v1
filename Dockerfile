FROM        alpine:latest

RUN         mkdir -p /var/www/laravel
COPY        . /var/www/laravel
RUN         touch /var/www/laravel/storage/logs/laravel.log
RUN         addgroup -g 3000 docker &&\
  adduser -u 2000 -D -G docker docker
RUN         chown -R docker:docker /var/www/laravel
WORKDIR     /var/www/laravel
CMD         ["tail", "-f", "storage/logs/laravel.log"]