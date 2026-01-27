<?php

class JuntaDAO
{
    private $conexao;
    
    public function __construct(PDO $conexao) 
    {
        $this->conexao = $conexao;
    }
    
    public function insert($presidente, $membro_1, $membro_2, $secao, $data, $cidade)
    {
        try
        {
            $id_om = null;
            if(isset($_SESSION['id_om_smo'])) $id_om = $_SESSION['id_om_smo'];
           
            $sql = "INSERT INTO exame_medico 
                    (id_om, secao, data, cidade, presidente, membro_1, membro_2) 
                    VALUES (:id_om, :secao, :data, :cidade, :presidente, :membro_1, :membro_2)";
            
            $this->conexao->beginTransaction();
            $stmt = $this->conexao->prepare($sql);

            $stmt->bindValue(":id_om", $id_om);
            $stmt->bindValue(":secao", $secao);
            $stmt->bindValue(":data", $data);
            $stmt->bindValue(":cidade", $cidade);
            $stmt->bindValue(":presidente", $presidente);
            $stmt->bindValue(":membro_1", $membro_1);
            $stmt->bindValue(":membro_2", $membro_2);
            
            if($stmt->execute())
            {
                $data = [
                        'id_adicionado' => $this->conexao->lastInsertId(),
                        'presidente'=>$presidente,
                        'membro_1'=>$membro_1,
                        'membro_2'=>$membro_2,
                        'secao'=>$secao,
                        'data'=>$data,
                        'cidade'=>$cidade
                        ];
                        
                $this->conexao->commit();
                return $data;
            }
            else
            {
                //print_r($stmt->errorInfo()); 
                //exit();
                return false;
            }
                
            
        } 
        catch (Exception $ex) 
        {
            //print_r($stmt->errorInfo()); 
            //exit();
            return false;
            //echo $ex->getMessage();
            //print_r($query->errorInfo()); 
            //exit();
        }
    }
   
    public function update($id_junta, $secao, $data, $cidade, $presidente, $membro_1, $membro_2)
    {
        $id_om = null;
        if(isset($_SESSION['id_om_smo'])) $id_om = $_SESSION['id_om_smo'];

        $sql = "UPDATE exame_medico SET secao = :secao, data = :data, cidade = :cidade, presidente = :presidente, membro_1 = :membro_1, membro_2 = :membro_2
                WHERE id = $id_junta";
        
        $this->conexao->beginTransaction();
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(":secao", $secao);
        $stmt->bindValue(":data", $data);
        $stmt->bindValue(":cidade", $cidade);
        $stmt->bindValue(":presidente", $presidente);
        $stmt->bindValue(":membro_1", $membro_1);
        $stmt->bindValue(":membro_2", $membro_2);
        
        if($stmt->execute())
        {
            $this->conexao->commit();
            return true;
        }
        else
        {
          // print_r($stmt->errorInfo()); 
          // exit();
            $this->conexao->rollBack();
            return false;
        }
    }
   
    public function findById($id)
    {
        if($id > 0)
        {       
           
                $stmt = $this->conexao->prepare("SELECT * FROM exame_medico WHERE id = :id");
                $stmt->bindValue(":id", $id);
                $stmt->execute();
                if($stmt->rowCount() > 0)
                {    
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                    return $data;
                }
                else 
                    return false;
        }
        else 
            echo "teste 2";
            return false;
    }
    
    public function deleteId($id_junta)
    {
        $sql = "update exame_medico set apagado = 1, _usuario_ultima_atualizacao =:_usuario_ultima_atualizacao where id = :id";
        $this->conexao->beginTransaction();
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(":id", $id_junta);
        $stmt->bindValue(":_usuario_ultima_atualizacao", $_SESSION['id_usuario_smo']);
        
        if($stmt->execute())
        {
            $this->conexao->commit();
            return true;
        }
        else
        {
         //  print_r($stmt->errorInfo()); 
          // exit();
            $this->conexao->rollBack();
            return false;
        }
    }
    
    public function findAllAtivos()
    {
        
        $stmt = $this->conexao->prepare("select * from exame_medico where apagado = 0 and id_om = :id_om order by id desc");
        $stmt->bindValue(":id_om", $_SESSION['id_om_smo']);
        $stmt->execute();
        
        if($stmt->rowCount() > 0)
        {
            $data = $stmt->fetchAll();
            return $data;
        }
        else 
            return false;
         
    }

    public function findByData($data)
    {
        
        $stmt = $this->conexao->prepare("select * from exame_medico where apagado = 0 and id_om = :id_om and data = :data");
        $stmt->bindValue(":id_om", $_SESSION['id_om_smo']);
        $stmt->bindValue(":data", $data);
        $stmt->execute();
        
        if($stmt->rowCount() > 0)
        {
            $data = $stmt->fetchAll();
            return $data;
        }
        else 
            return false;
         
    }
    
    
}


?>

