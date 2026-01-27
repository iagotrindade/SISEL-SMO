<?php
include_once 'header.php';
include_once 'dao/conecta_banco.php';
include_once 'models/Obrigatorio.php';
include_once 'models/Om.php';
include_once 'models/Arquivo.php';
include_once 'models/Oficio.php';
include_once 'models/Usuario.php';
include_once 'dao/OficioDAO.php';
include_once 'dao/ObrigatorioDAO.php';
include_once 'dao/ArquivoDAO.php';
include_once 'dao/AuxiliarDAO.php';
include_once 'dao/UsuarioDAO.php';

if ($_SESSION['perfil_smo'] != "admin") {
    erro($BASE_URL, 2, 9961356, $pagina_atual, "usuario_sem_permissao", "Usuário sem permissão!");
}

if (!isset($_SESSION['id_usuario_smo'])) {
    erro($BASE_URL, 2, 26347634, $pagina_atual, "usuario_nao_logado", "Página não encontrada!");
    exit();
}
if (!isset($_SESSION['mensagem'])) $_SESSION['mensagem'] = null;

$crip_url = filtra_campo_get('crip');
$id_obrigatorio = 0;
$aba = 1;

if (isset($_GET['id_obrigatorio']))  $id_obrigatorio = (int)filtra_campo_get('id_obrigatorio');
if (isset($_GET['aba']))  $aba = (int)filtra_campo_get('aba');

if ($id_obrigatorio == 0) {
    erro($BASE_URL, 2, 26346457, $pagina_atual, "id_obrigatorio==0", "Página não encontrada!");
    exit();
}

if (hash('sha256', $id_obrigatorio . "criptografia") != $crip_url) {
    erro($BASE_URL, 2, 2363677, $pagina_atual, "crip_invalida", "Página não encontrada!");
    exit();
}

$ObrigatorioDAO = new ObrigatorioDAO($conexao);
$obrigatorio = $ObrigatorioDAO->findById($id_obrigatorio);

$arquivosDAO = new ArquivoDAO($conexao);
$arquivos = $arquivosDAO->findByIdObrigatorio($id_obrigatorio);

$usuarioDAO = new UsuarioDAO($conexao);
$usuario = $usuarioDAO->findById($_SESSION['id_usuario_smo']);

$AuxiliarDAO = new AuxiliarDAO($conexao);
$todas_oms_1_fase = $AuxiliarDAO->findAllOM1Fase();
$todas_oms_2_fase = $AuxiliarDAO->findAllOM2Fase();
$todas_espec = $AuxiliarDAO->findAllEspec();
$todas_cid = $AuxiliarDAO->findAllCidades();
$todas_cid_inst = $AuxiliarDAO->findAllCidInst();
$todas_gu = $AuxiliarDAO->findAllGuarnicao();
$prioridade_guarnicao = $ObrigatorioDAO->findAllGuarnicaoPrioridade($id_obrigatorio);

$ano_sete_anos_frente = intval(date("Y")) + 7;

$oficioDAO = new OficioDAO($conexao);
$oficio = $oficioDAO->findByIdObrigatorio($id_obrigatorio);

if (!$oficio)  $oficio = new Oficio();

// Gestão de ABAS
$aba1active = "";
$aba2active = "";
$aba3active = "";
$aba4active = "";
$aba5active = "";
$aba6active = "";
$aba7active = "";
$aba1show = "";
$aba2show = "";
$aba3show = "";
$aba4show = "";
$aba5show = "";
$aba6show = "";
$aba7show = "";
if ($aba == 1) {
    $aba1active = " active ";
    $aba1show = " show active ";
};
if ($aba == 2) {
    $aba2active = " active ";
    $aba2show = " show active ";
};
if ($aba == 3) {
    $aba3active = " active ";
    $aba3show = " show active ";
};
if ($aba == 4) {
    $aba4active = " active ";
    $aba4show = " show active ";
};
if ($aba == 5) {
    $aba5active = " active ";
    $aba5show = " show active ";
};
if ($aba == 6) {
    $aba6active = " active ";
    $aba6show = " show active ";
};
if ($aba == 7) {
    $aba7active = " active ";
    $aba7show = " show active ";
};
if ($aba == 8) {
    $aba8active = " active ";
    $aba8show = " show active ";
};
if ($aba == 9) {
    $aba9active = " active ";
    $aba9show = " show active ";
};
if ($aba == 10) {
    $aba10active = " active ";
    $aba10show = " show active ";
};
?>

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="section-title" data-aos="fade-up">

                <h1><b><?php echo $obrigatorio->getNomecompleto() ?></b></h1>
                <br>

                <div class="d-flex">
                    <a class="me-3" href="mpdf/ficha_obrigatorio.php?crip=<?php echo $crip_url ?>&id_obrigatorio=<?php echo $id_obrigatorio ?>" target="_blank">
                        <label>Ficha</label>
                        <img class="p-0" src="imagens/pdf.png" height="60px"></a>
                    <?php
                    if ($obrigatorio->getDataComparecimentoSelecaoGeral() != null || $obrigatorio->getJise() != null || $obrigatorio->getCidJise() != null) {
                    ?>
                        <a class="me-3" href="mpdf/relatorio_jise.php?crip=<?php echo $crip_url ?>&id_obrigatorio=<?php echo $id_obrigatorio ?>" target="_blank">
                            <label>JISE</label>
                            <img class="p-0" src="imagens/pdf.png" height="60px"></a>
                    <?php
                    }
                    if ($obrigatorio->getDataJisr() != null || $obrigatorio->getJisr() != null || $obrigatorio->getCidJisr() != null) {
                    ?>
                        <a href="mpdf/relatorio_jisr.php?crip=<?php echo $crip_url ?>&id_obrigatorio=<?php echo $id_obrigatorio ?>" target="_blank">
                            <label>JISR</label>
                            <img class="p-0" src="imagens/pdf.png" height="60px"></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php if ($_SESSION['mensagem'] != null): ?>
    <center>
        <font size="4" color="green" size="4px"><?php echo $_SESSION['mensagem'];
                                                $_SESSION['mensagem'] = null; ?></font>
    </center>
<?php endif; ?>

<section id="contact" class="contact">
    <div class="container card">
        <div class="row  justify-content-center">

            <!-- ABAS -->

            <ul class="nav nav-tabs" id="myTabs">
                <li class="nav-item">
                    <a class="nav-link <?= $aba1active ?>" style="color: #495057;" data-toggle="tab" href="#aba1">Identificação</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $aba2active ?>" style="color: #495057;" data-toggle="tab" href="#aba2">FISEMI</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $aba3active ?>" style="color: #495057;" data-toggle="tab" href="#aba3">Justiça</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $aba4active ?>" style="color: #495057;" data-toggle="tab" href="#aba4">Adiamento</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $aba5active ?>" style="color: #495057;" data-toggle="tab" href="#aba5">Distribuição</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $aba10active ?>" style="color: #495057;" data-toggle="tab" href="#aba10">Incorporação</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $aba6active ?>" style="color: #495057;" data-toggle="tab" href="#aba6">Ficha Completa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $aba7active ?>" style="color: #495057;" data-toggle="tab" href="#aba7">Arquivos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $aba6active ?>" style="color: #495057;" data-toggle="tab" href="#aba9">Ofício</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" style="color: #495057;" data-toggle="tab" href="#aba8">Apagar</a>
                </li>
            </ul>

            <div class="tab-content">
                <br>

                <div class="tab-pane fade <?= $aba1show ?>" id="aba1">
                    <?php include_once 'obrigatorio_identificacao.php' ?>
                </div>

                <div class="tab-pane fade <?= $aba2show ?>" id="aba2">
                    <?php include_once 'obrigatorio_fisemi.php' ?>
                </div>
                <div class="tab-pane fade <?= $aba3show ?>" id="aba3">
                    <?php include_once 'obrigatorio_justica.php' ?>
                </div>
                <div class="tab-pane fade <?= $aba4show ?>" id="aba4">
                    <?php include_once 'obrigatorio_adiamento.php' ?>
                </div>
                <div class="tab-pane fade <?= $aba5show ?>" id="aba5">
                    <?php include_once 'obrigatorio_distribuicao.php' ?>
                </div>

                <div class="tab-pane fade <?= $aba10show ?>" id="aba10">
                    <?php include_once 'obrigatorio_incorporacao.php' ?>
                </div>
                <div class="tab-pane fade <?= $aba6show ?>" id="aba6">
                    <?php include_once 'obrigatorio_ficha.php' ?>
                </div>
                <div class="tab-pane fade <?= $aba7show ?>" id="aba7">
                    <?php include_once 'obrigatorio_arquivos.php' ?>
                </div>

                <div class="tab-pane fade <?= $aba9show ?>" id="aba9">
                    <?php include_once 'obrigatorio_oficio.php' ?>
                </div>

                <br>

                <div class="card" id="aba8">
                    <center>
                        <div class="text-center text-success">
                            <h3 color="green">APAGAR</h3>
                        </div>

                        <br>

                        <a href="controller/obrigatorio_apaga.php?crip=<?php echo $crip_url ?>&id_obrigatorio=<?php echo $id_obrigatorio ?>"><i class="fas fa-trash-alt fs-2"></i></a>
                        <br>
                    </center>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
include_once 'footer.php';
?>