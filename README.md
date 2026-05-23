Projeto de e-commerce da Foccus
ter o Xamp: 8.2.12 instalado
como instalar localmente:
usando git bash
git clone https://github.com/DevsFatecanos/PI-3-Semestre
e configurar a .env: Copiar o env.exemple e descomentar as entradas do banco de dados
logo após na pasta do projeto
1 - "composer install"
2- Config Env. descomentar os dados de entrada do Supabase (Postgres) e colocar a senha
3 - "php artisan key:generate"
4- Abrir pasta /xampp/php/php.ini (Parâmetros de configuração ) --> Control + F ==  extension=pdo_pgsql E extension=pgsql --> Remover a ; de cada um e depois salvar
5 - para rodar o projeto "php artisan serve"

## Como rodar no GitHub Codespaces

### Setup inicial (uma vez por Codespace)

```bash
cd /workspaces/PI-3-Semestre
./scripts/codespaces-setup.sh
```

Esse script instala dependencias PHP e Node, cria o arquivo `.env` (se nao existir) e gera a `APP_KEY`.
No Codespaces, ele tambem ajusta o `.env` local para SQLite e sessao em arquivo, evitando o erro `could not find driver` quando o `pdo_pgsql` nao estiver disponivel.

Observacao: no Codespaces deste projeto, o `php` padrao do PATH pode falhar por incompatibilidade de OpenSSL. O script ja usa o binario funcional em `/usr/local/php/current/bin/php`.

### Terminal 1 - Servidor Laravel

```bash
cd /workspaces/PI-3-Semestre
/usr/local/php/current/bin/php artisan serve --host=0.0.0.0 --port=8000
```

### Terminal 2 - Vite (Hot Module Replacement / Assets)

```bash
cd /workspaces/PI-3-Semestre
npm run dev
```

A aplicação estará disponível em: http://localhost:8000