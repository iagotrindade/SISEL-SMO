<?php

class ArquivoDAO
{
    private $conexao;
    
    public function __construct(PDO $conexao) 
    {
        $this->conexao = $conexao;
    }
    
    public function buildArquivo($data)
    {
        $arquivo = new Arquivo();
        $arquivo->setId($data['id']);
        $arquivo->setIdObrigatorio($data['id_obrigatorio']);
        $arquivo->setLabel($data['label']);
        $arquivo->setNome($data['nome']);
        return $arquivo;
    }
                                
    //public function insertArquivo($id_obrigatorio, $label, $nome_arquivo, $nome_original, $tamanho_do_arquivo, $extensao)
    public function insertArquivo($arquivo)
    {
        
        try
        {
            $id_usuario = null;
            if(isset($_SESSION['id_usuario'])) $id_usuario = $_SESSION['id_usuario'];

            $sql = "INSERT INTO arquivo 
                    (        id_obrigatorio,  label,  nome,  nome_original,  formato,  tamanho) 
                    VALUES (:id_obrigatorio, :label, :nome, :nome_original,  :formato, :tamanho)";
            
            $this->conexao->beginTransaction();
            $stmt = $this->conexao->prepare($sql);

            $stmt->bindValue(":id_obrigatorio", $arquivo->getIdObrigatorio());
            $stmt->bindValue(":label", $arquivo->getLabel());
            $stmt->bindValue(":nome", $arquivo->getNome());
            $stmt->bindValue(":nome_original", $arquivo->getNomeOriginal());
            $stmt->bindValue(":tamanho", $arquivo->getTamanho());
            $stmt->bindValue(":formato", $arquivo->getFormato());
            
            if($stmt->execute())
            {
                $data = 
                [
                    'id_adicionado' => $this->conexao->lastInsertId(),
                    'id_obrigatorio'=>$arquivo->getIdObrigatorio(),
                    'label'=>$arquivo->getLabel(),
                    'nome'=>$arquivo->getNome(),                    
                    'nome_original'=>$arquivo->getNomeOriginal(),
                    'tamanho'=>$arquivo->getTamanho(),
                    'formato'=>$arquivo->getFormato()
                ];

            
               $this->conexao->commit();
               return $data;
            }
            else
                return false;
            
        } 
        catch (Exception $ex) 
        {
            $this->conexao->rollBack();
            //return false;
            echo $ex->getMessage();
            //print_r($stmt->errorInfo()); 
            exit();
        }
        
       
    }
    
    
    public function deleteId($id_arquivo)
    {
        try
        {
            $sql = "UPDATE arquivo set apagado = 1 where id = :id_arquivo";
            
            $this->conexao->beginTransaction();
            $stmt = $this->conexao->prepare($sql);

            $stmt->bindValue(":id_arquivo", $id_arquivo);
            if($stmt->execute())
            {
                $this->conexao->commit();
                return true;
            }
            else
                return false;
            
        } 
        catch (Exception $ex) 
        {
            return false;
            //echo $e->getMessage();
            //print_r($stmt->errorInfo()); 
            exit();
        }
    }
    
    public function findByIdObrigatorio($id_obrigatorio)
    {
            $sql = "select * 
                    from arquivo
                    where id_obrigatorio = :id_obrigatorio
                    and apagado = 0
                    ";
            
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":id_obrigatorio", $id_obrigatorio, PDO::PARAM_INT);
            $stmt->execute();

            if($stmt->rowCount() > 0)
            {

                $data = $stmt->fetchAll();
                $arquivos = Array();
                foreach($data as $item) 
                {
                    $arquivo = $this->buildArquivo($item);
                    $arquivos[] = $arquivo;
                }
                return $arquivos;
            }
            else 
                return false;
    }
    
    public function findById($id_arquivo)
    {
            $sql = "select * 
                    from arquivo
                    where id = :id_arquivo
                    ";
            
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":id_arquivo", $id_arquivo, PDO::PARAM_INT);
            $stmt->execute();
            
            if($stmt->rowCount() > 0)
            {
                $data = $stmt->fetch();
                $arquivo = $this->buildArquivo($data);
                return $arquivo;
            }
                
            else
                return false;

    }
   
}


?>