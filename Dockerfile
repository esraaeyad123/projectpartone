# استخدم صورة PHP مع Apache و GD و Zip مثبتة مسبقًا
FROM php:8.2-apache

# تثبيت Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# تمكين بعض الـ PHP extensions المطلوبة
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring bcmath tokenizer xml fileinfo ctype zip gd

# نسخ ملفات المشروع
WORKDIR /var/www/html
COPY . .

# تثبيت حزم المشروع
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Laravel caching
RUN php artisan key:generate \
 && php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

# إعادة تشغيل Apache عند تشغيل الحاوية
CMD ["apache2-foreground"]
