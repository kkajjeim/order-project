FROM php:7.4.9-apache-buster
MAINTAINER Shimkj <kyungju.shim@gmail.com>

RUN a2dissite 000-default.conf && a2enmod rewrite
COPY container-conf/000-default.conf /etc/apache2/sites-enabled/000-default.conf

RUN apt-get update \
  && apt-get install -y libzip-dev zlib1g-dev libpq-dev default-mysql-client unzip \
  curl \
  libmemcached-dev \
  libz-dev \
  libjpeg-dev \
  libpng-dev \
  libfreetype6-dev \
  libssl-dev \
  libmcrypt-dev \
  libicu-dev \
  locales \
  locales-all \
  tzdata

RUN locale-gen ko_KR.UTF-8
ENV LANG ko_KR.UTF-8
ENV LC_MESSAGES POSIX

RUN ln -snf /usr/share/zoneinfo/Asia/Seoul /etc/localtime && \
    echo "Asia/Seoul" > /etc/timezone
ENV TZ Asia/Seoul

RUN docker-php-ext-install zip pdo_mysql mysqli \
  && docker-php-ext-enable mysqli

RUN docker-php-ext-configure gd && \
  docker-php-ext-install gd

RUN docker-php-ext-configure intl && \
  docker-php-ext-install intl

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/bin/composer
#RUN composer install

#CMD ["sleep", "infinity"]
