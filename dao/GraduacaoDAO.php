<?php

interface GraduacaoDAOInterface
{
    public function insert(Graduacao $graduacao);
    public function update(Graduacao $graduacao);
    public function deleteId($id);
    public function findById($id);
    public function findAll();
    public function findAllAtivos();
}

class GraduacaoDAO implements GraduacaoDAOInterface
{
    private $conexao;

    public function __construct(PDO $conexao)
    {
        $this->conexao = $conexao;
    }

    public function buildGraduacao($data)
    {
        $graduacao = new Graduacao($data['nome']);
        $graduacao->setId($data['id']);
        $graduacao->setRm($data['rm']);
        $graduacao->setApagado($data['apagado']);
        return $graduacao;
    }

    public function insert(Graduacao $graduacao)
    {
        $sql = "INSERT INTO graduacao (rm, nome) VALUES (:rm, UPPER(:nome))";

        $this->conexao->beginTransaction();
        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(":rm", $graduacao->getRm());
        $stmt->bindValue(":nome", $graduacao->getNome());

        if($stmt->execute())
        {
            $data = [
                'id_adicionado' => $this->conexao->lastInsertId(),
                'rm' => $graduacao->getRm(),
                'nome' => $graduacao->getNome()
            ];
            $this->conexao->commit();
            return $data;
        }
        else
        {
            $this->conexao->rollBack();
            return false;
        }
    }

    public function update(Graduacao $graduacao)
    {
        $sql = "UPDATE graduacao SET rm = :rm, nome = UPPER(:nome) WHERE id = :id";

        $this->conexao->beginTransaction();
        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(":id", $graduacao->getId());
        $stmt->bindValue(":rm", $graduacao->getRm());
        $stmt->bindValue(":nome", $graduacao->getNome());

        if($stmt->execute())
        {
            $data = [
                'id_atualizado' => $graduacao->getId(),
                'rm' => $graduacao->getRm(),
                'nome' => $graduacao->getNome()
            ];
            $this->conexao->commit();
            return $data;
        }
        else
        {
            $this->conexao->rollBack();
            return false;
        }
    }

    public function deleteId($id)
    {
        $sql = "UPDATE graduacao SET apagado = 1 WHERE id = :id";

        $this->conexao->beginTransaction();
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(":id", $id);

        if($stmt->execute())
        {
            $this->conexao->commit();
            return true;
        }
        else
        {
            $this->conexao->rollBack();
            return false;
        }
    }

    public function findById($id)
    {
        if((int)$id > 0)
        {
            $stmt = $this->conexao->prepare("SELECT * FROM graduacao WHERE id = :id");
            $stmt->bindValue(":id", $id);
            $stmt->execute();

            if($stmt->rowCount() > 0)
            {
                $data = $stmt->fetch();
                $graduacao = $this->buildGraduacao($data);
                return $graduacao;
            }
            else
                return false;
        }
        else
            return false;
    }

    public function findByNome($nome)
    {
        if(!empty($nome))
        {
            $stmt = $this->conexao->prepare("SELECT * FROM graduacao WHERE nome = UPPER(:nome) AND apagado = 0");
            $stmt->bindValue(":nome", $nome);
            $stmt->execute();

            if($stmt->rowCount() > 0)
            {
                $data = $stmt->fetch();
                $graduacao = $this->buildGraduacao($data);
                return $graduacao;
            }
            else
                return false;
        }
        else
            return false;
    }

    public function findAll()
    {
        $graduacoes = [];
        $stmt = $this->conexao->prepare("SELECT * FROM graduacao ORDER BY nome ASC");
        $stmt->execute();

        if($stmt->rowCount() > 0)
        {
            $data = $stmt->fetchAll();
            foreach($data as $item)
            {
                $graduacao = $this->buildGraduacao($item);
                $graduacoes[] = $graduacao;
            }
            return $graduacoes;
        }
        else
            return false;
    }

    public function findAllAtivos()
    {
        $graduacoes = [];
        $stmt = $this->conexao->prepare("SELECT * FROM graduacao WHERE apagado = 0 ORDER BY nome ASC");
        $stmt->execute();

        if($stmt->rowCount() > 0)
        {
            $data = $stmt->fetchAll();
            foreach($data as $item)
            {
                $graduacao = $this->buildGraduacao($item);
                $graduacoes[] = $graduacao;
            }
            return $graduacoes;
        }
        else
            return false;
    }

    public function findAllByRm($rm)
    {
        $graduacoes = [];
        $stmt = $this->conexao->prepare("SELECT * FROM graduacao WHERE rm = :rm AND apagado = 0 ORDER BY nome ASC");
        $stmt->bindValue(":rm", $rm);
        $stmt->execute();

        if($stmt->rowCount() > 0)
        {
            $data = $stmt->fetchAll();
            foreach($data as $item)
            {
                $graduacao = $this->buildGraduacao($item);
                $graduacoes[] = $graduacao;
            }
            return $graduacoes;
        }
        else
            return false;
    }
}
