<?php

if (file_exists('../funcoes.php'))
    include_once '../funcoes.php';
if (file_exists('funcoes.php'))
    include_once 'funcoes.php';

class ConfiguracaoDAO
{
    private $conexao;

    public function __construct(PDO $conexao)
    {
        $this->conexao = $conexao;
    }

    /**
     * Busca o valor de uma configuração pela chave
     */
    public function getValor($chave)
    {
        $sql = "SELECT valor FROM configuracao WHERE chave = :chave";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':chave', $chave);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data)
            return $data['valor'];
        else
            return null;
    }

    /**
     * Verifica se uma configuração está ativa (valor = '1')
     */
    public function isAtivo($chave)
    {
        $valor = $this->getValor($chave);
        return $valor === '1';
    }

    /**
     * Atualiza o valor de uma configuração
     */
    public function setValor($chave, $valor, $usuario_id = null)
    {
        $sql = "UPDATE configuracao SET valor = :valor, atualizado_por = :usuario_id WHERE chave = :chave";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':valor', $valor);
        $stmt->bindValue(':chave', $chave);
        $stmt->bindValue(':usuario_id', $usuario_id);

        return $stmt->execute();
    }

    /**
     * Alterna o valor de uma configuração (0 para 1, 1 para 0)
     */
    public function toggleValor($chave, $usuario_id = null)
    {
        $valorAtual = $this->getValor($chave);
        $novoValor = ($valorAtual === '1') ? '0' : '1';

        return $this->setValor($chave, $novoValor, $usuario_id);
    }

    /**
     * Busca todas as configurações
     */
    public function findAll()
    {
        try {
            $sql = "SELECT * FROM configuracao ORDER BY chave";
            $stmt = $this->conexao->prepare($sql);

            if (!$stmt) {
                return [];
            }

            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($data && count($data) > 0)
                return $data;
            else
                return [];
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Busca uma configuração pela chave com todos os detalhes
     */
    public function findByChave($chave)
    {
        $sql = "SELECT * FROM configuracao WHERE chave = :chave";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':chave', $chave);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cria uma nova configuração
     */
    public function criar($chave, $valor, $label = null, $descricao = null, $usuario_id = null)
    {
        $sql = "INSERT INTO configuracao (chave, valor, label, descricao, atualizado_por) VALUES (:chave, :valor, :label, :descricao, :usuario_id)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':chave', $chave);
        $stmt->bindValue(':valor', $valor);
        $stmt->bindValue(':label', $label);
        $stmt->bindValue(':descricao', $descricao);
        $stmt->bindValue(':usuario_id', $usuario_id);

        return $stmt->execute();
    }

    /**
     * Atualiza o label de uma configuração
     */
    public function setLabel($chave, $label, $usuario_id = null)
    {
        $sql = "UPDATE configuracao SET label = :label, atualizado_por = :usuario_id WHERE chave = :chave";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':label', $label);
        $stmt->bindValue(':chave', $chave);
        $stmt->bindValue(':usuario_id', $usuario_id);

        return $stmt->execute();
    }
}
