FROM phpswoole/swoole:php8.2-alpine

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

RUN rm /var/cache/apk/*

COPY . .

RUN composer install --no-scripts --no-progress \
    && rm -rf /root/.composer/cache

COPY start-container /usr/local/bin/start-container

RUN chmod +x /usr/local/bin/start-container \
  && cp .env.prod .env \
  && php artisan key:generate

ENTRYPOINT [ "start-container" ]
