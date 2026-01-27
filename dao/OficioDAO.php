<?php 


interface OficioDAOInterface
{
    public function insert(Oficio $oficio);
   
}

class OficioDAO implements OficioDAOInterface 

{
    private $conexao;
    
    public function __construct(PDO $conexao) 
    {
        $this->conexao = $conexao;
    }
    
    public function buildOficio($data)
    {
        $oficio = new Oficio();
        $oficio->setIdOficio($data['id']);
        $oficio->setIdObrigatorio($data['id_obrigatorio']);
        $oficio->setBairroOm1Fase($data['bairro_om_1_fase']);
        $oficio->setPresidenteComissao($data['presidente_comissao']);
        $oficio->setCepCidadeom1Fase($data['cep_cidade_om_1_fase']);
        $oficio->setDataApresentacao($data['data_apresentacao']);
        $oficio->setHoraApresentacao($data['hora_apresentacao']);
        $oficio->setTelOm1Fase($data['tel_om_1_fase']);
        $oficio->setEb($data['eb']);
        $oficio->setDataCabecalho($data['data_cabecalho']);

        return $oficio;
    }

    public function insert(Oficio $oficio)
    {
        $sql = "INSERT INTO oficio
                (id_obrigatorio, bairro_om_1_fase,
                presidente_comissao, cep_cidade_om_1_fase, 
                data_apresentacao, hora_apresentacao, 
                tel_om_1_fase, 
                eb, data_cabecalho) 
        VALUES (:id_obrigatorio, UPPER(:bairro_om_1_fase), 
                UPPER(:presidente_comissao), :cep_cidade_om_1_fase, 
                :data_apresentacao, :hora_apresentacao, 
                :tel_om_1_fase, 
                UPPER(:eb), :data_cabecalho)";


        
        $this->conexao->beginTransaction();
        $stmt = $this->conexao->prepare($sql);
        
        
        $stmt->bindValue(":id_obrigatorio", $oficio->getIdObrigatorio());
        $stmt->bindValue(":bairro_om_1_fase", $oficio->getBairroOm1Fase());
        $stmt->bindValue(":presidente_comissao", $oficio->getPresidenteComissao());
        $stmt->bindValue(":cep_cidade_om_1_fase", $oficio->getCepCidadeOm1Fase());
        $stmt->bindValue(":data_apresentacao", $oficio->getDataApresentacao());
        $stmt->bindValue(":hora_apresentacao", $oficio->getHoraApresentacao());
        $stmt->bindValue(":tel_om_1_fase", $oficio->getTelOm1Fase());
        $stmt->bindValue(":eb", $oficio->getEb());
        $stmt->bindValue(":data_cabecalho", $oficio->getDataCabecalho());

      
        
        if($stmt->execute())
        {
            $data = 
            [ 
            'id_adicionado' => $this->conexao->lastInsertId(),
            'id_obrigatorio' => $oficio->getIdObrigatorio(),
            'bairro_om_1_fase' => $oficio->getBairroOm1Fase(),
            'presidente_comissao'=> $oficio->getPresidenteComissao(),
            'cep_cidade_om_1_fase' =>  $oficio->getCepCidadeOm1Fase(),
            'data_apresentacao' =>  $oficio->getDataApresentacao(),
            'hora_apresentacao' =>  $oficio->getHoraApresentacao(),
            'tel_om_1_fase' =>  $oficio->getTelOm1Fase(),
            'eb' =>  $oficio->getEb(),
            'data_cabecalho' =>  $oficio->getDataCabecalho(),
            
            ];

            $this->conexao->commit();
            return $data;

        }

         else 
        {
          //print_r($stmt->errorInfo()); 
          // exit();
            $this->conexao->rollBack();
            return false;
        }

    }

    public function update(Oficio $oficio)
    {

        $sql = "UPDATE oficio set 
        bairro_om_1_fase = UPPER(:bairro_om_1_fase),
        presidente_comissao = UPPER(:presidente_comissao),
        cep_cidade_om_1_fase = :cep_cidade_om_1_fase,
        data_apresentacao = :data_apresentacao,
        hora_apresentacao = :hora_apresentacao,
        tel_om_1_fase = :tel_om_1_fase,
        eb = UPPER(:eb),
        data_cabecalho = :data_cabecalho

        where id_obrigatorio = :id_obrigatorio"; 

        $this->conexao->beginTransaction();
        $stmt = $this->conexao->prepare($sql);
    
        $stmt->bindValue(":id_obrigatorio", $oficio->getIdObrigatorio());
        $stmt->bindValue(":bairro_om_1_fase", $oficio->getBairroOm1Fase());
        $stmt->bindValue(":presidente_comissao", $oficio->getPresidenteComissao());
        $stmt->bindValue(":cep_cidade_om_1_fase", $oficio->getCepCidadeOm1Fase());
        $stmt->bindValue(":data_apresentacao", $oficio->getDataApresentacao());
        $stmt->bindValue(":hora_apresentacao", $oficio->getHoraApresentacao());
        $stmt->bindValue(":tel_om_1_fase", $oficio->getTelOm1Fase());
        $stmt->bindValue(":eb", $oficio->getEb());
        $stmt->bindValue(":data_cabecalho", $oficio->getDataCabecalho());
        
        if($stmt->execute())
        {   
            
            $data = 
            [ 
            'id' =>  $oficio->getIdOficio(),
            'id_obrigatorio' =>  $oficio->getIdObrigatorio(),       
            'bairro_om_1_fase' => $oficio->getBairroOm1Fase(),
            'presidente_comissao'=> $oficio->getPresidenteComissao(),
            'cep_cidade_om_1_fase' =>  $oficio->getCepCidadeOm1Fase(),
            'data_apresentacao' =>  $oficio->getDataApresentacao(),
            'hora_apresentacao' =>  $oficio->getHoraApresentacao(),
            'tel_om_1_fase' =>  $oficio->getTelOm1Fase(),
            'eb' =>  $oficio->getEb(),
            'data_cabecalho' =>  $oficio->getDataCabecalho(),
           
            ];

            $this->conexao->commit();
            return $data;

         }

         else 
        {
          //print_r($stmt->errorInfo()); 
          //exit();
            $this->conexao->rollBack();
            return false;
        }
    
    }
        
    public function findByIdObrigatorio($id_obrigatorio)
    {
        if($id_obrigatorio > 0)
        {
            $stmt = $this->conexao->prepare("select * from oficio where id_obrigatorio = :id");
            $stmt->bindValue(":id", $id_obrigatorio);
            $stmt->execute();
            if($stmt->rowCount() > 0)
            {
                $data = $stmt->fetch();
                $user = $this->buildOficio($data);
                return $user;
            }
            else 
                return false;
        }
        else 
            return false;
    }
   
   
    public function findById($id_oficio)
    {
        if((int)$id_oficio > 0)
        {
            $stmt = $this->conexao->prepare("select * from oficio where id = :id");
            $stmt->bindValue(":id", $id_oficio);
            $stmt->execute();
            if($stmt->rowCount() > 0)
            {
                $data = $stmt->fetch();
                $user = $this->buildOficio($data);
                return $user;
            }
            else 
                return false;
        }
        else 
            return false;
   
    }

 }




?>