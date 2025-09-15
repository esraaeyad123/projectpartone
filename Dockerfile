# استخدم صورة PHP مع Apache و GD و Zip مثبتة مسبقًا
FROM php:8.2-apache

# تثبيت الأدوات الأساسية و PHP extensions المطلوبة
RUN apt-get update && apt-get install -y \
    unzip git zip libpng-dev libjpeg-dev libfreetype6-dev libzip-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_pgsql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# تثبيت Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# مجلد العمل الرئيسي
WORKDIR /var/www/html

# نسخ المشروع كله
COPY . .

# إعطاء Apache صلاحية الوصول إلى الملفات
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 755 /var/www/html

# ضبط DocumentRoot لمجلد public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# تفعيل mod_rewrite للـ Laravel routing
RUN a2enmod rewrite

# تثبيت Dependencies الخاصة بالمشروع
RUN composer install --no-interaction --optimize-autoloader --no-dev

# لا نفعل Laravel caching أو key generation هنا لتجنب مشاكل Free Plan
# سيتم التعامل مع APP_KEY و caching من خلال Environment Variables بعد Deploy

# تشغيل Apache عند بدء الحاوية
CMD ["apache2-foreground"]
