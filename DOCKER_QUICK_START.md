# 🐳 Deploy Docker - Guia Rápido

## ✅ Arquivos Criados

Foram criados os seguintes arquivos para o deploy no Render:

### Arquivos Docker
- **Dockerfile** - Configuração multi-stage para build otimizado
- **.dockerignore** - Arquivos a ignorar no build
- **docker/entrypoint.sh** - Script de inicialização (migrações, APP_KEY, etc)
- **docker/nginx.conf** - Configuração Nginx para servir a aplicação
- **docker/php.conf** - Configuração PHP-FPM
- **docker/supervisord.conf** - Gerenciador de processos

### Configuração Render
- **render.yaml** - Blueprint do Render (deploy automático com Git)
- **.env.production** - Variáveis de ambiente para produção

### Testes Locais
- **docker-compose.yml** - Para testar Docker localmente com MySQL

### Documentação
- **DOCKER_DEPLOY.md** - Guia completo de deployment

---

## 🚀 Como Testar Localmente (Antes de fazer Deploy)

### 1. Instalar Docker Desktop
- Baixar em: https://www.docker.com/products/docker-desktop

### 2. Construir e Rodar
```bash
docker-compose up --build
```

### 3. Acessar a Aplicação
```
http://localhost:8080
```

### 4. Verificar Logs
```bash
docker-compose logs -f app
```

### 5. Parar Containers
```bash
docker-compose down
```

---

## 📤 Como Deploy no Render

### Passo 1: Commit no Git
```bash
git add .
git commit -m "Add Docker configuration for Render deployment"
git push origin main
```

### Passo 2: Conectar ao Render
1. Acesse https://dashboard.render.com
2. Clique em **"New +"** → **"Blueprint"**
3. Selecione seu repositório
4. Será criado automaticamente:
   - Web Service (aplicação)
   - MySQL Database

### Passo 3: Configurar Variáveis de Ambiente
No painel do Render, adicione estas variáveis:

```
APP_KEY=base64:VALOR_GERADO_LOCALMENTE
APP_URL=https://seu-app.onrender.com
BRASIL_API_BASE_URL=https://api.brasilapi.com.br
```

**Para gerar o APP_KEY localmente:**
```bash
php artisan key:generate
```
Copie o valor após `base64:` do arquivo `.env`

### Passo 4: Aguardar Deploy
- O Render vai fazer build automaticamente
- Pode levar 5-10 minutos na primeira vez
- As migrações rodam automaticamente no startup

### Passo 5: Acessar a Aplicação
Após o deploy, seu app estará em:
```
https://seu-app.onrender.com
```

---

## ⚠️ Variáveis Críticas para o Render

Certifique-se de configurar NO PAINEL DO RENDER:

| Variável | Exemplo | Obrigatório |
|----------|---------|-------------|
| APP_KEY | base64:chave_gerada | ✅ |
| APP_ENV | production | ✅ |
| APP_DEBUG | false | ✅ |
| DB_HOST | *fornecido pelo Render* | ✅ |
| DB_PASSWORD | *sua senha* | ✅ |
| APP_URL | https://seu-app.onrender.com | ✅ |

---

## 🔍 Troubleshooting

### ❌ "500 - Internal Server Error"
```bash
# Verifique os logs no Render
# Se APP_KEY não está set, execute localmente:
php artisan key:generate
# E copie a valor do .env para o Render
```

### ❌ "Cannot connect to database"
- Verifique se MySQL está rodando no Render
- Confirme DB_HOST, DB_USER e DB_PASSWORD
- Aguarde alguns minutos (MySQL leva tempo para iniciar)

### ❌ "Storage permission denied"
- Já está resolvido no Dockerfile (chown www-data)

### ❌ Assets (CSS/JS) não aparecem
- Os assets são compilados no build do Docker (npm run build)
- Se não aparecerem, verifique os logs do build

---

## 📝 Notas Importantes

1. **APP_KEY é obrigatório** - Gere localmente e copie para o Render
2. **Migrações rodam automaticamente** - No startup do container
3. **Cache está em arquivo** - Funciona em container
4. **Uploads** - Use object storage externo (AWS S3, etc)
5. **Email** - Configure MAIL_* ou use serviço externo

---

## 🎯 Próximos Passos

1. ✅ Testar localmente com `docker-compose up`
2. ✅ Push dos arquivos para Git
3. ✅ Criar Blueprint no Render
4. ✅ Configurar variáveis de ambiente
5. ✅ Acompanhar deployment

Pronto? Bora fazer o deploy! 🚀
