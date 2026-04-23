# --- Estágio 1: Build ---
FROM php:8.2-fpm AS builder

RUN apt-get update && apt-get install -y \
    curl git unzip libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Instalar Node.js 22 (Versão necessária para Vite 7 e Tailwind 4)
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /app
COPY . .

# Instalar dependências PHP
RUN composer install --no-dev --optimize-autoloader

# Instalar dependências Node e gerar assets
# Adicionamos o ci para uma instalação mais limpa em ambientes de build
RUN npm ci && NODE_OPTIONS="--max-old-space-size=450" npm run build
# --- Estágio 2: Runtime (A imagem final, leve) ---
FROM php:8.2-fpm

# Instalar extensões necessárias para rodar o sistema e Nginx/Supervisor
RUN apt-get update && apt-get install -y \
    libpq-dev nginx supervisor \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Copiamos apenas o que foi gerado no builder (já inclui a pasta /public/build)
COPY --from=builder /app .

# Ajuste de permissões para o Laravel conseguir escrever logs e cache
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Configurações de serviços
COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# O Render usa portas dinâmicas. O EXPOSE é apenas informativo.
EXPOSE 8080

COPY docker/entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]