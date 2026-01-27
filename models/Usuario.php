<?php

class Usuario
{
    private $id;
    private $id_om;
    private $posto_grad;
    private $nome_guerra;
    private $usuario;
    private $perfil;
    private $cpf;
    private $nome_completo;
    private $telefone;
    private $mail;
    private $trocar_senha;
    private $senha;
    private $validade;
    private $apagado;
    private $data_cadastro;
    private $rm;
    private $abreviatura_om;
    
    public function __construct($usuario)
    {
        if(!empty($usuario))
        {
            $this->setUsuario($usuario);
        }
    }
    
    //////////////////////////////////////////
    // SETs
    //////////////////////////////////////////
    
    public function setId($id)
    {
        if((int)$id > 0)
            $this->id = $id;
        else
            return null;
    }
    
    public function setIdOm($id_om)
    {
        if((int)$id_om > 0)
            $this->id_om = (int)$id_om;
        else
            return 0;
    }

    public function setRm($rm)
    {
        if((int)$rm > 0)
            $this->rm = (int)$rm;
       else 
            return 0;
    }
    
    public function setNomeGuerra($nome_guerra)
    {
        if(!empty($nome_guerra))
            $this->nome_guerra = ucwords($nome_guerra);
        else
            return null;
    }
    
    public function setNomeCompleto($nome_completo)
    {
        if(!empty($nome_completo))
            $this->nome_completo = ucwords($nome_completo);
    }
    
    public function setUsuario($usuario)
    {
        if(!empty($usuario))
            $this->usuario = trim($usuario);
    }
    
    public function setMail($mail)
    {
        if(!empty($mail))
            $this->mail = trim($mail);
    }
    
    public function setPostoGrad($posto_grad)
    {
        if(!empty($posto_grad))
            $this->posto_grad = $posto_grad;
    }
    
    public function setPerfil($perfil)
    {
        if(!empty($perfil))
            $this->perfil = trim($perfil);
    }
    
    public function setSenha($senha)
    {
        if(!empty($senha))
            $this->senha = trim($senha);
    }
    
    public function setTrocarSenha($trocar_senha)
    {
        if((int)$trocar_senha == 1)
            $this->trocar_senha = true;
        else
            $this->trocar_senha = false;
    }
    
    public function setCPF($cpf)
    {
        if(!empty($cpf))
            $this->cpf = $cpf;
    }
    
    public function setTelefone($telefone)
    {
        if(!empty($telefone))
            $this->telefone = $telefone;
    }
    public function setValidade($validade)
    {
        if(!empty($validade))
            $this->validade = $validade;
    }
    
    public function setApagado($apagado)
    {
        if($apagado == 1)
            $this->apagado = 1;
        if($apagado == 0)
            $this->apagado = 0;
    }
    
    public function setDataCadastro($data_cadastro)
    {
        if(!empty($data_cadastro))
            $this->data_cadastro = $data_cadastro;
    }
    
    //////////////////////////////////////////
    // GETs
    //////////////////////////////////////////
    
    public function getId()
    {
        if((int)$this->id > 0)
            return $this->id;
        else
            return 0;
    }
    
    public function getIdOm()
    {
        if(!empty($this->id_om))
            return $this->id_om;
        else
            return 0;
    }

    public function getRm()
    {
        if(!empty($this->rm))
            return $this->rm;
        else
            return 0;
    }
    
    public function getUsuario()
    {
        if(!empty($this->usuario))
            return $this->usuario;
        else
            return null;
    }
    
    public function getNomeGuerra()
    {
        if(!empty($this->nome_guerra))
            return $this->nome_guerra;
        else
            return null;
    }
    
    public function getMail()
    {
        if(!empty($this->mail))
            return $this->mail;
        else 
            return null;
    }
    
    public function getNomeCompleto()
    {
        if(!empty($this->nome_completo))
            return $this->nome_completo;
        else 
            return null;
    }
    
    public function getCPF()
    {
        if(!empty($this->cpf))
            return $this->cpf;
        else 
            return null;
    }
    
    public function getPerfil()
    {
        if(!empty($this->perfil))
            return $this->perfil;
        else
            return null;
    }
    
    public function getTrocarSenha()
    {
        if($this->trocar_senha == 1)
            return true;
        else
            return null;
    }
    
    public function getPostoGrad()
    {
        if(!empty($this->posto_grad))
            return $this->posto_grad;
        else
            return null;
    }
    
    public function getTelefone()
    {
        if(!empty($this->telefone))
            return $this->telefone;
        else
            return null;
    }
    
    public function getSenha()
    {
        if(!empty($this->senha))
            return $this->senha;
        else
            return null;
    }

    public function imprimeValidade()
    {
        if(!empty($this->validade))
            return trata_data($this->validade);
        else
            return null;
    }

    public function getValidade()
    {
        if(!empty($this->validade))
            return $this->validade;
        else
            return null;
    }
    
    public function getApagado()
    {
        if($this->apagado != null)
            return $this->apagado;
        else
            return null;
    }
    
    public function getDataCadastro()
    {
        if(!empty($this->data_cadastro))
            return $this->data_cadastro;
        else
            return false;
    }
    
    public function __toString() 
    {
        return "ID: {$this->getId()} | Posto/Grad: {$this->getPostoGrad()} | Nome: {$this->getNomeGuerra()} | E-Mail: {$this->getCPF()} | Perfil: {$this->getPerfil()} | Telefone: {$this->getTelefone()} | Data de Cadastro: {$this->getDataCadastro()} | Apagado: {$this->getApagado()}";
    }
    

    /**
     * Get the value of abreviatura_om
     */ 
    public function getAbreviatura_om()
    {
        return $this->abreviatura_om;
    }

    /**
     * Set the value of abreviatura_om
     *
     * @return  self
     */ 
    public function setAbreviatura_om($abreviatura_om)
    {
        $this->abreviatura_om = $abreviatura_om;

        return $this;
    }
}

?>