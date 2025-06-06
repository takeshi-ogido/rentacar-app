FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql zip gd

# Composer インストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 作業ディレクトリ
WORKDIR /var/www
