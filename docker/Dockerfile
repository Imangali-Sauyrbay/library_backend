FROM phpswoole/swoole:php8.2-alpine

WORKDIR /var/www

RUN apk --update add wget \
  curl \
  build-base \
  libxml2-dev \
  pcre-dev \
  zlib-dev \
  autoconf \
  oniguruma-dev \
  openssl \
  openssl-dev \
  postgresql-dev \
  bash \
  poppler-utils \
  imagemagick \
  imagemagick-dev \
  ghostscript \
  && pecl install imagick \
  && docker-php-ext-enable imagick \
  && apk del imagemagick-dev \
  && docker-php-ext-install pdo_pgsql \
  && rm -rf /var/cache/apk/*

# INSTALL COMPOSER
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . .

RUN composer install --no-scripts --no-progress \
    && rm -rf /root/.composer/cache

COPY start-container /usr/local/bin/start-container

RUN chmod +x /usr/local/bin/start-container \
  && cp .env.prod .env \
  && php artisan key:generate

ENTRYPOINT [ "start-container" ]
