<?php
    include_once 'header.php';
    include_once 'dao/conecta_banco.php';
    include_once 'models/Obrigatorio.php';
    include_once 'models/Om.php';
    include_once 'dao/ObrigatorioDAO.php';
    include_once 'dao/AuxiliarDAO.php';

    if($_SESSION['perfil_smo'] != "admin"){ erro($BASE_URL, 2, 963323, $pagina_atual, "perfil!=admin", "Sem permissão!"); exit();}
    
    $ObrigatorioDAO = new ObrigatorioDAO($conexao);
    $todos_obrigatorios = $ObrigatorioDAO->findAllAtivos();
    

    $AuxiliarDAO = new AuxiliarDAO($conexao);
    $todas_oms_1_fase = $AuxiliarDAO->findAllOM1Fase();
    $todas_oms_2_fase = $AuxiliarDAO->findAllOM2Fase();
    $todas_dt_comp_desigancao = $AuxiliarDAO->findAllDtCompDesignacao();
    $todas_espec = $AuxiliarDAO->findAllEspec();
    $todas_gu = $AuxiliarDAO->findAllGuarnicao();

    
    
    if(!isset($_SESSION['id_usuario_smo'])){ erro($BASE_URL, 2, 63216754, $pagina_atual, "Obrigatorio_nao_logado", "Página não encontrada!"); exit();}
    if(!isset($_SESSION['mensagem'])) $_SESSION['mensagem'] = null;

    $voluntario_filtro = null;
    if(isset($_GET['voluntario_filtro'])) $voluntario_filtro = filtra_campo_get("voluntario_filtro");
    $dependentes_filtro = null;
    if(isset($_GET['dependentes_filtro'])) $dependentes_filtro = filtra_campo_get("dependentes_filtro");
    $faculdade_filtro = null;
    if(isset($_GET['faculdade_filtro'])) $faculdade_filtro = filtra_campo_get("faculdade_filtro");
    $jise_filtro = null;
    if(isset($_GET['jise_filtro'])) $jise_filtro = filtra_campo_get("jise_filtro");
    $jisr_filtro = null;
    if(isset($_GET['jisr_filtro'])) $jisr_filtro = filtra_campo_get("jisr_filtro");
    $om_2_fase_filtro = null;
    if(isset($_GET['om_2_fase_filtro'])) $om_2_fase_filtro = filtra_campo_get("om_2_fase_filtro");
    $prox_apr_filtro = null;
    if(isset($_GET['prox_apr_filtro'])) $prox_apr_filtro = filtra_campo_get("prox_apr_filtro");
    $situacao_militar = null;
    if(isset($_GET['situacao_militar'])) $situacao_militar = filtra_campo_get("situacao_militar");
    $rm_destino_filtro = null;
    if(isset($_GET['rm_destino'])) $rm_destino_filtro = filtra_campo_get("rm_destino");
    $formacao_filtro = null;
    if(isset($_GET['formacao_filtro'])) $formacao_filtro = filtra_campo_get("formacao_filtro");
    $prioridade_forca_filtro = null;
    if(isset($_GET['prioridade_forca_filtro'])) $prioridade_forca_filtro = filtra_campo_get("prioridade_forca_filtro");
    $especialidade_filtro = null;
    if(isset($_GET['especialidade_filtro'])) $especialidade_filtro = filtra_campo_get("especialidade_filtro");
    $prioridade_gu_filtro = null;
    if(isset($_GET['prioridade_gu_filtro'])) $prioridade_gu_filtro = filtra_campo_get("prioridade_gu_filtro");

?>

<main id="main">
    <section class="breadcrumbs" >
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h1><b>Pré Distribuição MFDV - OM 1ª Fase</b></h1>
            </div>
        </div>
    </section>
</main>

    <script>
    window.onload = function()
    {
        $('#sit_mil').hide();
    }
    </script>

    <script>
        $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
    </script>


<center><font color="green" size="6px"><?php echo $_SESSION['mensagem']; $_SESSION['mensagem'] = null; ?></font></center>

<section id="contact" class="contact">
    <form action="<?php echo $_SERVER['PHP_SELF']?>" method='get'>
        <div class="row">

        <div class="col-lg-12 form-group">
            <select name="om_2_fase_filtro" style="width: 100%" class="chosen-select" onchange="this.form.submit()">
                <option value="">OM 2ª Fase</option>
                <?php 
                    foreach ($todas_oms_2_fase as $value) 
                    {
                        if ($value['abreviatura'] == $om_2_fase_filtro)  echo "<option value='" . $value['abreviatura'] . "' selected>" . $value['abreviatura'] . "</option>";
                        else echo "<option value='" . $value['abreviatura'] . "' >" . $value['abreviatura'] . "</option>";
                    }
                ?>
            </select>
        </div>

        <div class="col-lg-2 form-group">
            <select name="formacao_filtro" style="width: 100%" class="chosen-select" onchange="this.form.submit()">
                <option value="">Formação</option>
                <option <?php if($formacao_filtro == 'MÉDICO - Generalista') echo " selected " ?> value="MÉDICO - Generalista">MÉDICO - Generalista</option>
                <option <?php if($formacao_filtro != null && $formacao_filtro === 'DENTISTA - Cirurgião Dentista') echo " selected " ?> value="DENTISTA - Cirurgião Dentista">DENTISTA - Cirurgião Dentista</option>
                <?php /*
                    foreach ($todas_espec as $value) 
                    {
                        if(isset($formacao_filtro) && $formacao_filtro == $value['nome'])
                            echo "<option selected value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
                        else
                            echo "<option value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
                    }
                */?>
            </select>
        </div>
            <div class="col-lg-2 form-group">
                <select name="voluntario_filtro" style="width: 100%" class="chosen-select" onchange="this.form.submit()">
                    <option value="">Voluntário</option>
                    <option <?php if($voluntario_filtro == 'SIM') echo " selected " ?> value="SIM">Sim</option>
                    <option <?php if($voluntario_filtro != null && $voluntario_filtro === 'NÃO') echo " selected " ?> value="NÃO">Não</option>
                </select>
            </div>
            <div class="col-lg-2 form-group">
                    <select name="dependentes_filtro" style="width: 100%" class="chosen-select" onchange="this.form.submit()">
                        <option value="">Dependentes</option>
                        <option <?php if($dependentes_filtro == 'nenhum') echo " selected " ?> value="nenhum">Nenhum Dependente</option>
                        <option <?php if($dependentes_filtro == "possui_dependente") echo " selected " ?> value="possui_dependente">Com dependentes</option>
                    </select>
            </div>
            
            <div class="col-lg-2 form-group">
                <select name="situacao_militar" style="width: 100%"  id="situacao_militar" class="chosen-select" onchange="this.form.submit()">
                            <option value="">Situação Militar</option>
                            <option <?php if($situacao_militar == "Em Débito - REFRATÁRIO") echo " selected "?> value="Em Débito - REFRATÁRIO">Em Débito - REFRATÁRIO</option>
                            <option <?php if($situacao_militar == "Em Débito - INSUBMISSO") echo " selected "?> value="Em Débito - INSUBMISSO">Em Débito - INSUBMISSO</option>
                            <option <?php if($situacao_militar == "Em Dia - JUDICIAL") echo " selected "?> value="Em Dia - JUDICIAL">Em Dia - JUDICIAL</option>
                            <option <?php if($situacao_militar == "Em Dia - TRANSFERÊNCIA FISEMI") echo " selected "?> value="Em Dia - TRANSFERÊNCIA FISEMI">Em Dia - TRANSFERÊNCIA FISEMI</option>
                            <option <?php if($situacao_militar == "Em Dia - ALISTADO MFDV (FISEMI)") echo " selected "?> value="Em Dia - ALISTADO MFDV (FISEMI)">Em Dia - ALISTADO MFDV (FISEMI)</option>
                            <option <?php if($situacao_militar == "Em Dia - ADIADO CURSANDO RESIDÊNCIA") echo " selected "?> value="Em Dia - ADIADO CURSANDO RESIDÊNCIA">Em Dia - ADIADO CURSANDO RESIDÊNCIA</option>
                            <option <?php if($situacao_militar == "Em Dia - B1 - INSP SAU -Retornar próxima Seleção") echo " selected "?> value="Em Dia - B1 - INSP SAU -Retornar próxima Seleção">Em Dia - B1 - INSP SAU -Retornar próxima Seleção</option>
                            <option <?php if($situacao_militar == "Quite SMO - EXCESSO - CONTINGENTE") echo " selected "?> value="Quite SMO - EXCESSO - CONTINGENTE">Quite SMO - EXCESSO - CONTINGENTE</option>
                            <option <?php if($situacao_militar == "Quite SMO - EXCESSO - INCAPAZ SAÚDE") echo " selected "?> value="Quite SMO - EXCESSO - INCAPAZ SAÚDE">Quite SMO - EXCESSO - INCAPAZ SAÚDE</option>
                            <option <?php if($situacao_militar == "Quite SMO - DESOBRIGADO - MAIOR 38 ANOS") echo " selected "?> value="Quite SMO - DESOBRIGADO - MAIOR 38 ANOS">Quite SMO - DESOBRIGADO - MAIOR 38 ANOS</option>
                            <option <?php if($situacao_militar == "Quite SMO - DESOBRIGADO - JÁ RESERVISTA") echo " selected "?> value="Quite SMO - DESOBRIGADO - JÁ RESERVISTA">Quite SMO - DESOBRIGADO - JÁ RESERVISTA</option>
                            <option <?php if($situacao_militar == "Quite SMO - DESOBRIGADO - NATURALIZADO") echo " selected "?> value="Quite SMO - DESOBRIGADO - NATURALIZADO">Quite SMO - DESOBRIGADO - NATURALIZADO</option>
                            <option <?php if($situacao_militar == "Quite SMO - CONVOCADO") echo " selected "?> value="Quite SMO - CONVOCADO">Quite SMO - CONVOCADO</option>
                </select>
            </div>    

            <div class="col-lg-2 form-group">
                <select name="rm_destino" style="width: 100%" class="chosen-select" onchange="this.form.submit()">
                    <option value="">FISEMI - RM Destino</option>
                    <option <?php if($rm_destino_filtro == '1') echo " selected " ?> value="1">1ª RM</option>
                    <option <?php if($rm_destino_filtro == '2') echo " selected " ?> value="2">2ª RM</option>
                    <option <?php if($rm_destino_filtro == '3') echo " selected " ?> value="3">3ª RM</option>
                    <option <?php if($rm_destino_filtro =='4') echo " selected " ?> value="4">4ª RM</option>
                    <option <?php if($rm_destino_filtro == '5') echo " selected " ?> value="5">5ª RM</option>
                    <option <?php if($rm_destino_filtro == '6') echo " selected " ?> value="6">6ª RM</option>
                    <option <?php if($rm_destino_filtro == '7') echo " selected " ?> value="7">7ª RM</option>
                    <option <?php if($rm_destino_filtro == '8') echo " selected " ?> value="8">8ª RM</option>
                    <option <?php if($rm_destino_filtro == '9') echo " selected " ?> value="9">9ª RM</option>
                    <option <?php if($rm_destino_filtro == '10') echo " selected " ?> value="10">10ª RM</option>
                    <option <?php if($rm_destino_filtro == '11') echo " selected " ?> value="11">11ª RM</option>
                    <option <?php if($rm_destino_filtro == '12') echo " selected " ?> value="12">12ª RM</option>
                </select>
            </div>
            
            <div class="col-lg-2 form-group">
                <select name="jise_filtro" style="width: 100%" class="chosen-select" onchange="this.form.submit()">
                    <option value="">JISE</option>
                    <option <?php if($jise_filtro == "A") echo " selected "?> value="A">A</option>
                    <option <?php if($jise_filtro == "B1") echo " selected "?> value="B1">B1</option>
                    <option <?php if($jise_filtro == "B2") echo " selected "?> value="B2">B2</option>
                    <option <?php if($jise_filtro == "C") echo " selected "?> value="C">C</option>
                </select>
            </div>
            <div class="col-lg-2 form-group">
                <select name="jisr_filtro" style="width: 100%" class="chosen-select" onchange="this.form.submit()">
                    <option value="">JISR</option>
                    <option <?php if($jisr_filtro == "A") echo " selected "?> value="A">A</option>
                    <option <?php if($jisr_filtro == "B1") echo " selected "?> value="B1">B1</option>
                    <option <?php if($jisr_filtro == "B2") echo " selected "?> value="B2">B2</option>
                    <option <?php if($jisr_filtro == "C") echo " selected "?> value="C">C</option>
                </select>
            </div>
            <div class="col-lg-2 form-group">
                <select name="faculdade_filtro" style="width: 100%" class="chosen-select" onchange="this.form.submit()">
                    <option value="">IE Graduação</option>
                    <option <?php if($faculdade_filtro == "FURG - RIO GRANDE") echo " selected " ?> value="FURG - RIO GRANDE">FURG - RIO GRANDE</option>
                    <option <?php if($faculdade_filtro == "UFPEL - PELOTAS") echo " selected " ?> value="UFPEL - PELOTAS">UFPEL - PELOTAS</option>
                    <option <?php if($faculdade_filtro == "UCPEL - PELOTAS") echo " selected " ?> value="UCPEL - PELOTAS">UCPEL - PELOTAS</option>
                    <option <?php if($faculdade_filtro == "UPF - PASSO FUNDO") echo " selected " ?> value="UPF - PASSO FUNDO">UPF - PASSO FUNDO</option>
                    <option <?php if($faculdade_filtro == "UFFS - PASSO FUNDO") echo " selected " ?> value="UFFS - PASSO FUNDO">UFFS - PASSO FUNDO</option>
                    <option <?php if($faculdade_filtro == "ATITUS - PASSO FUNDO (IMED)") echo " selected " ?> value="ATITUS - PASSO FUNDO (IMED)">ATITUS - PASSO FUNDO (IMED)</option>
                    <option <?php if($faculdade_filtro == "UCS - CAXIAS DO SUL") echo " selected " ?> value="UCS - CAXIAS DO SUL">UCS - CAXIAS DO SUL</option>
                    <option <?php if($faculdade_filtro == "UNIVATES - LAJEADO") echo " selected " ?> value="UNIVATES - LAJEADO">UNIVATES - LAJEADO</option>
                    <option <?php if($faculdade_filtro == "UFCSPA - PORTO ALEGRE") echo " selected " ?> value="UFCSPA - PORTO ALEGRE">UFCSPA - PORTO ALEGRE</option>
                    <option <?php if($faculdade_filtro == "ULBRA - CANOAS") echo " selected " ?> value="ULBRA - CANOAS">ULBRA - CANOAS</option>
                    <option <?php if($faculdade_filtro == "PUCRS - PORTO ALEGRE") echo " selected " ?> value="PUCRS - PORTO ALEGRE">PUCRS - PORTO ALEGRE</option>
                    <option <?php if($faculdade_filtro == "UFRGS - PORTO ALEGRE") echo " selected " ?> value="UFRGS - PORTO ALEGRE">UFRGS - PORTO ALEGRE</option>
                    <option <?php if($faculdade_filtro == "UNISINOS - SÃO LEOPOLDO") echo " selected " ?> value="UNISINOS - SÃO LEOPOLDO">UNISINOS - SÃO LEOPOLDO</option>
                    <option <?php if($faculdade_filtro == "UNIPAMPA - URUGUAIANA") echo " selected " ?> value="UNIPAMPA - URUGUAIANA">UNIPAMPA - URUGUAIANA</option>
                    <option <?php if($faculdade_filtro == "UFSM - SANTA MARIA") echo " selected " ?> value="UFSM - SANTA MARIA">UFSM - SANTA MARIA</option>
                    <option <?php if($faculdade_filtro == "UFN - SANTA MARIA") echo " selected " ?> value="UFN - SANTA MARIA">UFN - SANTA MARIA</option>
                    <option <?php if($faculdade_filtro == "UNISC - SANTA CRUZ DO SUL") echo "selected " ?> value="UNISC - SANTA CRUZ DO SUL">UNISC - SANTA CRUZ DO SUL</option>
                    <option <?php if($faculdade_filtro == "URI - ERECHIM") echo " selected " ?> value="URI - ERECHIM">URI - ERECHIM</option>
                    <option <?php if($faculdade_filtro == "FEEVALE - NOVO HAMBURGO") echo " selected " ?> value="FEEVALE - NOVO HAMBURGO">FEEVALE - NOVO HAMBURGO</option>
                    <option <?php if($faculdade_filtro == "UNIJUÍ - IJUÍ") echo " selected " ?> value="UNIJUÍ - IJUÍ">UNIJUÍ - IJUÍ</option>
                    <option <?php if($faculdade_filtro == "GRADUADO EM FACULDADE FORA RS") echo " selected " ?> value="GRADUADO EM FACULDADE FORA RS">GRADUADO EM FACULDADE FORA RS</option>
                </select>
            </div>
            <div class="col-lg-2 form-group">
                    <select name="prioridade_forca_filtro" style="width: 100%" class="chosen-select" onchange="this.form.submit()">
                            <option value="">Prioridade da Força</option>
                            <option <?php if($prioridade_forca_filtro == "EMA") echo " selected "?>value="EMA">EMA</option>
                            <option <?php if($prioridade_forca_filtro  == "EAM") echo " selected "?>value="EAM">EAM</option>
                            <option <?php if($prioridade_forca_filtro  == "MAE") echo " selected "?>value="MAE">MAE</option>
                            <option <?php if($prioridade_forca_filtro  == "MEA") echo " selected "?>value="MEA">MEA</option>
                            <option <?php if($prioridade_forca_filtro  == "AEM") echo " selected "?>value="AEM">AEM</option>
                            <option <?php if($prioridade_forca_filtro  == "AME") echo " selected "?>value="AME">AME</option>
                        </select>
                    </div>

            <div class="col-lg-2 form-group">
                <select name="especialidade_filtro" style="width: 100%" class="chosen-select" onchange="this.form.submit()">
                    <option value="">Especialidade</option>
                    <?php 
                        foreach ($todas_espec as $value) 
                        {
                            if(isset($especialidade_filtro) && $especialidade_filtro == $value['nome'])
                                echo "<option selected value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
                            else
                                echo "<option value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-lg-2 form-group">
                <select name="prioridade_gu_filtro" style="width: 100%" class="chosen-select" onchange="this.form.submit()">
                    <option value="">Prioridade Gu</option>
                    <?php 
                        foreach ($todas_gu as $value) 
                        {
                            if(isset($prioridade_gu_filtro) && $prioridade_gu_filtro == $value['nome'])
                                echo "<option selected value='" . $value['id'] . "' >" . $value['nome'] . "</option>";
                            else
                                echo "<option value='" . $value['id'] . "' >" . $value['nome'] . "</option>";
                        }
                    ?>
                </select>
            </div>
        </div>
    </form>
    <br>
    <form method="post" action="controller/pre_disposicao_atualiza.php">
        <table id="tabela_dinamica" class="display responsive nowrap" style="width:100%; font-size: 10px;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Formação / Ano</th>
                    <th>OM 1ª Fase</th>
                    <th>OM 2ª Fase</th>
                    <th>Grad Facul</th>
                    <th>Ano Conc Resid</th>
                    <th>Dias Vida</th>
                    <th>Sit Militar</th>
                    <th>Prioridade Gu</th>
                    <th>Trans Fisemi</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if($todos_obrigatorios)
                foreach ($todos_obrigatorios as $obrigatorio)
                {
                    $criptografia = hash('sha256', $obrigatorio->getId(). "criptografia");

                    $teste = false;
                    $nome_gu = null;
                    $prioridade_gu = null;
                    if($prioridade_gu_filtro != null)
                    {
                        $resultado = $AuxiliarDAO->verifica_prioridade($obrigatorio->getId(), $prioridade_gu_filtro) ;
                        if($resultado)
                        {
                            $teste = true;
                            $prioridade_gu = $resultado[0]['prioridade']. " ª";
                            $nome_gu = $resultado[0]['nome_gu'] . " - ";
                        }
                    }


                    
                    if($prioridade_gu_filtro != null && !$teste) continue;

                    if($voluntario_filtro != null && $voluntario_filtro != $obrigatorio->getVoluntario()) continue;
                    if($dependentes_filtro == "nenhum" && ($obrigatorio->getDependentes() >= 1 || $obrigatorio->getDependentes() == null)) continue;
                    if($dependentes_filtro == "possui_dependente" && ($obrigatorio->getDependentes() === "0" || $obrigatorio->getDependentes() == null)) continue;
                    if($faculdade_filtro != null && $obrigatorio->getNomeInstitutoEnsino() != $faculdade_filtro) continue;
                    if($jise_filtro && ($obrigatorio->getJise() != $jise_filtro || $obrigatorio->getJise() == null)) continue;
                    if($jisr_filtro && ($obrigatorio->getJisr() != $jisr_filtro || $obrigatorio->getJisr() == null)) continue;
                    if($om_2_fase_filtro  != null && $obrigatorio->getOm2Fase() != null && ($obrigatorio->getOm2Fase()  != $om_2_fase_filtro)) continue;
                    if($situacao_militar != null && ($obrigatorio->getSituacaoMilitar() != $situacao_militar || $obrigatorio->getSituacaoMilitar() == null)) continue;   
                    if($rm_destino_filtro != null && $obrigatorio->getRmDestinoFisemi() != $rm_destino_filtro ) continue;    
                    // Se não tiver nenhuma das 3 especialidades == a do filtro continue
                    //if($formacao_filtro != null && ($obrigatorio->getEspecialidade() != $formacao_filtro && $obrigatorio->getEspecialidade2() != $formacao_filtro && $obrigatorio->getEspecialidade3() != $formacao_filtro)) continue;   
                    if($formacao_filtro != null && $obrigatorio->getFormacao() != $formacao_filtro) continue;   
                    if($prioridade_forca_filtro != null && $obrigatorio->getPrioridadeForca() != $prioridade_forca_filtro) continue; 
                    if($especialidade_filtro != null && $obrigatorio->getEspecialidade() != $especialidade_filtro) continue;  
                    //if($prioridade_gu_filtro != null && $obrigatorio->getPrioridade_gu() != $prioridade_gu_filtro) continue; 
                        
                    $cor = null;
                    if ($obrigatorio->getSituacaoMilitar() != null)
                    {
                        if (strpos($obrigatorio->getSituacaoMilitar(), "Quite") !== false) 
                        $cor = "#98FB98";
                        else $cor = "#FFDEAD";
                    } 

                    $dataNascimento = $obrigatorio->getDataNascimento();
                    $hoje = date("Y-m-d");
                    $diasDeVida = floor((strtotime($hoje) - strtotime($dataNascimento)) / (60 * 60 * 24));
                    
                    echo"  
                    <tr style='background-color:$cor'>
                        <td> <input type='checkbox' name='ids[]' value='" . $obrigatorio->getId() . "'> </td>
                        <td> <a href ='obrigatorio.php?crip=$criptografia&id_obrigatorio=".$obrigatorio->getId()."'><font color='black'> " . $obrigatorio->getNomeCompleto(). "</font></a> </td>
                        <td> <a href ='obrigatorio.php?crip=$criptografia&id_obrigatorio=".$obrigatorio->getId()."'><font color='black'>" . $obrigatorio->getCPF() . "</font></a> </td>
                        <td> " . $obrigatorio->getFormacao() . " / ".$obrigatorio->getAnoFormacao()." </td>
                        <td> " . $obrigatorio->getOm1Fase()->getAbreviatura() . " </td>
                        <td> " . $obrigatorio->getOm2Fase() . " </td>
                        <td> " . $obrigatorio->getNomeInstitutoEnsino() . " </td>
                        <td> " . $obrigatorio->imprime_ano_res_mais_recente() . " </td>
                        <td> " . $diasDeVida . " </td>
                        <td> " . $obrigatorio->getSituacaoMilitar() . " </td>
                        <td> " . $nome_gu . "  ".$prioridade_gu."  </td>
                        
                        <td> ";  if($obrigatorio->getRmOrigemFisemi()) echo $obrigatorio->getRmOrigemFisemi() . "ª à " . $obrigatorio->getRmDestinoFisemi() . "ª" . "</td>
                    </tr>
                        ";
                }
            ?>
            </tbody>
        </table>

        <br>

        <div class="row">
        <div class="col-lg-3 form-group">
                <select name="om_2_fase_destino" style="width: 100%" class="chosen-select" >
                    <option value="">OM 2ª Fase Destino</option>
                    <?php 
                        foreach ($todas_oms_2_fase as $value) 
                        {
                            if ($value['abreviatura'] == $om_2_fase_filtro)  echo "<option value='" . $value['abreviatura'] . "' selected>" . $value['abreviatura'] . "</option>";
                            else echo "<option value='" . $value['abreviatura'] . "' >" . $value['abreviatura'] . "</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="col-lg-3 form-group">
                <select name="id_om_1_fase" style="width: 100%" class="chosen-select" >
                    <option value="">OM 1ª Fase Destino</option>
                    <?php 
                        foreach ($todas_oms_1_fase as $value) 
                        {
                            if ($value['id'] == $id_om_1_fase)  echo "<option value='" . $value['id'] . "' selected>" . $value['abreviatura'] . "</option>";
                            else echo "<option value='" . $value['id'] . "' >" . $value['abreviatura'] . "</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="col-lg-3 form-group">
            <select name="distribuicao" class="form-control">
                <option value="">Selecione a Distribuição</option>
                <option value="DESIGNADO - 1ª Distribuição">DESIGNADO - 1ª Distribuição</option>
                <option value="DESIGNADO - 2ª Distribuição">DESIGNADO - 2ª Distribuição</option>
                <option value="MAJORADO - 1ª Distribuição">MAJORADO - 1ª Distribuição</option>
                <option value="MAJORADO - 2ª Distribuição">MAJORADO - 2ª Distribuição</option>
                <option value="EXCESSO CONTINGENTE">EXCESSO CONTINGENTE</option>
                <option value="MARINHA">MARINHA</option>
                <option value="FORÇA AÉREA">FORÇA AÉREA</option>
            </select>
            </div>

            <div class="col-lg-3 form-group">
                <input type="text" class="form-control" name="data_apresentacao_atualiza" placeholder="Data da próxima apresentação">
            </div>

            
            <input name="crip" hidden value="<?php echo hash('sha256', $_SESSION['chave']."atualiza"); ?>">
            <br>
            <br>
            <br>
            <div class="text-center" width="100%">
                <button type="submit">Atualizar</button>
            </div>
        </div>

            <br>
            <br>
            <br>
            <br>
            <br>
            <br>

            <center>
                <a href="javascript:history.back()"><button style="width:100%" class="btn btn-outline-light"><font color="black">VOLTAR</font></button></a>
            </center>
            <br>
            
        </form>
    </section>


<script type="text/javascript">
    $(document).ready(function() {
    $('#tabela_dinamica').DataTable({"aaSorting": [], "pageLength": 25, "lengthMenu": [ 25,50, 100, 200, 500 ]});
} );
</script>

  
<?php 
    include_once 'footer.php';
?>