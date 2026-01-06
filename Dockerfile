# Stage 1: Build
FROM php:8.2-apache AS builder
WORKDIR /var/www/html
RUN docker-php-ext-install mysqli pdo pdo_mysql
COPY src/ .

# Stage 2: Production
FROM php:8.2-apache
WORKDIR /var/www/html
COPY --from=builder /var/www/html /var/www/html
RUN chown -R www-data:www-data /var/www/html