# Estágio 1: Build
FROM php:8.2-fpm AS builder

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    libpq-dev \
    libmysqlclient-dev \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Copiar arquivos
COPY composer.json composer.lock* ./
COPY package.json package-lock.json* ./
COPY . .

# Instalar dependências PHP
RUN composer install --no-dev --optimize-autoloader

# Instalar dependências Node e build assets
RUN npm install && npm run build

# Estágio 2: Runtime
FROM php:8.2-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    curl \
    libpq-dev \
    libmysqlclient-dev \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Nginx
RUN apt-get update && apt-get install -y nginx supervisor \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Copiar aplicação do builder
COPY --from=builder /app .

# Configurar permissões
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Copiar configuração Nginx
COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/php.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expor porta
EXPOSE 8080

# Script de inicialização
COPY docker/entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
