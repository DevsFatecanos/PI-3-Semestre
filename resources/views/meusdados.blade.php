<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title> Cadastro • Meus Dados</title>

  <!-- Fonte + ícones -->
  <link rel="stylesheet" href="../css/meusdados.css">
  <style>

  </style>
</head>
<body>
  <div class="page">
    <div class="card-cadastro">
      <!-- AVATAR GRANDE -->
      <div class="avatar-box">
        <div class="avatar-wrapper">
          <div class="avatar-preview" id="avatarPreview"></div>
          <button class="avatar-btn" id="avatarButton" type="button" aria-label="Alterar foto">
            <i class="bx bx-camera"></i>
          </button>
          <input type="file" id="avatarInput" accept="image/*" hidden />
        </div>
      </div>
      <!-- FORMULÁRIO DE DADOS -->
      <form class="form-dados" autocomplete="off">
        <div class="form-grid">
          <div class="field">
            <label for="tipoConta">Tipo de conta</label>
            <select id="tipoConta">
              <option>Pessoa Física</option>
              <option>Pessoa Jurídica</option>
            </select>
          </div>

          <div class="field">
            <label for="cpf">CPF</label>
            <input id="cpf" type="text" placeholder="111.222.333-44" />
          </div>

          <div class="field">
            <label for="nome">Nome</label>
            <input id="nome" type="text" placeholder="Seu nome completo" />
          </div>

          <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" placeholder="seuemail@exemplo.com" />
          </div>

          <div class="field">
            <label for="telefone">Telefone (WhatsApp)</label>
            <input id="telefone" type="tel" placeholder="(11) 91234-5678" />
          </div>

          <div class="field">
            <label for="cep">CEP</label>
            <input id="cep" type="text" placeholder="00000-000" />
          </div>

          <div class="field">
            <label for="endereco">Endereço</label>
            <input id="endereco" type="text" placeholder="Rua dos Bobos" />
          </div>

          <div class="field">
            <label for="numero">Número</label>
            <input id="numero" type="text" placeholder="0" />
          </div>

          <div class="field">
            <label for="complemento">Complemento</label>
            <input id="complemento" type="text" placeholder="Apartamento, bloco..." />
          </div>

          <div class="field">
            <label for="bairro">Bairro</label>
            <input id="bairro" type="text" placeholder="Bairro" />
          </div>

          <div class="field">
            <label for="cidade">Cidade</label>
            <input id="cidade" type="text" placeholder="Cidade" />
          </div>

          <div class="field">
            <label for="estado">Estado</label>
            <input id="estado" type="text" placeholder="SP" />
          </div>
        </div>

        <div class="actions">
          <button type="button" class="btn-secondary">Alterar Senha</button>
          <button type="submit" class="btn-primary">Salvar</button>
        </div>
      </form>
    </div>
  </div>

  <script src="admin.js"></script>
</body>
</html>