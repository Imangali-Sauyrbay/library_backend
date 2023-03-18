FROM php:8.1.17-fpm-alpine3.17

WORKDIR /var/www

RUN apk --update add wget \
  curl \
  build-base \
  libmemcached-dev \
  libmcrypt-dev \
  libxml2-dev \
  pcre-dev \
  zlib-dev \
  autoconf \
  cyrus-sasl-dev \
  libgsasl-dev \
  oniguruma-dev \
  openssl \
  openssl-dev \
  postgresql-dev \
  && docker-php-ext-install pdo_pgsql pcntl

# INSTALL COMPOSER
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# INSTALL SWOOLE
RUN apk add --no-cache --virtual .build-deps \
  gcc \
  g++ \
  make \
  && pecl install swoole \
  && docker-php-ext-enable swoole \
  && apk del .build-deps


RUN rm /var/cache/apk/*

COPY composer.json composer.lock ./

RUN composer install --no-dev --no-scripts --no-autoloader --no-progress \
    && rm -rf /root/.composer/cache

CMD ["php", "artisan", "octane:start", "--server=swoole", "--host=0.0.0.0", "--port=80"]

