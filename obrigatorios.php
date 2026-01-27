<?php

include_once 'header.php';
include_once 'dao/conecta_banco.php';
include_once 'models/Obrigatorio.php';
include_once 'models/Om.php';
include_once 'dao/ObrigatorioDAO.php';
include_once 'dao/AuxiliarDAO.php';

$ObrigatorioDAO = new ObrigatorioDAO($conexao);
$todos_obrigatorios = $ObrigatorioDAO->findAllAtivos();

$AuxiliarDAO = new AuxiliarDAO($conexao);
$todas_gu = $AuxiliarDAO->findAllGuarnicao();
$todas_oms_1_fase = $AuxiliarDAO->findAllOM1Fase();
$todas_oms_2_fase = $AuxiliarDAO->findAllOM2Fase();
$todas_dt_sel_geral = $AuxiliarDAO->findAllDtSelecaoComp();
$todas_dt_sel_geral_semestre = $AuxiliarDAO->findAllDtSelecaoComp();
$todas_dt_comp_desigancao = $AuxiliarDAO->findAllDtCompDesignacao();
$todas_dt_prox_apresentacao = $AuxiliarDAO->findAllDtProxApresentacao();
$todas_dt_incorporacao = $AuxiliarDAO->findAllDtIncorporacao();
$todas_dt_sel_complementar = $AuxiliarDAO->findAllDtSelComplementar();
$todas_espec = $AuxiliarDAO->findAllEspec();

if ($_SESSION['perfil_smo'] != "admin") {
    erro($BASE_URL, 2, 9961356, $pagina_atual, "usuario_sem_permissao", "Usuário sem permissão!");
}
if (!isset($_SESSION['id_usuario_smo'])) {
    erro($BASE_URL, 2, 63216754, $pagina_atual, "Obrigatorio_nao_logado", "Página não encontrada!");
    exit();
}
if (!isset($_SESSION['mensagem'])) $_SESSION['mensagem'] = null;

$voluntario_filtro = null;
if (isset($_GET['voluntario_filtro'])) $voluntario_filtro = (array)$_GET['voluntario_filtro'];
$dependentes_filtro = null;
if (isset($_GET['dependentes_filtro'])) $dependentes_filtro = filtra_campo_get("dependentes_filtro");

$data_selecao_geral_semestre_filtro = null;
if (isset($_GET['data_selecao_geral_semestre_filtro'])) $data_selecao_geral_semestre_filtro = filtra_campo_get("data_selecao_geral_semestre_filtro");

$faculdade_filtro = null;
if (isset($_GET['faculdade_filtro'])) $faculdade_filtro = (array)$_GET['faculdade_filtro'];
$jise_filtro = null;
if (isset($_GET['jise_filtro'])) $jise_filtro = (array)$_GET['jise_filtro'];
$jisr_filtro = null;
if (isset($_GET['jisr_filtro'])) $jisr_filtro = (array)$_GET['jisr_filtro'];
$distribuicao_filtro = null;
if (isset($_GET['distribuicao_filtro'])) $distribuicao_filtro = (array)$_GET['distribuicao_filtro'];
$om_1_fase_filtro = null;
if (isset($_GET['om_1_fase_filtro'])) $om_1_fase_filtro = (array)$_GET['om_1_fase_filtro'];
$resultado_revisao_filtro = null;
if (isset($_GET['resultado_revisao_filtro'])) $resultado_revisao_filtro = (array)$_GET['resultado_revisao_filtro'];
$om_2_fase_filtro = null;
if (isset($_GET['om_2_fase_filtro'])) $om_2_fase_filtro = (array)$_GET['om_2_fase_filtro'];
$isgr_filtro = null;
if (isset($_GET['isgr_filtro'])) $isgr_filtro = (array)$_GET['isgr_filtro'];
$sel_geral_filtro = null;
if (isset($_GET['sel_geral_filtro'])) $sel_geral_filtro = (array)$_GET['sel_geral_filtro'];
$comp_designacao_filtro = null;
if (isset($_GET['comp_designacao_filtro'])) $comp_designacao_filtro = (array)$_GET['comp_designacao_filtro'];
$prox_apr_filtro = null;
if (isset($_GET['prox_apr_filtro'])) $prox_apr_filtro = (array)$_GET['prox_apr_filtro'];
$incorporacao_filtro = null;
if (isset($_GET['incorporacao_filtro'])) $incorporacao_filtro = (array)$_GET['incorporacao_filtro'];
$sel_complementar_filtro = null;
if (isset($_GET['sel_complementar_filtro'])) $sel_complementar_filtro = (array)$_GET['sel_complementar_filtro'];
$situacao_militar = null;
if (isset($_GET['situacao_militar'])) $situacao_militar = (array)$_GET['situacao_militar'];
$rm_destino_filtro = null;
if (isset($_GET['rm_destino'])) $rm_destino_filtro = (array)$_GET['rm_destino'];
$especialidade_filtro = null;
if (isset($_GET['especialidade_filtro'])) $especialidade_filtro = (array)$_GET['especialidade_filtro'];
$prioridade_forca_filtro = null;
if (isset($_GET['prioridade_forca_filtro'])) $prioridade_forca_filtro = (array)$_GET['prioridade_forca_filtro'];


?>

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h1><b><i class="fas fa-stethoscope"></i> Obrigatórios</b></h1>
            </div>
        </div>
    </section>
</main>

<?php if (isset($_SESSION['mensagem'])): ?>
    <center>
        <font color="green" size="6px"><?php echo $_SESSION['mensagem'];
                                        $_SESSION['mensagem'] = null; ?></font>
    </center>
<?php endif; ?>

<section id="contact" class="contact">
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method='get'>
        <div class="row">
            <div class="col-lg-6 form-group">
                <b>Data Seleção Geral</b>
                <select name="sel_geral_filtro[]" style="width: 100%" class="chosen-select" multiple>
                    <option value=""></option>
                    <?php
                    if ($todas_dt_sel_geral != null)
                        foreach ($todas_dt_sel_geral as $value) {
                            if ($value['data'] == $sel_geral_filtro) echo "<option value='" . $value['data'] . "' selected>" . trata_data($value['data']) . "</option>";
                            else  echo "<option value='" . $value['data'] . "' >" . trata_data($value['data']) . "</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-lg-6 form-group">
                <b>Data Seleção Geral SEMESTRE</b>
                <select name="data_selecao_geral_semestre_filtro" style="width: 100%" class="chosen-select">
                    <option value="">Selecione o Semestre</option>
                    <option value="um_semestre_vintetres">1º Semestre de 2023</option>
                    <option value="dois_semestre_vintetres">2º Semestre de 2023</option>
                    <option value="um_semestre_vintequatro">1º Semestre de 2024</option>
                    <option value="dois_semestre_vintequatro">2º Semestre de 2024</option>
                    <option value="um_semestre_vintecinco">1º Semestre de 2025</option>
                    <option value="dois_semestre_vintecinco">2º Semestre de 2025</option>
                    <option value="um_semestre_vinteseis">1º Semestre de 2026</option>
                    <option value="dois_semestre_vinteseis">2º Semestre de 2026</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 form-group">
                <b>Especialidade</b>
                <select placeholder="Especialidade" name="especialidade_filtro[]" style="width: 100%" class="chosen-select" multiple>
                    <option value="">Especialidade</option>
                    <?php
                    foreach ($todas_espec as $value) {
                        if (isset($especialidade_filtro) && $especialidade_filtro == $value['nome'])
                            echo "<option selected value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
                        else
                            echo "<option value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-lg-6 form-group">
                <b>IE Graduação</b>
                <select name="faculdade_filtro[]" style="width: 100%" class="chosen-select" multiple>
                    <option value="">IE Graduação</option>
                    <option <?php if ($faculdade_filtro == "FURG - RIO GRANDE") echo " selected " ?> value="FURG - RIO GRANDE">FURG - RIO GRANDE</option>
                    <option <?php if ($faculdade_filtro == "UFPEL - PELOTAS") echo " selected " ?> value="UFPEL - PELOTAS">UFPEL - PELOTAS</option>
                    <option <?php if ($faculdade_filtro == "UCPEL - PELOTAS") echo " selected " ?> value="UCPEL - PELOTAS">UCPEL - PELOTAS</option>
                    <option <?php if ($faculdade_filtro == "UPF - PASSO FUNDO") echo " selected " ?> value="UPF - PASSO FUNDO">UPF - PASSO FUNDO</option>
                    <option <?php if ($faculdade_filtro == "UFFS - PASSO FUNDO") echo " selected " ?> value="UFFS - PASSO FUNDO">UFFS - PASSO FUNDO</option>
                    <option <?php if ($faculdade_filtro == "ATITUS - PASSO FUNDO (IMED)") echo " selected " ?> value="ATITUS - PASSO FUNDO (IMED)">ATITUS - PASSO FUNDO (IMED)</option>
                    <option <?php if ($faculdade_filtro == "UCS - CAXIAS DO SUL") echo " selected " ?> value="UCS - CAXIAS DO SUL">UCS - CAXIAS DO SUL</option>
                    <option <?php if ($faculdade_filtro == "UNIVATES - LAJEADO") echo " selected " ?> value="UNIVATES - LAJEADO">UNIVATES - LAJEADO</option>
                    <option <?php if ($faculdade_filtro == "UFCSPA - PORTO ALEGRE") echo " selected " ?> value="UFCSPA - PORTO ALEGRE">UFCSPA - PORTO ALEGRE</option>
                    <option <?php if ($faculdade_filtro == "ULBRA - CANOAS") echo " selected " ?> value="ULBRA - CANOAS">ULBRA - CANOAS</option>
                    <option <?php if ($faculdade_filtro == "PUCRS - PORTO ALEGRE") echo " selected " ?> value="PUCRS - PORTO ALEGRE">PUCRS - PORTO ALEGRE</option>
                    <option <?php if ($faculdade_filtro == "UFRGS - PORTO ALEGRE") echo " selected " ?> value="UFRGS - PORTO ALEGRE">UFRGS - PORTO ALEGRE</option>
                    <option <?php if ($faculdade_filtro == "UNISINOS - SÃO LEOPOLDO") echo " selected " ?> value="UNISINOS - SÃO LEOPOLDO">UNISINOS - SÃO LEOPOLDO</option>
                    <option <?php if ($faculdade_filtro == "UNIPAMPA - URUGUAIANA") echo " selected " ?> value="UNIPAMPA - URUGUAIANA">UNIPAMPA - URUGUAIANA</option>
                    <option <?php if ($faculdade_filtro == "UFSM - SANTA MARIA") echo " selected " ?> value="UFSM - SANTA MARIA">UFSM - SANTA MARIA</option>
                    <option <?php if ($faculdade_filtro == "UFN - SANTA MARIA") echo " selected " ?> value="UFN - SANTA MARIA">UFN - SANTA MARIA</option>
                    <option <?php if ($faculdade_filtro == "UNISC - SANTA CRUZ DO SUL") echo "selected " ?> value="UNISC - SANTA CRUZ DO SUL">UNISC - SANTA CRUZ DO SUL</option>
                    <option <?php if ($faculdade_filtro == "URI - ERECHIM") echo " selected " ?> value="URI - ERECHIM">URI - ERECHIM</option>
                    <option <?php if ($faculdade_filtro == "FEEVALE - NOVO HAMBURGO") echo " selected " ?> value="FEEVALE - NOVO HAMBURGO">FEEVALE - NOVO HAMBURGO</option>
                    <option <?php if ($faculdade_filtro == "UNIJUÍ - IJUÍ") echo " selected " ?> value="UNIJUÍ - IJUÍ">UNIJUÍ - IJUÍ</option>
                    <option <?php if ($faculdade_filtro == "GRADUADO EM FACULDADE FORA RS") echo " selected " ?> value="GRADUADO EM FACULDADE FORA RS">GRADUADO EM FACULDADE FORA RS</option>
                </select>
            </div>
            <div class="col-lg-6 form-group">
                <b>Situação Militar</b>
                <select name="situacao_militar[]" style="width: 100%" class="chosen-select" multiple>
                    <option value="">Situação Militar</option>
                    <option <?php if ($situacao_militar == "Em Débito - REFRATÁRIO") echo " selected " ?> value="Em Débito - REFRATÁRIO">Em Débito - REFRATÁRIO</option>
                    <option <?php if ($situacao_militar == "Em Débito - INSUBMISSO") echo " selected " ?> value="Em Débito - INSUBMISSO">Em Débito - INSUBMISSO</option>
                    <option <?php if ($situacao_militar == "Em Dia - JUDICIAL") echo " selected " ?> value="Em Dia - JUDICIAL">Em Dia - JUDICIAL</option>
                    <option <?php if ($situacao_militar == "Em Dia - TRANSFERÊNCIA FISEMI") echo " selected " ?> value="Em Dia - TRANSFERÊNCIA FISEMI">Em Dia - TRANSFERÊNCIA FISEMI</option>
                    <option <?php if ($situacao_militar == "Em Dia - ALISTADO MFDV (FISEMI)") echo " selected " ?> value="Em Dia - ALISTADO MFDV (FISEMI)">Em Dia - ALISTADO MFDV (FISEMI)</option>
                    <option <?php if ($situacao_militar == "Em Dia - ADIADO CURSANDO RESIDÊNCIA") echo " selected " ?> value="Em Dia - ADIADO CURSANDO RESIDÊNCIA">Em Dia - ADIADO CURSANDO RESIDÊNCIA</option>
                    <option <?php if ($situacao_militar == "Em Dia - B1 - INSP SAU - RETORNAR PRÓXIMA SELEÇÃO") echo " selected " ?> value="Em Dia - B1 - INSP SAU - RETORNAR PRÓXIMA SELEÇÃO">Em Dia - B1 - INSP SAU - RETORNAR PRÓXIMA SELEÇÃO</option>
                    <option <?php if ($situacao_militar == "Em Dia - LIMINAR JUDICIAL") echo " selected " ?> value="Em Dia - LIMINAR JUDICIAL">Em Dia - LIMINAR JUDICIAL</option>
                    <option <?php if ($situacao_militar == "Quite SMO - EXCESSO - CONTINGENTE") echo " selected " ?> value="Quite SMO - EXCESSO - CONTINGENTE">Quite SMO - EXCESSO - CONTINGENTE</option>
                    <option <?php if ($situacao_militar == "Quite SMO - EXCESSO - INCAPAZ SAÚDE") echo " selected " ?> value="Quite SMO - EXCESSO - INCAPAZ SAÚDE">Quite SMO - EXCESSO - INCAPAZ SAÚDE</option>
                    <option <?php if ($situacao_militar == "Quite SMO - DESOBRIGADO - MAIOR 38 ANOS") echo " selected " ?> value="Quite SMO - DESOBRIGADO - MAIOR 38 ANOS">Quite SMO - DESOBRIGADO - MAIOR 38 ANOS</option>
                    <option <?php if ($situacao_militar == "Quite SMO - DESOBRIGADO - JÁ RESERVISTA") echo " selected " ?> value="Quite SMO - DESOBRIGADO - JÁ RESERVISTA">Quite SMO - DESOBRIGADO - JÁ RESERVISTA</option>
                    <option <?php if ($situacao_militar == "Quite SMO - DESOBRIGADO - NATURALIZADO") echo " selected " ?> value="Quite SMO - DESOBRIGADO - NATURALIZADO">Quite SMO - DESOBRIGADO - NATURALIZADO</option>
                    <option <?php if ($situacao_militar == "Quite SMO - CONVOCADO") echo " selected " ?> value="Quite SMO - CONVOCADO">Quite SMO - CONVOCADO</option>
                </select>
            </div>
            <div class="col-lg-4 form-group">
                <b>Dt Comp Designação</b>
                <select name="comp_designacao_filtro[]" style="width: 100%" class="chosen-select" multiple>
                    <option value="">Dt Compa Designação</option>
                    <?php
                    if ($todas_dt_comp_desigancao != null)
                        foreach ($todas_dt_comp_desigancao as $value) {
                            if ($value['data'] == $comp_designacao_filtro) echo "<option value='" . $value['data'] . "' selected>" . trata_data($value['data']) . "</option>";
                            else echo "<option value='" . $value['data'] . "' >" . trata_data($value['data']) . "</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-lg-2 form-group">
                <b>Voluntário</b>
                <select name="voluntario_filtro[]" style="width: 100%" class="chosen-select" multiple>
                    <option <?php if ($voluntario_filtro == 'SIM') echo " selected " ?> value="SIM">Sim</option>
                    <option <?php if ($voluntario_filtro != null && $voluntario_filtro == 'NÃO') echo " selected " ?> value="NÃO">Não</option>
                </select>
            </div>

            <div class="col-lg-6 form-group">
                <b>Distribuição</b>
                <select name="distribuicao_filtro[]" style="width: 100%" class="chosen-select" multiple>
                    <option value="">Distribuição</option>
                    <option <?php if ($distribuicao_filtro == "DESIGNADO - 1ª Distribuição") echo " selected " ?> value="DESIGNADO - 1ª Distribuição">DESIGNADO - 1ª Distribuição</option>
                    <option <?php if ($distribuicao_filtro == "DESIGNADO - 2ª Distribuição") echo " selected " ?> value="DESIGNADO - 2ª Distribuição">DESIGNADO - 2ª Distribuição</option>
                    <option <?php if ($distribuicao_filtro == "MAJORADO - 1ª Distribuição") echo " selected " ?> value="MAJORADO - 1ª Distribuição">MAJORADO - 1ª Distribuição</option>
                    <option <?php if ($distribuicao_filtro == "MAJORADO - 2ª Distribuição") echo " selected " ?> value="MAJORADO - 2ª Distribuição">MAJORADO - 2ª Distribuição</option>
                    <option <?php if ($distribuicao_filtro == "EXCESSO CONTINGENTE") echo " selected " ?> value="EXCESSO CONTINGENTE">EXCESSO CONTINGENTE</option>
                    <option <?php if ($distribuicao_filtro == "MARINHA") echo " selected " ?> value="MARINHA">MARINHA</option>
                    <option <?php if ($distribuicao_filtro == "FORÇA AÉREA") echo " selected " ?> value="FORÇA AÉREA">FORÇA AÉREA</option>
                </select>
            </div>

            <div class="col-lg-4 form-group">
                <b>OM 1ª Fase</b>
                <select name="om_1_fase_filtro[]" style="width: 100%" class="chosen-select" multiple>
                    <option value="">OM - 1ª Fase</option>
                    <?php
                    foreach ($todas_oms_1_fase as $value) {
                        if ($value['abreviatura'] == $om_1_fase_filtro) echo "<option value='" . $value['abreviatura'] . "' selected>" . $value['abreviatura'] . "</option>";
                        else echo "<option value='" . $value['abreviatura'] . "' >" . $value['abreviatura'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-lg-2 form-group">
                <b>Dependentes</b>
                <select name="dependentes_filtro" style="width: 100%" class="chosen-select" multiple>
                    <option <?php if ($dependentes_filtro == 'nenhum') echo " selected " ?> value="nenhum">Nenhum Dependente</option>
                    <option <?php if ($dependentes_filtro == "possui_dependente") echo " selected " ?> value="possui_dependente">Com dependentes</option>
                </select>
            </div>


            <div hidden class="col-lg-2 form-group">
                <b>Dt Prox Apresentação</b>
                <select name="prox_apr_filtro" style="width: 100%" class="chosen-select" onchange="this.form.submit()">
                    <option value="">Dt Prox Apresentação</option>
                    <?php
                    if ($todas_dt_prox_apresentacao != null)
                        foreach ($todas_dt_prox_apresentacao as $value) {
                            if ($value['data'] == $prox_apr_filtro) echo "<option value='" . $value['data'] . "' selected>" . trata_data($value['data']) . "</option>";
                            else echo "<option value='" . $value['data'] . "' >" . trata_data($value['data']) . "</option>";
                        }
                    ?>
                </select>
            </div>



            <div class="col-lg-2 form-group">
                <b>RM Destino</b>
                <select name="rm_destino[]" style="width: 100%" class="chosen-select" multiple>
                    <option value="">FISEMI - RM Destino</option>
                    <option <?php if ($rm_destino_filtro == '1') echo " selected " ?> value="1">1ª RM</option>
                    <option <?php if ($rm_destino_filtro == '2') echo " selected " ?> value="2">2ª RM</option>
                    <option <?php if ($rm_destino_filtro == '3') echo " selected " ?> value="3">3ª RM</option>
                    <option <?php if ($rm_destino_filtro == '4') echo " selected " ?> value="4">4ª RM</option>
                    <option <?php if ($rm_destino_filtro == '5') echo " selected " ?> value="5">5ª RM</option>
                    <option <?php if ($rm_destino_filtro == '6') echo " selected " ?> value="6">6ª RM</option>
                    <option <?php if ($rm_destino_filtro == '7') echo " selected " ?> value="7">7ª RM</option>
                    <option <?php if ($rm_destino_filtro == '8') echo " selected " ?> value="8">8ª RM</option>
                    <option <?php if ($rm_destino_filtro == '9') echo " selected " ?> value="9">9ª RM</option>
                    <option <?php if ($rm_destino_filtro == '10') echo " selected " ?> value="10">10ª RM</option>
                    <option <?php if ($rm_destino_filtro == '11') echo " selected " ?> value="11">11ª RM</option>
                    <option <?php if ($rm_destino_filtro == '12') echo " selected " ?> value="12">12ª RM</option>
                </select>
            </div>

            <div class="col-lg-2 form-group">
                <b>JISE</b>
                <select name="jise_filtro[]" style="width: 100%" class="chosen-select" multiple>
                    <option value="">JISE</option>
                    <option <?php if ($jise_filtro == "A") echo " selected " ?> value="A">A</option>
                    <option <?php if ($jise_filtro == "B1") echo " selected " ?> value="B1">B1</option>
                    <option <?php if ($jise_filtro == "B2") echo " selected " ?> value="B2">B2</option>
                    <option <?php if ($jise_filtro == "C") echo " selected " ?> value="C">C</option>
                </select>
            </div>
            <div class="col-lg-2 form-group">
                <b>JISR</b>
                <select name="jisr_filtro[]" style="width: 100%" class="chosen-select" multiple>
                    <option value="">JISR</option>
                    <option <?php if ($jisr_filtro == "A") echo " selected " ?> value="A">A</option>
                    <option <?php if ($jisr_filtro == "B1") echo " selected " ?> value="B1">B1</option>
                    <option <?php if ($jisr_filtro == "B2") echo " selected " ?> value="B2">B2</option>
                    <option <?php if ($jisr_filtro == "C") echo " selected " ?> value="C">C</option>
                </select>
            </div>

            <div class="col-lg-4 form-group">
                <b>OM 2ª Fase</b>
                <select name="om_2_fase_filtro[]" style="width: 100%" class="chosen-select" multiple>
                    <option value="">OM 2ª Fase</option>
                    <?php
                    foreach ($todas_oms_2_fase as $value) {
                        if ($value['nome'] == $om_2_fase_filtro)  echo "<option value='" . $value['abreviatura'] . "' selected>" . $value['abreviatura'] . "</option>";
                        else echo "<option value='" . $value['abreviatura'] . "' >" . $value['abreviatura'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-lg-2 form-group">
                <b>Prioridade Força</b>
                <select name="prioridade_forca_filtro[]" style="width: 100%" class="chosen-select" multiple>
                    <option value="">Prioridade da Força</option>
                    <option <?php if ($prioridade_forca_filtro == "EMA") echo " selected " ?>value="EMA">EMA</option>
                    <option <?php if ($prioridade_forca_filtro  == "EAM") echo " selected " ?>value="EAM">EAM</option>
                    <option <?php if ($prioridade_forca_filtro  == "MAE") echo " selected " ?>value="MAE">MAE</option>
                    <option <?php if ($prioridade_forca_filtro  == "MEA") echo " selected " ?>value="MEA">MEA</option>
                    <option <?php if ($prioridade_forca_filtro  == "AEM") echo " selected " ?>value="AEM">AEM</option>
                    <option <?php if ($prioridade_forca_filtro  == "AME") echo " selected " ?>value="AME">AME</option>
                </select>

            </div>

            <div class="col-lg-4 form-group">
                <b>Dt Seleção Complementar</b>
                <select name="sel_complementar_filtro[]" style="width: 100%" class="chosen-select" multiple>
                    <option value="">Dt Seleção Complementar</option>
                    <?php
                    foreach ($todas_dt_sel_complementar as $value) {
                        if ($value['data'] == $sel_complementar_filtro) echo "<option value='" . $value['data'] . "' selected>" . trata_data($value['data']) . "</option>";
                        else echo "<option value='" . $value['data'] . "' >" . trata_data($value['data']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-lg-2 form-group">
                <b>Resultado Revisão Médica</b>
                <select name="resultado_revisao_filtro[]" style="width: 100%" class="chosen-select" multiple>
                    <option value="">Resultado Revisão Médica</option>
                    <option <?php if ($resultado_revisao_filtro == "APTO") echo " selected " ?> value="APTO">APTO</option>
                    <option <?php if ($resultado_revisao_filtro == "INAPTO") echo " selected " ?> value="INAPTO">INAPTO</option>
                    <option <?php if ($resultado_revisao_filtro == "NÃO COMPARECEU") echo " selected " ?> value="NÃO COMPARECEU">NÃO COMPARECEU</option>
                </select>
            </div>
            <div class="col-lg-2 form-group">
                <b>Isgr</b>
                <select name="isgr_filtro[]" style="width: 100%" class="chosen-select" multiple>
                    <option value="">Resultado ISGRev</option>
                    <option <?php if ($isgr_filtro == "Não é o caso") echo " selected " ?> value="Não é o caso">Não é o caso</option>
                    <option <?php if ($isgr_filtro == "A") echo " selected " ?> value="A">A</option>
                    <option <?php if ($isgr_filtro == "B1") echo " selected " ?> value="B1">B1</option>
                    <option <?php if ($isgr_filtro == "B2") echo " selected " ?> value="B2">B2</option>
                    <option <?php if ($isgr_filtro == "C") echo " selected " ?> value="C">C</option>
                </select>
            </div>
            <div class="col-lg-4 form-group">
                <b>Dt Incorporação</b>
                <select name="incorporacao_filtro[]" style="width: 100%" class="chosen-select" multiple>
                    <option value="">Dt Incorporação</option>
                    <?php
                    foreach ($todas_dt_incorporacao as $value) {
                        if ($value['data'] == $incorporacao_filtro) echo "<option value='" . $value['data'] . "' selected>" . trata_data($value['data']) . "</option>";
                        else echo "<option value='" . $value['data'] . "' >" . trata_data($value['data']) . "</option>";
                    }
                    ?>
                </select>
            </div>



            <br>
            <br>
            <br>

            <input type="submit" class="btn btn-primary btn-block" value="Enviar">
        </div>
    </form>
    <br>
    <div class="">
        <table id="tabela_dinamica" class="display responsive nowrap" style="width:100%; font-size: 11px;">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Formação / Ano</th>
                    <th>Grad Facul</th>
                    <th>Ano Conc Resid</th>
                    <th>Especialidade</th>
                    <th>Nascimento</th>
                    <th>Sit Militar</th>
                    <th>Trans Fisemi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($todos_obrigatorios)
                    foreach ($todos_obrigatorios as $obrigatorio) {
                        $criptografia = hash('sha256', $obrigatorio->getId() . "criptografia");

                        if ($sel_geral_filtro != null && !in_array($obrigatorio->getDataSelecaoGeral(), $sel_geral_filtro)) continue;
                        if ($distribuicao_filtro != null && !in_array($obrigatorio->getDistribuicao(), $distribuicao_filtro)) continue;
                        if ($comp_designacao_filtro != null && !in_array($obrigatorio->getDataComparecimentoDesignacao(), $comp_designacao_filtro)) continue;
                        if ($voluntario_filtro != null && !in_array($obrigatorio->getVoluntario(), $voluntario_filtro)) continue;
                        if ($dependentes_filtro == "nenhum" && ($obrigatorio->getDependentes() >= 1 || $obrigatorio->getDependentes() == null)) continue;
                        if ($dependentes_filtro == "possui_dependente" && ($obrigatorio->getDependentes() === "0" || $obrigatorio->getDependentes() == null)) continue;

                        //##### FILTROS DOS SEMESTRES
                        if ($data_selecao_geral_semestre_filtro == "um_semestre_vintetres" && ($obrigatorio->getDataSelecaoGeral() >= '2023-06-30' || $obrigatorio->getDataSelecaoGeral() == null)) continue;

                        if ($data_selecao_geral_semestre_filtro == "dois_semestre_vintetres" && ($obrigatorio->getDataSelecaoGeral() <= '2023-06-30' || $obrigatorio->getDataSelecaoGeral() >= '2024-01-01' || $obrigatorio->getDataSelecaoGeral() == null)) continue;

                        if ($data_selecao_geral_semestre_filtro == "um_semestre_vintequatro" && ($obrigatorio->getDataSelecaoGeral() >= '2024-06-30' || $obrigatorio->getDataSelecaoGeral() <= '2024-01-01' || $obrigatorio->getDataSelecaoGeral() == null)) continue;

                        if ($data_selecao_geral_semestre_filtro == "dois_semestre_vintequatro" && ($obrigatorio->getDataSelecaoGeral() <= '2024-06-30' || $obrigatorio->getDataSelecaoGeral() >= '2025-01-01' || $obrigatorio->getDataSelecaoGeral() == null)) continue;

                        if ($data_selecao_geral_semestre_filtro == "um_semestre_vintecinco" && ($obrigatorio->getDataSelecaoGeral() >= '2025-06-30' || $obrigatorio->getDataSelecaoGeral() <= '2025-01-01' || $obrigatorio->getDataSelecaoGeral() == null)) continue;

                        if ($data_selecao_geral_semestre_filtro == "dois_semestre_vintecinco" && ($obrigatorio->getDataSelecaoGeral() <= '2025-06-30' || $obrigatorio->getDataSelecaoGeral() >= '2026-01-01' || $obrigatorio->getDataSelecaoGeral() == null)) continue;

                        if ($data_selecao_geral_semestre_filtro == "um_semestre_vinteseis" && ($obrigatorio->getDataSelecaoGeral() >= '2026-06-30' || $obrigatorio->getDataSelecaoGeral() <= '2026-01-01' || $obrigatorio->getDataSelecaoGeral() == null)) continue;

                        if ($data_selecao_geral_semestre_filtro == "dois_semestre_vinteseis" && ($obrigatorio->getDataSelecaoGeral() <= '2026-06-30' || $obrigatorio->getDataSelecaoGeral() >= '2027-01-01' || $obrigatorio->getDataSelecaoGeral() == null)) continue;

                        if ($faculdade_filtro != null && !in_array($obrigatorio->getNomeInstitutoEnsino(), $faculdade_filtro)) continue;
                        if ($jise_filtro != null && !in_array($obrigatorio->getJise(), $jise_filtro)) continue;
                        if ($jisr_filtro != null && !in_array($obrigatorio->getJisr(), $jisr_filtro)) continue;
                        if ($om_1_fase_filtro  != null && !in_array($obrigatorio->getOm1Fase()->getAbreviatura(), $om_1_fase_filtro)) continue;
                        //if($om_2_fase_filtro  != null && !in_array($obrigatorio->getOm2Fase()->getAbreviatura(), $om_2_fase_filtro)) continue;
                        if ($situacao_militar != null && !in_array($obrigatorio->getSituacaoMilitar(), $situacao_militar)) continue;
                        if ($rm_destino_filtro != null && !in_array($obrigatorio->getRmDestinoFisemi(), $rm_destino_filtro)) continue;
                        // Se não tiver nenhuma das 3 especialidades == a do filtro continues
                        if ($prioridade_forca_filtro != null && !in_array($obrigatorio->getPrioridadeForca(), $prioridade_forca_filtro)) continue;
                        if ($especialidade_filtro != null && !in_array('todas_espec', $especialidade_filtro) && !in_array($obrigatorio->getEspecialidade(), $especialidade_filtro)) continue;
                        if ($especialidade_filtro != null && in_array('todas_espec', $especialidade_filtro) && $obrigatorio->getEspecialidade() == null)  continue;
                        if ($sel_complementar_filtro != null && !in_array($obrigatorio->getDataSelecaoComplementar(), $sel_complementar_filtro)) continue;
                        if ($resultado_revisao_filtro != null && !in_array($obrigatorio->getResultadoRevisaoMedicaComplementar(), $resultado_revisao_filtro)) continue;
                        if ($isgr_filtro != null && !in_array($obrigatorio->getResultadoIsgr(), $isgr_filtro)) continue;
                        if ($incorporacao_filtro != null && !in_array($obrigatorio->getDataIncorporacao(), $incorporacao_filtro)) continue;

                        $cor = null;
                        if ($obrigatorio->getSituacaoMilitar() != null) {
                            if (strpos($obrigatorio->getSituacaoMilitar(), "Quite") !== false)
                                $cor = "#98FB98";
                            else $cor = "#FFDEAD";
                        }

                        echo "  
                        <tr style='background-color:$cor'>
                        <td> <a href ='obrigatorio.php?crip=$criptografia&id_obrigatorio=" . $obrigatorio->getId() . "'><font color='black'> " . $obrigatorio->getNomeCompleto() . "</font></a> </td>
                            <td> <a href ='obrigatorio.php?crip=$criptografia&id_obrigatorio=" . $obrigatorio->getId() . "'><font color='black'>" . $obrigatorio->getCPF() . "</font></a> </td>
                            <td> " . $obrigatorio->getFormacao() . " / " . $obrigatorio->getAnoFormacao() . " </td>
                            <td> " . $obrigatorio->getNomeInstitutoEnsino() . " </td>
                            <td> " . $obrigatorio->imprime_ano_res_mais_recente() . " </td>
                            <td> " . $obrigatorio->imprime_ultima_especialidade() . " </td>
                            <td> " . $obrigatorio->imprimeDataNascimento() . " </td>
                            <td> " . $obrigatorio->getSituacaoMilitar() . " </td>
                            <td> ";
                        if ($obrigatorio->getRmOrigemFisemi()) echo $obrigatorio->getRmOrigemFisemi() . "ª à " . $obrigatorio->getRmDestinoFisemi() . "ª" . "</td>
                        </tr>
                          ";
                    }
                ?>
            </tbody>
        </table>
        <br>
        <br>

        <center>
            <a href="javascript:history.back()"><button style="width:100%" class="btn btn-outline-light">
                    <font color="black">VOLTAR</font>
                </button></a>
        </center>
        <br>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tabela_dinamica').DataTable({
            "aaSorting": [],
            "pageLength": 50,
            "lengthMenu": [50, 100, 200, 500]
        });
    });
</script>

<?php
include_once 'footer.php';
?>