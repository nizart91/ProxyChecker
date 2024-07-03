# Используем базовый образ PHP 8.1 FPM
FROM php:8.3-fpm
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

# Устанавливаем необходимые зависимости и пакеты для Laravel
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
        unzip \
        git \
        nano \
        curl \
        npm \
        redis \
        supervisor \
        && docker-php-ext-install zip pdo_mysql

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Устанавливаем расширения PHP
RUN docker-php-ext-install bcmath exif pcntl

# Устанавливаем расширения PHP Redis
RUN pecl install redis \
    && docker-php-ext-enable redis

# Устанавливаем директорию рабочего приложения
WORKDIR /var/www

# Копируем зависимости проекта и устанавливаем их
COPY . /var/www

# Устанавливаем права на директорию хранилища Laravel
RUN chown -R www-data:www-data /var/www/storage
RUN chmod -R 775 /var/www/storage

# Устанавливаем права на кэш Laravel
RUN chown -R www-data:www-data /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/bootstrap/cache

# Install node and npm
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash -
RUN apt-get install -y nodejs

RUN if [ ! -f .env ]; then cp .env.example .env; fi
