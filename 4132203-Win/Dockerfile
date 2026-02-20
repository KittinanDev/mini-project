FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock* ./
RUN composer install --no-dev --prefer-dist --no-interaction

FROM php:8.2-apache
WORKDIR /var/www/html
RUN a2enmod rewrite
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY --from=vendor /app/vendor ./vendor
COPY public/ ./public/
COPY src/ ./src/
COPY templates/ ./templates/
COPY composer.json ./
ENV SMTP_HOST=mailhog \
    SMTP_PORT=1025 \
    MAILHOG_API=http://mailhog:8025/api/v2/messages
EXPOSE 80
