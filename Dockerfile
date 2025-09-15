FROM php:8.2-cli

# تحديث النظام وتثبيت الأدوات الأساسية
RUN apt-get update && apt-get install -y \
    unzip git zip curl \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libzip-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_pgsql mbstring bcmath tokenizer xml fileinfo ctype zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# تثبيت Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# تثبيت حزم المشروع
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Laravel caching
RUN php artisan key:generate \
 && php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

CMD php artisan serve --host 0.0.0.0 --port $PORT
