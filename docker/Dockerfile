# Dockerfile for Book Review PHP-FPM
FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_pgsql \
    pgsql \
    gd \
    mbstring \
    xml \
    bcmath \
    opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN mkdir -p /var/www/html/storage/cache /var/www/html/storage/sessions /var/www/html/storage/logs && \
    mkdir -p /var/www/html/public/uploads/covers && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/public/uploads

RUN composer install --no-interaction --optimize-autoloader --no-dev

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/public/uploads

EXPOSE 9000

CMD ["php-fpm"]
