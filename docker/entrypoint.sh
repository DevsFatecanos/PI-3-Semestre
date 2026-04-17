#!/bin/bash
set -e

# Copiar .env se não existir
if [ ! -f /app/.env ]; then
    cp /app/.env.example /app/.env
fi

# Gerar APP_KEY se não existir
if [ -z "$(grep '^APP_KEY=' /app/.env | cut -d '=' -f 2)" ]; then
    php /app/artisan key:generate
fi

# Rodar migrações
php /app/artisan migrate --force

# Limpar cache
php /app/artisan config:clear
php /app/artisan cache:clear

# Iniciar supervisord
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
