FROM php:7.1-cli-alpine

MAINTAINER Poul Babilas <p.babilas@i-systems.pl>

ENV DEBIAN_FRONTEND noninteractive

RUN apk update
RUN apk add bash
RUN apk add coreutils
RUN apk add curl
RUN apk add zlib-dev
RUN apk add --no-cache \
		$PHPIZE_DEPS

RUN curl -fsSL https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer global require ayeo/sniffer:1.0.3 --no-progress --no-scripts --no-interaction

RUN pecl install xdebug \
    && echo 'zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20160303/xdebug.so' > \
        /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && php -m | grep xdebug

RUN docker-php-ext-install zip

ADD CodeSniffer.conf /root/.composer/vendor/squizlabs/php_codesniffer/

ENV PATH /root/.composer/vendor/bin:$PATH
CMD ["phpcs"]