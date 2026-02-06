-- =====================================================
-- SISTEMA DE NOTIFICAÇÕES - SMO
-- Execute este script no banco de dados
-- =====================================================

-- Tabela principal de notificações
CREATE TABLE IF NOT EXISTS notificacao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('adiamento_vencendo', 'incorporacao_sem_revisao') NOT NULL,
    criticidade ENUM('alta', 'media', 'baixa') NOT NULL DEFAULT 'media',
    titulo VARCHAR(255) NOT NULL,
    mensagem TEXT,
    id_obrigatorio INT NULL,
    id_om INT NULL COMMENT 'NULL = visível para todas as OMs',
    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    lida TINYINT(1) NOT NULL DEFAULT 0,
    data_leitura DATETIME NULL,
    id_usuario_leitura INT NULL,
    ativa TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1=ativa, 0=arquivada',

    INDEX idx_tipo (tipo),
    INDEX idx_criticidade (criticidade),
    INDEX idx_id_om (id_om),
    INDEX idx_lida (lida),
    INDEX idx_ativa (ativa),
    INDEX idx_data_criacao (data_criacao),

    FOREIGN KEY (id_obrigatorio) REFERENCES obrigatorio(id) ON DELETE CASCADE,
    FOREIGN KEY (id_om) REFERENCES om(id) ON DELETE SET NULL,
    FOREIGN KEY (id_usuario_leitura) REFERENCES usuario(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de controle de execução (para o cron simulado)
CREATE TABLE IF NOT EXISTS sistema_config (
    chave VARCHAR(100) PRIMARY KEY,
    valor TEXT,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir configuração inicial
INSERT INTO sistema_config (chave, valor) VALUES
('ultima_verificacao_notificacoes', NULL)
ON DUPLICATE KEY UPDATE chave = chave;

-- =====================================================
-- VIEWS ÚTEIS PARA CONSULTAS
-- =====================================================

-- View de notificações com dados do obrigatório
CREATE OR REPLACE VIEW vw_notificacoes AS
SELECT
    n.*,
    o.nome_completo AS obrigatorio_nome,
    o.cpf AS obrigatorio_cpf,
    om.abreviatura AS om_abreviatura,
    om.nome AS om_nome
FROM notificacao n
LEFT JOIN obrigatorio o ON o.id = n.id_obrigatorio
LEFT JOIN om ON om.id = n.id_om;
