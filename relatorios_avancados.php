<?php
include_once 'header.php';
include_once 'dao/conecta_banco.php';
include_once 'models/Obrigatorio.php';
include_once 'models/Om.php';
include_once 'dao/ObrigatorioDAO.php';
include_once 'dao/OmDAO.php';
include_once 'dao/AuxiliarDAO.php';

if (!isset($_SESSION['id_usuario_smo'])) {
    erro($BASE_URL, 2, 63216754, $pagina_atual, "usuario_nao_logado", "Página não encontrada!");
    exit();
}

if ($_SESSION['perfil_smo'] != 'admin') {
    header("Location: index.php");
    exit();
}

$auxiliar = new AuxiliarDAO($conexao);
$omDAO = new OmDAO($conexao);
$obrigatorioDAO = new ObrigatorioDAO($conexao);

// Listas para filtros
$lista_oms = $omDAO->findAllAtivos();
$lista_especialidades = $auxiliar->findAllEspecialidades();

$anoAtual = (int)date('Y');
?>

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h1><i class="fas fa-chart-bar me-3"></i>Relatórios Avançados</h1>
                <p class="text-secondary mt-2">Gere relatórios detalhados com filtros avançados</p>
            </div>
        </div>
    </section>
</main>

<style>
    /* Estilos das abas de relatórios - Compatível com tema claro e escuro */
    #reportTabs.nav-tabs {
        border-bottom: 2px solid #006400 !important;
        background: var(--gray-dark, #e9ecef) !important;
        padding: 0.5rem !important;
        border-radius: 8px 8px 0 0 !important;
    }
    #reportTabs .nav-item {
        margin-bottom: 0 !important;
    }
    #reportTabs .nav-link {
        color: var(--text-primary, #333) !important;
        background: transparent !important;
        border: 2px solid transparent !important;
        border-radius: 8px 8px 0 0 !important;
        padding: 0.75rem 1.25rem !important;
        font-weight: 500 !important;
        transition: all 0.3s ease !important;
        margin-right: 4px !important;
    }
    #reportTabs .nav-link:hover {
        color: #006400 !important;
        background: rgba(0, 100, 0, 0.15) !important;
        border-color: rgba(0, 100, 0, 0.3) !important;
    }
    #reportTabs .nav-link.active {
        color: #ffffff !important;
        background: #006400 !important;
        border-color: #006400 !important;
    }
    #reportTabs .nav-link i {
        color: #006400 !important;
    }
    #reportTabs .nav-link.active i {
        color: #ffffff !important;
    }

    /* Conteúdo das abas */
    #reportTabsContent.tab-content {
        background: var(--gray-darker, #f8f9fa) !important;
        border-radius: 0 0 8px 8px !important;
        padding: 1rem !important;
    }
    #reportTabsContent .card {
        border: none !important;
        box-shadow: none !important;
        background: var(--gray-darker, #f8f9fa) !important;
    }
    #reportTabsContent .card-header {
        background: var(--gray-dark, #e9ecef) !important;
        border-bottom: 1px solid rgba(0, 100, 0, 0.2) !important;
        border-radius: 8px 8px 0 0 !important;
    }
    #reportTabsContent .card-header h5 {
        color: var(--text-primary, #333) !important;
    }
    #reportTabsContent .card-header h5 i {
        color: #006400 !important;
    }
    #reportTabsContent .card-header small {
        color: var(--text-secondary, #6c757d) !important;
    }
    #reportTabsContent .card-body {
        background: var(--gray-darker, #f8f9fa) !important;
        color: var(--text-primary, #333) !important;
    }
    #reportTabsContent .form-label {
        color: var(--text-primary, #333) !important;
    }
    #reportTabsContent .form-check-label {
        color: var(--text-primary, #333) !important;
    }
</style>

<section class="section-bg" style="padding: 2rem 0;">
    <div class="container">

        <!-- Navegação por abas -->
        <ul class="nav nav-tabs mb-4" id="reportTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="consolidado-tab" data-bs-toggle="tab" data-bs-target="#consolidado" type="button" role="tab">
                    <i class="fas fa-file-alt me-2"></i>Consolidado
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="por-om-tab" data-bs-toggle="tab" data-bs-target="#por-om" type="button" role="tab">
                    <i class="fas fa-building me-2"></i>Por OM
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="comparativo-tab" data-bs-toggle="tab" data-bs-target="#comparativo" type="button" role="tab">
                    <i class="fas fa-balance-scale me-2"></i>Comparativo
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="exportar-tab" data-bs-toggle="tab" data-bs-target="#exportar" type="button" role="tab">
                    <i class="fas fa-download me-2"></i>Exportar Dados
                </button>
            </li>
        </ul>

        <div class="tab-content" id="reportTabsContent">

            <!-- Tab: Relatório Consolidado -->
            <div class="tab-pane fade show active" id="consolidado" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-file-pdf me-2" style="color: #006400;"></i>Relatório Consolidado Anual</h5>
                        <small class="text-muted">Relatório completo com estatísticas, gráficos e listagem detalhada</small>
                    </div>
                    <div class="card-body">
                        <form id="formConsolidado" action="mpdf/relatorio_consolidado.php" method="POST" target="_blank">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Ano</label>
                                    <select name="ano" class="form-select" required>
                                        <?php for ($a = $anoAtual; $a >= 2020; $a--): ?>
                                            <option value="<?= $a ?>" <?= $a == $anoAtual ? 'selected' : '' ?>><?= $a ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Período</label>
                                    <select name="periodo" class="form-select">
                                        <option value="ano">Ano Completo</option>
                                        <option value="1sem">1º Semestre</option>
                                        <option value="2sem">2º Semestre</option>
                                        <option value="1tri">1º Trimestre</option>
                                        <option value="2tri">2º Trimestre</option>
                                        <option value="3tri">3º Trimestre</option>
                                        <option value="4tri">4º Trimestre</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Formato</label>
                                    <select name="formato" class="form-select">
                                        <option value="pdf">PDF</option>
                                        <option value="excel">Excel (XLSX)</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Seções do Relatório</label>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="secoes[]" value="resumo" id="secaoResumo" checked>
                                                <label class="form-check-label" for="secaoResumo">Resumo Executivo</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="secoes[]" value="especialidades" id="secaoEspecialidades" checked>
                                                <label class="form-check-label" for="secaoEspecialidades">Por Especialidade</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="secoes[]" value="situacao" id="secaoSituacao" checked>
                                                <label class="form-check-label" for="secaoSituacao">Situação Militar</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="secoes[]" value="oms" id="secaoOMs" checked>
                                                <label class="form-check-label" for="secaoOMs">Distribuição por OM</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="secoes[]" value="revisao" id="secaoRevisao" checked>
                                                <label class="form-check-label" for="secaoRevisao">Revisão Médica</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="secoes[]" value="mensal" id="secaoMensal" checked>
                                                <label class="form-check-label" for="secaoMensal">Evolução Mensal</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="secoes[]" value="listagem" id="secaoListagem">
                                                <label class="form-check-label" for="secaoListagem">Listagem Completa</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input name="crip" type="hidden" value="<?= hash('sha256', $_SESSION['chave'] . "criptografia"); ?>">

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-file-pdf me-2"></i>Gerar Relatório Consolidado
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tab: Relatório por OM -->
            <div class="tab-pane fade" id="por-om" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-building me-2" style="color: #006400;"></i>Relatório por Organização Militar</h5>
                        <small class="text-muted">Estatísticas detalhadas de uma ou mais OMs</small>
                    </div>
                    <div class="card-body">
                        <form id="formPorOM" action="mpdf/relatorio_por_om.php" method="POST" target="_blank">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Organizações Militares</label>
                                    <select name="oms[]" class="form-select chosen-select" multiple data-placeholder="Selecione as OMs...">
                                        <option value="todas">Todas as OMs</option>
                                        <?php foreach ($lista_oms as $om): ?>
                                            <option value="<?= $om->getId() ?>"><?= htmlspecialchars($om->getAbreviatura() . ' - ' . $om->getNome()) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Ano</label>
                                    <select name="ano" class="form-select" required>
                                        <?php for ($a = $anoAtual; $a >= 2020; $a--): ?>
                                            <option value="<?= $a ?>" <?= $a == $anoAtual ? 'selected' : '' ?>><?= $a ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Formato</label>
                                    <select name="formato" class="form-select">
                                        <option value="pdf">PDF</option>
                                        <option value="excel">Excel (XLSX)</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Incluir no Relatório</label>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="incluir[]" value="estatisticas" id="omEstatisticas" checked>
                                                <label class="form-check-label" for="omEstatisticas">Estatísticas Gerais</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="incluir[]" value="especialidades" id="omEspecialidades" checked>
                                                <label class="form-check-label" for="omEspecialidades">Por Especialidade</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="incluir[]" value="listagem" id="omListagem" checked>
                                                <label class="form-check-label" for="omListagem">Listagem de Designados</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="incluir[]" value="comparativo" id="omComparativo">
                                                <label class="form-check-label" for="omComparativo">Comparativo entre OMs</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input name="crip" type="hidden" value="<?= hash('sha256', $_SESSION['chave'] . "criptografia"); ?>">

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-building me-2"></i>Gerar Relatório por OM
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tab: Relatório Comparativo -->
            <div class="tab-pane fade" id="comparativo" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-balance-scale me-2 text-info"></i>Relatório Comparativo</h5>
                        <small class="text-muted">Compare estatísticas entre diferentes anos ou períodos</small>
                    </div>
                    <div class="card-body">
                        <form id="formComparativo" action="mpdf/relatorio_comparativo.php" method="POST" target="_blank">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Primeiro Período</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <select name="ano1" class="form-select" required>
                                                <?php for ($a = $anoAtual; $a >= 2020; $a--): ?>
                                                    <option value="<?= $a ?>" <?= $a == $anoAtual ? 'selected' : '' ?>><?= $a ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <select name="periodo1" class="form-select">
                                                <option value="ano">Ano Completo</option>
                                                <option value="1sem">1º Semestre</option>
                                                <option value="2sem">2º Semestre</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Segundo Período</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <select name="ano2" class="form-select" required>
                                                <?php for ($a = $anoAtual; $a >= 2020; $a--): ?>
                                                    <option value="<?= $a ?>" <?= $a == ($anoAtual - 1) ? 'selected' : '' ?>><?= $a ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <select name="periodo2" class="form-select">
                                                <option value="ano">Ano Completo</option>
                                                <option value="1sem">1º Semestre</option>
                                                <option value="2sem">2º Semestre</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Comparar por</label>
                                    <select name="tipo_comparacao" class="form-select">
                                        <option value="geral">Visão Geral (Todos os indicadores)</option>
                                        <option value="especialidade">Por Especialidade</option>
                                        <option value="om">Por Organização Militar</option>
                                        <option value="situacao">Por Situação Militar</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Formato</label>
                                    <select name="formato" class="form-select">
                                        <option value="pdf">PDF</option>
                                        <option value="excel">Excel (XLSX)</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Exibir Variação</label>
                                    <select name="exibir_variacao" class="form-select">
                                        <option value="sim">Sim - Com percentuais</option>
                                        <option value="nao">Não - Apenas valores</option>
                                    </select>
                                </div>
                            </div>

                            <input name="crip" type="hidden" value="<?= hash('sha256', $_SESSION['chave'] . "criptografia"); ?>">

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-info btn-lg text-white">
                                    <i class="fas fa-balance-scale me-2"></i>Gerar Relatório Comparativo
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tab: Exportar Dados -->
            <div class="tab-pane fade" id="exportar" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-download me-2 text-warning"></i>Exportar Dados</h5>
                        <small class="text-muted">Exporte dados completos em formato Excel (XLSX) com filtros avançados</small>
                    </div>
                    <div class="card-body">
                        <form id="formExportar" action="controller/relatorio_export_excel.php" method="POST">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Tipo de Dados</label>
                                    <select name="tipo_dados" class="form-select" id="tipoDadosExport" required>
                                        <option value="obrigatorios">Todos os Obrigatórios</option>
                                        <option value="designados">Apenas Designados</option>
                                        <option value="incorporados">Apenas Incorporados</option>
                                        <option value="pendentes">Pendentes de Revisão</option>
                                        <option value="adiados">Com Adiamento</option>
                                        <option value="estatisticas">Estatísticas Consolidadas</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Ano</label>
                                    <select name="ano" class="form-select">
                                        <option value="">Todos os anos</option>
                                        <?php for ($a = $anoAtual; $a >= 2020; $a--): ?>
                                            <option value="<?= $a ?>" <?= $a == $anoAtual ? 'selected' : '' ?>><?= $a ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Período</label>
                                    <select name="periodo" class="form-select">
                                        <option value="ano">Ano Completo</option>
                                        <option value="1sem">1º Semestre</option>
                                        <option value="2sem">2º Semestre</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Filtrar por OM</label>
                                    <select name="om_filtro" class="form-select chosen-select">
                                        <option value="">Todas as OMs</option>
                                        <?php foreach ($lista_oms as $om): ?>
                                            <option value="<?= $om->getId() ?>"><?= htmlspecialchars($om->getAbreviatura()) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Filtrar por Especialidade</label>
                                    <select name="especialidade_filtro" class="form-select chosen-select">
                                        <option value="">Todas as especialidades</option>
                                        <?php foreach ($lista_especialidades as $esp): ?>
                                            <option value="<?= htmlspecialchars($esp['nome']) ?>"><?= htmlspecialchars($esp['nome']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Colunas a Exportar</label>
                                    <div class="row" id="colunasExport">
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="colunas[]" value="nome" id="colNome" checked>
                                                <label class="form-check-label" for="colNome">Nome</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="colunas[]" value="cpf" id="colCPF" checked>
                                                <label class="form-check-label" for="colCPF">CPF</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="colunas[]" value="especialidade" id="colEsp" checked>
                                                <label class="form-check-label" for="colEsp">Especialidade</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="colunas[]" value="situacao" id="colSituacao" checked>
                                                <label class="form-check-label" for="colSituacao">Situação</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="colunas[]" value="om" id="colOM" checked>
                                                <label class="form-check-label" for="colOM">OM</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="colunas[]" value="distribuicao" id="colDist" checked>
                                                <label class="form-check-label" for="colDist">Distribuição</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="colunas[]" value="revisao" id="colRevisao" checked>
                                                <label class="form-check-label" for="colRevisao">Revisão Médica</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="colunas[]" value="jise" id="colJISE" checked>
                                                <label class="form-check-label" for="colJISE">JISE/JISR</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="colunas[]" value="telefone" id="colTelefone">
                                                <label class="form-check-label" for="colTelefone">Telefone</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="colunas[]" value="email" id="colEmail">
                                                <label class="form-check-label" for="colEmail">E-mail</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="colunas[]" value="endereco" id="colEndereco">
                                                <label class="form-check-label" for="colEndereco">Endereço</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="colunas[]" value="formacao" id="colFormacao">
                                                <label class="form-check-label" for="colFormacao">Formação</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="selecionarTodasColunas()">Selecionar Todas</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="limparColunas()">Limpar Seleção</button>
                                    </div>
                                </div>
                            </div>

                            <input name="crip" type="hidden" value="<?= hash('sha256', $_SESSION['chave'] . "criptografia"); ?>">

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-warning btn-lg">
                                    <i class="fas fa-file-excel me-2"></i>Exportar para Excel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
function selecionarTodasColunas() {
    document.querySelectorAll('#colunasExport input[type="checkbox"]').forEach(cb => cb.checked = true);
}

function limparColunas() {
    document.querySelectorAll('#colunasExport input[type="checkbox"]').forEach(cb => cb.checked = false);
}

$(document).ready(function() {
    // Inicializa Chosen para selects múltiplos
    if ($.fn.chosen) {
        $('.chosen-select').chosen({
            width: '100%',
            no_results_text: 'Nenhum resultado encontrado'
        });
    }

    // Muda action do formulário baseado no formato selecionado
    $('select[name="formato"]').on('change', function() {
        var form = $(this).closest('form');
        var formato = $(this).val();
        var action = form.attr('action');

        if (formato === 'excel') {
            action = action.replace('mpdf/', 'controller/').replace('.php', '_excel.php');
        } else {
            action = action.replace('controller/', 'mpdf/').replace('_excel.php', '.php');
        }
        form.attr('action', action);
    });
});
</script>

<?php
include_once 'footer.php';
?>
