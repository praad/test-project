FROM php:8.2-fpm-alpine

COPY php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

RUN apk update && apk upgrade && apk add \
    git \
    bash \
    curl \
    mc \
    linux-headers

RUN docker-php-ext-install pdo_mysql opcache

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN apk del -f .build-deps

COPY xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN curl -sS https://get.symfony.com/cli/installer | bash \
  && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

WORKDIR /var/www/symfony