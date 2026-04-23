# Integração de APIs de Produtos: EanPictures + BlueSoft

Este documento descreve como usar as integrações de APIs para buscar dados e imagens de produtos no projeto.

## 📋 APIs Integradas

### 1. **EanPictures**
- **URL**: http://www.eanpictures.com.br:9000/api
- **Descrição**: Fornece imagens de produtos e dados baseados em código de barras (EAN/GTIN)

### 2. **BlueSoft**
- **URL**: https://cdn-cosmos.bluesoft.com.br/products
- **Descrição**: Fornece dados detalhados de produtos incluindo nome, marca, categoria, preço e imagem

### 3. **API Agregadora** (novo)
- **Descrição**: Combina dados de múltiplas fontes, priorizando BlueSoft sobre EanPictures
- **Vantagem**: Busca automática na melhor fonte disponível

## 🔧 Arquivos Adicionados

### Serviços
1. **[app/Services/EanPicturesService.php](app/Services/EanPicturesService.php)** - Integração com EanPictures
2. **[app/Services/BlueSoftService.php](app/Services/BlueSoftService.php)** - Integração com BlueSoft
3. **[app/Services/ProdutoApiAggregatorService.php](app/Services/ProdutoApiAggregatorService.php)** - Agregador de múltiplas fontes

### Controllers
1. **[app/Http/Controllers/EanPicturesController.php](app/Http/Controllers/EanPicturesController.php)** - Endpoints para EanPictures
2. **[app/Http/Controllers/BlueSoftController.php](app/Http/Controllers/BlueSoftController.php)** - Endpoints para BlueSoft
3. **[app/Http/Controllers/ProdutoApiAggregatorController.php](app/Http/Controllers/ProdutoApiAggregatorController.php)** - Endpoints agregadores

### Comandos Artisan
1. **[app/Console/Commands/SincronizarEanPictures.php](app/Console/Commands/SincronizarEanPictures.php)** - Sincronização com suporte a múltiplas fontes

### Arquivos Modificados
- **routes/web.php** - Adicionadas rotas para `/api/ean-pictures/*`, `/api/bluesoft/*` e `/api/produtos/*`
- **ProductImageResolver.php** - Integração automática com API Agregadora

## 📡 Endpoints da API

### API EanPictures (`/api/ean-pictures/`)

```
GET /api/ean-pictures/{ean}/imagem              # URL da imagem
GET /api/ean-pictures/{ean}/descricao           # Descrição básica
GET /api/ean-pictures/{ean}/descricao-200       # Descrição completa (200 campos)
GET /api/ean-pictures/{ean}/descricao-ini       # Descrição em INI
GET /api/ean-pictures/{ean}/verificar-foto      # Verifica se existe foto
GET /api/ean-pictures/{ean}/completo            # Dados completos
GET /api/ean-pictures/unidade/{codigo}          # Info de unidade de medida
```

### API BlueSoft (`/api/bluesoft/`)

```
GET /api/bluesoft/{ean}                         # Dados completos do produto
GET /api/bluesoft/{ean}/imagem                  # URL da imagem
GET /api/bluesoft/{ean}/completo                # Dados estruturados
```

### API Agregadora (`/api/produtos/`) - RECOMENDADO

Combina dados de múltiplas fontes, priorizando BlueSoft:

```
GET /api/produtos/{ean}                         # Produto com dados de todas as fontes
GET /api/produtos/{ean}/imagem                  # Melhor imagem disponível
GET /api/produtos/{ean}/nome                    # Nome do produto
GET /api/produtos/{ean}/marca                   # Marca do produto
GET /api/produtos/{ean}/fontes                  # Quais fontes têm dados
```

## 🚀 Como Usar

### Via Command Artisan

Sincronizar todos os produtos (tenta BlueSoft e EanPictures automaticamente):
```bash
php artisan ean:sincronizar
```

Sincronizar um produto específico:
```bash
php artisan ean:sincronizar --id=1
```

Sincronizar apenas de uma fonte:
```bash
php artisan ean:sincronizar --source=bluesoft    # Apenas BlueSoft
php artisan ean:sincronizar --source=ean         # Apenas EanPictures
```

Sincronizar apenas imagens:
```bash
php artisan ean:sincronizar --only-image
```

Sobrescrever dados existentes:
```bash
php artisan ean:sincronizar --force
```

### Via Controller (em código PHP)

#### Usando API Agregadora (RECOMENDADO)
```php
use App\Services\ProdutoApiAggregatorService;

$aggregator = app(ProdutoApiAggregatorService::class);

// Obter imagem (tenta BlueSoft primeiro)
$imagemUrl = $aggregator->obterImagem('7898951180079');

// Obter dados completos de múltiplas fontes
$produto = $aggregator->obterProdutoCompleto('7898951180079');

// Verificar quais fontes têm dados
$fontes = $aggregator->verificarFontes('7898951180079');
// Retorna: ['bluesoft' => true, 'eanpictures' => false]
```

#### Usando BlueSoft Diretamente
```php
use App\Services\BlueSoftService;

$blueSoft = app(BlueSoftService::class);

// Obter dados completos
$produto = $blueSoft->obterProdutoCompleto('7898951180079');
// Retorna nome, marca, categoria, imagem_url, etc.

// Obter imagem
$imagem = $blueSoft->obterImagem('7898951180079');
```

#### Usando EanPictures Diretamente
```php
use App\Services\EanPicturesService;

$eanService = app(EanPicturesService::class);

// Obter descrição
$descricao = $eanService->obterDescricao('78932609');

// Obter imagem
$imagem = $eanService->obterImagem('78932609');

// Obter dados completos
$produto = $eanService->obterProdutoCompleto('78932609');
```

### Via HTTP API

#### Agregadora (melhor opção)
```bash
# Obter produto com dados de todas as fontes
curl http://localhost/api/produtos/7898951180079

# Obter melhor imagem disponível
curl http://localhost/api/produtos/7898951180079/imagem

# Verificar quais fontes têm dados
curl http://localhost/api/produtos/7898951180079/fontes
```

#### BlueSoft
```bash
# Obter produto
curl http://localhost/api/bluesoft/7898951180079

# Obter imagem
curl http://localhost/api/bluesoft/7898951180079/imagem
```

#### EanPictures
```bash
# Obter descrição
curl http://localhost/api/ean-pictures/78932609/descricao

# Obter imagem
curl http://localhost/api/ean-pictures/78932609/imagem
```

### Integração Automática no Modelo

A classe `ProductImageResolver` foi modificada para buscar automaticamente:

```php
// No modelo Produto, a imagem é resolvida assim:
$produto->url_imagem; 
// Tenta BlueSoft > EanPictures > fallback para picsum.photos
```

## 📊 Exemplos de Responses

### BlueSoft - Produto Encontrado
```json
{
    "status": "success",
    "ean": "7898951180079",
    "data": {
        "nome": "Produto Exemplo",
        "descricao": "Descrição do produto",
        "marca": "Marca X",
        "categoria": "Categoria Y",
        "preco": 19.90,
        "imagem_url": "https://cdn-cosmos.bluesoft.com.br/...",
        "sku": "SKU123",
        "peso": 500
    }
}
```

### API Agregadora - Produto Completo
```json
{
    "status": "success",
    "ean": "7898951180079",
    "data": {
        "ean": "7898951180079",
        "nome": "Produto Exemplo",
        "descricao": "Descrição detalhada",
        "marca": "Marca X",
        "categoria": "Categoria Y",
        "preco": 19.90,
        "imagem_url": "https://cdn-cosmos.bluesoft.com.br/...",
        "fonte_imagem": "bluesoft",
        "tem_foto_bluesoft": true,
        "tem_foto_eanpictures": false,
        "dados_bluesoft": {...},
        "dados_eanpictures": null
    }
}
```

### EanPictures - Descrição Encontrada
```json
{
    "status": "success",
    "ean": "78932609",
    "data": {
        "Status": "200",
        "Status_Desc": "Ok",
        "Nome": "Chiclets Adams Hortelã",
        "Ncm": "17041000",
        "Cest_Codigo": "1800200",
        "Embalagem": "Unidade",
        "QuantidadeEmbalagem": "1",
        "Marca": "ADAMS",
        "Categoria": "MERCEARIA"
    }
}
```

### Produto Não Encontrado
```json
{
    "status": "not_found",
    "ean": "999999999999",
    "message": "Produto não encontrado em nenhuma fonte"
}
```

## ⚙️ Configuração

Não há configurações adicionais necessárias. O serviço está pronto para usar imediatamente.

### Variáveis de Ambiente
Nenhuma variável de ambiente é obrigatória. O serviço usa a URL padrão da API EanPictures.

## 🛡️ Tratamento de Erros

Todos os métodos da `EanPicturesService` retornam `null` em caso de erro:

```php
$imagem = $eanService->obterImagem('ean-invalido');
if ($imagem === null) {
    // Erro ou não encontrado
}
```

Os erros são registrados no log em `storage/logs/laravel.log`.

## 📝 Notas Importantes

1. **Prioridade de Fontes**: BlueSoft > EanPictures > Fallback (picsum.photos)
2. **EAN Válido**: Deve conter apenas dígitos
3. **Timeout**: Cada requisição tem timeout de 10 segundos
4. **Cache**: Considere implementar cache para respostas frequentes
5. **Logging**: Erros são registrados em `storage/logs/laravel.log`
6. **BlueSoft**: Geralmente tem dados mais completos e imagens de melhor qualidade
7. **EanPictures**: Ótimo como fallback, especialmente para produtos brasileiros

## 🔗 Referências

- **EanPictures**: https://github.com/ConectivaSoftware/EanPictures
- **EanPictures API**: http://www.eanpictures.com.br:9000/api
- **BlueSoft**: https://www.bluesoft.com.br
- **BlueSoft CDN**: https://cdn-cosmos.bluesoft.com.br/products

## 💡 Casos de Uso

1. **Importação de Produtos**: Sincronizar base com fotos e descrições reais
2. **Enriquecimento de Dados**: Adicionar informações a produtos incompletos
3. **Resolução de Imagens**: Buscar imagens reais de produtos por código de barras
4. **Validação de Produtos**: Verificar se um EAN existe nas APIs
5. **Integração com Fornecedores**: Sincronizar dados com base em EAN
6. **Busca por Produto**: Encontrar dados de um produto desconhecido pelo EAN

## ⚠️ Tratamento de Erros

Todos os métodos das classes de serviço retornam `null` em caso de erro:

```php
$imagem = $aggregator->obterImagem('ean-invalido');
if ($imagem === null) {
    // Erro ou não encontrado - verifique o log
}
```

Os erros são registrados em `storage/logs/laravel.log` com nível `warning` ou `error`.

