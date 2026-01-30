<?php

if (file_exists('../funcoes.php'))
    include_once '../funcoes.php';
if (file_exists('funcoes.php'))
    include_once 'funcoes.php';

class AcessoDAO
{
    private $conexao;
    
    public function __construct(PDO $conexao) 
    {
        $this->conexao = $conexao;
    }
    
    
    public function findAll($limite)
    {
            $sql = "select u.usuario, a.*
                    from acesso a
                    inner join usuario u on u.id = a.id_usuario
                    where u.id_om = :id_om
                    order by a.id desc
                    limit :limite
                    ";
            
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":limite", $limite, PDO::PARAM_INT);
            $stmt->bindValue(":id_om", $_SESSION['id_om_smo']);
            $stmt->execute();

            $data = $stmt->fetchAll();
            
            if(count($data) > 0)
                return $data;
            else
                return false;

    }
    
}


?>

