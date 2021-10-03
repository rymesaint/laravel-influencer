FROM php:latest

RUN apt-get update -y && apt-get install -y \
    openssl \
    zip \
    unzip \
    git \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    zlib1g-dev \
    libxml2-dev \
    libzip-dev \
    libonig-dev \
    graphviz

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN pecl install redis swoole && docker-php-ext-enable redis swoole
RUN docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql mysqli zip sockets gd \
    && docker-php-source delete

WORKDIR /app
COPY . .
RUN composer install