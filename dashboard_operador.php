<?php
include_once 'header.php';
include_once 'dao/conecta_banco.php';
include_once 'models/Obrigatorio.php';
include_once 'dao/ObrigatorioDAO.php';
include_once 'dao/LogDAO.php';
include_once 'dao/ConfiguracaoDAO.php';

if (!isset($_SESSION['id_usuario_smo'])) {
    erro($BASE_URL, 2, 236325634, $pagina_atual, "usuario_nao_logado", "Página não encontrada!");
    exit();
}

// Apenas operadores podem acessar esta página
if ($_SESSION['perfil_smo'] != 'operador') {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['mensagem'])) $_SESSION['mensagem'] = null;

// Busca dados específicos da OM do operador
$ObrigatorioDAO = new ObrigatorioDAO($conexao);
$configuracaoDAO = new ConfiguracaoDAO($conexao);
$logDAO = new LogDAO($conexao);

// Verifica se a visualização está liberada
$visualizacao_liberada = $configuracaoDAO->isAtivo('visualizacao_distribuidos_om_1_fase');

// Estatísticas da OM
$anoAtual = (int)date('Y');
$id_om = $_SESSION['id_om_smo'];

// Carrega estatísticas iniciais (serão atualizadas via AJAX)
$total_designados = $ObrigatorioDAO->countDesignadosOM($id_om, $anoAtual);
$total_aptos = $ObrigatorioDAO->countAptosOM($id_om, $anoAtual);
$total_inaptos = $ObrigatorioDAO->countInaptosOM($id_om, $anoAtual);
$pendentes_revisao = $ObrigatorioDAO->countPendentesRevisaoOM($id_om, $anoAtual);

// Últimas atividades do operador
$ultimasAtividades = $logDAO->getUltimasAtividades(5, $_SESSION['id_usuario_smo']);

?>

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h1><i class="fas fa-tachometer-alt me-3"></i>Bem-vindo ao SISEL - SMO</h1>
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

    <section class="section-bg" style="padding: 3rem 0;">
        <div class="container">

            <?php if (!$visualizacao_liberada): ?>
            <!-- Aviso de visualização não liberada -->
            <div class="row justify-content-center mb-4">
                <div class="col-lg-10">
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle me-3 fs-4"></i>
                        <div>
                            <strong>Atenção:</strong> A visualização dos candidatos distribuídos ainda não foi liberada pela Seção de Serviço Militar.
                            Algumas estatísticas podem não estar disponíveis.
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Dashboard Principal -->
            <div class="row">
                <div class="col-12" data-aos="fade-up">
                    <div class="card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                            <h3 class="mb-0 d-flex align-items-center">
                                <i class="fas fa-chart-bar me-2" style="color: var(--accent-green);"></i>
                                Estatísticas da Sua OM
                            </h3>
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="filtro_ano_dashboard" class="form-label mb-0">Ano:</label>
                                    <select id="filtro_ano_dashboard" class="form-select form-select-sm" style="width: auto;">
                                        <?php
                                        for ($a = $anoAtual; $a >= 2023; $a--) {
                                            $selected = ($a == $anoAtual) ? 'selected' : '';
                                            echo "<option value=\"$a\" $selected>$a</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Cards de Indicadores -->
                        <div class="row mb-4">
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="p-3 h-100" style="background: var(--gray-dark); border-radius: var(--border-radius); border-left: 4px solid var(--accent-green);">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; background: rgba(0, 100, 0, 0.15);">
                                            <i class="fas fa-users fs-4" style="color: var(--accent-green);"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-0 fw-bold" id="indicador-designados" style="color: var(--accent-green);"><?= $total_designados ?></h3>
                                            <small class="text-muted">Total Designados</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="p-3 h-100" style="background: var(--gray-dark); border-radius: var(--border-radius); border-left: 4px solid #198754;">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; background: rgba(25, 135, 84, 0.15);">
                                            <i class="fas fa-check-circle fs-4" style="color: #198754;"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-0 fw-bold" id="indicador-aptos" style="color: #198754;"><?= $total_aptos ?></h3>
                                            <small class="text-muted">Aptos</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="p-3 h-100" style="background: var(--gray-dark); border-radius: var(--border-radius); border-left: 4px solid #dc3545;">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; background: rgba(220, 53, 69, 0.15);">
                                            <i class="fas fa-times-circle fs-4" style="color: #dc3545;"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-0 fw-bold" id="indicador-inaptos" style="color: #dc3545;"><?= $total_inaptos ?></h3>
                                            <small class="text-muted">Inaptos</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="p-3 h-100" style="background: var(--gray-dark); border-radius: var(--border-radius); border-left: 4px solid #ffc107;">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; background: rgba(255, 193, 7, 0.15);">
                                            <i class="fas fa-clock fs-4" style="color: #ffc107;"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-0 fw-bold" id="indicador-pendentes" style="color: #ffc107;"><?= $pendentes_revisao ?></h3>
                                            <small class="text-muted">Pendentes Revisão</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gráficos -->
                        <div class="row">
                            <!-- Gráfico: Resultado Revisão Médica -->
                            <div class="col-md-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">
                                        <i class="fas fa-heartbeat me-2" style="color: var(--accent-green);"></i>Resultado Revisão Médica
                                    </h5>
                                </div>
                                <div class="p-3" style="background: var(--gray-dark); border-radius: var(--border-radius); height: 300px; position: relative;">
                                    <canvas id="chartRevisao"></canvas>
                                </div>
                            </div>

                            <!-- Gráfico: Por Especialidade -->
                            <div class="col-md-6 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">
                                        <i class="fas fa-user-md me-2" style="color: var(--accent-green);"></i>Por Especialidade
                                    </h5>
                                </div>
                                <div class="p-3" style="background: var(--gray-dark); border-radius: var(--border-radius); height: 300px; position: relative;">
                                    <canvas id="chartEspecialidade"></canvas>
                                </div>
                            </div>

                            <!-- Gráfico: Por Distribuição -->
                            <div class="col-md-12 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">
                                        <i class="fas fa-sitemap me-2" style="color: var(--accent-green);"></i>Por Tipo de Distribuição
                                    </h5>
                                </div>
                                <div class="p-3" style="background: var(--gray-dark); border-radius: var(--border-radius); height: 300px; position: relative;">
                                    <canvas id="chartDistribuicao"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="row mt-4">
                <div class="col-12" data-aos="fade-up">
                    <div class="card p-4">
                        <h3 class="mb-4 d-flex align-items-center">
                            <i class="bi bi-lightning-charge-fill me-2 text-success"></i>
                            Ações Rápidas
                        </h3>

                        <div class="row">
                            <div class="col-md-4 mb-3" data-aos="fade-up" data-aos-delay="100">
                                <a href="distribuidos_om_1_fase.php" class="btn btn-primary w-100">
                                    <i class="bi bi-diagram-3-fill me-2"></i>
                                    <span>Distribuídos OM 1ª Fase</span>
                                </a>
                            </div>

                            <div class="col-md-4 mb-3" data-aos="fade-up" data-aos-delay="150">
                                <a href="revisao_medica.php" class="btn btn-primary w-100">
                                    <i class="bi bi-heart-pulse-fill me-2"></i>
                                    <span>Revisão Médica</span>
                                </a>
                            </div>

                            <div class="col-md-4 mb-3" data-aos="fade-up" data-aos-delay="200">
                                <a href="controller/logout.php" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-box-arrow-left me-2"></i>
                                    <span>Sair</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações do Sistema e Atividades -->
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
                            Suas Atividades Recentes
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

<script src="assets/js/chart.min.js"></script>
<script type="text/javascript">
    // Variáveis globais para os gráficos
    let chartRevisao, chartEspecialidade, chartDistribuicao;

    // Paleta de cores
    const coresPrimarias = [
        '#006400', '#228b22', '#32cd32', '#90ee90', '#98fb98',
        '#3cb371', '#2e8b57', '#66cdaa', '#20b2aa', '#008b8b'
    ];

    $(document).ready(function() {
        // Auto-hide mensagens de sucesso após 5 segundos
        setTimeout(function() {
            $('.alert-success').fadeOut('slow');
        }, 5000);

        // Carrega os gráficos iniciais
        carregarDados($('#filtro_ano_dashboard').val());

        // Atualiza ao mudar o ano
        $('#filtro_ano_dashboard').on('change', function() {
            carregarDados($(this).val());
        });
    });

    function carregarDados(ano) {
        $.ajax({
            url: 'controller/dashboard_operador_ajax.php',
            type: 'GET',
            dataType: 'json',
            data: { ano: ano },
            success: function(response) {
                // Atualiza indicadores
                $('#indicador-designados').text(response.total_designados);
                $('#indicador-aptos').text(response.total_aptos);
                $('#indicador-inaptos').text(response.total_inaptos);
                $('#indicador-pendentes').text(response.pendentes_revisao);

                // Renderiza gráficos
                renderizarGraficoRevisao(response.resultado_revisao);
                renderizarGraficoEspecialidade(response.especialidades);
                renderizarGraficoDistribuicao(response.distribuicao);
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar dados:', error);
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
            type: 'doughnut',
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
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
    }

    function renderizarGraficoEspecialidade(dados) {
        const ctx = document.getElementById('chartEspecialidade').getContext('2d');

        if (chartEspecialidade) {
            chartEspecialidade.destroy();
        }

        chartEspecialidade = new Chart(ctx, {
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
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
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

    function renderizarGraficoDistribuicao(dados) {
        const ctx = document.getElementById('chartDistribuicao').getContext('2d');

        if (chartDistribuicao) {
            chartDistribuicao.destroy();
        }

        chartDistribuicao = new Chart(ctx, {
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
                        callbacks: {
                            label: function(context) {
                                return ' Quantidade: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: getComputedStyle(document.documentElement).getPropertyValue('--text-secondary').trim() || '#666' },
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
</script>

<?php
include_once 'footer.php';
?>
