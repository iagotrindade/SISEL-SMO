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

?>

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="section-title" data-aos="fade-up">

                <h1><?php echo $obrigatorio->getNomecompleto() ?></h1>

                <a href="mpdf/ficha_obrigatorio.php?crip=<?php echo $crip_url ?>&id_obrigatorio=<?php echo $id_obrigatorio ?>" target="_blank" class="text-decoration-none" <?php if ($usuario->getPerfil() != 'admin') echo 'hidden' ?>>
                    <span class="me-1">Ficha</span>
                    <img src="imagens/pdf.png" height="50px" alt="PDF Ficha"></a>

                <?php
                if ($obrigatorio->getDataComparecimentoSelecaoGeral() != null || $obrigatorio->getJise() != null || $obrigatorio->getCidJise() != null) {
                ?>
                    <a href="mpdf/relatorio_jise.php?crip=<?php echo $crip_url ?>&id_obrigatorio=<?php echo $id_obrigatorio ?>" target="_blank" class="text-decoration-none" <?php if ($usuario->getPerfil() != 'admin') echo 'hidden' ?>>
                        <span class="me-1">JISE</span>
                        <img src="imagens/pdf.png" height="50px" alt="PDF JISE"></a>
                <?php
                }
                if ($obrigatorio->getDataJisr() != null || $obrigatorio->getJisr() != null || $obrigatorio->getCidJisr() != null) {
                ?>
                    <a href="mpdf/relatorio_jisr.php?crip=<?php echo $crip_url ?>&id_obrigatorio=<?php echo $id_obrigatorio ?>" target="_blank" class="text-decoration-none" <?php if ($usuario->getPerfil() != 'admin') echo 'hidden' ?>>
                        <span class="me-1">JISR</span>
                        <img src="imagens/pdf.png" height="50px" alt="PDF JISR"></a>
                <?php } ?>

            </div>
        </div>
    </section>
</main>

<?php if (isset($_SESSION['mensagem']) && $_SESSION['mensagem'] != null) : ?>
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            <?php echo $_SESSION['mensagem'];
            $_SESSION['mensagem'] = null; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    </div>
<?php endif; ?>

<section id="contact" class="contact">
    <div class="container card">
        <?php include_once 'obrigatorio_ficha.php' ?>
    </div>
</section>

<section id="contact" class="contact">
    <div class="container card">
        <div class="row  justify-content-center">
            <form action="controller/obrigatorio_edita_revisao_medica.php" method="post" role="form">
                <div class="row form">
                    <div class="text-center text-success">
                        <h3>REVISÃO MÉDICA - OM 1ª FASE</h3>
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="form-label fw-semibold">Data Revisão Médica (novo) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="data_revisao_medica" value="<?php echo $obrigatorio->imprimeData_revisao_medica() ?>" placeholder="Data Revisão Médica">
                    </div>

                    <div class="col-md-4 form-group">
                        <label class="form-label fw-semibold">Resultado Revisão Médica</label>
                        <select name="resultado_revisao_medica_complementar" class="form-control">
                            <option value="">Selecione a Opção</option>
                            <option <?php if ($obrigatorio->getResultadoRevisaoMedicaComplementar() == "APTO") echo " selected " ?> value="APTO">APTO</option>
                            <option <?php if ($obrigatorio->getResultadoRevisaoMedicaComplementar() == "INAPTO") echo " selected " ?> value="INAPTO">INAPTO</option>
                            <option <?php if ($obrigatorio->getResultadoRevisaoMedicaComplementar() == "NÃO COMPARECEU") echo " selected " ?> value="NÃO COMPARECEU">NÃO COMPARECEU</option>
                        </select>

                    </div>
                    <div class="col-md-4 form-group">
                        <label class="form-label fw-semibold">CID Revisão Médica (novo) <span class="text-danger">*</span></label>
                        <textarea placeholder="CID Revisão Médica" name="cid_revisao_medica" class="form-control"><?php echo $obrigatorio->getCid_revisao_medica() ?></textarea>
                    </div>

                    <div class="col-md-12 form-group">
                        <label class="form-label fw-semibold">Observação Revisão Médica (novo) <span class="text-danger">*</span></label>
                        <textarea placeholder="OBS Revisão Médica" name="observacao_revisao_medica" class="form-control"><?php echo $obrigatorio->getObs_revisao_medica() ?></textarea>
                    </div>
                    <input hidden type="text" value="<?= $id_obrigatorio ?>" name="id_obrigatorio">
                    <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave'] . "obrigatorio"); ?>">
                </div>

                <div class="text-center">
                    <br>
                    <button type="submit" class="btn btn-primary w-100">SALVAR - Revisão Médica</button>
                </div>

            </form>
        </div>
    </div>
</section>

<section id="contact" class="contact">
    <div class="container card">
        <div class="row  justify-content-center">
            <form action="controller/obrigatorio_edita_isgr.php" method="post" role="form">
                <div class="row form">
                    <div class="text-center text-success">
                        <h3>ISGRev</h3>
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="form-label fw-semibold">ISGRev</label>
                        <select name="resultado_isgr" class="form-control">
                            <option value="">Selecione a Opção</option>
                            <option <?php if ($obrigatorio->getResultadoIsgr() == "NÃO é o caso") echo " selected " ?> value="NÃO é o caso">NÃO é o caso</option>
                            <option <?php if ($obrigatorio->getResultadoIsgr() == "A") echo " selected " ?> value="A">A</option>
                            <option <?php if ($obrigatorio->getResultadoIsgr() == "B1") echo " selected " ?> value="B1">B1</option>
                            <option <?php if ($obrigatorio->getResultadoIsgr() == "B2") echo " selected " ?> value="B2">B2</option>
                            <option <?php if ($obrigatorio->getResultadoIsgr() == "C") echo " selected " ?> value="C">C</option>
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <label class="form-label fw-semibold">Data ISGRev <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="data_isgr" value="<?php echo $obrigatorio->imprimeData_isgr() ?>" placeholder="Data ISGR">
                    </div>

                    <div class="col-md-4 form-group">
                        <label class="form-label fw-semibold">CID ISGRev <span class="text-danger">*</span></label>
                        <textarea placeholder="CID Revisão Médica" name="cid_isgr" class="form-control"><?php echo $obrigatorio->getCid_isgr() ?></textarea>
                    </div>

                    <div class="col-md-12 form-group">
                        <label class="form-label fw-semibold">Observação ISGRev <span class="text-danger">*</span></label>
                        <textarea placeholder="OBS ISGRev" name="observacao_isgr" class="form-control"><?php echo $obrigatorio->getObservacao_isgr() ?></textarea>
                    </div>
                    <input hidden type="text" value="<?= $id_obrigatorio ?>" name="id_obrigatorio">
                    <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave'] . "obrigatorio"); ?>">
                </div>

                <div class="text-center">
                    <br>
                    <button type="submit" class="btn btn-primary w-100">SALVAR - ISGRev</button>
                </div>
            </form>
        </div>
    </div>
</section>

<section id="contact" class="contact">
    <div class="container card">
        <div class="row  justify-content-center">
            <form action="controller/obrigatorio_edita_incorporacao_om.php" method="post" role="form">
                <div class="row form">
                    <div class="text-center text-success">
                        <h3>INCORPORAÇÃO</h3>
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="form-label fw-semibold">Incorporação - Compareceu? (novo) <span class="text-danger">*</span></label>
                        <select name="incorporacao" class="form-control">
                            <option value="">Selecione a Opção</option>
                            <option <?php if ($obrigatorio->getIncorporacao() == 'SIM') echo 'selected' ?> value="SIM">SIM</option>
                            <option <?php if ($obrigatorio->getIncorporacao() == 'NAO') echo 'selected' ?> value="NAO">NÃO</option>
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <label class="form-label fw-semibold">Data Incorporação</label>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->imprimeDataIncorporacao() ?>" name="data_incorporacao" placeholder="Data de Incorporação">
                    </div>

                    <div class="col-md-4 form-group">
                        <label class="form-label fw-semibold">OM 1ª Fase</label>
                        <select name="id_om_1_fase" class="form-control">
                            <option value="">Selecione a Opção</option>
                            <?php
                            foreach ($todas_oms_1_fase as $value) {
                                if ($value['nome'] == $obrigatorio->getOm1Fase()->getNome())
                                    echo "<option value='" . $value['id'] . "' selected>" . $value['abreviatura'] . "</option>";
                                else
                                    echo "<option value='" . $value['id'] . "' >" . $value['abreviatura'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-12 form-group">
                        <label class="form-label fw-semibold">BAR Incorporação - OM 1ª Fase (novo) <span class="text-danger">*</span></label>
                        <textarea placeholder="BAR - OM 1ª Fase" name="bar_om_1_fase" class="form-control"><?php echo $obrigatorio->getBar_om_1_fase() ?></textarea>
                    </div>

                    <div class="col-md-4 form-group" id="observacoes">
                        <label class="form-label fw-semibold">OM 2ª Fase</label>
                        <select name="om_2_fase" class="form-control">
                            <option value="">OM 2ª Fase</option>
                            <?php
                            foreach ($todas_oms_2_fase as $value) {
                                if ($value['abreviatura'] == $obrigatorio->getOm2Fase())
                                    echo "<option value='" . $value['abreviatura'] . "' selected>" . $value['abreviatura'] . "</option>";
                                else
                                    echo "<option value='" . $value['abreviatura'] . "' >" . $value['abreviatura'] . "</option>";
                            }
                            ?>
                        </select>
                        <input hidden type="text" value="<?= $id_obrigatorio ?>" name="id_obrigatorio">
                        <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave'] . "obrigatorio"); ?>">
                    </div>
                </div>
                <br>
                <button type="submit" class="btn btn-primary w-100">SALVAR - Incorporação</button>
            </form>
        </div>
    </div>
</section>

<section id="contact" class="contact">
    <div class="container card">
        <div class="row  justify-content-center">
            <div class="row form">
                <div class="text-center text-success">
                    <h3>ARQUIVOS</h3>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">

                        <form action="controller/arquivo_adiciona_opom.php" method="POST" enctype="multipart/form-data" style="width: 100%">
                            <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave'] . "arquivo_obrigatorio"); ?>">
                            <input name="id_obrigatorio" hidden value="<?php echo  $obrigatorio->getId() ?>">
                            <input hidden type="text" value="<?php echo $crip_url ?>" name="crip_url">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="nome_arquivo" placeholder="Nome do arquivo">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="file" class="form-control" name="arquivo">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary w-100">ENVIAR ARQUIVO</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-12">

                    <br>
                    <hr>
                    <br>
                    <?php
                    if (!empty($arquivos)) {
                        foreach ($arquivos as $arquivo) {

                            $crip = hash('sha256', $arquivo->getNome() . "crip_download_arquivo");
                    ?>
                            <div class="col-md-12">
                                <form action="controller/arquivo_apaga_opom.php" method="POST" style="width: 100%">
                                    <input name="crip" hidden value="<?php echo  hash('sha256', $arquivo->getId() . "criptografia_arquivo"); ?>">
                                    <input name="id_arquivo" hidden value="<?php echo $arquivo->getId() ?>">
                                    <input name="id_obrigatorio" hidden value="<?php echo $id_obrigatorio ?>">
                                    <input name="crip_url" hidden value="<?php echo $crip_url ?>">
                                    <input name="chave" hidden value="<?php echo $_SESSION['chave'] ?>">

                                    <a target='_blank' href='controller/arquivo_download.php?crip=<?php echo $crip ?>&id_arquivo=<?php echo $arquivo->getId() ?>'>

                                        <img src='imagens/pdf.png' height='60px'>
                                        <?php echo $arquivo->getLabel() ?>
                                        &nbsp;&nbsp;&nbsp;
                                    </a>
                                    <button type="submit" <?php if ($usuario->getPerfil() != "admin") echo "hidden" ?>>Apagar</button>
                                    <br>
                                    <br>
                                    <br>
                                </form>
                            </div>
                    <?php
                        }
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
</section>
<?php
include_once 'footer.php';
?>