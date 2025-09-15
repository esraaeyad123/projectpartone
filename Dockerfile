# صورة PHP مع Apache و GD و Zip
FROM php:8.2-apache

# تثبيت الأدوات المطلوبة (أدنى قدر)
RUN apt-get update && apt-get install -y \
    unzip git zip libpng-dev libjpeg-dev libfreetype6-dev libzip-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_pgsql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# تثبيت Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# مجلد العمل
WORKDIR /var/www/html

# نسخ المشروع
COPY . .

# تثبيت Dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Laravel caching
RUN php artisan key:generate \
 && php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

# تشغيل Apache
CMD ["apache2-foreground"]
