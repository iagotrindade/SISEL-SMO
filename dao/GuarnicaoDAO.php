<?php

interface GuarnicaoDAOInterface
{
    public function insert(Guarnicao $guarnicao);
    public function update(Guarnicao $guarnicao);
    public function deleteId($id);
    public function findById($id);
    public function findAll();
    public function findAllAtivos();
}

class GuarnicaoDAO implements GuarnicaoDAOInterface
{
    private $conexao;

    public function __construct(PDO $conexao)
    {
        $this->conexao = $conexao;
    }

    public function buildGuarnicao($data)
    {
        $guarnicao = new Guarnicao($data['nome']);
        $guarnicao->setId($data['id']);
        $guarnicao->setRm($data['rm']);
        $guarnicao->setApagado($data['apagado']);
        return $guarnicao;
    }

    public function insert(Guarnicao $guarnicao)
    {
        $sql = "INSERT INTO guarnicao (rm, nome) VALUES (:rm, UPPER(:nome))";

        $this->conexao->beginTransaction();
        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(":rm", $guarnicao->getRm());
        $stmt->bindValue(":nome", $guarnicao->getNome());

        if($stmt->execute())
        {
            $data = [
                'id_adicionado' => $this->conexao->lastInsertId(),
                'rm' => $guarnicao->getRm(),
                'nome' => $guarnicao->getNome()
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

    public function update(Guarnicao $guarnicao)
    {
        $sql = "UPDATE guarnicao SET rm = :rm, nome = UPPER(:nome) WHERE id = :id";

        $this->conexao->beginTransaction();
        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(":id", $guarnicao->getId());
        $stmt->bindValue(":rm", $guarnicao->getRm());
        $stmt->bindValue(":nome", $guarnicao->getNome());

        if($stmt->execute())
        {
            $data = [
                'id_atualizado' => $guarnicao->getId(),
                'rm' => $guarnicao->getRm(),
                'nome' => $guarnicao->getNome()
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
        $sql = "UPDATE guarnicao SET apagado = 1 WHERE id = :id";

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
            $stmt = $this->conexao->prepare("SELECT * FROM guarnicao WHERE id = :id");
            $stmt->bindValue(":id", $id);
            $stmt->execute();

            if($stmt->rowCount() > 0)
            {
                $data = $stmt->fetch();
                $guarnicao = $this->buildGuarnicao($data);
                return $guarnicao;
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
            $stmt = $this->conexao->prepare("SELECT * FROM guarnicao WHERE nome = UPPER(:nome) AND apagado = 0");
            $stmt->bindValue(":nome", $nome);
            $stmt->execute();

            if($stmt->rowCount() > 0)
            {
                $data = $stmt->fetch();
                $guarnicao = $this->buildGuarnicao($data);
                return $guarnicao;
            }
            else
                return false;
        }
        else
            return false;
    }

    public function findAll()
    {
        $guarnicoes = [];
        $stmt = $this->conexao->prepare("SELECT * FROM guarnicao ORDER BY nome ASC");
        $stmt->execute();

        if($stmt->rowCount() > 0)
        {
            $data = $stmt->fetchAll();
            foreach($data as $item)
            {
                $guarnicao = $this->buildGuarnicao($item);
                $guarnicoes[] = $guarnicao;
            }
            return $guarnicoes;
        }
        else
            return false;
    }

    public function findAllAtivos()
    {
        $guarnicoes = [];
        $stmt = $this->conexao->prepare("SELECT * FROM guarnicao WHERE apagado = 0 ORDER BY nome ASC");
        $stmt->execute();

        if($stmt->rowCount() > 0)
        {
            $data = $stmt->fetchAll();
            foreach($data as $item)
            {
                $guarnicao = $this->buildGuarnicao($item);
                $guarnicoes[] = $guarnicao;
            }
            return $guarnicoes;
        }
        else
            return false;
    }

    public function findAllByRm($rm)
    {
        $guarnicoes = [];
        $stmt = $this->conexao->prepare("SELECT * FROM guarnicao WHERE rm = :rm AND apagado = 0 ORDER BY nome ASC");
        $stmt->bindValue(":rm", $rm);
        $stmt->execute();

        if($stmt->rowCount() > 0)
        {
            $data = $stmt->fetchAll();
            foreach($data as $item)
            {
                $guarnicao = $this->buildGuarnicao($item);
                $guarnicoes[] = $guarnicao;
            }
            return $guarnicoes;
        }
        else
            return false;
    }
}
