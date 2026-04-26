Projeto de e-commerce da Foccus
ter o Xamp: 8.2.12 instalado
como instalar localmente:
usando git bash
git clone https://github.com/DevsFatecanos/PI-3-Semestre
e configurar a .env: Copiar o env.exemple e descomentar as entradas do banco de dados
logo após na pasta do projeto
1 - "composer install"
2 - "php artisan migrate"
3 - "php artisan key:generate"
4 - "php artisan db:seed"  
5 - para rodar o projeto "php artisan serve"
6 - caso nao funcione tente "composer global require laravel/installer" e tente o item 4

## Como rodar no GitHub Codespaces

### Terminal 1 - Servidor Laravel

```bash
cd /workspaces/PI-3-Semestre
/usr/local/php/8.4.8/bin/php artisan serve --host=0.0.0.0 --port=8000
```

### Terminal 2 - Vite (Hot Module Replacement / Assets)

```bash
cd /workspaces/PI-3-Semestre
npm run dev
```

A aplicação estará disponível em: http://localhost:8000

### Funcionalidades Implementadas

#### ✅ Carrinho de Compras com Modal/Overlay

- **SEM Redirecionamento**: Ao clicar em "Adicionar ao Carrinho", o item é adicionado na mesma página
- **Modal Sobreposto**: Carrinho exibido como um overlay/modal sobre a página inicial
- **Requisições AJAX**: Todas as operações do carrinho usam AJAX (sem refresh de página)
- **Notificações**: Feedback visual ao adicionar, remover ou atualizar quantidade
- **Badge Dinâmico**: Numero de itens atualizado em tempo real

Como usar:

1. Clique em "Adicionar ao Carrinho" ou "Adicionar ao Pedido" em qualquer produto
2. Veja a notificação de sucesso (canto superior direito)
3. Badge com número de itens atualiza automaticamente
4. Clique no ícone do carrinho (🛒) para abrir o modal
5. No modal pode: aumentar/diminuir quantidade, remover itens, continuar comprando

#### ✅ Checkout e Finalizacao de Pedido

- Rota de checkout em `/checkout`
- Processamento da finalizacao em `/checkout/processar` com dados do cliente e método preferido (PIX, Cartão, Boleto)
- Integração com Mercado Pago Checkout Pro (quando `MERCADO_PAGO_ACCESS_TOKEN` estiver configurado)
- Fallback automático para modo simulado local quando não há token
- Página de retorno com status da transação em `/checkout/retorno`

Variáveis para integração de pagamento:

```env
MERCADO_PAGO_ACCESS_TOKEN=
MERCADO_PAGO_SANDBOX=true
MERCADO_PAGO_WEBHOOK_URL=
```

### Possíveis Erros e Soluções

Se PHP não funcionar com `libcrypto.so.1.1 error`, use:

```bash
/usr/local/php/8.4.8/bin/php artisan serve --host=0.0.0.0 --port=8000
```
