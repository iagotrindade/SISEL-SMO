<?php
include_once 'header.php';
include_once 'dao/conecta_banco.php';
include_once 'models/Obrigatorio.php';
include_once 'models/Om.php';
include_once 'dao/ObrigatorioDAO.php';
include_once 'dao/AuxiliarDAO.php';

if ($_SESSION['perfil_smo'] != "admin") {
    erro($BASE_URL, 2, 66855462, $pagina_atual, "perfil!=admin", "Sem permissão!");
    exit();
}

$ObrigatorioDAO = new ObrigatorioDAO($conexao);
$todos_obrigatorios = $ObrigatorioDAO->findAllAtivos();


$AuxiliarDAO = new AuxiliarDAO($conexao);
$todas_oms_1_fase = $AuxiliarDAO->findAllOM1Fase();
$todas_oms_2_fase = $AuxiliarDAO->findAllOM2Fase();
$todas_cid_inst = $AuxiliarDAO->findAllCidInst();
$todas_dt_comp_desigancao = $AuxiliarDAO->findAllDtCompDesignacao();
$todas_incorporacao = $AuxiliarDAO->findAllIncorporacao();
$todas_espec = $AuxiliarDAO->findAllEspec();
$todas_gu = $AuxiliarDAO->findAllGuarnicao();
$todas_dt_sel_geral = $AuxiliarDAO->findAllDtSelecaoComp();

if (!isset($_SESSION['id_usuario_smo'])) {
    erro($BASE_URL, 2, 63216754, $pagina_atual, "Obrigatorio_nao_logado", "Página não encontrada!");
    exit();
}
if (!isset($_SESSION['mensagem'])) $_SESSION['mensagem'] = null;

// $voluntario_filtro = null;
// $voluntario_filtro = isset($_GET['voluntario_filtro']) ? (array)$_GET['voluntario_filtro'] : [];

$voluntario_filtro = null;
if (isset($_GET['voluntario_filtro'])) $voluntario_filtro = filtra_campo_get('voluntario_filtro');
$sel_geral_filtro = null;
if (isset($_GET['sel_geral_filtro'])) $sel_geral_filtro = (array)$_GET['sel_geral_filtro'];
$dependentes_filtro = null;
$data_selecao_geral_semestre_filtro = null;
if (isset($_GET['data_selecao_geral_semestre_filtro'])) $data_selecao_geral_semestre_filtro = filtra_campo_get("data_selecao_geral_semestre_filtro");
if (isset($_GET['dependentes_filtro'])) $dependentes_filtro = filtra_campo_get('dependentes_filtro');
$faculdade_filtro = null;
if (isset($_GET['faculdade_filtro'])) $faculdade_filtro = (array)$_GET['faculdade_filtro'];
$jise_filtro = null;
if (isset($_GET['jise_filtro'])) $jise_filtro = (array)$_GET['jise_filtro'];
$jisr_filtro = null;
if (isset($_GET['jisr_filtro'])) $jisr_filtro = (array)$_GET['jisr_filtro'];
$om_1_fase_filtro = null;
if (isset($_GET['om_1_fase_filtro'])) $om_1_fase_filtro = (array)$_GET['om_1_fase_filtro'];
$prox_apr_filtro = null;
if (isset($_GET['prox_apr_filtro'])) $prox_apr_filtro = (array)$_GET['prox_apr_filtro'];
$situacao_militar = null;
if (isset($_GET['situacao_militar'])) $situacao_militar = (array)$_GET['situacao_militar'];
$rm_destino_filtro = null;
if (isset($_GET['rm_destino'])) $rm_destino_filtro = filtra_campo_get('rm_destino');
$formacao_filtro = null;
if (isset($_GET['formacao_filtro'])) $formacao_filtro = filtra_campo_get('formacao_filtro');
$prioridade_forca_filtro = null;
if (isset($_GET['prioridade_forca_filtro'])) $prioridade_forca_filtro = filtra_campo_get('prioridade_forca_filtro');
$especialidade_filtro = null;
if (isset($_GET['especialidade_filtro'])) $especialidade_filtro = (array)$_GET['especialidade_filtro'];
$prioridade_gu_filtro = null;
if (isset($_GET['prioridade_gu_filtro'])) $prioridade_gu_filtro = filtra_campo_get('prioridade_gu_filtro');
$distribuicao_filtro = null;
if (isset($_GET['distribuicao_filtro'])) $distribuicao_filtro = (array)$_GET['distribuicao_filtro'];

$resultado_revisao_filtro = null;
if (isset($_GET['resultado_revisao_filtro'])) $resultado_revisao_filtro = (array)$_GET['resultado_revisao_filtro'];
$incorporacao_filtro = null;
if (isset($_GET['incorporacao_filtro'])) $incorporacao_filtro = (array)$_GET['incorporacao_filtro'];
$compareceu_designacao_filtro = null;
if (isset($_GET['compareceu_designacao_filtro'])) $compareceu_designacao_filtro = $_GET['compareceu_designacao_filtro'];
$local_compareceu_designacao_filtro = null;
if (isset($_GET['local_compareceu_designacao_filtro'])) $local_compareceu_designacao_filtro = $_GET['local_compareceu_designacao_filtro'];
?>

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h1><b><i class="bi bi-arrow-left-right"></i> Pré Distribuição MFDV - OM 1ª Fase</b></h1>
            </div>
        </div>
    </section>
</main>

<?php if (isset($_SESSION['mensagem'])) : ?>
    <center>
        <font color="green" size="6px"><?php echo $_SESSION['mensagem'];
                                        $_SESSION['mensagem'] = null; ?></font>
    </center>
<?php endif; ?>

<section id="contact" class="contact">
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method='get'>
        <div class="row">


            <div class="col-lg-6 form-group">
                <label><strong>OM 1ª Fase</strong></label>

                <select name="om_1_fase_filtro[]" style="width: 100%" class="chosen-select" multiple>
                    <option value="">OM 1ª Fase</option>
                    <?php
                    foreach ($todas_oms_1_fase as $value) {
                        if ($value['abreviatura'] == $om_1_fase_filtro)  echo "<option value='" . $value['abreviatura'] . "' selected>" . $value['abreviatura'] . "</option>";
                        else echo "<option value='" . $value['abreviatura'] . "' >" . $value['abreviatura'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-lg-6 form-group">
                <label><strong>Situação Militar</strong></label>
                <select name="situacao_militar[]" style="width: 100%" id="situacao_militar" class="chosen-select" multiple>
                    <option value="todas_sit_mil">Todas as Opções</option>
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

            <div class="col-lg-6 form-group">
                <label><strong>Resultado Revisão Médica***</strong></label>
                <select name="resultado_revisao_filtro[]" style="width: 100%" class="chosen-select" multiple>
                    <option value="">Resultado Revisão Médica</option>
                    <option <?php if ($resultado_revisao_filtro == "APTO") echo " selected " ?> value="APTO">APTO</option>
                    <option <?php if ($resultado_revisao_filtro == "INAPTO") echo " selected " ?> value="INAPTO">INAPTO</option>
                    <option <?php if ($resultado_revisao_filtro == "NÃO COMPARECEU") echo " selected " ?> value="NÃO COMPARECEU">NÃO COMPARECEU</option>
                </select>
            </div>

            <div class="col-lg-6 form-group">
                <label><strong>Designação - Compareceu?***</strong></label>
                <select name="compareceu_designacao_filtro" style="width: 100%" class="chosen-select" multiple>
                    <option value="">Compareceu Designação</option>
                    <option <?php if ($compareceu_designacao_filtro == "sim") echo " selected " ?> value="sim">Sim</option>
                    <option <?php if ($compareceu_designacao_filtro == "nao") echo " selected " ?> value="nao">Não</option>
                </select>
            </div>

            <div class="col-lg-6 form-group">
                <label><strong>Local Designação Compareceu</strong></label>
                <select name="local_compareceu_designacao_filtro" style="width: 100%" class="chosen-select" multiple>
                    <option value="">Selecione a Opção</option>
                    <?php
                    foreach ($todas_oms_1_fase as $value) {
                        if ($value['nome'] == $local_compareceu_designacao_filtro)
                            echo "<option value='" . $value['nome'] . "' selected>" . $value['abreviatura'] . "</option>";
                        else
                            echo "<option value='" . $value['nome'] . "' >" . $value['abreviatura'] . "</option>";
                    }
                    ?>

                    <?php foreach ($todas_cid_inst as $value) {
                        if ($value['nome'] == $local_compareceu_designacao_filtro)
                            echo "<option value='" . $value['nome'] . "' selected>" . $value['nome'] . "</option>";
                        else
                            echo "<option value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-lg-6 form-group">
                <label><strong>Incorporação - Compareceu?***</strong></label>

                <select name="incorporacao_filtro[]" style="width: 100%" class="chosen-select" multiple>
                    <option value="">Incorporação</option>
                    <?php
                    foreach ($todas_incorporacao as $value) {
                        if ($value['incorporacao'] == $incorporacao_filtro) echo "<option value='" . $value['incorporacao'] . "' selected>" . trata_data($value['incorporacao']) . "</option>";
                        else echo "<option value='" . $value['incorporacao'] . "' >" . trata_data($value['incorporacao']) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-lg-6 form-group">
                <label><strong>Data Seleção Geral SEMESTRE</strong></label>
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

            <div class="col-lg-6 form-group">
                <label><strong>Data Seleção Geral</strong></label>
                <select name="sel_geral_filtro[]" style="width: 100%" class="chosen-select" multiple>
                    <option value="">Data Seleção Geral</option>
                    <?php
                    foreach ($todas_dt_sel_geral as $value) {
                        if ($value['data'] == $sel_geral_filtro)  echo "<option value='" . $value['data'] . "' selected>" . trata_data($value['data']) . "</option>";
                        else echo "<option value='" . $value['data'] . "' >" . trata_data($value['data']) . "</option>";
                    }
                    ?>
                </select>
            </div>


            <label><strong>Especialidade</strong></label>
            <select name="especialidade_filtro[]" style="width: 100%" class="chosen-select" multiple size="4">
                <option value="todas_espec">Todas Especialidades</option>
                <?php
                foreach ($todas_espec as $value) {
                    if (isset($especialidade_filtro) && $especialidade_filtro == $value['nome'])
                        echo "<option selected value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
                    else
                        echo "<option value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
                }
                ?>
            </select>

            <div class="col-lg-2 form-group">
                <label><strong>Formação</strong></label>
                <select name="formacao_filtro" style="width: 100%" class="chosen-select">
                    <option value="">Formação</option>
                    <option <?php if ($formacao_filtro == 'MÉDICO - Generalista') echo " selected " ?> value="MÉDICO - Generalista">MÉDICO - Generalista</option>
                    <option <?php if ($formacao_filtro != null && $formacao_filtro === 'DENTISTA - Cirurgião Dentista') echo " selected " ?> value="DENTISTA - Cirurgião Dentista">DENTISTA - Cirurgião Dentista</option>
                    <?php /*
                    foreach ($todas_espec as $value) 
                    {
                        if(isset($formacao_filtro) && $formacao_filtro == $value['nome'])
                            echo "<option selected value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
                        else
                            echo "<option value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
                    }
                */ ?>
                </select>
            </div>
            <div class="col-lg-2 form-group">
                <label><strong>Voluntário</strong></label>
                <select name="voluntario_filtro" style="width: 100%" class="chosen-select">
                    <option value="">Voluntário</option>
                    <option value="SIM">Sim</option>
                    <option value="NÃO">Não</option>
                </select>
            </div>
            <div class="col-lg-2 form-group">
                <label><strong>Dependentes</strong></label>
                <select name="dependentes_filtro" style="width: 100%" class="chosen-select">
                    <option value="">Dependentes</option>
                    <option <?php if ($dependentes_filtro == 'nenhum') echo " selected " ?> value="nenhum">Nenhum Dependente</option>
                    <option <?php if ($dependentes_filtro == "possui_dependente") echo " selected " ?> value="possui_dependente">Com dependentes</option>
                </select>
            </div>
            <br>
            <br>

            <div class="col-lg-2 form-group">
                <label><strong>FISEMI - RM Destino</strong></label>
                <select name="rm_destino" style="width: 100%" class="chosen-select">
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
                <label><strong>JISE</strong></label>
                <select name="jise_filtro[]" style="width: 100%" class="chosen-select" multiple>
                    <option value="">JISE</option>
                    <option <?php if ($jise_filtro == "A") echo " selected " ?> value="A">A</option>
                    <option <?php if ($jise_filtro == "B1") echo " selected " ?> value="B1">B1</option>
                    <option <?php if ($jise_filtro == "B2") echo " selected " ?> value="B2">B2</option>
                    <option <?php if ($jise_filtro == "C") echo " selected " ?> value="C">C</option>
                </select>
            </div>
            <div class="col-lg-2 form-group">
                <label><strong>JISR</strong></label>
                <select name="jisr_filtro[]" style="width: 100%" class="chosen-select" multiple>
                    <option value="">JISR</option>
                    <option <?php if ($jisr_filtro == "A") echo " selected " ?> value="A">A</option>
                    <option <?php if ($jisr_filtro == "B1") echo " selected " ?> value="B1">B1</option>
                    <option <?php if ($jisr_filtro == "B2") echo " selected " ?> value="B2">B2</option>
                    <option <?php if ($jisr_filtro == "C") echo " selected " ?> value="C">C</option>
                </select>
            </div>

            <div class="col-lg-2 form-group">
                <label><strong>Prioridade da Força</strong></label>
                <select name="prioridade_forca_filtro" style="width: 100%" class="chosen-select">
                    <option value="">Prioridade da Força</option>
                    <option <?php if ($prioridade_forca_filtro == "EMA") echo " selected " ?>value="EMA">EMA</option>
                    <option <?php if ($prioridade_forca_filtro  == "EAM") echo " selected " ?>value="EAM">EAM</option>
                    <option <?php if ($prioridade_forca_filtro  == "MAE") echo " selected " ?>value="MAE">MAE</option>
                    <option <?php if ($prioridade_forca_filtro  == "MEA") echo " selected " ?>value="MEA">MEA</option>
                    <option <?php if ($prioridade_forca_filtro  == "AEM") echo " selected " ?>value="AEM">AEM</option>
                    <option <?php if ($prioridade_forca_filtro  == "AME") echo " selected " ?>value="AME">AME</option>
                </select>
            </div>


            <div class="col-lg-2 form-group">
                <label><strong>Prioridade Gu</strong></label>
                <select name="prioridade_gu_filtro" style="width: 100%" class="chosen-select">
                    <option value="">Prioridade Gu</option>
                    <?php
                    foreach ($todas_gu as $value) {
                        if (isset($prioridade_gu_filtro) && $prioridade_gu_filtro == $value['nome'])
                            echo "<option selected value='" . $value['id'] . "' >" . $value['nome'] . "</option>";
                        else
                            echo "<option value='" . $value['id'] . "' >" . $value['nome'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-lg-4 form-group">
                <label><strong>IE Graduação</strong></label>
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

            <div class="col-lg-4 form-group">

                <label><strong>Distribuição</strong></label>
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

            <br>
            <br>

            <div class="col-lg-12 form-group">
                <input type="submit" class="w-100 btn btn-primary btn-block" value="Enviar">
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
                    <th>Formação / Ano</th>
                    <th>OM 1ª Fase</th>
                    <th>Grad Facul</th>
                    <th>Especialidade</th>
                    <th>Ano Conc Resid</th>
                    <th>Dias Vida</th>
                    <th>Sit Militar</th>
                    <th>Res Rev Médica</th>
                    <th>Compareceu?</th>
                    <th>Distribuição</th>
                    <th>Prioridade Gu</th>
                    <th>Trans Fisemi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($todos_obrigatorios)
                    foreach ($todos_obrigatorios as $obrigatorio) {
                        $criptografia = hash('sha256', $obrigatorio->getId() . "criptografia");

                        $teste = false;
                        $nome_gu = null;
                        $prioridade_gu = null;
                        if ($prioridade_gu_filtro != null) {
                            $resultado = $AuxiliarDAO->verifica_prioridade($obrigatorio->getId(), $prioridade_gu_filtro);
                            if ($resultado) {
                                $teste = true;
                                $prioridade_gu = $resultado[0]['prioridade'] . " ª";
                                $nome_gu = $resultado[0]['nome_gu'] . " - ";
                            }
                        }

                        if ($sel_geral_filtro != null && !in_array($obrigatorio->getDataSelecaoGeral(), $sel_geral_filtro)) continue;
                        if ($distribuicao_filtro != null && !in_array($obrigatorio->getDistribuicao(), $distribuicao_filtro)) continue;
                        if ($prioridade_gu_filtro != null && !$teste) continue;
                        if ($voluntario_filtro != null && $voluntario_filtro != $obrigatorio->getVoluntario()) continue;
                        if ($dependentes_filtro == "nenhum" && ($obrigatorio->getDependentes() >= 1 || $obrigatorio->getDependentes() == null)) continue;
                        if ($dependentes_filtro == "possui_dependente" && ($obrigatorio->getDependentes() === "0" || $obrigatorio->getDependentes() == null)) continue;
                        if ($faculdade_filtro != null && !in_array($obrigatorio->getNomeInstitutoEnsino(), $faculdade_filtro)) continue;
                        if ($jise_filtro != null && !in_array($obrigatorio->getJise(), $jise_filtro)) continue;
                        if ($jisr_filtro != null && !in_array($obrigatorio->getJisr(), $jisr_filtro)) continue;
                        if ($om_1_fase_filtro  != null && !in_array($obrigatorio->getOm1Fase()->getAbreviatura(), $om_1_fase_filtro)) continue;
                        if ($situacao_militar != null && !in_array($obrigatorio->getSituacaoMilitar(), $situacao_militar)) continue;
                        if ($rm_destino_filtro != null && $obrigatorio->getRmDestinoFisemi() != $rm_destino_filtro) continue;
                        if ($compareceu_designacao_filtro != null) {
                            if ($compareceu_designacao_filtro == "sim" && $obrigatorio->getCompareceuDesignacao() != "sim") continue;
                            if ($compareceu_designacao_filtro == "nao" && $obrigatorio->getCompareceuDesignacao() != "nao") continue;
                        }
                        if ($local_compareceu_designacao_filtro != null) {
                            if ($local_compareceu_designacao_filtro != $obrigatorio->getLocalCompareceuDesignacao()) continue;
                        }
                        // Se não tiver nenhuma das 3 especialidades == a do filtro continues
                        //if($formacao_filtro != null && ($obrigatorio->getEspecialidade() != $formacao_filtro && $obrigatorio->getEspecialidade2() != $formacao_filtro && $obrigatorio->getEspecialidade3() != $formacao_filtro)) continue;   
                        if ($formacao_filtro != null && $obrigatorio->getFormacao() != $formacao_filtro) continue;
                        if ($prioridade_forca_filtro != null && ($obrigatorio->getPrioridadeForca() != $prioridade_forca_filtro)) continue;
                        if ($especialidade_filtro != null && !in_array('todas_espec', $especialidade_filtro) && !in_array($obrigatorio->getEspecialidade(), $especialidade_filtro)) continue;
                        if ($especialidade_filtro != null && in_array('todas_espec', $especialidade_filtro) && $obrigatorio->getEspecialidade() == null)  continue;
                        if ($resultado_revisao_filtro != null && !in_array($obrigatorio->getResultadoRevisaoMedicaComplementar(), $resultado_revisao_filtro)) continue;
                        if ($incorporacao_filtro != null && !in_array($obrigatorio->getIncorporacao(), $incorporacao_filtro)) continue;
                        //if($distribuicao_filtro && ($obrigatorio->getDistribuicao() != $distribuicao_filtro || $obrigatorio->getDistribuicao() == null)) continue;
                        //if($prioridade_gu_filtro != null && $obrigatorio->getPrioridade_gu() != $prioridade_gu_filtro) continue; 

                        //##### FILTROS DOS SEMESTRES
                        if ($data_selecao_geral_semestre_filtro == "um_semestre_vintetres" && ($obrigatorio->getDataSelecaoGeral() >= '2023-06-30' || $obrigatorio->getDataSelecaoGeral() == null)) continue;

                        if ($data_selecao_geral_semestre_filtro == "dois_semestre_vintetres" && ($obrigatorio->getDataSelecaoGeral() <= '2023-06-30' || $obrigatorio->getDataSelecaoGeral() >= '2024-01-01' || $obrigatorio->getDataSelecaoGeral() == null)) continue;

                        if ($data_selecao_geral_semestre_filtro == "um_semestre_vintequatro" && ($obrigatorio->getDataSelecaoGeral() >= '2024-06-30' || $obrigatorio->getDataSelecaoGeral() <= '2024-01-01' || $obrigatorio->getDataSelecaoGeral() == null)) continue;

                        if ($data_selecao_geral_semestre_filtro == "dois_semestre_vintequatro" && ($obrigatorio->getDataSelecaoGeral() <= '2024-06-30' || $obrigatorio->getDataSelecaoGeral() >= '2025-01-01' || $obrigatorio->getDataSelecaoGeral() == null)) continue;

                        if ($data_selecao_geral_semestre_filtro == "um_semestre_vintecinco" && ($obrigatorio->getDataSelecaoGeral() >= '2025-06-30' || $obrigatorio->getDataSelecaoGeral() <= '2025-01-01' || $obrigatorio->getDataSelecaoGeral() == null)) continue;

                        if ($data_selecao_geral_semestre_filtro == "dois_semestre_vintecinco" && ($obrigatorio->getDataSelecaoGeral() <= '2025-06-30' || $obrigatorio->getDataSelecaoGeral() >= '2026-01-01' || $obrigatorio->getDataSelecaoGeral() == null)) continue;

                        if ($data_selecao_geral_semestre_filtro == "um_semestre_vinteseis" && ($obrigatorio->getDataSelecaoGeral() >= '2026-06-30' || $obrigatorio->getDataSelecaoGeral() <= '2026-01-01' || $obrigatorio->getDataSelecaoGeral() == null)) continue;

                        if ($data_selecao_geral_semestre_filtro == "dois_semestre_vinteseis" && ($obrigatorio->getDataSelecaoGeral() <= '2026-06-30' || $obrigatorio->getDataSelecaoGeral() >= '2027-01-01' || $obrigatorio->getDataSelecaoGeral() == null)) continue;

                        $cor = null;
                        if ($obrigatorio->getSituacaoMilitar() != null) {
                            if (strpos($obrigatorio->getSituacaoMilitar(), "Quite") !== false)
                                $cor = "#98FB98";
                            else $cor = "#FFDEAD";
                        }

                        $dataNascimento = $obrigatorio->getDataNascimento();
                        $hoje = date("Y-m-d");
                        $diasDeVida = floor((strtotime($hoje) - strtotime($dataNascimento)) / (60 * 60 * 24));

                        echo "  
                    <tr style='background-color:$cor'>
                        <td> <input type='checkbox' name='ids[]' value='" . $obrigatorio->getId() . "'> </td>
                        <td> <a href ='obrigatorio.php?crip=$criptografia&id_obrigatorio=" . $obrigatorio->getId() . "'><font color='black'> " . $obrigatorio->getNomeCompleto() . "</font></a> </td>
                        <td> " . $obrigatorio->getFormacao() . " / " . $obrigatorio->getAnoFormacao() . " </td>
                        <td> " . $obrigatorio->getOm1Fase()->getAbreviatura() . " </td>
                        <td> " . $obrigatorio->getNomeInstitutoEnsino() . " </td>
                        <td> " . $obrigatorio->imprime_ultima_especialidade() . " </td>
                        <td> " . $obrigatorio->imprime_ano_res_mais_recente() . " </td>
                        <td> " . $diasDeVida . " </td>
                        <td> " . $obrigatorio->getSituacaoMilitar() . " </td>
                          <td> " . $obrigatorio->getResultadoRevisaoMedicaComplementar() . " </td>
                            <td> " . $obrigatorio->getIncorporacao() . " </td>
                        <td> " . $obrigatorio->getDistribuicao() . " </td>
                        <td> " . $nome_gu . "  " . $prioridade_gu . "  </td>
                        <td> ";
                        if ($obrigatorio->getRmOrigemFisemi()) echo $obrigatorio->getRmOrigemFisemi() . "ª à " . $obrigatorio->getRmDestinoFisemi() . "ª" . "</td>
                    </tr>
                        ";
                    }
                ?>
            </tbody>
        </table>

        <br>

        <div class="row">
            <div class="col-lg-4 form-group">
                <select name="id_om_1_fase" style="width: 100%" class="chosen-select">
                    <option value="">OM 1ª Fase Destino</option>
                    <?php
                    foreach ($todas_oms_1_fase as $value) {
                        if ($value['id'] == $id_om_1_fase)  echo "<option value='" . $value['id'] . "' selected>" . $value['abreviatura'] . "</option>";
                        else echo "<option value='" . $value['id'] . "' >" . $value['abreviatura'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-lg-4 form-group">
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

            <div class="col-lg-4 form-group">
                <input type="text" class="form-control" name="data_apresentacao_atualiza" placeholder="Data da próxima apresentação">
            </div>

            <input name="crip" hidden value="<?php echo hash('sha256', $_SESSION['chave'] . "atualiza"); ?>">

            <div class="text-center" width="100%">
                <button type="submit">Atualizar</button>
            </div>
        </div>
    </form>
</section>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tabela_dinamica').DataTable({
            "aaSorting": [],
            "pageLength": 25,
            "lengthMenu": [25, 50, 100, 200, 500]
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


<?php
include_once 'footer.php';
?>