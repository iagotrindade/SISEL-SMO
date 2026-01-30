<?php

if (file_exists('../funcoes.php'))
    include_once '../funcoes.php';
if (file_exists('funcoes.php'))
    include_once 'funcoes.php';

class AuxiliarDAO
{
    private $conexao;
    
    public function __construct(PDO $conexao) 
    {
        $this->conexao = $conexao;
    }

    public function findAllOM1Fase()
    {
            $sql = "SELECT * FROM om WHERE rm = " . $_SESSION['rm_smo']. " AND fase = 1 AND apagado = 0 order by abreviatura";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll();
            if(count($data) > 0)
                return $data;
            else
                return false;
    }

    

    public function findAllOM2Fase()
    {
            $sql = "SELECT * FROM om WHERE rm = " . $_SESSION['rm_smo']. " AND fase = 2 AND apagado = 0  order by abreviatura";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll();
            if(count($data) > 0)
                return $data;
            else
                return false;
    }
    
    public function findAllEspec()
    {
        $sql = "SELECT * FROM especialidade WHERE apagado = 0 ORDER BY nome";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        if(count($data) > 0)
            return $data;
        else
            return false;
    }


    public function findAllCidades()
    {
        $sql = "SELECT * FROM cidade";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        if(count($data) > 0)
            return $data;
        else
            return false;
    }

    public function findAllGuarnicao()
    {
        $sql = "SELECT * FROM guarnicao WHERE apagado = 0";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        if(count($data) > 0)
            return $data;
        else
            return false;
    }

    public function findAllGraduacao()
    {
        $sql = "SELECT * FROM graduacao WHERE apagado = 0 and rm = 3";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        if(count($data) > 0)
            return $data;
        else
            return false;
    }

    public function findAllCidInst()
    {
        $sql = "SELECT * FROM graduacao WHERE apagado = 0 order by nome";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        if(count($data) > 0)
            return $data;
        else
            return false;
    }

    ////////////////////////////////////////////
    ///////////
    //  Todas as datas 
    ///////////
    ////////////////////////////////////////////

    public function findAllDtSelecaoComp()
    {
        $sql = "SELECT data_selecao_geral data FROM obrigatorio WHERE apagado = 0 AND id_om = " . $_SESSION['id_om_smo']. " AND data_selecao_geral IS NOT NULL
                GROUP BY data_selecao_geral ORDER BY data_selecao_geral";
        
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        if(count($data) > 0)
            return $data;
        else
            return false;
    }

    public function verifica_prioridade($id_obrigatorio, $id_guarnicao)
    {
        $sql = "SELECT g.nome nome_gu, og.prioridade 
        FROM obrigatorio_x_guarnicao og
        inner join guarnicao g on g.id = og.id_guarnicao
        where og.apagado = 0
        and og.id_guarnicao = :id_guarnicao
        and og.id_obrigatorio = :id_obrigatorio
        and og.prioridade <= 5
        ";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(":id_obrigatorio", $id_obrigatorio);
        $stmt->bindValue(":id_guarnicao", $id_guarnicao);
        $stmt->execute();
        $data = $stmt->fetchAll();
        if(count($data) > 0)
            return $data;
        else
            return false;

    }
   

    public function findAllDtCompDesignacao()
    {
            $sql = "SELECT data_comparecimento_designacao data FROM obrigatorio WHERE apagado = 0 AND id_om = " . $_SESSION['id_om_smo']. " AND data_comparecimento_designacao IS NOT NULL
                    GROUP BY data_comparecimento_designacao ORDER BY data_comparecimento_designacao";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll();
            if(count($data) > 0)
                return $data;
            else
                return false;
    }

    public function findAllDtProxApresentacao()
    {
        $sql = "SELECT data_proxima_apresentacao data FROM obrigatorio WHERE apagado = 0 AND id_om = " . $_SESSION['id_om_smo']. " AND data_proxima_apresentacao IS NOT NULL
                GROUP BY data_proxima_apresentacao ORDER BY data_proxima_apresentacao";
        
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        if(count($data) > 0)
            return $data;
        else
            return false;
    }
   
    public function findAllDtIncorporacao()
    {
        $sql = "SELECT data_incorporacao data FROM obrigatorio WHERE apagado = 0 AND id_om = " . $_SESSION['id_om_smo']. " AND data_incorporacao IS NOT NULL
                GROUP BY data_incorporacao ORDER BY data_incorporacao";
        
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        if(count($data) > 0)
            return $data;
        else
            return false;
    }

    public function findAllIncorporacao()
    {
        $sql = "SELECT incorporacao FROM obrigatorio WHERE apagado = 0 AND id_om = " . $_SESSION['id_om_smo']. " AND incorporacao IS NOT NULL
                GROUP BY incorporacao ORDER BY incorporacao";
        
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        if(count($data) > 0)
            return $data;
        else
            return false;
    }

    public function findAllDtSelComplementar()
    {
            $sql = "SELECT data_selecao_complementar data FROM obrigatorio WHERE apagado = 0 AND id_om = " . $_SESSION['id_om_smo']. " AND data_selecao_complementar IS NOT NULL
                    GROUP BY data_selecao_complementar ORDER BY data_selecao_complementar";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll();
            if(count($data) > 0)
                return $data;
            else
                return false;
    }
   
   public function findExameMedico($data_sel_geral)
   {
            $sql = "SELECT * FROM exame_medico WHERE id_om = " . $_SESSION['id_om_smo']. " AND data = :data AND apagado = 0";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":data", $data_sel_geral);
            $stmt->execute();
            $data = $stmt->fetchAll();
            if(count($data) > 0)
                return $data;
           else
                return false;
   }

   public function findObrigatoriosPorDataCompSelGeral($data_comp)
   {
           $sql = "SELECT * FROM obrigatorio WHERE (data_comparecimento_selecao_geral = :data OR data_jisr = :data) AND id_om = " . $_SESSION['id_om_smo']. " AND apagado = 0";
           $stmt = $this->conexao->prepare($sql);
           $stmt->bindValue(":data", $data_comp);
           $stmt->execute();
           $data = $stmt->fetchAll();
           if(count($data) > 0)
               return $data;
          else
              return false;
   }

   public function findObrigatoriosParaListaPresenca()
   {
           $sql = "SELECT nome_completo, cpf, situacao_militar, nome_instituicao_ensino FROM obrigatorio WHERE apagado = 0 AND id_om = " . $_SESSION['id_om_smo']. " AND apagado = 0";
           $stmt = $this->conexao->prepare($sql);
           $stmt->execute();
           $data = $stmt->fetchAll();
           if(count($data) > 0)
               return $data;
          else
              return false;
   }
    

   

}


?>

