FROM nginx:stable-alpine

ADD ./nginx.conf /etc/nginx/nginx.conf
ADD ./admin.conf /etc/nginx/conf.d/admin.conf
ADD ./site.conf /etc/nginx/conf.d/site.conf
ADD ./hotel.conf /etc/nginx/conf.d/hotel.conf

RUN mkdir -p /var/www/html

RUN addgroup -g 1000 www && adduser -G www -g www -s /bin/sh -D www

RUN chown www:www /var/www/html
