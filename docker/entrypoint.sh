#!/bin/bash
set -e

# 1. Ajuste da Porta (CRUCIAL para o Render)
# O Render passa uma porta dinâmica na variável $PORT. 
# Esse comando substitui a porta 8080 no arquivo do Nginx pela porta real.
if [ -n "$PORT" ]; then
    echo "Configurando Nginx para ouvir na porta $PORT"
    sed -i "s/8080/${PORT}/g" /etc/nginx/sites-available/default
fi

# 2. Otimização de Cache
# Em produção, é melhor usar 'config:cache' em vez de apenas 'clear'
php /app/artisan config:cache
php /app/artisan route:cache
php /app/artisan view:cache

# 3. Migrações
# Tenta rodar as migrações no banco do Supabase
echo "Rodando migrações..."
php /app/artisan migrate --force

# 4. Iniciar o Supervisor
# O parâmetro -n é obrigatório aqui para o container não fechar imediatamente
echo "Iniciando Supervisor..."
exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf