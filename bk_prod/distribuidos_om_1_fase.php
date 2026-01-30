<?php

include_once 'header.php';
include_once 'dao/conecta_banco.php';
include_once 'models/Obrigatorio.php';
include_once 'models/Om.php';
include_once 'dao/ObrigatorioDAO.php';
include_once 'dao/AuxiliarDAO.php';

$ObrigatorioDAO = new ObrigatorioDAO($conexao);
$todos_obrigatorios = $ObrigatorioDAO->findAllAtivos();
$todos_obrigatorios_da_om = $ObrigatorioDAO->findAllAtivosdaOM();
$om_operador =  $_SESSION['id_om_smo'];

if ($_SESSION['perfil_smo'] != "operador") {
    erro($BASE_URL, 2, 356367, $pagina_atual, "perfil!=operador", "Sem permissão!");
    exit();
}
if (!isset($_SESSION['id_usuario_smo'])) {
    erro($BASE_URL, 2, 63216754, $pagina_atual, "Obrigatorio_nao_logado", "Página não encontrada!");
    exit();
}
if (!isset($_SESSION['mensagem'])) $_SESSION['mensagem'] = null;


$distribuicao_filtro = null;
if (isset($_GET['distribuicao_filtro'])) $distribuicao_filtro = filtra_campo_get("distribuicao_filtro");

$resultado_revisao_medica_filtro = null;
if (isset($_GET['resultado_revisao_medica_filtro'])) $resultado_revisao_medica_filtro = filtra_campo_get("resultado_revisao_medica_filtro");

?>

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h1><b>MFD<img src='imagens/v-esteto.png' width='50px'> Obrigatórios</b></h1>
            </div>
        </div>
    </section>
</main>

<?php if ($_SESSION['mensagem'] != null): ?>
    <center>
        <font color="green" size="6px"><?php echo $_SESSION['mensagem'];
                                        $_SESSION['mensagem'] = null; ?></font>
    </center>
<?php endif; ?>

<section id="contact" class="contact">
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method='get'>
        <div class="row">

            <div class="col-lg-6 form-group">
                <b>Distribuição</b>
                <select name="distribuicao_filtro" style="width: 100%" class="chosen-select" onchange="this.form.submit()">
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

            <div class="col-md-6 form-group">
                <b>Resultado Revisão Médica </b>
                <select name="resultado_revisao_medica_filtro" class="form-control" onchange="this.form.submit()">
                    <option value="">Selecione a Opção</option>
                    <option <?php if ($resultado_revisao_medica_filtro == "APTO") echo " selected " ?> value="APTO">APTO</option>
                    <option <?php if ($resultado_revisao_medica_filtro == "INAPTO") echo " selected " ?> value="INAPTO">INAPTO</option>
                    <option <?php if ($resultado_revisao_medica_filtro == "NÃO COMPARECEU") echo " selected " ?> value="NÃO COMPARECEU">NÃO COMPARECEU</option>
                </select>
            </div>
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
                    <th>Distribuição</th>
                    <th>Revisão Mé</th>
                    <th>Incorporação</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($todos_obrigatorios_da_om)
                    foreach ($todos_obrigatorios_da_om as $obrigatorio) {
                        $criptografia = hash('sha256', $obrigatorio->getId() . "criptografia");

                        if ($distribuicao_filtro && ($obrigatorio->getDistribuicao() != $distribuicao_filtro || $obrigatorio->getDistribuicao() == null)) continue;
                        if ($resultado_revisao_medica_filtro && ($obrigatorio->getResultadoRevisaoMedicaComplementar() != $resultado_revisao_medica_filtro || $obrigatorio->getResultadoRevisaoMedicaComplementar() == null)) continue;

                        $cor = null;
                        if ($obrigatorio->getSituacaoMilitar() != null) {
                            if (strpos($obrigatorio->getSituacaoMilitar(), "Quite") !== false)
                                $cor = "#98FB98";
                            else $cor = "#FFDEAD";
                        }

                        if ($obrigatorio->getSituacaoMilitar() == null) continue;
                        if ($obrigatorio->getSituacaoMilitar() == "Em Dia - JUDICIAL") continue;
                        if ($obrigatorio->getSituacaoMilitar() == "Em Débito - REFRATÁRIO") continue;
                        if ($obrigatorio->getSituacaoMilitar() == "Em Débito - INSUBMISSO") continue;
                        if ($obrigatorio->getSituacaoMilitar() == "Em Dia - B1 - INSP SAU - RETORNAR PRÓXIMA SELEÇÃO") continue;
                        if ($obrigatorio->getSituacaoMilitar() == "Quite SMO - EXCESSO - CONTINGENTE") continue;
                        if ($obrigatorio->getSituacaoMilitar() == "Quite SMO - EXCESSO - INCAPAZ SAÚDE") continue;
                        if ($obrigatorio->getSituacaoMilitar() == "Quite SMO - DESOBRIGADO - MAIOR 38 ANOS") continue;
                        if ($obrigatorio->getSituacaoMilitar() == "Quite SMO - DESOBRIGADO - JÁ RESERVISTA") continue;
                        if ($obrigatorio->getSituacaoMilitar() == "Quite SMO - DESOBRIGADO - NATURALIZADO") continue;
                        if ($obrigatorio->getSituacaoMilitar() == "Quite SMO - CONVOCADO") continue;
                        //  if($obrigatorio->getDistribuicao() == "MAJORADO - 1ª Distribuição") continue;
                        //  if($obrigatorio->getDistribuicao() == "MAJORADO - 2ª Distribuição") continue;
                        if ($obrigatorio->getDistribuicao() == "EXCESSO CONTINGENTE") continue;
                        if ($obrigatorio->getDistribuicao() == "MARINHA") continue;
                        if ($obrigatorio->getDistribuicao() == "FORÇA AÉREA") continue;
                        if ($obrigatorio->getDistribuicao() == null) continue;
                        if ($obrigatorio->getJise() == null) continue;
                        if ($obrigatorio->getJisr() == null) {
                            if ($obrigatorio->getJise() == "B1") continue;
                            if ($obrigatorio->getJise() == "B2") continue;
                            if ($obrigatorio->getJise() == "C") continue;
                        }
                        if ($obrigatorio->getJisr() == "B1") continue;
                        if ($obrigatorio->getJisr() == "B2") continue;
                        if ($obrigatorio->getJisr() == "C") continue;

                        echo "  
                        <tr style='background-color:$cor'>
                        <td> <a href ='obrigatorio_om.php?crip=$criptografia&id_obrigatorio=" . $obrigatorio->getId() . "'><font color='black'> " . $obrigatorio->getNomeCompleto() . "</font></a> </td>
                            <td> <a href ='obrigatorio_om.php?crip=$criptografia&id_obrigatorio=" . $obrigatorio->getId() . "'><font color='black'>" . $obrigatorio->getCPF() . "</font></a> </td>
                            <td> " . $obrigatorio->getFormacao() . " / " . $obrigatorio->getAnoFormacao() . " </td>
                            <td> " . $obrigatorio->getNomeInstitutoEnsino() . " </td>
                            <td> " . $obrigatorio->imprime_ano_res_mais_recente() . " </td>
                            <td> " . $obrigatorio->imprime_ultima_especialidade() . " </td>
                            <td> " . $obrigatorio->getDistribuicao() . " </td>
                            <td> " . $obrigatorio->getResultadoRevisaoMedicaComplementar() . " </td>
                            <td> " . $obrigatorio->getIncorporacao() . " </td>
                        </tr>
                          ";
                    }
                ?>
            </tbody>
        </table>
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