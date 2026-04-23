# Painel de Administração - Loja

## 📋 Descrição

Este é um painel administrativo completo para gerenciar produtos da loja. Ele oferece funcionalidades de:

- ✅ **Adicionar Produtos** - Criar novos produtos com todos os detalhes
- ✏️ **Editar Produtos** - Modificar informações de produtos existentes
- 👁️ **Ativar/Desativar Produtos** - Controlar visibilidade dos produtos na loja
- ⭐ **Destacar Produtos** - Marcar produtos como destaque
- 🗑️ **Deletar Produtos** - Remover produtos do banco de dados
- 🔍 **Buscar Produtos** - Procurar produtos por nome, categoria ou código de barras

---

## 🔐 Acessando o Painel de Admin

### URL de Acesso
```
http://localhost/admin/dashboard
```

### Credenciais Padrão (após seeder)
- **Email:** `admin@loja.com`
- **Senha:** `admin123`

⚠️ **IMPORTANTE:** Altere a senha do admin imediatamente em produção!

---

## 📊 Funcionalidades Principais

### 1. Dashboard
- Visualizar estatísticas gerais:
  - Total de produtos
  - Produtos ativos
  - Produtos inativos
  - Produtos em destaque
- Tabela com todos os produtos cadastrados

### 2. Adicionar Produto
Clique em **"Novo Produto"** para acessar o formulário de criação com campos:

- **Nome do Produto*** (obrigatório)
- **Marca**
- **Código de Barras (EAN)**
- **Categoria*** (obrigatório)
- **Descrição**
- **Preço de Custo**
- **Preço Antigo** (para promoções)
- **Preço Atual*** (obrigatório)
- **Quantidade em Estoque*** (obrigatório)
- **URL da Imagem**
- **Ativo** (checkbox - ativa o produto na loja)
- **Destaque** (checkbox - marca como produto em destaque)

### 3. Editar Produto
Clique no ícone **✏️ Editar** para modificar as informações do produto. 

O formulário é idêntico ao de criação, mantendo todos os dados anteriores.

**Extras na página de edição:**
- Prévia da imagem do produto
- Informações de data de criação/atualização
- Cálculo automático de margem de lucro
- Botão para deletar o produto

### 4. Ativar/Desativar Produto
Clique no ícone **👁️ Olho** para alternar a visibilidade do produto:
- ✅ Visível na loja (Ativo)
- ❌ Oculto na loja (Inativo)

### 5. Destacar Produto
Clique no ícone **⭐ Estrela** para marcar/desmarcar como destaque.

Produtos em destaque aparecerão em seção especial na página inicial da loja.

### 6. Deletar Produto
Clique no ícone **🗑️ Lixeira** para remover o produto.

⚠️ **Cuidado:** Esta ação é **irreversível**! Será solicitada confirmação antes de deletar.

### 7. Buscar Produtos
Use a barra de busca para procurar produtos por:
- Nome
- Categoria
- Código de barras

---

## 🗄️ Estrutura de Banco de Dados

### Campos da Tabela `produtos`

```sql
CREATE TABLE produtos (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    codigo_barras VARCHAR(255),
    descricao TEXT,
    preco_de_custo DECIMAL(8,2),
    preco_antigo DECIMAL(8,2),
    preco_atual DECIMAL(8,2) NOT NULL,
    quantidade INT NOT NULL,
    marca VARCHAR(255),
    categoria VARCHAR(255) NOT NULL,
    imagem_url TEXT,
    destaque BOOLEAN DEFAULT FALSE,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Campos da Tabela `users`

```sql
ALTER TABLE users ADD COLUMN is_admin BOOLEAN DEFAULT FALSE;
```

---

## 📁 Estrutura de Arquivos

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── AdminProdutoController.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   └── Models/
│       └── Produto.php
├── database/
│   ├── migrations/
│   │   └── 2026_04_18_000000_add_is_admin_to_users_table.php
│   └── seeders/
│       └── AdminUserSeeder.php
├── resources/
│   └── views/
│       └── admin/
│           ├── dashboard.blade.php
│           └── produtos/
│               ├── create.blade.php
│               └── edit.blade.php
└── routes/
    └── web.php
```

---

## 🔑 Permissões e Segurança

### Middleware de Admin
O painel está protegido por dois middlewares:

1. **auth** - Verifica se o usuário está autenticado
2. **admin** - Verifica se o usuário possui `is_admin = true`

```php
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // Rotas de admin
});
```

### Como tornar um usuário admin

Via CLI:
```bash
php artisan tinker
>>> $user = User::find(1);
>>> $user->is_admin = true;
>>> $user->save();
```

Ou via banco de dados:
```sql
UPDATE users SET is_admin = 1 WHERE id = 1;
```

---

## 🚀 Rotas Disponíveis

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/admin/dashboard` | Dashboard principal |
| GET | `/admin/produtos/create` | Formulário de criação |
| POST | `/admin/produtos` | Armazenar novo produto |
| GET | `/admin/produtos/{id}/edit` | Formulário de edição |
| PUT | `/admin/produtos/{id}` | Atualizar produto |
| DELETE | `/admin/produtos/{id}` | Deletar produto |
| PATCH | `/admin/produtos/{id}/toggle` | Ativar/Desativar |
| PATCH | `/admin/produtos/{id}/toggle-destaque` | Marcar/Desmarcar destaque |
| GET | `/admin/produtos/search` | Buscar produtos |

---

## 💡 Dicas de Uso

1. **Imagens:** Use URLs completas de imagens. A aplicação também tentará buscar imagens automaticamente se não encontrar a URL fornecida.

2. **Preços:** Todos os valores de preço devem ser numéricos. Use ponto (.) como separador decimal.

3. **Estoque:** Quantidade em estoque deve ser um número inteiro. Pode ser 0 para produtos fora de estoque.

4. **Categoria:** Você pode criar novas categorias apenas digitando um nome novo, pois usa datalist com sugestões.

5. **Destaque:** Produtos em destaque aparecem no início da página inicial em um carrossel especial.

---

## ⚙️ Configuração Inicial

1. Rode as migrations:
```bash
php artisan migrate
```

2. Execute o seeder de admin:
```bash
php artisan db:seed --class=AdminUserSeeder
```

3. Acesse: `http://localhost/admin/dashboard`

4. Faça login com:
   - Email: `admin@loja.com`
   - Senha: `admin123`

5. **Altere a senha imediatamente!**

---

## 🐛 Solução de Problemas

### "Acesso não autorizado"
- Verifique se o usuário tem `is_admin = 1` no banco de dados
- Verifique se está logado corretamente

### Produtos não aparecem na loja
- Verifique se o campo `ativo` está marcado como `true`
- Verifique se as imagens estão carregando corretamente

### Erros de validação ao salvar
- Verifique se todos os campos obrigatórios foram preenchidos
- Certifique-se de que o código de barras é único (se preenchido)

---

## 📞 Suporte

Para reportar bugs ou sugerir melhorias, entre em contato com o desenvolvedor.

---

**Versão:** 1.0  
**Última atualização:** 18 de Abril de 2026
