FROM php:8-fpm

WORKDIR /usr/src/app

COPY --chown=1000:1000 ./ /usr/src/app
RUN PATH=$PATH:/usr/src/app/vendor/bin:bin

RUN apt-get update && \
apt-get install -y \
libzip-dev

RUN pecl install apcu

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install zip
RUN docker-php-ext-enable apcu

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
