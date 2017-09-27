FROM alpine:3.5
MAINTAINER kolaente - mowie.cc

ENV TZ "Europe/Berlin"

RUN apk update && \
    apk --no-cache add bash tzdata curl ca-certificates s6 ssmtp mysql-client \
    nginx nginx-mod-http-headers-more

RUN ln -sf "/usr/share/zoneinfo/$TZ" /etc/localtime && \
    echo "$TZ" > /etc/timezone && date

RUN apk --no-cache add \
    php7 php7-phar php7-curl php7-fpm php7-json php7-zlib php7-gd \
    php7-xml php7-dom php7-ctype php7-opcache php7-zip php7-iconv \
    php7-pdo php7-pdo_mysql php7-mysqli php7-mbstring php7-session \
    php7-mcrypt php7-openssl php7-sockets php7-posix

RUN rm -rf /var/cache/apk/* && \
    ln -s /usr/bin/php7 /usr/bin/php && \
    rm -f /etc/php7/php-fpm.d/www.conf && \
    touch /etc/php7/php-fpm.d/env.conf

RUN rm -rf /var/www

COPY conf/services.d /etc/services.d
COPY conf/nginx/nginx.conf /etc/nginx/nginx.conf
COPY conf/php/php-fpm.conf /etc/php7/
COPY conf/php/conf.d/php.ini /etc/php7/conf.d/zphp.ini

VOLUME /var/www

EXPOSE 80

ENTRYPOINT ["/bin/s6-svscan", "/etc/services.d"]
CMD []
