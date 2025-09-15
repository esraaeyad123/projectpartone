# استخدام PHP 8.2 CLI مع Composer
FROM php:8.2-cli

# تثبيت الأدوات والـ PHP extensions المطلوبة
RUN apt-get update && apt-get install -y \
    unzip git libpq-dev libzip-dev zip \
    libonig-dev libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql mbstring bcmath tokenizer xml fileinfo ctype gd zip

# نسخ Composer من الصورة الرسمية
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# تعيين مجلد العمل
WORKDIR /app

# نسخ كل ملفات المشروع
COPY . .

# تثبيت Dependencies الخاصة بالمشروع
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Laravel caching
RUN php artisan key:generate \
 && php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

# تشغيل التطبيق
CMD php artisan serve --host 0.0.0.0 --port $PORT
