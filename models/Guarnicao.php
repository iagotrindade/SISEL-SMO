<?php

class Guarnicao
{
    private $id;
    private $rm;
    private $nome;
    private $apagado;

    public function __construct($nome = null)
    {
        if(!empty($nome))
        {
            $this->setNome($nome);
        }
    }

    // Setters
    public function setId($id)
    {
        if((int)$id > 0)
            $this->id = $id;
        else
            return null;
    }

    public function setRm($rm)
    {
        if((int)$rm > 0)
            $this->rm = $rm;
        else
            $this->rm = null;
    }

    public function setNome($nome)
    {
        if(!empty($nome))
            $this->nome = strtoupper(trim($nome));
        else
            return null;
    }

    public function setApagado($apagado)
    {
        $this->apagado = (int)$apagado;
    }

    // Getters
    public function getId()
    {
        if((int)$this->id > 0)
            return $this->id;
        else
            return 0;
    }

    public function getRm()
    {
        if((int)$this->rm > 0)
            return $this->rm;
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

    public function getApagado()
    {
        return $this->apagado;
    }

    public function __toString()
    {
        return "ID: {$this->getId()} | RM: {$this->getRm()} | Nome: {$this->getNome()}";
    }
}
