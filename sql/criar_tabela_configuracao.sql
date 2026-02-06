-- Script para criar a tabela de configurações do sistema
-- Execute este script no banco de dados SMO

CREATE TABLE IF NOT EXISTS configuracao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chave VARCHAR(100) NOT NULL UNIQUE,
    valor VARCHAR(255) NOT NULL,
    label VARCHAR(100),
    descricao VARCHAR(255),
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    atualizado_por INT,
    FOREIGN KEY (atualizado_por) REFERENCES usuario(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir configurações iniciais
INSERT INTO configuracao (chave, valor, label, descricao) VALUES
('visualizacao_distribuidos_om_1_fase', '0', 'Visualização Distribuídos OM', 'Liberar visualização dos distribuídos para operadores de OM'),
('login_operador_om', '0', 'Login Operadores de OM', 'Liberar login dos operadores de OM no sistema');

-- Script para adicionar o campo label em tabelas existentes (se necessário)
-- ALTER TABLE configuracao ADD COLUMN label VARCHAR(100) AFTER valor;

-- Script para adicionar a configuração de login em tabelas existentes
-- INSERT INTO configuracao (chave, valor, label, descricao) VALUES ('login_operador_om', '0', 'Login Operadores de OM', 'Liberar login dos operadores de OM no sistema (0=Bloqueado, 1=Liberado)');
