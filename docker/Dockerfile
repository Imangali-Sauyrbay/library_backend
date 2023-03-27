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

COPY . .

RUN composer install --no-scripts --no-progress \
    && rm -rf /root/.composer/cache

COPY start-container /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

ENTRYPOINT [ "start-container" ]

