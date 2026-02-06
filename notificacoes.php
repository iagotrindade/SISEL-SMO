<?php
include_once 'header.php';
include_once 'dao/conecta_banco.php';
include_once 'models/Notificacao.php';
include_once 'dao/NotificacaoDAO.php';

if (!isset($_SESSION['id_usuario_smo'])) {
    erro($BASE_URL, 2, 63216754, $pagina_atual, "usuario_nao_logado", "Página não encontrada!");
    exit();
}

$notificacaoDAO = new NotificacaoDAO($conexao);

// Parâmetros de filtro
$perfil = $_SESSION['perfil_smo'];
$idOm = ($perfil == 'operador' && !empty($_SESSION['id_om_smo'])) ? (int)$_SESSION['id_om_smo'] : null;

$filtros = [];
if (isset($_GET['criticidade']) && !empty($_GET['criticidade'])) {
    $filtros['criticidade'] = $_GET['criticidade'];
}
if (isset($_GET['tipo']) && !empty($_GET['tipo'])) {
    $filtros['tipo'] = $_GET['tipo'];
}
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'lidas') {
        $filtros['lida'] = 1;
    } elseif ($_GET['status'] === 'nao_lidas') {
        $filtros['lida'] = 0;
    }
}

// Busca notificações
$notificacoes = $notificacaoDAO->findAtivas($perfil, $idOm, $filtros);
$contagens = $notificacaoDAO->countPorCriticidade($perfil, $idOm);
$totalNaoLidas = $notificacaoDAO->countNaoLidas($perfil, $idOm);
?>

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="section-title">
                <h1><b><i class="fas fa-bell"></i> Notificações</b></h1>
            </div>
        </div>
    </section>
</main>

<section id="contact" class="contact">
    <div class="container">

        <!-- Cards de estatísticas -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="display-6 fw-bold text-success"><?= $totalNaoLidas ?></div>
                        <div class="text-muted small">Total Não Lidas</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #dc3545 !important;">
                    <div class="card-body text-center">
                        <div class="display-6 fw-bold text-danger"><?= $contagens['alta'] ?></div>
                        <div class="text-muted small">Criticidade Alta</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #ffc107 !important;">
                    <div class="card-body text-center">
                        <div class="display-6 fw-bold text-warning"><?= $contagens['media'] ?></div>
                        <div class="text-muted small">Criticidade Média</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #17a2b8 !important;">
                    <div class="card-body text-center">
                        <div class="display-6 fw-bold text-info"><?= $contagens['baixa'] ?></div>
                        <div class="text-muted small">Criticidade Baixa</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card de Filtros -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filtros</h5>
                <div class="btn-group">
                    <?php if ($perfil == 'admin'): ?>
                    <button class="btn btn-sm btn-outline-primary" onclick="forcarVerificacao()" title="Verificar agora">
                        <i class="fas fa-sync-alt me-1"></i>Verificar Agora
                    </button>
                    <?php endif; ?>
                    <?php if ($totalNaoLidas > 0): ?>
                    <button class="btn btn-sm btn-outline-success" onclick="marcarTodasLidas()">
                        <i class="fas fa-check-double me-1"></i>Marcar todas como lidas
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-3">
                        <label class="form-label fw-semibold">Criticidade</label>
                        <select class="form-select" id="filtroCriticidade" onchange="aplicarFiltros()">
                            <option value="">Todas</option>
                            <option value="alta" <?= (isset($_GET['criticidade']) && $_GET['criticidade'] == 'alta') ? 'selected' : '' ?>>Alta</option>
                            <option value="media" <?= (isset($_GET['criticidade']) && $_GET['criticidade'] == 'media') ? 'selected' : '' ?>>Média</option>
                            <option value="baixa" <?= (isset($_GET['criticidade']) && $_GET['criticidade'] == 'baixa') ? 'selected' : '' ?>>Baixa</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label fw-semibold">Tipo</label>
                        <select class="form-select" id="filtroTipo" onchange="aplicarFiltros()">
                            <option value="">Todos</option>
                            <option value="adiamento_vencendo" <?= (isset($_GET['tipo']) && $_GET['tipo'] == 'adiamento_vencendo') ? 'selected' : '' ?>>Adiamento Vencendo</option>
                            <option value="incorporacao_sem_revisao" <?= (isset($_GET['tipo']) && $_GET['tipo'] == 'incorporacao_sem_revisao') ? 'selected' : '' ?>>Incorporação sem Revisão</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select class="form-select" id="filtroStatus" onchange="aplicarFiltros()">
                            <option value="">Todas</option>
                            <option value="nao_lidas" <?= (isset($_GET['status']) && $_GET['status'] == 'nao_lidas') ? 'selected' : '' ?>>Não lidas</option>
                            <option value="lidas" <?= (isset($_GET['status']) && $_GET['status'] == 'lidas') ? 'selected' : '' ?>>Lidas</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <a href="notificacoes.php" class="btn btn-secondary w-100">
                            <i class="fas fa-times me-1"></i>Limpar Filtros
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de notificações -->
        <?php if (empty($notificacoes)): ?>
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhuma notificação encontrada</h5>
                    <p class="text-muted mb-0">Não há notificações que correspondam aos filtros selecionados.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Listagem (<?= count($notificacoes) ?> registro(s))</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php foreach ($notificacoes as $notif): ?>
                            <div class="list-group-item notification-item <?= $notif->isLida() ? 'lida' : '' ?>" id="notif-<?= $notif->getId() ?>" data-criticidade="<?= $notif->getCriticidade() ?>">
                                <div class="d-flex align-items-start">
                                    <!-- Indicador de criticidade -->
                                    <div class="notification-indicator me-3 flex-shrink-0 <?= $notif->getCriticidade() ?>">
                                        <i class="fas <?= $notif->getTipoIcon() ?>"></i>
                                    </div>

                                    <!-- Conteúdo -->
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <h6 class="mb-0 fw-bold">
                                                <?= htmlspecialchars($notif->getTitulo()) ?>
                                                <?php if (!$notif->isLida()): ?>
                                                    <span class="badge bg-success ms-2">Nova</span>
                                                <?php endif; ?>
                                            </h6>
                                            <small class="text-muted ms-2 text-nowrap"><?= $notif->getTempoRelativo() ?></small>
                                        </div>

                                        <p class="mb-2 text-muted small"><?= htmlspecialchars($notif->getMensagem()) ?></p>

                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                            <div>
                                                <span class="badge bg-<?= $notif->getCriticidadeClass() ?>">
                                                    <?= $notif->getCriticidadeLabel() ?>
                                                </span>
                                                <span class="badge bg-secondary">
                                                    <?= $notif->getTipoLabel() ?>
                                                </span>
                                                <?php if ($notif->getOmAbreviatura()): ?>
                                                    <span class="badge bg-dark">
                                                        <i class="fas fa-building me-1"></i><?= htmlspecialchars($notif->getOmAbreviatura()) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="btn-group btn-group-sm">
                                                <?php if ($notif->getIdObrigatorio()):
                                                    $cripNotif = hash('sha256', $notif->getIdObrigatorio() . "criptografia");
                                                    $paginaObrigatorio = ($perfil == 'admin') ? 'obrigatorio.php' : 'obrigatorio_om.php';
                                                ?>
                                                    <a href="<?= $paginaObrigatorio ?>?crip=<?= $cripNotif ?>&id_obrigatorio=<?= $notif->getIdObrigatorio() ?>" class="btn btn-outline-success" title="Ver obrigatório">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (!$notif->isLida()): ?>
                                                    <button class="btn btn-outline-success" onclick="marcarLida(<?= $notif->getId() ?>)" title="Marcar como lida">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <button class="btn btn-outline-secondary" onclick="arquivar(<?= $notif->getId() ?>)" title="Arquivar">
                                                    <i class="fas fa-archive"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>

<style>
/* Estilos das notificações - Padrão SMO */
.notification-item {
    border-left: 4px solid transparent !important;
    transition: all 0.2s ease;
    padding: 1rem 1.25rem !important;
}
.notification-item:hover {
    background-color: var(--gray-dark, #f8f9fa) !important;
}
.notification-item.lida {
    opacity: 0.7;
    background-color: var(--gray-darker, #f1f1f1) !important;
}
.notification-item[data-criticidade="alta"] {
    border-left-color: #dc3545 !important;
}
.notification-item[data-criticidade="media"] {
    border-left-color: #ffc107 !important;
}
.notification-item[data-criticidade="baixa"] {
    border-left-color: #17a2b8 !important;
}

.notification-indicator {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}
.notification-indicator.alta {
    background-color: rgba(220, 53, 69, 0.15);
    color: #dc3545;
}
.notification-indicator.media {
    background-color: rgba(255, 193, 7, 0.15);
    color: #856404;
}
.notification-indicator.baixa {
    background-color: rgba(23, 162, 184, 0.15);
    color: #17a2b8;
}

/* Botões padrão SMO */
.btn-outline-success {
    color: #006400;
    border-color: #006400;
}
.btn-outline-success:hover {
    background-color: #006400;
    border-color: #006400;
    color: #fff;
}
</style>

<script>
function aplicarFiltros() {
    const criticidade = document.getElementById('filtroCriticidade').value;
    const tipo = document.getElementById('filtroTipo').value;
    const status = document.getElementById('filtroStatus').value;

    let params = new URLSearchParams();
    if (criticidade) params.append('criticidade', criticidade);
    if (tipo) params.append('tipo', tipo);
    if (status) params.append('status', status);

    window.location.href = 'notificacoes.php' + (params.toString() ? '?' + params.toString() : '');
}

function marcarLida(id) {
    fetch('controller/notificacao_marcar_lida.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'id=' + id
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const item = document.getElementById('notif-' + id);
            item.classList.add('lida');
            const novaBadge = item.querySelector('.badge.bg-success');
            if (novaBadge) novaBadge.remove();
            const btnLida = item.querySelector('button[onclick*="marcarLida"]');
            if (btnLida) btnLida.remove();
            atualizarContador();
        }
    });
}

function marcarTodasLidas() {
    if (!confirm('Marcar todas as notificações como lidas?')) return;

    fetch('controller/notificacao_marcar_todas_lidas.php', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function arquivar(id) {
    if (!confirm('Arquivar esta notificação?')) return;

    fetch('controller/notificacao_arquivar.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'id=' + id
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('notif-' + id).remove();
            atualizarContador();
        }
    });
}

function atualizarContador() {
    fetch('controller/notificacao_count.php')
    .then(response => response.json())
    .then(data => {
        const badge = document.getElementById('notification-badge');
        if (badge) {
            badge.textContent = data.total;
            badge.style.display = data.total > 0 ? 'inline-block' : 'none';
        }
    });
}

function forcarVerificacao() {
    const btn = event.target.closest('button');
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Verificando...';

    fetch('controller/notificacao_forcar_verificacao.php', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;

        if (data.success) {
            alert(data.mensagem);
            location.reload();
        } else {
            alert('Erro: ' + (data.error || 'Falha na verificação'));
        }
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        alert('Erro na requisição');
    });
}
</script>

<?php include_once 'footer.php'; ?>
