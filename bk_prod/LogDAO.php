<?php

    if (file_exists('../funcoes.php'))
        include_once '../funcoes.php';
    if (file_exists('funcoes.php'))
        include_once 'funcoes.php';

    class LogDAO
    {
        private $conexao;
        
        public function __construct(PDO $conexao) 
        {
            $this->conexao = $conexao;
        }
        
        public function insertLog($codigo, $tabela, $id_alterado, $alteracao, $alteracao_detalhada)
        {
            try
            {
                $id_usuario = null;
                if(isset($_SESSION['id_usuario_smo'])) $id_usuario = $_SESSION['id_usuario_smo'];

                $navegador = getBrowser();
                $navegador = $navegador['platform'] . " - " . $navegador['name']. " ". $navegador['version'] ;
                $ip = $_SERVER['REMOTE_ADDR'];

                $sql = "INSERT INTO log 
                        (id_usuario, codigo, tabela, id_alterado, alteracao, alteracao_detalhada, ip, sistema) 
                        VALUES (:id_usuario, :codigo, :tabela, :id_alterado, :alteracao, :alteracao_detalhada, :ip, :sistema)";
                
                $this->conexao->beginTransaction();
                $stmt = $this->conexao->prepare($sql);

                $stmt->bindValue(":id_usuario", $id_usuario);
                $stmt->bindValue(":codigo", $codigo);
                $stmt->bindValue(":tabela", $tabela);
                $stmt->bindValue(":id_alterado", $id_alterado);
                $stmt->bindValue(":alteracao", $alteracao);
                $stmt->bindValue(":alteracao_detalhada", $alteracao_detalhada);
                $stmt->bindValue(":ip", $ip);
                $stmt->bindValue(":sistema", $navegador);
                
                if($stmt->execute())
                {
                    $this->conexao->commit();
                    return true;
                }
                else
                {
                    print_r($stmt->errorInfo()); 
                    exit();
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
        
        public function insertAcessoPagina($pagina, $url)
        {
            try
            {
                $id_usuario = null;
                if(isset($_SESSION['id_usuario_smo'])) $id_usuario = $_SESSION['id_usuario_smo'];

                $navegador = getBrowser();
                $navegador = $navegador['platform'] . " - " . $navegador['name']. " ". $navegador['version'] ;
                $ip = $_SERVER['REMOTE_ADDR'];

                $sql = "INSERT INTO acesso
                        (id_usuario, pagina, url, ip, sistema) 
                        VALUES (:id_usuario, :pagina, :url, :ip, :sistema)";
                
                $this->conexao->beginTransaction();
                $stmt = $this->conexao->prepare($sql);

                $stmt->bindValue(":id_usuario", $id_usuario);
                $stmt->bindValue(":pagina", $pagina);
                $stmt->bindValue(":url", $url);
                $stmt->bindValue(":ip", $ip);
                $stmt->bindValue(":sistema", $navegador);
                
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
                //echo $ex->getMessage();
                //print_r($query->errorInfo()); 
                //exit();
            }
        }
        
        public function insertErro($nivel, $codigo, $arquivo, $descricao, $retorno_usuario)
        {
            try
            {
                $id_usuario = null;
                if(isset($_SESSION['id_usuario_smo'])) $id_usuario = $_SESSION['id_usuario_smo'];

                $navegador = getBrowser();
                $navegador = $navegador['platform'] . " - " . $navegador['name']. " ". $navegador['version'] ;
                $ip = $_SERVER['REMOTE_ADDR'];

                $sql = "INSERT INTO erro 
                        (id_usuario, nivel, codigo, arquivo, retorno_usuario, descricao, ip, sistema) 
                        VALUES (:id_usuario, :nivel, :codigo, :arquivo, :retorno_usuario, :descricao, :ip, :sistema)";
                
                $this->conexao->beginTransaction();
                $stmt = $this->conexao->prepare($sql);

                $stmt->bindValue(":id_usuario", $id_usuario);
                $stmt->bindValue(":codigo", $codigo);
                $stmt->bindValue(":nivel", $nivel);
                $stmt->bindValue(":arquivo", $arquivo);
                $stmt->bindValue(":retorno_usuario", $retorno_usuario);
                $stmt->bindValue(":descricao", $descricao);
                $stmt->bindValue(":ip", $ip);
                $stmt->bindValue(":sistema", $navegador);
                
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
                //print_r($query->errorInfo()); 
                //exit();
            }
        }
        
        public function quantidadeTentativasLogin($ip)
        {
            if($ip != null)
            {
                $sql = 'select * from tentativa_login where DATE_FORMAT(data, "%Y-%m-%d") = DATE_FORMAT(now(), "%Y-%m-%d") and ip = :ip';
                
                $stmt = $this->conexao->prepare($sql);
                $stmt->bindValue(":ip", $ip);
                $stmt->execute();
                
                if($stmt->rowCount() > 0)
                {
                    $data = $stmt->fetch();
                    return $data;
                }
                else 
                    return false;
            }
            else 
                return false;
        }
        
        public function findAllLimite($limite)
        {
                $sql = "select u.usuario, log.* from 
                        log
                        left join usuario u on u.id = log.id_usuario
                        where u.id_om = :id_om
                        order by log.id desc
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

        public function findAll()
        {
                $sql = "select u.usuario, log.* from 
                        log
                        left join usuario u on u.id = log.id_usuario
                        where u.id_om = :id_om
                        order by log.id desc
                        ";
                
                $stmt = $this->conexao->prepare($sql);
                $stmt->bindValue(":id_om", $_SESSION['id_om_smo']);
                $stmt->execute();

                $data = $stmt->fetchAll();
                
                if(count($data) > 0)
                    return $data;
                else
                    return false;

        }

        public function findAllCodigo($codigo_pesquisa)
        {
                $sql = "select u.usuario, log.* from 
                        log
                        left join usuario u on u.id = log.id_usuario
                        where log.codigo = :codigo_pesquisa
                        order by log.id desc
                        
                        ";
                
                $stmt = $this->conexao->prepare($sql);
                $stmt->bindValue(":codigo_pesquisa", $codigo_pesquisa, PDO::PARAM_INT);
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

