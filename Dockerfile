FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    unzip git zip libpng-dev libjpeg-dev libfreetype6-dev libzip-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_pgsql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-interaction --optimize-autoloader --no-dev

# لا نفذ caching أثناء build لتجنب مشاكل .env
# caching يتم بعد Deploy على Render

CMD ["apache2-foreground"]
