FROM php:8.3-fpm-alpine

# Systemabhängigkeiten
RUN apk add --no-cache \
    icu-dev \
    libzip-dev \
    oniguruma-dev

# PHP Erweiterungen
RUN docker-php-ext-install \
    pdo_mysql \
    intl \
    mbstring \
    zip

# Composer installieren
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Arbeitsverzeichnis festlegen
WORKDIR /var/www/html

# Den gesamten Projekt-Code in das Image kopieren
COPY . .

# Berechtigungen für den Web-User setzen
RUN chown -R www-data:www-data /var/www/html

# FPM starten
CMD ["php-fpm"]