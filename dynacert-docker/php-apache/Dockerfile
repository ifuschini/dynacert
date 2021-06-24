FROM php:7.4-apache

RUN apt-get update

RUN docker-php-ext-install pdo pdo_mysql mysqli

RUN a2enmod ssl
RUN a2enmod proxy
RUN a2enmod proxy_balancer
RUN a2enmod proxy_http
COPY conf/apache-httpd.conf /etc/apache2/sites-enabled/000-default.conf



#RUN curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer

#RUN curl -sS https://get.symfony.com/cli/installer | bash
#RUN mv /root/.symfony/bin/symfony /usr/bin/symfony
