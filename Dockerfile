from php:8.3-apache

RUN apt-get update -y

RUN apt-get install -y libpq-dev
RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install pgsql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt install git -y


RUN apt-get update && \
    apt-get install -y libzip-dev && \
    docker-php-ext-install zip

RUN a2enmod rewrite
