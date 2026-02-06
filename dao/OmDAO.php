<?php

interface OmDAOInterface
{
    public function insert(OM $om);
    public function update(OM $om);
    public function deleteId($id);
    public function findById($id);
    public function findAll();
    public function findAllAtivos();
}

class OmDAO implements OmDAOInterface
{
    private $conexao;

    public function __construct(PDO $conexao)
    {
        $this->conexao = $conexao;
    }

    public function buildOm($data)
    {
        $om = new OM();
        $om->setId($data['id']);
        $om->setRm($data['rm']);
        $om->setFase($data['fase']);
        $om->setCodom($data['codom']);
        $om->setNome($data['nome']);
        $om->setAbreviatura($data['abreviatura']);
        $om->setTelefone($data['telefone']);
        $om->setEndereco($data['endereco']);
        $om->setCidade($data['cidade']);
        $om->setCep($data['cep']);
        $om->setApagado($data['apagado']);
        return $om;
    }

    public function insert(OM $om)
    {
        $sql = "INSERT INTO om (rm, fase, codom, nome, abreviatura, telefone, endereco, cidade, cep)
                VALUES (:rm, :fase, :codom, UPPER(:nome), UPPER(:abreviatura), :telefone, :endereco, :cidade, :cep)";

        $this->conexao->beginTransaction();
        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(":rm", $om->getRm());
        $stmt->bindValue(":fase", $om->getFase());
        $stmt->bindValue(":codom", $om->getCodom());
        $stmt->bindValue(":nome", $om->getNome());
        $stmt->bindValue(":abreviatura", $om->getAbreviatura());
        $stmt->bindValue(":telefone", $om->getTelefone());
        $stmt->bindValue(":endereco", $om->getEndereco());
        $stmt->bindValue(":cidade", $om->getCidade());
        $stmt->bindValue(":cep", $om->getCep());

        if($stmt->execute())
        {
            $data = [
                'id_adicionado' => $this->conexao->lastInsertId(),
                'rm' => $om->getRm(),
                'nome' => $om->getNome(),
                'abreviatura' => $om->getAbreviatura()
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

    public function update(OM $om)
    {
        $sql = "UPDATE om SET rm = :rm, fase = :fase, codom = :codom, nome = UPPER(:nome),
                abreviatura = UPPER(:abreviatura), telefone = :telefone, endereco = :endereco,
                cidade = :cidade, cep = :cep WHERE id = :id";

        $this->conexao->beginTransaction();
        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(":id", $om->getId());
        $stmt->bindValue(":rm", $om->getRm());
        $stmt->bindValue(":fase", $om->getFase());
        $stmt->bindValue(":codom", $om->getCodom());
        $stmt->bindValue(":nome", $om->getNome());
        $stmt->bindValue(":abreviatura", $om->getAbreviatura());
        $stmt->bindValue(":telefone", $om->getTelefone());
        $stmt->bindValue(":endereco", $om->getEndereco());
        $stmt->bindValue(":cidade", $om->getCidade());
        $stmt->bindValue(":cep", $om->getCep());

        if($stmt->execute())
        {
            $data = [
                'id_atualizado' => $om->getId(),
                'rm' => $om->getRm(),
                'nome' => $om->getNome(),
                'abreviatura' => $om->getAbreviatura()
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
        $sql = "UPDATE om SET apagado = 1 WHERE id = :id";

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
            $stmt = $this->conexao->prepare("SELECT * FROM om WHERE id = :id");
            $stmt->bindValue(":id", $id);
            $stmt->execute();

            if($stmt->rowCount() > 0)
            {
                $data = $stmt->fetch();
                $om = $this->buildOm($data);
                return $om;
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
            $stmt = $this->conexao->prepare("SELECT * FROM om WHERE nome = UPPER(:nome) AND apagado = 0");
            $stmt->bindValue(":nome", $nome);
            $stmt->execute();

            if($stmt->rowCount() > 0)
            {
                $data = $stmt->fetch();
                $om = $this->buildOm($data);
                return $om;
            }
            else
                return false;
        }
        else
            return false;
    }

    public function findByAbreviatura($abreviatura)
    {
        if(!empty($abreviatura))
        {
            $stmt = $this->conexao->prepare("SELECT * FROM om WHERE abreviatura = UPPER(:abreviatura) AND apagado = 0");
            $stmt->bindValue(":abreviatura", $abreviatura);
            $stmt->execute();

            if($stmt->rowCount() > 0)
            {
                $data = $stmt->fetch();
                $om = $this->buildOm($data);
                return $om;
            }
            else
                return false;
        }
        else
            return false;
    }

    public function findAll()
    {
        $oms = [];
        $stmt = $this->conexao->prepare("SELECT * FROM om ORDER BY nome ASC");
        $stmt->execute();

        if($stmt->rowCount() > 0)
        {
            $data = $stmt->fetchAll();
            foreach($data as $item)
            {
                $om = $this->buildOm($item);
                $oms[] = $om;
            }
            return $oms;
        }
        else
            return false;
    }

    public function findAllAtivos()
    {
        $oms = [];
        $stmt = $this->conexao->prepare("SELECT * FROM om WHERE apagado = 0 ORDER BY nome ASC");
        $stmt->execute();

        if($stmt->rowCount() > 0)
        {
            $data = $stmt->fetchAll();
            foreach($data as $item)
            {
                $om = $this->buildOm($item);
                $oms[] = $om;
            }
            return $oms;
        }
        else
            return false;
    }

    public function findAllByRm($rm)
    {
        $oms = [];
        $stmt = $this->conexao->prepare("SELECT * FROM om WHERE rm = :rm AND apagado = 0 ORDER BY nome ASC");
        $stmt->bindValue(":rm", $rm);
        $stmt->execute();

        if($stmt->rowCount() > 0)
        {
            $data = $stmt->fetchAll();
            foreach($data as $item)
            {
                $om = $this->buildOm($item);
                $oms[] = $om;
            }
            return $oms;
        }
        else
            return false;
    }

    public function findAllByFase($fase)
    {
        $oms = [];
        $stmt = $this->conexao->prepare("SELECT * FROM om WHERE fase = :fase AND apagado = 0 ORDER BY nome ASC");
        $stmt->bindValue(":fase", $fase);
        $stmt->execute();

        if($stmt->rowCount() > 0)
        {
            $data = $stmt->fetchAll();
            foreach($data as $item)
            {
                $om = $this->buildOm($item);
                $oms[] = $om;
            }
            return $oms;
        }
        else
            return false;
    }
}
