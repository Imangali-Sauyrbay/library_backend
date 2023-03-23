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
  bash \
  && docker-php-ext-install pdo_pgsql pcntl \
  && rm /var/cache/apk/*

# INSTALL SWOOLE
RUN apk add --no-cache --virtual .build-deps \
  gcc \
  g++ \
  make \
  && pecl install swoole \
  && docker-php-ext-enable swoole \
  && apk del .build-deps \
  && rm /var/cache/apk/*

# INSTALL COMPOSER
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY composer.json composer.lock ./

RUN composer install --no-dev --no-scripts --no-autoloader --no-progress \
    && rm -rf /root/.composer/cache

COPY start-container /usr/local/bin/start-container

RUN chmod +x /usr/local/bin/start-container
ENTRYPOINT [ "start-container" ]
