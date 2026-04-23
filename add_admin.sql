-- Script para adicionar usuário admin
-- Execute este comando no seu banco de dados PostgreSQL

-- Verificar e deletar usuário anterior se existir
DELETE FROM users WHERE email = 'admin@loja.com';

-- Inserir novo usuário admin
INSERT INTO users (name, email, password, is_admin, created_at, updated_at) VALUES
('Administrador', 'admin@loja.com', '$2y$12$WQXlT.C2KXQmlRuVz4zCBOwMF/QEKoNxZpO4SXoRKILZvlG.rwP.e', true, NOW(), NOW());

-- Verificar se foi inserido
SELECT id, name, email, is_admin FROM users WHERE email = 'admin@loja.com';
