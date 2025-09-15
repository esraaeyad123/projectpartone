# 1) اختر نسخة PHP مع Composer
FROM php:8.2-cli

# 2) ثبّت المكتبات المطلوبة
RUN apt-get update && apt-get install -y \
    unzip git libpq-dev libzip-dev zip \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql zip

# 3) نسخ Composer من صورة رسمية
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 4) نسخ المشروع داخل الحاوية
WORKDIR /app
COPY . .

# 5) تثبيت Dependencies
RUN composer install --optimize-autoloader --no-dev

# 6) Laravel caching
RUN php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

# 7) تشغيل Laravel على البورت اللي يحدده Render
CMD php artisan serve --host 0.0.0.0 --port $PORT
