<?php
include_once 'header.php';
include_once 'dao/conecta_banco.php';
include_once 'models/Obrigatorio.php';
include_once 'models/Om.php';
include_once 'dao/ObrigatorioDAO.php';
include_once 'dao/AuxiliarDAO.php';
include_once 'dao/ConfiguracaoDAO.php';

// Verificar se a visualização está liberada
$configuracaoDAO = new ConfiguracaoDAO($conexao);
$visualizacao_liberada = $configuracaoDAO->isAtivo('visualizacao_distribuidos_om_1_fase');

$ObrigatorioDAO = new ObrigatorioDAO($conexao);
$todos_obrigatorios_da_om = $visualizacao_liberada ? $ObrigatorioDAO->findAllAtivosdaOM() : [];

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

$resultado_revisao_medica_filtro = null;
if (isset($_GET['resultado_revisao_medica_filtro'])) $resultado_revisao_medica_filtro = filtra_campo_get('resultado_revisao_medica_filtro');
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
    <div class="container mt-4">
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center justify-content-center" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div><?php echo $_SESSION['mensagem']; $_SESSION['mensagem'] = null; ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    </div>
<?php endif; ?>

<?php if (!$visualizacao_liberada): ?>
    <!-- Mensagem de visualização não liberada -->
    <section id="contact" class="contact">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-lg border-0">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-lock fa-5x text-warning"></i>
                            </div>
                            <h3 class="text-dark mb-3">Visualização Ainda Não Liberada</h3>
                            <p class="text-muted fs-5 mb-4">
                                A visualização dos candidatos distribuídos para sua OM ainda não foi liberada pela Seção de Serviço Militar.
                            </p>
                            <p class="text-muted mb-4">
                                Por favor, aguarde a liberação ou entre em contato com a administração para mais informações.
                            </p>
                            <hr class="my-4">
                            <p class="text-muted small mb-0">
                                <i class="fas fa-info-circle me-1"></i>
                                Quando a visualização for liberada, você poderá ver os candidatos e realizar a revisão médica nesta página.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php else: ?>
    <!-- Conteúdo normal quando liberado -->
<section id="contact" class="contact">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="get">
        <div class="row">
            <div class="col-md-12 form-group">
                <label class="form-label fw-semibold">Resultado Revisão Médica</label>
                <select name="resultado_revisao_medica_filtro" class="form-control" onchange="this.form.submit()">
                    <option value="">Selecione a Opção</option>
                    <option <?php if ($resultado_revisao_medica_filtro == "APTO") echo "selected"; ?> value="APTO">APTO</option>
                    <option <?php if ($resultado_revisao_medica_filtro == "INAPTO") echo "selected"; ?> value="INAPTO">INAPTO</option>
                    <option <?php if ($resultado_revisao_medica_filtro == "NÃO COMPARECEU") echo "selected"; ?> value="NÃO COMPARECEU">NÃO COMPARECEU</option>
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

                        if ($resultado_revisao_medica_filtro != null && $resultado_revisao_medica_filtro != $obrigatorio->getResultadoRevisaoMedicaComplementar()) continue;

                        $classe_cor = '';
                        if ($obrigatorio->getSituacaoMilitar() != null) {
                            if (strpos($obrigatorio->getSituacaoMilitar(), "Quite") !== false)
                                $classe_cor = 'table-success';
                            else
                                $classe_cor = 'table-warning';
                        }

                        echo "<tr class='$classe_cor'>
                            <td><input type='checkbox' name='ids[]' value='" . $obrigatorio->getId() . "'></td>
                            <td><a href='obrigatorio_om.php?crip=$criptografia&id_obrigatorio=" . $obrigatorio->getId() . "' class='text-dark'>" . htmlspecialchars($obrigatorio->getNomeCompleto()) . "</a></td>
                            <td><a href='obrigatorio_om.php?crip=$criptografia&id_obrigatorio=" . $obrigatorio->getId() . "' class='text-dark'>" . htmlspecialchars($obrigatorio->getCPF()) . "</a></td>
                            <td>" . htmlspecialchars($obrigatorio->getFormacao() . " / " . $obrigatorio->getAnoFormacao()) . "</td>
                            <td>" . htmlspecialchars($obrigatorio->getNomeInstitutoEnsino()) . "</td>
                            <td>" . htmlspecialchars($obrigatorio->imprime_ano_res_mais_recente()) . "</td>
                            <td>" . htmlspecialchars($obrigatorio->imprime_ultima_especialidade()) . "</td>
                            <td>" . htmlspecialchars($obrigatorio->imprimeDataNascimento()) . "</td>
                            <td>" . htmlspecialchars($obrigatorio->getDistribuicao()) . "</td>
                            <td>" . htmlspecialchars($obrigatorio->getResultadoRevisaoMedicaComplementar()) . "</td>
                        </tr>";
                    }
                ?>
            </tbody>
        </table>

        <br>

        <div class="row">
            <div class="col-md-4 form-group">
                <label class="form-label fw-semibold">Resultado Revisão Médica</label>
                <select name="resultado_revisao_medica_complementar" class="form-control">
                    <option value="">Selecione a Opção</option>
                    <option value="APTO">APTO</option>
                    <option value="INAPTO">INAPTO</option>
                    <option value="NÃO COMPARECEU">NÃO COMPARECEU</option>
                </select>
            </div>

            <div class="col-md-4 form-group">
                <label class="form-label fw-semibold">ISGRev - MPGu</label>
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
                <label class="form-label fw-semibold">CID Revisão Médica (novo)</label>
                <textarea placeholder="CID Revisão Médica" name="cid_revisao_medica" class="form-control"></textarea>
            </div>

            <input name="crip" hidden value="<?php echo hash('sha256', $_SESSION['chave'] . "atualiza"); ?>">
            <br>
            <br>
            <br>
            <div class="text-center" width="100%">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Atualizar
                </button>
            </div>
        </div>
    </form>
</section>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tabela_dinamica').DataTable({
            "aaSorting": [],
            "pageLength": 25,
            "lengthMenu": [25, 50, 100, 200, 500],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
            }
        });
    });
</script>

<?php endif; ?>

<?php
include_once 'footer.php';
?>
