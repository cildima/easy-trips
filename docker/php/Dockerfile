FROM php:7.2.3-fpm

WORKDIR /home/wwwroot

RUN apt-get update && apt-get install -y \
    zip p7zip-full cron git \
    zlib1g-dev curl openssl \
    libmemcached-dev libpq-dev libldb-dev \
    libmcrypt-dev libxml2-dev libgmp-dev \
    mysql-client libmagickwand-dev \
    && pecl install imagick

RUN docker-php-ext-install pdo pdo_mysql mbstring json soap xml zip bcmath gmp
RUN docker-php-ext-enable imagick

# RUN pecl install redis && docker-php-ext-enable redis

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY php.ini /usr/local/etc/php/