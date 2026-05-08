FROM php:8.2-fpm

# تثبيت الاعتمادات
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    nginx

# تثبيت إضافات PHP
RUN docker-php-ext-install pdo_mysql gd

# نسخ ملفات المشروع
COPY . /var/www/html

# ضبط الصلاحيات
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# إعداد Nginx
COPY ./nginx.conf /etc/nginx/sites-available/default

EXPOSE 80

CMD service nginx start && php-fpm
