FROM php:8.2-fpm

# install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# install php and more
RUN apt-get update && apt-get install -y --no-install-recommends \
    unzip \
    curl \
    libmagickwand-dev \
    libzip-dev \
    libpq-dev

# php extensions
RUN pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install -j$(nproc) opcache gd zip bcmath pdo_mysql mysqli pcntl intl \
    && pecl install redis \
    && docker-php-ext-enable redis

# install ext-http
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions http

# for composer cache
RUN mkdir -p /var/www/.composer

# install symfony console
RUN  echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' | tee /etc/apt/sources.list.d/symfony-cli.list
RUN apt update
RUN apt-get install -y symfony-cli && \
    apt install -y libicu-dev
RUN apt-cache depends php-mbstring

ADD php.ini /usr/local/etc/php/php.ini
ADD php-fpm.conf /etc/php/php-fpm.d/

WORKDIR /var/www/workdir

EXPOSE 9000

CMD ["php-fpm", "-F"]
