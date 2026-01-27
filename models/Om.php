<?php

class OM
{   
    private $id;
    private $nome;
    private $abreviatura;
    private $telefone; 
    private $rm;  
    private $endereco; 
    private $cep;
    private $cidade;  
    private $apagado;
   
    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of nome
     */ 
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set the value of nome
     *
     * @return  self
     */ 
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get the value of abreviatura
     */ 
    public function getAbreviatura()
    {
        return $this->abreviatura;
    }

    /**
     * Set the value of abreviatura
     *
     * @return  self
     */ 
    public function setAbreviatura($abreviatura)
    {
        $this->abreviatura = $abreviatura;

        return $this;
    }

    /**
     * Get the value of telefone
     */ 
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * Set the value of telefone
     *
     * @return  self
     */ 
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;

        return $this;
    }

    /**
     * Get the value of rm
     */ 
    public function getRm()
    {
        return $this->rm;
    }

    /**
     * Set the value of rm
     *
     * @return  self
     */ 
    public function setRm($rm)
    {
        $this->rm = $rm;

        return $this;
    }

    /**
     * Get the value of endereco
     */ 
    public function getEndereco()
    {
        return $this->endereco;
    }

    /**
     * Set the value of endereco
     *
     * @return  self
     */ 
    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;

        return $this;
    }

    /**
     * Get the value of cep
     */ 
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * Set the value of cep
     *
     * @return  self
     */ 
    public function setCep($cep)
    {
        $this->cep = $cep;

        return $this;
    }

    /**
     * Get the value of cidade
     */ 
    public function getCidade()
    {
        return $this->cidade;
    }

    /**
     * Set the value of cidade
     *
     * @return  self
     */ 
    public function setCidade($cidade)
    {
        $this->cidade = $cidade;

        return $this;
    }

    /**
     * Get the value of apagado
     */ 
    public function getApagado()
    {
        return $this->apagado;
    }

    /**
     * Set the value of apagado
     *
     * @return  self
     */ 
    public function setApagado($apagado)
    {
        $this->apagado = $apagado;

        return $this;
    }
}  

?>