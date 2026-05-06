FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libpq-dev libzip-dev \
    && docker-php-ext-install pdo_pgsql pgsql zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

COPY docker/entrypoint.sh /usr/local/bin/coffeedoo-entrypoint
RUN chmod +x /usr/local/bin/coffeedoo-entrypoint

ENTRYPOINT ["coffeedoo-entrypoint"]
CMD ["apache2-foreground"]
