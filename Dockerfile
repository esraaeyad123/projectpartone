FROM php:8.2-cli

# تثبيت الأدوات والـ PHP extensions المطلوبة
RUN apt-get update && apt-get install -y \
    unzip git libpq-dev libzip-dev zip \
    libonig-dev libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql mbstring bcmath tokenizer xml fileinfo ctype gd zip

# تثبيت Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# تثبيت Dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Laravel caching
RUN php artisan key:generate \
 && php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

CMD php artisan serve --host 0.0.0.0 --port $PORT
