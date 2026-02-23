# Use uma imagem do PHP com Apache
FROM php:8.2-apache

# Instala o módulo do MySQLi
RUN docker-php-ext-install mysqli

# Habilita o mod_rewrite, se necessário para URLs amigáveis
RUN a2enmod rewrite

# Copia todos os arquivos da pasta local para a pasta padrão do Apache no contêiner
COPY . /var/www/html/

# Expõe a porta 80
EXPOSE 80
