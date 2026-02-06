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
$om_operador = $_SESSION['id_om_smo'];

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
                <h1><i class="fas fa-users"></i> Distribuídos OM 1ª Fase</h1>
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
                                Quando a visualização for liberada, você poderá ver os candidatos designados e seus dados nesta página.
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

            <div class="col-lg-6 form-group">
                <label class="form-label fw-semibold">Distribuição</label>
                <select name="distribuicao_filtro" class="form-select chosen-select" onchange="this.form.submit()">
                    <option value="">Selecione a Distribuição</option>
                    <option <?php if ($distribuicao_filtro == "DESIGNADO - 1ª Distribuição") echo "selected"; ?> value="DESIGNADO - 1ª Distribuição">DESIGNADO - 1ª Distribuição</option>
                    <option <?php if ($distribuicao_filtro == "DESIGNADO - 2ª Distribuição") echo "selected"; ?> value="DESIGNADO - 2ª Distribuição">DESIGNADO - 2ª Distribuição</option>
                    <option <?php if ($distribuicao_filtro == "MAJORADO - 1ª Distribuição") echo "selected"; ?> value="MAJORADO - 1ª Distribuição">MAJORADO - 1ª Distribuição</option>
                    <option <?php if ($distribuicao_filtro == "MAJORADO - 2ª Distribuição") echo "selected"; ?> value="MAJORADO - 2ª Distribuição">MAJORADO - 2ª Distribuição</option>
                    <option <?php if ($distribuicao_filtro == "EXCESSO CONTINGENTE") echo "selected"; ?> value="EXCESSO CONTINGENTE">EXCESSO CONTINGENTE</option>
                    <option <?php if ($distribuicao_filtro == "MARINHA") echo "selected"; ?> value="MARINHA">MARINHA</option>
                    <option <?php if ($distribuicao_filtro == "FORÇA AÉREA") echo "selected"; ?> value="FORÇA AÉREA">FORÇA AÉREA</option>
                </select>
            </div>

            <div class="col-lg-6 form-group">
                <label class="form-label fw-semibold">Resultado Revisão Médica</label>
                <select name="resultado_revisao_medica_filtro" class="form-select chosen-select" onchange="this.form.submit()">
                    <option value="">Selecione a Opção</option>
                    <option <?php if ($resultado_revisao_medica_filtro == "APTO") echo "selected"; ?> value="APTO">APTO</option>
                    <option <?php if ($resultado_revisao_medica_filtro == "INAPTO") echo "selected"; ?> value="INAPTO">INAPTO</option>
                    <option <?php if ($resultado_revisao_medica_filtro == "NÃO COMPARECEU") echo "selected"; ?> value="NÃO COMPARECEU">NÃO COMPARECEU</option>
                </select>
            </div>

            <div class="col-12 mt-3">
                <button type="button" id="btn-exportar-excel" class="btn btn-primary">
                    <i class="fas fa-file-excel me-2"></i>Exportar Excel
                </button>
            </div>
        </div>
    </form>

    <div class="mt-3">
        <table id="tabela_dinamica" class="table table-hover" style="width:100%; font-size: 11px;">
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

                        $classe_cor = '';
                        if ($obrigatorio->getSituacaoMilitar() != null) {
                            if (strpos($obrigatorio->getSituacaoMilitar(), "Quite") !== false)
                                $classe_cor = 'table-success';
                            else
                                $classe_cor = 'table-warning';
                        }

                        // Situações militares a ignorar
                        $situacoes_ignorar = [
                            null,
                            "Em Dia - JUDICIAL",
                            "Em Débito - REFRATÁRIO",
                            "Em Débito - INSUBMISSO",
                            "Em Dia - B1 - INSP SAU - RETORNAR PRÓXIMA SELEÇÃO",
                            "Quite SMO - EXCESSO - CONTINGENTE",
                            "Quite SMO - EXCESSO - INCAPAZ SAÚDE",
                            "Quite SMO - DESOBRIGADO - MAIOR 38 ANOS",
                            "Quite SMO - DESOBRIGADO - JÁ RESERVISTA",
                            "Quite SMO - DESOBRIGADO - NATURALIZADO",
                            "Quite SMO - CONVOCADO"
                        ];
                        if (in_array($obrigatorio->getSituacaoMilitar(), $situacoes_ignorar)) continue;

                        // Distribuições a ignorar
                        $distribuicoes_ignorar = [null, "EXCESSO CONTINGENTE", "MARINHA", "FORÇA AÉREA"];
                        if (in_array($obrigatorio->getDistribuicao(), $distribuicoes_ignorar)) continue;

                        // Verificar JISE
                        if ($obrigatorio->getJise() == null) continue;

                        // Verificar JISR e JISE inaptos
                        $jise_jisr_inaptos = ["B1", "B2", "C"];
                        if ($obrigatorio->getJisr() == null && in_array($obrigatorio->getJise(), $jise_jisr_inaptos)) continue;
                        if (in_array($obrigatorio->getJisr(), $jise_jisr_inaptos)) continue;

                        $id = $obrigatorio->getId();
                        $link = "obrigatorio_om.php?crip=$criptografia&id_obrigatorio=$id";

                        echo "<tr class='$classe_cor'>
                            <td><a href='$link' class='text-dark'>" . htmlspecialchars($obrigatorio->getNomeCompleto()) . "</a></td>
                            <td><a href='$link' class='text-dark'>" . htmlspecialchars($obrigatorio->getCPF()) . "</a></td>
                            <td>" . htmlspecialchars($obrigatorio->getFormacao() . " / " . $obrigatorio->getAnoFormacao()) . "</td>
                            <td>" . htmlspecialchars($obrigatorio->getNomeInstitutoEnsino()) . "</td>
                            <td>" . htmlspecialchars($obrigatorio->imprime_ano_res_mais_recente()) . "</td>
                            <td>" . htmlspecialchars($obrigatorio->imprime_ultima_especialidade()) . "</td>
                            <td>" . htmlspecialchars($obrigatorio->getDistribuicao()) . "</td>
                            <td>" . htmlspecialchars($obrigatorio->getResultadoRevisaoMedicaComplementar()) . "</td>
                            <td>" . htmlspecialchars($obrigatorio->getIncorporacao()) . "</td>
                        </tr>";
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
            "lengthMenu": [50, 100, 200, 500],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
            }
        });

        // Botão Exportar Excel
        $('#btn-exportar-excel').click(function() {
            // Monta a URL com os filtros atuais
            var params = new URLSearchParams();

            var distribuicao = $('select[name="distribuicao_filtro"]').val();
            var resultado = $('select[name="resultado_revisao_medica_filtro"]').val();

            if (distribuicao) params.append('distribuicao_filtro', distribuicao);
            if (resultado) params.append('resultado_revisao_medica_filtro', resultado);

            // Abre a URL de exportação
            window.location.href = 'controller/distribuidos_om_export_excel.php?' + params.toString();
        });
    });
</script>

<?php endif; ?>

<?php
include_once 'footer.php';
?>