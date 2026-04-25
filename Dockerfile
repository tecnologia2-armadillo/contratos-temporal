# Etapa 1: Compilar assets frontend (Laravel Mix)
# Bajamos a Node 16 para compatibilidad con versiones antiguas de Laravel Mix/Webpack
FROM node:16-alpine AS build-node
WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm install
COPY . .
RUN npm run prod

# Etapa 2: Configurar entorno PHP y servidor web
FROM php:8.2-apache

# Habilitar mod_rewrite de Apache para Laravel
RUN a2enmod rewrite

# Instalar dependencias del sistema requeridas por Laravel y PostgreSQL
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    git \
    curl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP (incluyendo pdo_pgsql para PostgreSQL)
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# Instalar Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar el directorio de trabajo
WORKDIR /var/www/html

# Copiar el código base de la aplicación
COPY . .

# Copiar los assets compilados de la Etapa 1 sobrescribiendo la carpeta public
COPY --from=build-node /app/public ./public

# Instalar las dependencias de Composer (optimizado para producción)
RUN composer install --optimize-autoloader --no-dev

# Ajustar permisos para storage y bootstrap cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Cambiar la raíz del servidor Apache a la carpeta 'public' de Laravel
RUN sed -i -e 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i -e 's|<Directory /var/www/>|<Directory /var/www/html/public>|g' /etc/apache2/apache2.conf

# Exponer el puerto 8000
EXPOSE 8000

# Iniciar Apache
CMD ["apache2-foreground"]