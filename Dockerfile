# Stage 1: Build
FROM php:8.2-apache
WORKDIR /var/www/html

# تثبيت الـ extensions المطلوبة للـ MySQL
RUN apt-get update && apt-get install -y libonig-dev libzip-dev unzip \
    && docker-php-ext-install mysqli pdo pdo_mysql

# نسخ سورس المشروع
COPY src/ .

# تحديد صلاحيات
RUN chown -R www-data:www-data /var/www/html
