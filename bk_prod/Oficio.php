<?php

class Oficio
{   
    private $id_oficio;
    private $id_obrigatorio;
    private $bairro_om_1_fase; 
    private $presidente_comissao;  
    private $cep_cidade_om_1_fase; 
    private $data_apresentacao;
    private $hora_apresentacao;  
    private $tel_om_1_fase;
    private $eb;
    private $data_cabecalho;
    
    //////////////////////////////////////////
    // SETs
    //////////////////////////////////////////

    public function setIdOficio($id_oficio)
    {
        if((int)$id_oficio > 0)
            $this->id_oficio = $id_oficio;
        else
            return null;
    }

    public function setIdObrigatorio($id_obrigatorio)
    {
        if((int)$id_obrigatorio > 0)
            $this->id_obrigatorio = $id_obrigatorio;
        else
            return null;
    }
    
    public function setBairroOm1Fase($bairro_om_1_fase)
    {
        if(!empty($bairro_om_1_fase))
        $this->bairro_om_1_fase = $bairro_om_1_fase;
    else
        return false;
    }

    public function setPresidenteComissao($presidente_comissao)
    {
        if(!empty($presidente_comissao))
        $this->presidente_comissao = $presidente_comissao;
    else
        return false;
    }

    public function setCidadeOm1Fase($cep_cidade_om_1_fase)
    {
        if(!empty($cep_cidade_om_1_fase))
        $this->cep_cidade_om_1_fase = $cep_cidade_om_1_fase;
    else
        return false;
    }

    public function setCepCidadeom1Fase($cep_cidade_om_1_fase)
    {
        if(!empty($cep_cidade_om_1_fase))
        $this->cep_cidade_om_1_fase = $cep_cidade_om_1_fase;
    else
        return false;
    }

    public function setDataApresentacao($data_apresentacao)
    {
        if(!empty($data_apresentacao))
        $this->data_apresentacao = $data_apresentacao;
    else
        return false;
    }

  

    public function setTelOm1Fase($tel_om_1_fase)
    {
        if(!empty($tel_om_1_fase))
        $this->tel_om_1_fase = $tel_om_1_fase;
    else
        return false;
    }


    public function setHoraApresentacao($hora_apresentacao)
    {
        if(!empty($hora_apresentacao))
        $this->hora_apresentacao = $hora_apresentacao;
    else
        return false;
    }

    public function setEb($eb)
    {
        if(!empty($eb))
        $this->eb = $eb;
    else
        return false;
    }

    public function setDataCabecalho($data_cabecalho)
    {
        if(!empty($data_cabecalho))
        $this->data_cabecalho = $data_cabecalho;
    else
        return false;
    }

 
    //////////////////////////////////////////
    // GETs
    //////////////////////////////////////////
    
    public function getIdOficio()
    {
        if((int)$this->id_oficio > 0)
            return $this->id_oficio;
        else
            return 0;
    }

    public function getIdObrigatorio()
    {
        if((int)$this->id_obrigatorio > 0)
            return $this->id_obrigatorio;
        else
            return 0;
    }
    


    public function getBairroOm1Fase()
    {
        if(!empty($this->bairro_om_1_fase))
        return $this->bairro_om_1_fase;
    else 
        return null;
    }

 
    public function getPresidenteComissao()
    {
        if(!empty($this->presidente_comissao))
        return $this->presidente_comissao;
    else 
        return null;
    }


  

    public function getCepCidadeOm1Fase()
    {
        if(!empty($this->cep_cidade_om_1_fase))
        return $this->cep_cidade_om_1_fase;
    else 
        return null;
    }

   
    public function getDataApresentacao()
    {
        if(!empty($this->data_apresentacao))
        return $this->data_apresentacao;
    else 
        return null;
    }
    
    public function imprimeDataApresentacao()
    {
        if(!empty($this->data_apresentacao))
            return trata_data($this->data_apresentacao);
        else 
            return null;
    }


    public function getHoraApresentacao()
    {
        if(!empty($this->hora_apresentacao))
        return $this->hora_apresentacao;
    else 
        return null;
    }

 
    public function getTelOm1Fase()
    {
        if(!empty($this->tel_om_1_fase))
        return $this->tel_om_1_fase;
    else 
        return null;
    }


    public function getEb()
    {
        if(!empty($this->eb))
        return $this->eb;
    else 
        return null;
    }

    
    public function getDataCabecalho()
    {
        if(!empty($this->data_cabecalho))
        return $this->data_cabecalho;
     else 
        return null;
    }

    public function imprimeDataCabecalho()
    {
        if(!empty($this->data_cabecalho))
            return trata_data($this->data_cabecalho);
        else 
            return null;
    }
}

?>