<?php
include_once 'header.php';
include_once 'dao/conecta_banco.php';
include_once 'models/Obrigatorio.php';
include_once 'models/Om.php';
include_once 'dao/ObrigatorioDAO.php';
include_once 'dao/AuxiliarDAO.php';

// if($_SESSION['perfil_smo'] != "admin"){ erro($BASE_URL, 2, 356367, $pagina_atual, "perfil!=admin", "Sem permissão!"); exit();}

$ObrigatorioDAO = new ObrigatorioDAO($conexao);
$todos_obrigatorios_da_om = $ObrigatorioDAO->findAllAtivosdaOM();


$AuxiliarDAO = new AuxiliarDAO($conexao);
$todas_oms_1_fase = $AuxiliarDAO->findAllOM1Fase();
$todas_oms_2_fase = $AuxiliarDAO->findAllOM2Fase();
$todas_dt_comp_desigancao = $AuxiliarDAO->findAllDtCompDesignacao();
$todas_espec = $AuxiliarDAO->findAllEspec();
$todas_gu = $AuxiliarDAO->findAllGuarnicao();

if (!isset($_SESSION['id_usuario_smo'])) {
    erro($BASE_URL, 2, 63216754, $pagina_atual, "Obrigatorio_nao_logado", "Página não encontrada!");
    exit();
}
if (!isset($_SESSION['mensagem'])) $_SESSION['mensagem'] = null;

// $voluntario_filtro = null;
// $voluntario_filtro = isset($_GET['voluntario_filtro']) ? (array)$_GET['voluntario_filtro'] : [];


$resultado_revisao_medica_filtro = null;
if (isset($_GET['resultado_revisao_medica_filtro'])) $resultado_revisao_medica_filtro = filtra_campo_get('resultado_revisao_medica_filtro');
$dependentes_filtro = null;
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

?>

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h1><b><i class="fa fa-stethoscope"></i> Revisão Médica - OM 1ª Fase</b></h1>
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
            <div class="col-md-12 form-group">
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
    <form method="post" action="controller/revisao_medica_atualiza.php">
        <table id="tabela_dinamica" class="display responsive nowrap" style="width:100%; font-size: 10px;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Formação / Ano</th>
                    <th>Grad Facul</th>
                    <th>Ano Conc Resid</th>
                    <th>Especialidade</th>
                    <th>Nascimento</th>
                    <th>Distribuição</th>
                    <th>Revisão Med</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($todos_obrigatorios_da_om)
                    foreach ($todos_obrigatorios_da_om as $obrigatorio) {
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

                        if ($resultado_revisao_medica_filtro != null && $resultado_revisao_medica_filtro != $obrigatorio->getResultadoRevisaoMedicaComplementar()) continue;


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
                        <td> <a href ='obrigatorio_om.php?crip=$criptografia&id_obrigatorio=" . $obrigatorio->getId() . "'><font color='black'> " . $obrigatorio->getNomeCompleto() . "</font></a> </td>
                        <td> <a href ='obrigatorio_om.php?crip=$criptografia&id_obrigatorio=" . $obrigatorio->getId() . "'><font color='black'>" . $obrigatorio->getCPF() . "</font></a> </td>
                        <td> " . $obrigatorio->getFormacao() . " / " . $obrigatorio->getAnoFormacao() . " </td>
                        <td> " . $obrigatorio->getNomeInstitutoEnsino() . " </td>
                        <td> " . $obrigatorio->imprime_ano_res_mais_recente() . " </td>
                        <td> " . $obrigatorio->imprime_ultima_especialidade() . " </td>
                        <td> " . $obrigatorio->imprimeDataNascimento() . " </td>
                        <td> " . $obrigatorio->getDistribuicao() . " </td>
                        <td> " . $obrigatorio->getResultadoRevisaoMedicaComplementar() . " </td>
                    </tr>
                        ";
                    }
                ?>
            </tbody>
        </table>

        <br>

        <div class="row">

            <div class="col-md-4 form-group">
                <b>Resultado Revisão Médica </b>
                <select name="resultado_revisao_medica_complementar" class="form-control">
                    <option value="">Selecione a Opção</option>
                    <option value="APTO">APTO</option>
                    <option value="INAPTO">INAPTO</option>
                    <option value="NÃO COMPARECEU">NÃO COMPARECEU</option>
                </select>
            </div>

            <div class="col-md-4 form-group">
                <b>ISGRev - MPGu</b>
                <select name="resultado_isgr" class="form-control">
                    <option value="">Selecione a Opção</option>
                    <option value="NÃO é o caso">NÃO é o caso</option>
                    <option value="A">A</option>
                    <option value="B1">B1</option>
                    <option value="B2">B2</option>
                    <option value="C">C</option>
                </select>
            </div>


            <div class="col-md-4 form-group">
                <b>*CID Revisão Médica (novo)</b>
                <textarea placeholder="CID Revisão Médica" name="cid_revisao_medica" class="form-control"></textarea>
            </div>

            <input name="crip" hidden value="<?php echo hash('sha256', $_SESSION['chave'] . "atualiza"); ?>">
            <br>
            <br>
            <br>
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