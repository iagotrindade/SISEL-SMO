<?php

interface EspecialidadeDAOInterface
{
    public function insert(Especialidade $especialidade);
    public function update(Especialidade $especialidade);
    public function deleteId($id);
    public function findById($id);
    public function findAll();
    public function findAllAtivos();
}

class EspecialidadeDAO implements EspecialidadeDAOInterface
{
    private $conexao;

    public function __construct(PDO $conexao)
    {
        $this->conexao = $conexao;
    }

    public function buildEspecialidade($data)
    {
        $especialidade = new Especialidade($data['nome']);
        $especialidade->setId($data['id']);
        $especialidade->setRm($data['rm']);
        $especialidade->setApagado($data['apagado']);
        return $especialidade;
    }

    public function insert(Especialidade $especialidade)
    {
        $sql = "INSERT INTO especialidade (rm, nome) VALUES (:rm, UPPER(:nome))";

        $this->conexao->beginTransaction();
        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(":rm", $especialidade->getRm());
        $stmt->bindValue(":nome", $especialidade->getNome());

        if($stmt->execute())
        {
            $data = [
                'id_adicionado' => $this->conexao->lastInsertId(),
                'rm' => $especialidade->getRm(),
                'nome' => $especialidade->getNome()
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

    public function update(Especialidade $especialidade)
    {
        $sql = "UPDATE especialidade SET rm = :rm, nome = UPPER(:nome) WHERE id = :id";

        $this->conexao->beginTransaction();
        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(":id", $especialidade->getId());
        $stmt->bindValue(":rm", $especialidade->getRm());
        $stmt->bindValue(":nome", $especialidade->getNome());

        if($stmt->execute())
        {
            $data = [
                'id_atualizado' => $especialidade->getId(),
                'rm' => $especialidade->getRm(),
                'nome' => $especialidade->getNome()
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
        $sql = "UPDATE especialidade SET apagado = 1 WHERE id = :id";

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
            $stmt = $this->conexao->prepare("SELECT * FROM especialidade WHERE id = :id");
            $stmt->bindValue(":id", $id);
            $stmt->execute();

            if($stmt->rowCount() > 0)
            {
                $data = $stmt->fetch();
                $especialidade = $this->buildEspecialidade($data);
                return $especialidade;
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
            $stmt = $this->conexao->prepare("SELECT * FROM especialidade WHERE nome = UPPER(:nome) AND apagado = 0");
            $stmt->bindValue(":nome", $nome);
            $stmt->execute();

            if($stmt->rowCount() > 0)
            {
                $data = $stmt->fetch();
                $especialidade = $this->buildEspecialidade($data);
                return $especialidade;
            }
            else
                return false;
        }
        else
            return false;
    }

    public function findAll()
    {
        $especialidades = [];
        $stmt = $this->conexao->prepare("SELECT * FROM especialidade ORDER BY nome ASC");
        $stmt->execute();

        if($stmt->rowCount() > 0)
        {
            $data = $stmt->fetchAll();
            foreach($data as $item)
            {
                $especialidade = $this->buildEspecialidade($item);
                $especialidades[] = $especialidade;
            }
            return $especialidades;
        }
        else
            return false;
    }

    public function findAllAtivos()
    {
        $especialidades = [];
        $stmt = $this->conexao->prepare("SELECT * FROM especialidade WHERE apagado = 0 ORDER BY nome ASC");
        $stmt->execute();

        if($stmt->rowCount() > 0)
        {
            $data = $stmt->fetchAll();
            foreach($data as $item)
            {
                $especialidade = $this->buildEspecialidade($item);
                $especialidades[] = $especialidade;
            }
            return $especialidades;
        }
        else
            return false;
    }

    public function findAllByRm($rm)
    {
        $especialidades = [];
        $stmt = $this->conexao->prepare("SELECT * FROM especialidade WHERE rm = :rm AND apagado = 0 ORDER BY nome ASC");
        $stmt->bindValue(":rm", $rm);
        $stmt->execute();

        if($stmt->rowCount() > 0)
        {
            $data = $stmt->fetchAll();
            foreach($data as $item)
            {
                $especialidade = $this->buildEspecialidade($item);
                $especialidades[] = $especialidade;
            }
            return $especialidades;
        }
        else
            return false;
    }
}
