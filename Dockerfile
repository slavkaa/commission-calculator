FROM php:8.3-cli

# Cleanup after install
RUN docker-php-source delete \
    && apt-get -y autoremove --purge \
    && apt-get -y autoclean \
    && apt-get -y clean

# Memory Limit
RUN echo "memory_limit=256M" > $PHP_INI_DIR/conf.d/memory-limit.ini

# Installing tools & libraries
RUN apt-get -y update && apt-get -y upgrade
RUN apt-get -y install --fix-missing git unzip pkg-config libxml2 libxml2-dev libicu-dev libssl-dev #mc nano

# Php extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod 777 /usr/local/bin/install-php-extensions
ARG PHP_EXTENSIONS="ctype iconv intl pcntl"
RUN install-php-extensions ${PHP_EXTENSIONS}
RUN rm /usr/local/bin/install-php-extensions

# install Xdebug
RUN pecl install xdebug  && docker-php-ext-enable xdebug
COPY docker/php/xdebug.ini /usr/local/etc/php/conf.d/

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY .. ./
