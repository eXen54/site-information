FROM php:8.2-apache

# Installation des extensions PHP nécessaires pour MySQL
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Activation du module de réécriture d'URL Apache (essentiel pour l'URL Rewriting)
RUN a2enmod rewrite

# Redémarrage d'Apache
RUN service apache2 restart