FROM php:7.3-apache

# bullseyeがEOLになったため、アーカイブリポジトリに向け直す
RUN sed -i 's|http://deb.debian.org/debian|http://archive.debian.org/debian|g' /etc/apt/sources.list \
  && sed -i '/security/d' /etc/apt/sources.list \
  && sed -i '/stretch-updates/d' /etc/apt/sources.list \
  && echo 'Acquire::Check-Valid-Until "false";' > /etc/apt/apt.conf.d/99no-check-valid-until

COPY ./php.ini /usr/local/etc/php/
RUN apt-get update \
  && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
  && docker-php-ext-install pdo_mysql mysqli mbstring gd iconv

RUN apt-get install -y vim

RUN apt-get update && apt-get install -y default-mysql-client

COPY ./php_apache2.conf /etc/apache2/mods-enabled
RUN apache2ctl restart

RUN apt-get update && apt-get install -y openssh-server

RUN apt-get update && apt-get install -y --no-install-recommends libmemcached-dev zlib1g-dev && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install memcached && docker-php-ext-enable memcached

RUN apt-get update && apt-get install -y autoconf build-essential && rm -rf /var/lib/apt/lists/*

RUN pecl install redis-4.2.0 && docker-php-ext-enable redis