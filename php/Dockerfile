FROM php:7.4-apache-buster
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_HOME=/composer
COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer
RUN apt-get update \
    && apt-get -y install \
        libjpeg62-turbo-dev \
        libpng-dev \
        libicu-dev \
        libonig-dev \
        libzip-dev \
        unzip
RUN apt-get clean
RUN docker-php-ext-configure gd --with-jpeg
RUN docker-php-ext-install \
        -j$(nproc) gd \
        bcmath \
        zip \
        pdo_mysql \
        intl
COPY php.ini /usr/local/etc/php/php.ini
COPY ./app /var/www/html