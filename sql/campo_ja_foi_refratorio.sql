-- =====================================================
-- CAMPO "JÁ FOI REFRATÁRIO" - SMO
-- Adiciona o campo ja_foi_refratorio na tabela obrigatorio
-- Valores possíveis: 'SIM', 'NÃO', NULL
-- =====================================================

ALTER TABLE obrigatorio
    ADD COLUMN ja_foi_refratorio VARCHAR(10) NULL DEFAULT NULL
    AFTER voluntario;
