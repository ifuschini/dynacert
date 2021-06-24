FROM php:7.4-apache

RUN apt-get update

RUN apt-get install -y libzip-dev

RUN docker-php-ext-install pdo pdo_mysql mysqli zip

RUN curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony/bin/symfony /usr/bin/symfony

COPY conf/dynacert-httpd.conf /etc/apache2/sites-enabled/000-default.conf

WORKDIR /var/dynacert-server