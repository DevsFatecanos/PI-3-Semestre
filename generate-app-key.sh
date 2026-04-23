#!/bin/bash

echo "==================================="
echo "Gerador de APP_KEY para Render"
echo "==================================="
echo ""

# Verificar se está em um projeto Laravel
if [ ! -f "artisan" ]; then
    echo "❌ Erro: arquivo 'artisan' não encontrado."
    echo "Execute este script na raiz do projeto Laravel."
    exit 1
fi

# Gerar APP_KEY
echo "🔑 Gerando APP_KEY..."
php artisan key:generate --force

# Ler o APP_KEY do .env
APP_KEY=$(grep '^APP_KEY=' .env | cut -d '=' -f 2)

echo ""
echo "✅ APP_KEY gerada com sucesso!"
echo ""
echo "📋 Copie este valor e cole no Render em:"
echo "   Environment Variables → APP_KEY"
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "$APP_KEY"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "Pressione Enter para copiar para clipboard (requer xclip no Linux)"
read -r

# Tentar copiar para clipboard (funciona em Linux com xclip)
if command -v xclip &> /dev/null; then
    echo "$APP_KEY" | xclip -selection clipboard
    echo "✅ Copiado para clipboard!"
elif command -v clip &> /dev/null; then
    # Windows
    echo "$APP_KEY" | clip
    echo "✅ Copiado para clipboard!"
else
    echo "⚠️  Não foi possível copiar automaticamente."
    echo "Copie manualmente o valor acima."
fi
