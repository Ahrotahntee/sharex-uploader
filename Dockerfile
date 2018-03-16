FROM alpine:latest
RUN mkdir /srv/uploads && apk update && apk upgrade && apk add php7-fpm php7-json && chown nobody:nobody /srv/uploads
ADD index.php /srv/web/index.php
ADD www.conf /etc/php7/php-fpm.d/www.conf
ENTRYPOINT ["/usr/sbin/php-fpm7","-F"]
