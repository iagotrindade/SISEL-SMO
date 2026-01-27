<?php

class Arquivo
{
    private $id;
    private $id_obrigatorio;
    private $label;
    private $nome;
    private $nome_original;
    private $tamanho;
    private $formato;
    
    public function __construct()
    {
        //echo "Novo Objeto Denuncia ";
    }
    
    //////////////////////////////////////////
    // SETs
    //////////////////////////////////////////
    
    public function setId($id)
    {
        if((int)$id > 0)
            $this->id = $id;
        else
            return false;
    }
    
    public function setIdObrigatorio($id_obrigatorio)
    {
        if((int)$id_obrigatorio > 0)
            $this->id_obrigatorio = $id_obrigatorio;
        else
            return false;
    }
    
    public function setLabel($label)
    {
        if(!empty($label))
            $this->label = $label;
        else
            return false;
    }
    public function setNome($nome)
    {
        if(!empty($nome))
            $this->nome = $nome;
        else
            return false;
    }
    
    public function setNomeOriginal($nome_original)
    {
        if(!empty($nome_original))
            $this->nome_original = $nome_original;
        else
            return false;
    }
    
    public function setTamanho($tamanho)
    {
        if((int)$tamanho > 0)
            $this->tamanho = $tamanho;
        else
            return false;
    }
    
    public function setFormato($formato)
    {
        if(!empty($formato))
            $this->formato = $formato;
        else
            return false;
    }
    
    //////////////////////////////////////////
    // GETs
    //////////////////////////////////////////
    
    public function getId()
    {
        if((int)$this->id > 0)
            return $this->id;
        else
            return null;
    }
    
    public function getIdObrigatorio()
    {
        if((int)$this->id_obrigatorio > 0)
            return $this->id_obrigatorio;
        else
            return null;
    }
    
    public function getLabel()
    {
        if(!empty($this->label))
            return $this->label;
        else
            return null;
    }
    
    public function getNome()
    {
        if(!empty($this->nome))
            return $this->nome;
        else
            return null;
    }
    
    public function getNomeOriginal()
    {
        if(!empty($this->nome_original))
            return $this->nome_original;
        else
            return null;
    }
    
    public function getFormato()
    {
        if(!empty($this->formato))
            return $this->formato;
        else
            return null;
    }
    
    public function getTamanho()
    {
        if((int)$this->tamanho > 0)
            return $this->tamanho;
        else
            return null;
    }
    
    public function __toString() 
    {
        return "ID: {$this->getId()} | ID Obrigatorio: {$this->getIdObrigatorio()} | Label: {$this->getLabel()}";
    }
    
}

?>