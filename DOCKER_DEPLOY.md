# Guia de Deploy no Render com Docker

## Pré-requisitos
- Conta no [Render.com](https://render.com)
- Repositório Git (GitHub, GitLab, etc)

## Passos para Deploy

### 1. Preparar o Repositório
```bash
# Adicionar arquivos Docker ao repositório
git add Dockerfile .dockerignore docker/ render.yaml .env.production
git commit -m "Add Docker configuration for Render deployment"
git push
```

### 2. Conectar ao Render
1. Acesse [https://dashboard.render.com](https://dashboard.render.com)
2. Clique em "New +"
3. Selecione "Blueprint" (para deploy automático com render.yaml)
4. Conecte seu repositório Git

### 3. Configurar Variáveis de Ambiente
No painel do Render, configure as seguintes variáveis:

```
APP_KEY=base64:seu-app-key-aqui
DB_HOST=seu-db-host.render.internal
DB_PASSWORD=sua-senha-db
APP_URL=https://seu-app.onrender.com
```

### 4. Deploy Manual (sem Blueprint)
Se preferir fazer deploy manual:

1. Crie um novo Web Service
2. Selecione "Docker"
3. Configure:
   - Build Command: `(deixe em branco ou use comandos padrão)`
   - Start Command: `/usr/local/bin/entrypoint.sh`
   - Port: `8080`

### 5. Dados Sensíveis
Sempre configure as variáveis sensíveis no painel do Render:
- `APP_KEY`: Gere com `php artisan key:generate`
- `DB_PASSWORD`: Senha do MySQL
- Outras credenciais de serviços externos

### 6. Primeira Execução
Após o deploy, você pode:
- Ver logs em tempo real no painel
- Executar comandos via terminal (opcional)
- Acessar a aplicação via URL fornecida pelo Render

## Troubleshooting

### Erro: "500 Internal Server Error"
1. Verifique os logs no painel do Render
2. Certifique-se que o `APP_KEY` está configurado
3. Verifique a conexão com o banco de dados

### Erro de Conexão com Banco de Dados
1. Confirme que o MySQL está rodando
2. Verifique as credenciais (`DB_HOST`, `DB_USER`, `DB_PASSWORD`)
3. Certifique-se que a rede está corretamente configurada

### Assets não carregam
Os assets já devem ser compilados durante o build do Docker (npm run build)

## Notas Importantes

- O arquivo `Dockerfile` usa multi-stage build para otimizar tamanho
- O Nginx está configurado para ouvir a porta 8080 (obrigatório no Render)
- Migrações rodam automaticamente no inicialização
- Cache e storage estão configurados para funcionar em ambiente containerizado
