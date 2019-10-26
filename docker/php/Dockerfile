FROM php:7.3-cli

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update \
    && docker-php-ext-install pdo_mysql

ENV APP_DIR /app

WORKDIR $APP_DIR
VOLUME $APP_DIR

EXPOSE 80

CMD ["php", "-S", "0.0.0.0:80", "-t", "/app/public", "/app/public/index.php"]