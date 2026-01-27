<?php

interface UsuarioDAOInterface
{
    public function insert(Usuario $usuario);
    public function findAll();
}

class UsuarioDAO implements UsuarioDAOInterface
{
    
    private $conexao;
    
    public function __construct(PDO $conexao) 
    {
        $this->conexao = $conexao;
    }
    
    public function buildUsuario($data)
    {

        $usuario = new Usuario($data['usuario']);
        
        $usuario->setId($data['id']);
        $usuario->setIdOm($data['id_om']);
        $usuario->setUsuario($data['usuario']);
        $usuario->setNomeCompleto($data['nome_completo']);
        $usuario->setNomeGuerra($data['nome_guerra']);
        $usuario->setMail($data['mail']);
        $usuario->setCPF($data['cpf']);
        $usuario->setPerfil($data['perfil']);
        $usuario->setTrocarSenha($data['trocar_senha']);
        $usuario->setTelefone($data['telefone']);
        $usuario->setPostoGrad($data['posto_grad']);
        $usuario->setDataCadastro($data['data_cadastro']);
        $usuario->setValidade($data['validade']);
        $usuario->setApagado($data['apagado']);
        $usuario->setRm($data['rm']);
        $usuario->setAbreviatura_om($data['abreviatura']);
       
       
         return $usuario;
        

    }
    
    public function insert(Usuario $usuario)
    {
        $sql = "INSERT INTO usuario 
                (id_om, usuario, nome_guerra, nome_completo,  cpf,  telefone,  mail,  senha,  perfil, posto_grad, validade, trocar_senha) 
        VALUES (:id_om,  :usuario, UPPER(:nome_guerra), UPPER(:nome_completo), :cpf, :telefone, UPPER(:mail), :senha, :perfil, :posto_grad, :validade, 1)";
        
        $this->conexao->beginTransaction();
        $stmt = $this->conexao->prepare($sql);
        
        $stmt->bindValue(":id_om", $usuario->getIdOm());
        $stmt->bindValue(":nome_completo", $usuario->getNomeCompleto());
        $stmt->bindValue(":nome_guerra", $usuario->getNomeGuerra());
        $stmt->bindValue(":usuario", $usuario->getUsuario());
        $stmt->bindValue(":cpf", $usuario->getCPF());
        $stmt->bindValue(":telefone", $usuario->getTelefone());
        $stmt->bindValue(":posto_grad", $usuario->getPostoGrad());
        $stmt->bindValue(":mail", $usuario->getMail());
        $stmt->bindValue(":senha", $usuario->getSenha());
        $stmt->bindValue(":perfil", $usuario->getPerfil());
        $stmt->bindValue(":validade", $usuario->getValidade());

        if($stmt->execute())
        {
            $data = 
            [
                'id_adicionado' => $this->conexao->lastInsertId(),
                'id_om'=>$usuario->getIdOm(),
                'usuario'=>$usuario->getUsuario(),
                'cpf'=>$usuario->getCPF(),
                'nome_guerra'=>$usuario->getNomeGuerra(),
                'nome_completo'=>$usuario->getNomeCompleto(),
                'telefone'=>$usuario->getTelefone(),
                'mail'=>$usuario->getMail(),
                'posto_grad'=>$usuario->getPostoGrad(),
                'perfil'=>$usuario->getPerfil(),                    
                'validade'=>$usuario->getValidade(),                    
                'senha'=>$usuario->getSenha()
            ];

            $this->conexao->commit();
            return $data;
        }
        else
        {
           print_r($stmt->errorInfo()); 
            exit();
            $this->conexao->rollBack();
            return false;
        }
    }
    
    public function update_pass(Usuario $usuario)
    {
        $sql = "UPDATE usuario set senha = :senha, _usuario_ultima_atualizacao =:_usuario_ultima_atualizacao where id = :id"; 
        
        $this->conexao->beginTransaction();
        $stmt = $this->conexao->prepare($sql);
        
        $stmt->bindValue(":id", $usuario->getId());
        $stmt->bindValue(":senha", $usuario->getSenha());
        $stmt->bindValue(":_usuario_ultima_atualizacao", $_SESSION['id_usuario_smo']);
        
        if($stmt->execute())
        {
            $data = 
            [
                'id_atualizado' => $usuario->getId(),
                'usuario'=>$usuario->getUsuario(),
                'senha'=>$usuario->getSenha(),
            ];

            $this->conexao->commit();
            return $data;
        }
        else
        {
           // print_r($stmt->errorInfo()); 
            //exit();
            $this->conexao->rollBack();
            return false;
        }
    }

    public function alterou_senha_padrao(Usuario $usuario)
    {
        $sql = "UPDATE usuario set trocar_senha = 0 where id = :id"; 
        
        $this->conexao->beginTransaction();
        $stmt = $this->conexao->prepare($sql);
        
        $stmt->bindValue(":id", $usuario->getId());
        
        if($stmt->execute())
        {
            $this->conexao->commit();
            return true;
        }
        else
        {
           // print_r($stmt->errorInfo()); 
           //exit();
            $this->conexao->rollBack();
            return false;
        }
    }
    
    public function update(Usuario $usuario)
    {
        $sql = "UPDATE usuario set nome_completo = UPPER(:nome_completo), nome_guerra = UPPER(:nome_guerra),  cpf = :cpf,  telefone = :telefone, posto_grad = :posto_grad,
            mail = UPPER(:mail), perfil = :perfil, validade = :validade, id_om = :id_om, _usuario_ultima_atualizacao = :_usuario_ultima_atualizacao where id = :id"; 
        
        $this->conexao->beginTransaction();
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(":id", $usuario->getId());
        $stmt->bindValue(":id_om", $usuario->getIdOm());
        $stmt->bindValue(":nome_completo", $usuario->getNomeCompleto());
        $stmt->bindValue(":nome_guerra", $usuario->getNomeGuerra());
        $stmt->bindValue(":cpf", $usuario->getCPF());
        $stmt->bindValue(":telefone", $usuario->getTelefone());
        $stmt->bindValue(":mail", $usuario->getMail());
        $stmt->bindValue(":posto_grad", $usuario->getPostoGrad());
        $stmt->bindValue(":perfil", $usuario->getPerfil());
        $stmt->bindValue(":validade", $usuario->getValidade());
        $stmt->bindValue(":_usuario_ultima_atualizacao", $_SESSION['id_usuario_smo']);
        if($stmt->execute())
        {
            $data = 
            [
                'id_atualizado' => $usuario->getId(),
                'id_om'=>$usuario->getIdOm(),
                'usuario'=>$usuario->getUsuario(),
                'CPF'=>$usuario->getCPF(),
                'nome_guerra'=>$usuario->getNomeGuerra(),
                'nome_completo'=>$usuario->getNomeCompleto(),
                'telefone'=>$usuario->getTelefone(),
                'posto_grad'=>$usuario->getPostoGrad(),
                'mail'=>$usuario->getMail(),
                'perfil'=>$usuario->getPerfil(),
                'validade'=>$usuario->getValidade()
                
            ];

            $this->conexao->commit();
            return $data;
        }
        else
        {
            print_r($stmt->errorInfo()); 
            exit();
            $this->conexao->rollBack();
            return false;
        }
    }
    
    public function tentativa_login($mail)
    {
        $navegador = getBrowser();
        $navegador = $navegador['platform'] . " - " . $navegador['name']. " ". $navegador['version'] ;
        $ip = $_SERVER['REMOTE_ADDR'];
        
        $sql = "INSERT INTO tentativa_login 
                (usuario, ip, sistema) 
                VALUES (:mail, :ip, :sistema)";
        
        $this->conexao->beginTransaction();
        $stmt = $this->conexao->prepare($sql);
        
        $stmt->bindValue(":mail", $mail);
        $stmt->bindValue(":ip", $ip);
        $stmt->bindValue(":sistema", $navegador);
        
        if($stmt->execute())
        {
            $this->conexao->commit();
            //return $data;
        }
        else
        {
          // print_r($stmt->errorInfo()); 
           //exit();
            $this->conexao->rollBack();
            return false;
        }
    }
    
    public function findByUsuario($usuario)
    {
        if(!empty($usuario))
        {
            $stmt = $this->conexao->prepare("SELECT usuario.*, om.rm FROM usuario INNER JOIN om ON usuario.id_om = om.id 
            WHERE usuario = :usuario AND usuario.apagado = 0;");
            $stmt->bindValue(":usuario", $usuario);
            $stmt->execute();
            if($stmt->rowCount() > 0)
            {
                $data = $stmt->fetch();
                $user = $this->buildUsuario($data);
                return $user;
            }
            else 
                return false;
        }
        else 
            return false;
    }
    
    public function findById($id)
    {
        if((int)$id > 0)
        {
            $stmt = $this->conexao->prepare("SELECT usuario.*, om.rm, om.abreviatura FROM usuario INNER JOIN om ON usuario.id_om = om.id WHERE usuario.id = :id");
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            if($stmt->rowCount() > 0)
            {
                $data = $stmt->fetch();
                $user = $this->buildUsuario($data);
                return $user;
            }
            else 
                return false;
        }
        else 
            return false;
    }

    public function findByCPF($cpf)
    {
        if((int)$cpf > 0)
        {
            $stmt = $this->conexao->prepare("SELECT usuario.*, om.rm FROM usuario INNER JOIN om ON usuario.id_om = om.id WHERE usuario.cpf = :cpf");
            $stmt->bindValue(":cpf", $cpf);
            $stmt->execute();
            if($stmt->rowCount() > 0)
            {
                $data = $stmt->fetch();
                $user = $this->buildUsuario($data);
                return $user;
            }
            else 
                return false;
        }
        else 
            return false;
    }

    public function findByEmailCPF($email, $cpf)
    {
        if($email != null && $cpf != null)
        {
            $sql = "SELECT u.*, o.rm, o.abreviatura FROM usuario u INNER JOIN om o ON u.id_om = o.id WHERE UPPER(u.mail) = UPPER(:email) AND u.cpf = :cpf AND u.apagado = 0;";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":email", $email);
            $stmt->bindValue(":cpf", $cpf);
            $stmt->execute();
            if($stmt->rowCount() == 1)
            {
                $data = $stmt->fetch();
                $user = $this->buildUsuario($data);
                return $user;
            }
            else 
                return false;
        }
        else 
            return false;
    }
    
    
    public function findByLogin($usuario, $senha)
    {
     
        if($usuario != null && $senha != null)
        {
            $sql = "SELECT u.*, o.rm FROM usuario u INNER JOIN om o ON u.id_om = o.id WHERE u.usuario = :usuario AND u.senha = :senha AND u.apagado = 0;";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":usuario", $usuario);
            $stmt->bindValue(":senha", $senha);
            $stmt->execute();
            if($stmt->rowCount() == 1)
            {
                $data = $stmt->fetch();
                $user = $this->buildUsuario($data);
                return $user;
            }
            else 
                return false;
        }
        else 
            return false;
    } 

    
    public function deleteId($id_usuario)
    {
        $sql = "update usuario set apagado = 1, _usuario_ultima_atualizacao =:_usuario_ultima_atualizacao where id = :id";
        $this->conexao->beginTransaction();
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(":id", $id_usuario);
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
        $usuarios = [];
        $stmt = $this->conexao->prepare("SELECT u.*, om.rm, om.abreviatura FROM usuario u INNER JOIN om ON u.id_om = om.id where u.apagado = 0 ORDER BY u.id ASC");
       // $stmt->bindValue(":id_om", $_SESSION['id_om_smo']);
        $stmt->execute();
        
        if($stmt->rowCount() > 0)
        {
            $data = $stmt->fetchAll();

            foreach($data as $item) 
            {
                $usuario = $this->buildUsuario($item);
                $usuarios[] = $usuario;
            }

            return $usuarios;
        }
        else 
            return false;
         
    }
    
    public function findAll()
    {
        $usuarios = [];
        $stmt = $this->conexao->query("SELECT *, om.rm FROM usuario INNER JOIN om ON usuario.id_om = om.id ORDER BY nome_guerra");
        $data = $stmt->fetchAll();

        foreach($data as $item) 
        {
          $usuario = $this->buildUsuario($item);
          $usuarios[] = $usuario;
        }

        return $usuarios;
         
    }

    public function forcar_troca_senha(Usuario $usuario)
    {
        $sql = "UPDATE usuario set trocar_senha = 1 where id = :id"; 
        
        $this->conexao->beginTransaction();
        $stmt = $this->conexao->prepare($sql);
        
        $stmt->bindValue(":id", $usuario->getId());
        
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
}


?>

