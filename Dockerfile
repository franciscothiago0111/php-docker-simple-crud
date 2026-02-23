# Use uma imagem do PHP com Apache
FROM php:8.2-apache

# Instala os módulos do MySQLi e PDO MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilita o mod_rewrite, se necessário para URLs amigáveis
RUN a2enmod rewrite

# Copia todos os arquivos da pasta local para a pasta padrão do Apache no contêiner
COPY . /var/www/html/

# Expõe a porta 80
EXPOSE 80
