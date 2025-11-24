# Image PHP + Apache
FROM php:8.2-apache

# Active les modules Apache
RUN a2enmod rewrite

# Copie le projet dans le serveur Apache
COPY . /var/www/html/

# Donne les bons droits
RUN chown -R www-data:www-data /var/www/html

# Expose le port utilisé par Render
EXPOSE 8080

# Apache écoute sur le port 8080
RUN sed -i 's/80/8080/g' /etc/apache2/ports.conf /etc/apache2/sites-enabled/000-default.conf

# Lance Apache
CMD ["apache2-foreground"]
