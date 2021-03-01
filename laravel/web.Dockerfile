FROM nginx:alpine

COPY web.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www