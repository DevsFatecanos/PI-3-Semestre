#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

PHP_BIN="${PHP_BIN:-/usr/local/php/current/bin/php}"
COMPOSER_BIN="${COMPOSER_BIN:-/usr/local/bin/composer}"

if [[ ! -x "$PHP_BIN" ]]; then
  echo "Erro: PHP nao encontrado em $PHP_BIN"
  exit 1
fi

if [[ ! -f "$COMPOSER_BIN" ]]; then
  echo "Erro: Composer nao encontrado em $COMPOSER_BIN"
  exit 1
fi

set_env_value() {
  local key="$1"
  local value="$2"

  if grep -q "^${key}=" .env; then
    sed -i "s|^${key}=.*|${key}=${value}|g" .env
  else
    echo "${key}=${value}" >> .env
  fi
}

echo "[1/4] Instalando dependencias PHP"
"$PHP_BIN" "$COMPOSER_BIN" install --no-interaction --prefer-dist

echo "[2/4] Instalando dependencias Node"
npm install

echo "[3/4] Preparando .env"
if [[ ! -f .env ]]; then
  cp .env.example .env
  echo "Arquivo .env criado a partir de .env.example"
else
  echo "Arquivo .env ja existe (mantido)"
fi

echo "[4/6] Ajustando banco/sessao para Codespaces"
touch database/database.sqlite
set_env_value "DB_CONNECTION" "sqlite"
set_env_value "DB_DATABASE" "database/database.sqlite"
set_env_value "SESSION_DRIVER" "file"
set_env_value "CACHE_STORE" "file"
set_env_value "QUEUE_CONNECTION" "sync"

echo "[5/6] Limpando cache de configuracao"
"$PHP_BIN" artisan config:clear

echo "[6/6] Gerando APP_KEY"
"$PHP_BIN" artisan key:generate --force

echo
echo "Setup concluido."
echo "Para rodar o servidor Laravel: $PHP_BIN artisan serve --host=0.0.0.0 --port=8000"
echo "Para rodar o Vite: npm run dev"
