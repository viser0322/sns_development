FROM php:8.1-fpm

RUN apt-get update
RUN apt-get install -y vim
RUN apt-get install -y less
RUN docker-php-ext-install pdo_mysql
COPY php.ini /usr/local/etc/php/

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# install SMTP
RUN apt-get install msmtp msmtp-mta -yqq
COPY msmtprc /etc/msmtprc
# Webからメール送信できるように実行ユーザーの所有にする
RUN chown www-data:www-data /etc/msmtprc
RUN chmod 600 /etc/msmtprc

WORKDIR /var/www/html

# install
RUN apt-get update && apt-get install -y \
  libzip-dev \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libmcrypt-dev \
  libpng-dev \
  && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
  && docker-php-ext-install \
  zip \
  gd \
  exif
