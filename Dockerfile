FROM php:8.2-apache

# Installation des extensions PHP nécessaires pour MySQL
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Activation des modules Apache (URL Rewriting, Compression Gzip, etc.)
RUN a2enmod rewrite deflate headers expires