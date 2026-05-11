FROM php:8.2-apache

# 1. تثبيت الإضافات الضرورية لـ Filament و Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip libzip-dev unzip libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip intl

# 2. تفعيل مود الـ Rewrite في Apache
RUN a2enmod rewrite

# 3. إجبار Apache على جعل مجلد /public هو المجلد الرئيسي (حل مشكلة 403)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# 5. تثبيت المكتبات وتجاهل توافق النسخ
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# 6. ضبط الصلاحيات (ضروري جداً)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["apache2-foreground"]