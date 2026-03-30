FROM php:8.2-apache

# Installation extensions PHP
RUN docker-php-ext-install pdo pdo_mysql

# Activation mod_rewrite (IMPORTANT pour SEO)
RUN a2enmod rewrite

# Configuration Apache
RUN echo "<Directory /var/www/html>\n\
    AllowOverride All\n\
</Directory>" >> /etc/apache2/apache2.conf

# Copier le code source
COPY ../../src/ /var/www/html/

# Permissions
RUN chown -R www-data:www-data /var/www/html