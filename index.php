<?php
include_once 'header.php';
include_once 'dao/conecta_banco.php';
include_once 'models/Usuario.php';
include_once 'dao/UsuarioDAO.php';
include_once 'models/Obrigatorio.php';
include_once 'dao/ObrigatorioDAO.php';
include_once 'dao/LogDAO.php';

if (!isset($_SESSION['id_usuario_smo'])) {
    erro($BASE_URL, 2, 236325634, $pagina_atual, "usuario_nao_logado", "Página não encontrada!");
    exit();
}

// Redireciona operadores para o dashboard específico
if ($_SESSION['perfil_smo'] == 'operador') {
    header("Location: dashboard_operador.php");
    exit();
}

if (!isset($_SESSION['mensagem'])) $_SESSION['mensagem'] = null;

// Busca dados dinâmicos do banco
$ObrigatorioDAO = new ObrigatorioDAO($conexao);
$total_obrigatorios = $ObrigatorioDAO->countObrigatoriosAnoAtual();
$total_selecao_concluida = $ObrigatorioDAO->countObrigatoriosSelecaoConcluida();
$total_distribuidos = $ObrigatorioDAO->countObrigatoriosIncorporadosAnoAtual();

// Indicadores de pendências (apenas para admin) - carrega com ano atual
$pendentes_distribuicao = 0;
$pendentes_revisao = 0;
$adiamentos_ativos = 0;
$total_inaptos = 0;
$anoFiltro = (int)date('Y');

if ($_SESSION['perfil_smo'] == 'admin') {
    $mesesFiltro = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]; // Ano completo
    $pendentes_distribuicao = $ObrigatorioDAO->countPendentesDistribuicao($anoFiltro, $mesesFiltro);
    $pendentes_revisao = $ObrigatorioDAO->countPendentesRevisaoMedica($anoFiltro, $mesesFiltro);
    $adiamentos_ativos = $ObrigatorioDAO->countAdiamentosAtivos($anoFiltro, $mesesFiltro);
    $total_inaptos = $ObrigatorioDAO->countInaptos($anoFiltro, $mesesFiltro);
}

// Busca últimas atividades do log
// Admin vê todas as atividades, outros usuários veem apenas as próprias
$logDAO = new LogDAO($conexao);
$filtroUsuario = ($_SESSION['perfil_smo'] == 'admin') ? null : $_SESSION['id_usuario_smo'];
$ultimasAtividades = $logDAO->getUltimasAtividades(5, $filtroUsuario);

?>

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h1><i class="fas fa-stethoscope me-3"></i>Bem-vindo ao SISEL - SMO</h1>
                <p class="text-secondary mt-3 fs-5">
                    <?php echo saudacoes() . ", " . $_SESSION['posto_grad_smo'] . " " . $_SESSION['nome_guerra_smo'] ?>
                </p>
            </div>
        </div>
    </section>

    <!-- Mensagem de Sucesso/Erro -->
    <?php if (!empty($_SESSION['mensagem'])): ?>
        <div class="container mt-4">
            <div class="alert alert-success" role="alert" data-aos="fade-down">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?php echo $_SESSION['mensagem'];
                $_SESSION['mensagem'] = null; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Cards de Estatísticas -->
    <section class="section-bg" style="padding: 3rem 0;">
        <div class="container">

            <?php if ($_SESSION['perfil_smo'] == 'admin'): ?>
            <!-- Dashboard Analítico -->
            <div class="row">
                <div class="col-12" data-aos="fade-up">
                    <div class="card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="mb-0 d-flex align-items-center">
                                <i class="fas fa-chart-pie me-2" style="color: var(--accent-green);"></i>
                                Dashboard
                            </h3>
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="filtro_ano_dashboard" class="form-label mb-0">Ano:</label>
                                    <select id="filtro_ano_dashboard" class="form-select form-select-sm" style="width: auto;">
                                        <?php
                                        $anoAtual = (int)date('Y');
                                        for ($a = $anoAtual; $a >= 2023; $a--) {
                                            $selected = ($a == $anoAtual) ? 'selected' : '';
                                            echo "<option value=\"$a\" $selected>$a</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <label for="filtro_periodo_dashboard" class="form-label mb-0">Período:</label>
                                    <select id="filtro_periodo_dashboard" class="form-select form-select-sm" style="width: auto;">
                                        <option value="ano" selected>Ano Completo</option>
                                        <option value="1sem">1º Semestre</option>
                                        <option value="2sem">2º Semestre</option>
                                        <option value="1tri">1º Trimestre</option>
                                        <option value="2tri">2º Trimestre</option>
                                        <option value="3tri">3º Trimestre</option>
                                        <option value="4tri">4º Trimestre</option>
                                    </select>
                                </div>
                                <button class="btn btn-sm btn-primary" onclick="exportarExcel()" title="Exportar dados para Excel">
                                    <i class="fas fa-file-excel me-1"></i> Exportar Excel
                                </button>
                            </div>
                        </div>

                        <!-- Estatísticas Gerais -->
                        <div class="row mb-4">
                            <div class="col-lg-6 col-md-6 mb-3">
                                <div class="p-3 h-100" style="background: var(--gray-dark); border-radius: var(--border-radius); border-left: 4px solid var(--accent-green);">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; background: rgba(0, 100, 0, 0.15);">
                                            <i class="bi bi-geo-alt fs-4" style="color: var(--accent-green);"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-0 fw-bold" id="indicador-incorporados" style="color: var(--accent-green);"><?= $total_distribuidos ?></h3>
                                            <small class="text-muted">Incorporados no Ano</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-3">
                                <div class="p-3 h-100" style="background: var(--gray-dark); border-radius: var(--border-radius); border-left: 4px solid var(--accent-green);">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; background: rgba(0, 100, 0, 0.15);">
                                            <i class="bi bi-clock-history fs-4" style="color: var(--accent-green);"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-0 fw-bold" style="color: var(--accent-green);"><?= $total_selecao_concluida ?></h3>
                                            <small class="text-muted">Histórico de Processos (Total)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Indicadores de Pendências -->
                        <div class="row mb-4">
                            <div class="col-12 mb-3">
                                <h5 class="mb-0 d-flex align-items-center" style="color: var(--text-primary); font-weight: 600;">
                                    <i class="fas fa-bell me-2" style="color: var(--accent-green);"></i>
                                    Indicadores de Pendências
                                </h5>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="p-3 h-100" style="background: var(--gray-dark); border-radius: var(--border-radius); border-left: 4px solid var(--accent-green);">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px; background: rgba(0, 100, 0, 0.15);">
                                            <i class="fas fa-map-marker-alt" style="color: var(--accent-green);"></i>
                                        </div>
                                        <div>
                                            <h4 class="mb-0 fw-bold" id="indicador-pendentes-distribuicao" style="color: var(--accent-green);"><?= $pendentes_distribuicao ?></h4>
                                            <small class="text-muted">Prontos p/ Distribuição</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="p-3 h-100" style="background: var(--gray-dark); border-radius: var(--border-radius); border-left: 4px solid var(--accent-green);">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px; background: rgba(0, 100, 0, 0.15);">
                                            <i class="fas fa-stethoscope" style="color: var(--accent-green);"></i>
                                        </div>
                                        <div>
                                            <h4 class="mb-0 fw-bold" id="indicador-pendentes-revisao" style="color: var(--accent-green);"><?= $pendentes_revisao ?></h4>
                                            <small class="text-muted">Pendentes Revisão - OM 1ª Fase</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="p-3 h-100" style="background: var(--gray-dark); border-radius: var(--border-radius); border-left: 4px solid var(--accent-green);">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px; background: rgba(0, 100, 0, 0.15);">
                                            <i class="fas fa-calendar-times" style="color: var(--accent-green);"></i>
                                        </div>
                                        <div>
                                            <h4 class="mb-0 fw-bold" id="indicador-adiamentos" style="color: var(--accent-green);"><?= $adiamentos_ativos ?></h4>
                                            <small class="text-muted">Com Adiamento</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="p-3 h-100" style="background: var(--gray-dark); border-radius: var(--border-radius); border-left: 4px solid var(--accent-green);">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px; background: rgba(0, 100, 0, 0.15);">
                                            <i class="fas fa-user-times" style="color: var(--accent-green);"></i>
                                        </div>
                                        <div>
                                            <h4 class="mb-0 fw-bold" id="indicador-inaptos" style="color: var(--accent-green);"><?= $total_inaptos ?></h4>
                                            <small class="text-muted">Inaptos Seleção Complementar</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Gráfico: Por Especialidade -->
                            <div class="col-md-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">
                                        <i class="fas fa-user-md me-2" style="color: var(--accent-green);"></i>Por Especialidade
                                    </h5>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="exportarGrafico('chartEspecialidade', 'especialidade')" title="Exportar como imagem">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                                <div class="p-3" style="background: var(--gray-dark); border-radius: var(--border-radius); height: 380px; position: relative;">
                                    <canvas id="chartEspecialidade"></canvas>
                                </div>
                            </div>

                            <!-- Gráfico: Situação Militar -->
                            <div class="col-md-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">
                                        <i class="fas fa-flag me-2" style="color: var(--accent-green);"></i>Situação Militar
                                    </h5>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="exportarGrafico('chartSituacao', 'situacao_militar')" title="Exportar como imagem">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                                <div class="p-3" style="background: var(--gray-dark); border-radius: var(--border-radius); height: 380px; position: relative;">
                                    <canvas id="chartSituacao"></canvas>
                                </div>
                            </div>

                            <!-- Gráfico: Resultado Revisão Médica -->
                            <div class="col-md-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">
                                        <i class="fas fa-heartbeat me-2" style="color: var(--accent-green);"></i>Resultado Revisão Médica (OM 1ª Fase)
                                    </h5>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="exportarGrafico('chartRevisao', 'resultado_revisao')" title="Exportar como imagem">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                                <div class="p-3" style="background: var(--gray-dark); border-radius: var(--border-radius); height: 380px; position: relative;">
                                    <canvas id="chartRevisao"></canvas>
                                </div>
                            </div>

                            <!-- Gráfico: Evolução Mensal -->
                            <div class="col-md-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">
                                        <i class="fas fa-chart-line me-2" style="color: var(--accent-green);"></i>Incorporações por Mês
                                    </h5>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="exportarGrafico('chartMensal', 'evolucao_mensal')" title="Exportar como imagem">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                                <div class="p-3" style="background: var(--gray-dark); border-radius: var(--border-radius); height: 380px; position: relative;">
                                    <canvas id="chartMensal"></canvas>
                                </div>
                            </div>

                            <!-- Gráfico: Por OM de Destino -->
                            <div class="col-md-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">
                                        <i class="fas fa-building me-2" style="color: var(--accent-green);"></i>Top 10 OMs de Destino
                                    </h5>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="exportarGrafico('chartOM', 'por_om')" title="Exportar como imagem">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                                <div class="p-3" style="background: var(--gray-dark); border-radius: var(--border-radius); height: 380px; position: relative;">
                                    <canvas id="chartOM"></canvas>
                                </div>
                            </div>

                            <!-- Gráfico: JISE -->
                            <div class="col-md-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">
                                        <i class="bi bi-heart-pulse-fill me-2" style="color: var(--accent-green);"></i>Resultados JISE
                                    </h5>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="exportarGrafico('chartJISE', 'por_jise')" title="Exportar como imagem">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                                <div class="p-3" style="background: var(--gray-dark); border-radius: var(--border-radius); height: 380px; position: relative;">
                                    <canvas id="chartJISE"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Calendário de Eventos -->
                        <div class="row mt-4">
                            <div class="col-md-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">
                                        <i class="fas fa-calendar-alt me-2" style="color: var(--accent-green);"></i>Calendário
                                    </h5>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-secondary" onclick="mesAnterior()" title="Mês anterior">
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary" onclick="mesAtual()" title="Mês atual">
                                            <i class="fas fa-dot-circle"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary" onclick="mesSeguinte()" title="Próximo mês">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="p-3" style="background: var(--gray-dark); border-radius: var(--border-radius);">
                                    <div id="calendario-header" class="text-center mb-3">
                                        <h6 id="calendario-mes-ano" class="mb-0" style="color: var(--accent-green);"></h6>
                                    </div>
                                    <div id="calendario-grid" class="calendario-grid">
                                        <div class="calendario-semana">
                                            <span>Dom</span><span>Seg</span><span>Ter</span><span>Qua</span><span>Qui</span><span>Sex</span><span>Sáb</span>
                                        </div>
                                        <div id="calendario-dias" class="calendario-dias"></div>
                                    </div>
                                    <div class="mt-3 d-flex gap-3 justify-content-center" style="font-size: 0.75rem;">
                                        <span><span class="badge bg-success">&nbsp;</span> Incorporação</span>
                                        <span><span class="badge bg-warning">&nbsp;</span> Adiamento</span>
                                        <span><span class="badge bg-danger">&nbsp;</span> Revisão</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">
                                        <i class="fas fa-clock me-2" style="color: var(--accent-green);"></i>Próximos Eventos
                                    </h5>
                                    <select id="filtro-dias-eventos" class="form-select form-select-sm" style="width: auto;" onchange="carregarProximosEventos()">
                                        <option value="7">7 dias</option>
                                        <option value="15">15 dias</option>
                                        <option value="30" selected>30 dias</option>
                                        <option value="60">60 dias</option>
                                    </select>
                                </div>
                                <div class="p-3" style="background: var(--gray-dark); border-radius: var(--border-radius); max-height: 380px; overflow-y: auto;">
                                    <div id="lista-eventos">
                                        <div class="text-center py-4">
                                            <i class="fas fa-spinner fa-spin fa-2x" style="color: var(--accent-green);"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Ações Rápidas -->
            <div class="row mt-5">
                <div class="col-12" data-aos="fade-up">
                    <div class="card p-4">
                        <h3 class="mb-4 d-flex align-items-center">
                            <i class="bi bi-lightning-charge-fill me-2 text-success"></i>
                            Ações Rápidas
                        </h3>

                        <div class="row">
                            <?php if ($_SESSION['perfil_smo'] == 'admin'): ?>

                                <div class="col-md-3 mb-3" data-aos="fade-up" data-aos-delay="100">
                                    <a href="obrigatorio_cadastra.php" class="btn btn-primary w-100" class="btn-action-card">
                                        <i class="fas fa-user-md" ></i>
                                        <span>Cadastrar Obrigatório</span>
                                    </a>
                                </div>

                                <div class="col-md-3 mb-3" data-aos="fade-up" data-aos-delay="150">
                                    <a href="obrigatorios.php" class="btn btn-primary w-100" class="btn-action-card">
                                        <i class="bi bi-list-ul" ></i>
                                        <span>Listar Obrigatórios</span>
                                    </a>
                                </div>

                                <div class="col-md-3 mb-3" data-aos="fade-up" data-aos-delay="200">
                                    <a href="pre_distribuicao.php" class="btn btn-primary w-100" class="btn-action-card">
                                        <i class="bi bi-diagram-3-fill" ></i>
                                        <span>Pré Distribuição</span>
                                    </a>
                                </div>

                                <div class="col-md-3 mb-3" data-aos="fade-up" data-aos-delay="250">
                                    <a href="relatorios.php" class="btn btn-primary w-100" class="btn-action-card">
                                        <i class="bi bi-file-earmark-text-fill" ></i>
                                        <span>Relatórios</span>
                                    </a>
                                </div>

                                <div class="col-md-3 mb-3" data-aos="fade-up" data-aos-delay="300">
                                    <a href="junta_saude_cadastra.php" class="btn btn-primary w-100" class="btn-action-card">
                                        <i class="bi bi-journal-medical" ></i>
                                        <span>Inspeções de Saúde</span>
                                    </a>
                                </div>

                                <div class="col-md-3 mb-3" data-aos="fade-up" data-aos-delay="350">
                                    <a href="usuarios.php" class="btn btn-primary w-100" class="btn-action-card">
                                        <i class="bi bi-people-fill" ></i>
                                        <span>Gerenciar Usuários</span>
                                    </a>
                                </div>

                                <div class="col-md-3 mb-3" data-aos="fade-up" data-aos-delay="400">
                                    <a href="auditoria.php" class="btn btn-primary w-100" class="btn-action-card">
                                        <i class="bi bi-shield-check" ></i>
                                        <span>Auditoria</span>
                                    </a>
                                </div>

                            <?php else: ?>

                                <div class="col-md-4 mb-3" data-aos="fade-up" data-aos-delay="100">
                                    <a href="distribuidos_om_1_fase.php" class="btn btn-primary w-100" class="btn-action-card">
                                        <i class="bi bi-diagram-3-fill" ></i>
                                        <span>Distribuídos OM 1ª Fase</span>
                                    </a>
                                </div>

                                <div class="col-md-4 mb-3" data-aos="fade-up" data-aos-delay="150">
                                    <a href="revisao_medica.php" class="btn btn-primary w-100" class="btn-action-card">
                                        <i class="bi bi-heart-pulse-fill" ></i>
                                        <span>Revisão Médica</span>
                                    </a>
                                </div>

                            <?php endif; ?>

                            <div class="col-md-3 mb-3" data-aos="fade-up" data-aos-delay="450">
                                <a href="controller/logout.php" class="btn btn-primary w-100" class="btn-action-card">
                                    <i class="bi bi-box-arrow-left" ></i>
                                    <span>Sair</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações do Sistema -->
            <div class="row mt-4">
                <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card" style="height: 100%;">
                        <h4 style="margin-bottom: 1.5rem; color: var(--text-primary); display: flex; align-items: center;">
                            <i class="bi bi-info-circle-fill me-2" style="color: var(--accent-green);"></i>
                            Informações do Sistema
                        </h4>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--gray-dark); border-radius: var(--border-radius);">
                                <span style="color: var(--text-secondary);">Versão do Sistema:</span>
                                <strong style="color: var(--accent-green);">1.0.0</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--gray-dark); border-radius: var(--border-radius);">
                                <span style="color: var(--text-secondary);">Última Atualização:</span>
                                <strong style="color: var(--accent-green);">Fevereiro/2026</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--gray-dark); border-radius: var(--border-radius);">
                                <span style="color: var(--text-secondary);">Perfil de Acesso:</span>
                                <strong style="color: var(--accent-green); text-transform: uppercase;"><?php echo $_SESSION['perfil_smo']; ?></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card" style="height: 100%;">
                        <h4 style="margin-bottom: 1.5rem; color: var(--text-primary); display: flex; align-items: center;">
                            <i class="bi bi-calendar-event me-2" style="color: var(--accent-green);"></i>
                            <?= ($_SESSION['perfil_smo'] == 'admin') ? 'Atividades Recentes do Sistema' : 'Suas Atividades Recentes' ?>
                        </h4>
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <?php if (!empty($ultimasAtividades)): ?>
                                <?php foreach ($ultimasAtividades as $atividade):
                                    $operacao = LogDAO::getOperacaoInfo((int)$atividade['codigo']);
                                    $dataAtividade = new DateTime($atividade['data']);
                                    $agora = new DateTime();
                                    $diff = $agora->diff($dataAtividade);

                                    if ($diff->days == 0 && $diff->h == 0 && $diff->i < 5) {
                                        $tempoRelativo = 'Agora';
                                    } elseif ($diff->days == 0 && $diff->h == 0) {
                                        $tempoRelativo = $diff->i . ' min';
                                    } elseif ($diff->days == 0) {
                                        $tempoRelativo = $diff->h . 'h';
                                    } elseif ($diff->days == 1) {
                                        $tempoRelativo = 'Ontem';
                                    } else {
                                        $tempoRelativo = $dataAtividade->format('d/m');
                                    }

                                    $coresBorda = [
                                        'success' => 'var(--success)',
                                        'danger' => 'var(--danger)',
                                        'warning' => 'var(--warning)',
                                        'info' => 'var(--info)',
                                        'primary' => 'var(--accent-green)'
                                    ];
                                    $corBorda = isset($coresBorda[$operacao['cor']]) ? $coresBorda[$operacao['cor']] : 'var(--secondary)';
                                ?>
                                <div style="padding: 0.6rem 0.75rem; background: var(--gray-dark); border-radius: var(--border-radius); border-left: 3px solid <?= $corBorda ?>;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <span style="color: var(--text-secondary); font-size: 0.85rem; flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?= htmlspecialchars($atividade['alteracao']) ?>">
                                            <i class="fas <?= $operacao['icone'] ?> me-2" style="color: <?= $corBorda ?>;"></i>
                                            <?= htmlspecialchars(mb_strimwidth($atividade['alteracao'], 0, 40, '...')) ?>
                                        </span>
                                        <small style="color: var(--text-muted); margin-left: 10px;"><?= $tempoRelativo ?></small>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div style="padding: 1rem; text-align: center; color: var(--text-muted);">
                                    <i class="bi bi-clock-history me-2"></i>
                                    Nenhuma atividade recente
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</main>

<!-- Estilos do Calendário -->
<style>
.calendario-grid {
    font-size: 0.85rem;
}
.calendario-semana {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    text-align: center;
    font-weight: 600;
    color: var(--text-secondary);
    padding-bottom: 8px;
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 8px;
}
.calendario-semana span {
    padding: 4px;
}
.calendario-dias {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px;
}
.calendario-dia {
    aspect-ratio: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
    padding: 2px;
}
.calendario-dia:hover {
    background: rgba(0, 100, 0, 0.1);
}
.calendario-dia.hoje {
    background: rgba(0, 100, 0, 0.2);
    font-weight: bold;
}
.calendario-dia.outro-mes {
    color: var(--text-muted);
    opacity: 0.5;
}
.calendario-dia .dia-numero {
    font-size: 0.9rem;
    color: var(--text-primary);
}
.calendario-dia .dia-eventos {
    display: flex;
    gap: 2px;
    margin-top: 2px;
}
.calendario-dia .evento-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}
.evento-item {
    padding: 10px;
    background: white;
    border-radius: 6px;
    margin-bottom: 8px;
    border-left: 3px solid;
    transition: all 0.2s ease;
}
.evento-item:hover {
    transform: translateX(3px);
}
.evento-item.success { border-color: var(--success); }
.evento-item.warning { border-color: var(--warning); }
.evento-item.danger { border-color: var(--danger); }
.evento-item.primary { border-color: var(--primary); }
.evento-item.info { border-color: var(--info); }
.evento-titulo {
    font-weight: 600;
    font-size: 0.85rem;
    color: var(--text-primary);
}
.evento-descricao {
    font-size: 0.8rem;
    color: var(--text-secondary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.evento-data {
    font-size: 0.75rem;
    color: var(--text-muted);
}
</style>

<script type="text/javascript">
    // Variáveis globais para os gráficos
    let chartEspecialidade, chartSituacao, chartRevisao, chartMensal, chartOM, chartJISE;

    // Paleta de cores do sistema
    const coresPrimarias = [
        '#006400', '#228b22', '#32cd32', '#90ee90', '#98fb98',
        '#3cb371', '#2e8b57', '#66cdaa', '#20b2aa', '#008b8b'
    ];

    const coresSecundarias = [
        '#006400', '#0d6efd', '#dc3545', '#ffc107', '#6c757d',
        '#198754', '#0dcaf0', '#fd7e14', '#6610f2', '#d63384'
    ];

    // Variáveis do calendário
    let calendarioMes = new Date().getMonth() + 1;
    let calendarioAno = new Date().getFullYear();
    let eventosCalendario = {};

    $(document).ready(function() {
        // Inicializa DataTables se existir
        if ($.fn.DataTable) {
            $('#tabela_dinamica').DataTable();
        }

        // Auto-hide mensagens de sucesso após 5 segundos
        setTimeout(function() {
            $('.alert-success').fadeOut('slow');
        }, 5000);

        <?php if ($_SESSION['perfil_smo'] == 'admin'): ?>
        // Carrega os gráficos iniciais
        carregarGraficos($('#filtro_ano_dashboard').val(), $('#filtro_periodo_dashboard').val());

        // Atualiza gráficos ao mudar o ano
        $('#filtro_ano_dashboard').on('change', function() {
            carregarGraficos($(this).val(), $('#filtro_periodo_dashboard').val());
        });

        // Atualiza gráficos ao mudar o período
        $('#filtro_periodo_dashboard').on('change', function() {
            carregarGraficos($('#filtro_ano_dashboard').val(), $(this).val());
        });

        // Inicializa o calendário
        carregarCalendario(calendarioMes, calendarioAno);
        carregarProximosEventos();
        <?php endif; ?>
    });

    <?php if ($_SESSION['perfil_smo'] == 'admin'): ?>
    function carregarGraficos(ano, periodo) {
        $.ajax({
            url: 'controller/dashboard_ajax.php',
            type: 'GET',
            dataType: 'json',
            data: { ano: ano, periodo: periodo },
            success: function(response) {
                renderizarGraficoEspecialidade(response.especialidades);
                renderizarGraficoSituacao(response.situacao_militar);
                renderizarGraficoRevisao(response.resultado_revisao);
                renderizarGraficoMensal(response.evolucao_mensal);
                renderizarGraficoOM(response.por_om);
                renderizarGraficoJISE(response.jise);

                // Atualiza estatísticas gerais
                $('#indicador-incorporados').text(response.incorporados);

                // Atualiza indicadores de pendências
                if (response.indicadores) {
                    $('#indicador-pendentes-distribuicao').text(response.indicadores.pendentes_distribuicao);
                    $('#indicador-pendentes-revisao').text(response.indicadores.pendentes_revisao);
                    $('#indicador-adiamentos').text(response.indicadores.adiamentos_ativos);
                    $('#indicador-inaptos').text(response.indicadores.total_inaptos);
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados dos gráficos:', error);
            }
        });
    }

    // Função para exportar gráfico como imagem PNG
    function exportarGrafico(canvasId, nomeArquivo) {
        const canvas = document.getElementById(canvasId);
        const ano = $('#filtro_ano_dashboard').val();
        const periodo = $('#filtro_periodo_dashboard').val();

        // Cria um link temporário para download
        const link = document.createElement('a');
        link.download = `grafico_${nomeArquivo}_${ano}_${periodo}.png`;
        link.href = canvas.toDataURL('image/png', 1.0);
        link.click();
    }

    // Função para exportar dados para Excel (CSV)
    function exportarExcel() {
        const ano = $('#filtro_ano_dashboard').val();
        const periodo = $('#filtro_periodo_dashboard').val();

        // Redireciona para o endpoint de exportação
        window.location.href = `controller/dashboard_export_excel.php?ano=${ano}&periodo=${periodo}&tipo=todos`;
    }

    function renderizarGraficoEspecialidade(dados) {
        const ctx = document.getElementById('chartEspecialidade').getContext('2d');

        if (chartEspecialidade) {
            chartEspecialidade.destroy();
        }

        chartEspecialidade = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: dados.labels,
                datasets: [{
                    data: dados.data,
                    backgroundColor: coresPrimarias,
                    borderWidth: 2,
                    borderColor: 'var(--primary-light)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: getComputedStyle(document.documentElement).getPropertyValue('--text-primary').trim() || '#333',
                            font: { size: 11 },
                            boxWidth: 12
                        }
                    }
                }
            }
        });
    }

    function renderizarGraficoSituacao(dados) {
        const ctx = document.getElementById('chartSituacao').getContext('2d');

        if (chartSituacao) {
            chartSituacao.destroy();
        }

        chartSituacao = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dados.labels,
                datasets: [{
                    label: 'Quantidade',
                    data: dados.data,
                    backgroundColor: coresSecundarias,
                    borderWidth: 1,
                    borderRadius: 4,
                    minBarLength: 8
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 10,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return ' Quantidade: ' + context.parsed.x;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { color: getComputedStyle(document.documentElement).getPropertyValue('--text-secondary').trim() || '#666' },
                        grid: { color: 'rgba(128,128,128,0.2)' }
                    },
                    y: {
                        ticks: { color: getComputedStyle(document.documentElement).getPropertyValue('--text-secondary').trim() || '#666' },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    function renderizarGraficoRevisao(dados) {
        const ctx = document.getElementById('chartRevisao').getContext('2d');

        if (chartRevisao) {
            chartRevisao.destroy();
        }

        // Cores específicas para resultados médicos
        const coresRevisao = dados.labels.map(label => {
            if (label.toUpperCase().includes('APTO') && !label.toUpperCase().includes('INAPTO')) return '#006400';
            if (label.toUpperCase().includes('INAPTO')) return '#dc3545';
            if (label.toUpperCase().includes('PENDENTE')) return '#ffc107';
            if (label.toUpperCase().includes('NÃO COMPARECEU')) return '#6c757d';
            return '#0d6efd';
        });

        chartRevisao = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: dados.labels,
                datasets: [{
                    data: dados.data,
                    backgroundColor: coresRevisao,
                    borderWidth: 2,
                    borderColor: 'var(--primary-light)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: getComputedStyle(document.documentElement).getPropertyValue('--text-primary').trim() || '#333',
                            font: { size: 12 }
                        }
                    }
                }
            }
        });
    }

    function renderizarGraficoMensal(dados) {
        const ctx = document.getElementById('chartMensal').getContext('2d');

        if (chartMensal) {
            chartMensal.destroy();
        }

        chartMensal = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dados.labels,
                datasets: [{
                    label: 'Incorporações',
                    data: dados.data,
                    borderColor: '#006400',
                    backgroundColor: 'rgba(0, 100, 0, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#006400',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        ticks: { color: getComputedStyle(document.documentElement).getPropertyValue('--text-secondary').trim() || '#666' },
                        grid: { color: 'rgba(128,128,128,0.2)' }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { color: getComputedStyle(document.documentElement).getPropertyValue('--text-secondary').trim() || '#666' },
                        grid: { color: 'rgba(128,128,128,0.2)' }
                    }
                }
            }
        });
    }

    function renderizarGraficoOM(dados) {
        const ctx = document.getElementById('chartOM').getContext('2d');

        if (chartOM) {
            chartOM.destroy();
        }

        chartOM = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dados.labels,
                datasets: [{
                    label: 'Quantidade',
                    data: dados.data,
                    backgroundColor: coresPrimarias,
                    borderWidth: 1,
                    borderRadius: 4,
                    minBarLength: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 10,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return ' Quantidade: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: getComputedStyle(document.documentElement).getPropertyValue('--text-secondary').trim() || '#666',
                            maxRotation: 45,
                            minRotation: 45
                        },
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { color: getComputedStyle(document.documentElement).getPropertyValue('--text-secondary').trim() || '#666' },
                        grid: { color: 'rgba(128,128,128,0.2)' }
                    }
                }
            }
        });
    }

    function renderizarGraficoJISE(dados) {
        const ctx = document.getElementById('chartJISE').getContext('2d');

        if (chartJISE) {
            chartJISE.destroy();
        }

        // Cores específicas para classificações JISE
        const coresJISE = dados.labels.map(label => {
            if (label === 'A') return '#006400';
            if (label === 'B1') return '#228b22';
            if (label === 'B2') return '#32cd32';
            if (label === 'C') return '#ffc107';
            if (label === 'Não avaliado') return '#6c757d';
            return '#0d6efd';
        });

        chartJISE = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: dados.labels,
                datasets: [{
                    data: dados.data,
                    backgroundColor: coresJISE,
                    borderWidth: 2,
                    borderColor: 'var(--primary-light)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: getComputedStyle(document.documentElement).getPropertyValue('--text-primary').trim() || '#333',
                            font: { size: 12 }
                        }
                    }
                }
            }
        });
    }

    // ===== FUNÇÕES DO CALENDÁRIO =====

    function carregarCalendario(mes, ano) {
        $.ajax({
            url: 'controller/calendario_ajax.php',
            type: 'GET',
            dataType: 'json',
            data: { acao: 'mes', mes: mes, ano: ano },
            success: function(response) {
                if (response.success) {
                    eventosCalendario = response.eventos;
                    renderizarCalendario(response);
                }
            },
            error: function() {
                $('#calendario-dias').html('<div class="text-center text-muted py-3">Erro ao carregar</div>');
            }
        });
    }

    function renderizarCalendario(data) {
        $('#calendario-mes-ano').text(data.nome_mes + ' ' + data.ano);

        let html = '';
        let diaAtual = 1;
        const hoje = new Date();
        const ehMesAtual = (data.mes === (hoje.getMonth() + 1) && data.ano === hoje.getFullYear());
        const diaHoje = hoje.getDate();

        // Dias vazios antes do primeiro dia
        for (let i = 0; i < data.dia_semana_inicio; i++) {
            html += '<div class="calendario-dia outro-mes"></div>';
        }

        // Dias do mês
        for (let dia = 1; dia <= data.dias_no_mes; dia++) {
            const ehHoje = ehMesAtual && dia === diaHoje;
            const eventos = data.eventos[dia] || [];

            html += `<div class="calendario-dia ${ehHoje ? 'hoje' : ''}" onclick="verEventosDia(${dia}, ${data.mes}, ${data.ano})">`;
            html += `<span class="dia-numero">${dia}</span>`;

            if (eventos.length > 0) {
                html += '<div class="dia-eventos">';
                eventos.forEach(ev => {
                    html += `<span class="evento-dot bg-${ev.cor}" title="${ev.total} ${ev.tipo}"></span>`;
                });
                html += '</div>';
            }

            html += '</div>';
        }

        $('#calendario-dias').html(html);
    }

    function mesAnterior() {
        calendarioMes--;
        if (calendarioMes < 1) {
            calendarioMes = 12;
            calendarioAno--;
        }
        carregarCalendario(calendarioMes, calendarioAno);
    }

    function mesSeguinte() {
        calendarioMes++;
        if (calendarioMes > 12) {
            calendarioMes = 1;
            calendarioAno++;
        }
        carregarCalendario(calendarioMes, calendarioAno);
    }

    function mesAtual() {
        const hoje = new Date();
        calendarioMes = hoje.getMonth() + 1;
        calendarioAno = hoje.getFullYear();
        carregarCalendario(calendarioMes, calendarioAno);
    }

    function verEventosDia(dia, mes, ano) {
        // Pode ser expandido para mostrar detalhes do dia
        console.log('Ver eventos do dia:', dia, mes, ano);
    }

    function carregarProximosEventos() {
        const dias = $('#filtro-dias-eventos').val() || 30;

        $('#lista-eventos').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x" style="color: var(--accent-green);"></i></div>');

        $.ajax({
            url: 'controller/calendario_ajax.php',
            type: 'GET',
            dataType: 'json',
            data: { acao: 'proximos', dias: dias },
            success: function(response) {
                if (response.success && response.eventos.length > 0) {
                    let html = '';

                    response.eventos.forEach(evento => {
                        html += `
                            <div class="evento-item ${evento.cor}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div style="flex: 1; min-width: 0;">
                                        <div class="evento-titulo">
                                            <i class="fas ${evento.icone} me-1"></i>
                                            ${evento.titulo}
                                        </div>
                                        <div class="evento-descricao" title="${escapeHtml(evento.descricao)}">
                                            ${escapeHtml(evento.descricao)}
                                        </div>
                                    </div>
                                    <div class="text-end ms-2">
                                        <div class="evento-data">${evento.quando}</div>
                                        <small class="text-muted">${evento.data_formatada}</small>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    $('#lista-eventos').html(html);
                } else {
                    $('#lista-eventos').html(`
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-calendar-check fa-2x mb-2"></i>
                            <p class="mb-0">Nenhum evento nos próximos ${dias} dias</p>
                        </div>
                    `);
                }
            },
            error: function() {
                $('#lista-eventos').html('<div class="text-center text-danger py-3">Erro ao carregar eventos</div>');
            }
        });
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    <?php endif; ?>
</script>

<?php
include_once 'footer.php';
?>