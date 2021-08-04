FROM php:8-fpm

WORKDIR /usr/src/app

RUN mkdir /.config
RUN mkdir /.config/psysh
RUN chown -R 1000:1000 /.config
RUN chmod -R 774 /.config

COPY --chown=1000:1000 ./ /usr/src/app
RUN PATH=$PATH:/usr/src/app/vendor/bin:bin

RUN apt-get update && \
apt-get install -y \
libzip-dev && \
apt-get install -y \
libmagickwand-dev --no-install-recommends

RUN pecl install apcu
RUN pecl install imagick

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install zip
RUN docker-php-ext-enable imagick
RUN docker-php-ext-enable apcu
