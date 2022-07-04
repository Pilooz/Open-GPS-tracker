FROM php:fpm

RUN docker-php-ext-install pdo pdo_mysql

# RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN sed -i.bak 's/^pdo_mysql.default_socket=.*$/pdo_mysql.default_socket=\/var\/run\/mysqld\/mysqld.sock/' /usr/local/etc/php/php.ini-development
RUN sed -i.bak 's/^pdo_mysql.default_socket=.*$/pdo_mysql.default_socket=\/var\/run\/mysqld\/mysqld.sock/' /usr/local/etc/php/php.ini-production