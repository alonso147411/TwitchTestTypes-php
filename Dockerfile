FROM php:8.2-cli

ENV COMPOSER_ALLOW_SUPERUSER=1
WORKDIR /540/TwitchTestTypes

# Instalar dependencias
RUN apt-get update && apt-get install -y unzip git

# Instalar Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

# Instalar y habilitar Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Configuración mínima para cobertura
RUN echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

CMD ["php"]
