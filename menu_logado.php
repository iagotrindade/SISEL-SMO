<?php
// Carrega contagem de notificações
include_once __DIR__ . '/models/Notificacao.php';
include_once __DIR__ . '/dao/NotificacaoDAO.php';

$notificacaoDAO = new NotificacaoDAO($conexao);

// Executa verificação diária de notificações (cron simulado)
$notificacaoDAO->gerarNotificacoesAutomaticas();

// Conta notificações não lidas
$perfilMenu = $_SESSION['perfil_smo'];
$idOmMenu = ($perfilMenu == 'operador' && !empty($_SESSION['id_om_smo'])) ? (int)$_SESSION['id_om_smo'] : null;
$totalNotificacoes = $notificacaoDAO->countNaoLidas($perfilMenu, $idOmMenu);
$notifPorCriticidade = $notificacaoDAO->countPorCriticidade($perfilMenu, $idOmMenu);

// Busca as últimas 5 notificações para o dropdown
$ultimasNotificacoes = $notificacaoDAO->findAtivas($perfilMenu, $idOmMenu, ['limite' => 5]);
?>

<header id="header" class="fixed-top d-flex align-items-center">
    <div class="container d-flex justify-content-between">

        <div class="logo d-flex align-items-center">
            <h1><a href="<?= $_SESSION['perfil_smo'] == 'admin' ? 'index.php' : 'dashboard_operador.php' ?>"><i class="fa fa-stethoscope"></i> SiSel - SMO</a></h1>
        </div>

        <nav id="navbar" class="navbar">
            <ul>
                <?php if ($_SESSION['perfil_smo'] == 'admin') : ?>
                    <li class="<?= $_SESSION['perfil_smo'] != 'admin' ? 'menu-hidden' : '' ?>">
                        <a class="nav-link" href="index.php">Painel Inicial</a>
                    </li>
                <?php endif; ?>

                <?php if ($_SESSION['perfil_smo'] == 'operador') : ?>
                    <li>
                        <a class="nav-link" href="dashboard_operador.php">Painel Inicial</a>
                    </li>

                    <li>
                        <a class="nav-link" href="distribuidos_om_1_fase.php">Distribuídos OM 1ª Fase</a>
                    </li>

                    <li>
                        <a class="nav-link" href="revisao_medica.php">Revisão Médica</a>
                    </li>
                <?php endif; ?>

                <?php if ($_SESSION['perfil_smo'] == 'admin') : ?>

                    <li>
                        <a class="nav-link" href="obrigatorios.php">Obrigatórios</a>
                    </li>

                    <li>
                        <a class="nav-link" href="pre_distribuicao.php">Pré Distribuição MFDV</a>
                    </li>

                    <li class="dropdown">
                        <a href="#">
                            <span>Relatórios</span>
                            <i class="bi bi-chevron-down"></i>
                        </a>
                        <ul>
                            <li><a href="relatorios.php">Publicações e Documentos</a></li>
                            <li><a href="relatorios_avancados.php">Relatórios Avançados</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#">
                            <span>Cadastros</span>
                            <i class="bi bi-chevron-down"></i>
                        </a>
                        <ul>
                            <li><a href="obrigatorio_cadastra.php">Obrigatório</a></li>
                            <li><a href="fisemi_upload.php">Importar FISEMI (OCR)</a></li>
                            <li><a href="usuarios.php">Usuário</a></li>
                            <li><a href="especialidades.php">Especialidade</a></li>
                            <li><a href="graduacoes.php">Graduação</a></li>
                            <li><a href="guarnicoes.php">Guarnição</a></li>
                            <li><a href="oms.php">OM</a></li>
                            <li><a href="junta_saude_cadastra.php">JISE</a></li>
                            <li><a href="configuracoes.php">Configurações</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#">
                            <span>Auditoria</span>
                            <i class="bi bi-chevron-down"></i>
                        </a>
                        <ul>
                            <li><a href="auditoria.php">Logs</a></li>
                            <li><a href="acessos.php">Acessos</a></li>
                        </ul>
                    </li>

                <?php endif; ?>

                <!-- Notificações -->
                <li class="nav-notification">
                    <a class="nav-link position-relative" href="notificacoes.php" title="Notificações" id="notification-link">
                        <i class="fas fa-bell"></i>
                        <?php if ($totalNotificacoes > 0): ?>
                            <span class="badge bg-danger rounded-pill position-absolute notification-badge" id="notification-badge">
                                <?= $totalNotificacoes > 99 ? '99+' : $totalNotificacoes ?>
                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger rounded-pill position-absolute notification-badge" id="notification-badge" style="display: none;">0</span>
                        <?php endif; ?>
                    </a>
                    <!-- Dropdown de preview -->
                    <div class="notification-dropdown" id="notification-dropdown">
                        <div class="notification-header">
                            <strong>Notificações</strong>
                            <?php if ($totalNotificacoes > 0): ?>
                                <span class="badge bg-danger"><?= $totalNotificacoes ?> nova(s)</span>
                            <?php endif; ?>
                        </div>
                        <div class="notification-list">
                            <?php if (empty($ultimasNotificacoes)): ?>
                                <div class="notification-empty">
                                    <i class="fas fa-bell-slash"></i>
                                    <span>Nenhuma notificação</span>
                                </div>
                            <?php else: ?>
                                <?php foreach ($ultimasNotificacoes as $notifMenu):
                                    $cripMenu = $notifMenu->getIdObrigatorio() ? hash('sha256', $notifMenu->getIdObrigatorio() . "criptografia") : '';
                                    $paginaObrig = ($perfilMenu == 'admin') ? 'obrigatorio.php' : 'obrigatorio_om.php';
                                ?>
                                <a href="<?= $notifMenu->getIdObrigatorio() ? $paginaObrig . '?crip=' . $cripMenu . '&id_obrigatorio=' . $notifMenu->getIdObrigatorio() : 'notificacoes.php' ?>" class="notification-item-menu <?= $notifMenu->getCriticidade() ?> <?= $notifMenu->isLida() ? 'lida' : '' ?>">
                                    <div class="notif-icon <?= $notifMenu->getCriticidade() ?>">
                                        <i class="fas <?= $notifMenu->getTipoIcon() ?>"></i>
                                    </div>
                                    <div class="notif-content">
                                        <div class="notif-title"><?= htmlspecialchars($notifMenu->getTitulo()) ?></div>
                                        <div class="notif-time"><?= $notifMenu->getTempoRelativo() ?></div>
                                    </div>
                                </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <a href="notificacoes.php" class="notification-footer">
                            Ver todas as notificações <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </li>

                <li>
                    <a class="nav-link" href="controller/logout.php">Sair</a>
                </li>

            </ul>

            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>
    </div>
</header>

<style>
/* Estilos do sistema de notificações no menu */
.nav-notification {
    position: relative;
}
.nav-notification .nav-link {
    padding: 10px 15px !important;
}
.notification-badge {
    top: 5px !important;
    right: 5px !important;
    font-size: 0.65rem !important;
    padding: 0.25em 0.5em !important;
    min-width: 18px;
}
.notification-dropdown {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    width: 320px;
    background: var(--white, #fff);
    border-radius: 8px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.15);
    z-index: 9999;
    overflow: hidden;
}
.nav-notification:hover .notification-dropdown {
    display: block;
}
.notification-header {
    padding: 12px 15px;
    background: var(--gray-dark, #f8f9fa);
    border-bottom: 1px solid var(--border-color, #dee2e6);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.notification-summary {
    padding: 15px;
    text-align: center;
}
.notification-list {
    max-height: 300px;
    overflow-y: auto;
}
.notification-empty {
    padding: 30px 15px;
    text-align: center;
    color: #6c757d;
}
.notification-empty i {
    font-size: 2rem;
    display: block;
    margin-bottom: 10px;
}
.notification-item-menu {
    display: flex;
    align-items: flex-start;
    padding: 12px 15px;
    border-bottom: 1px solid var(--border-color, #eee);
    text-decoration: none;
    color: inherit;
    transition: background 0.2s;
    border-left: 3px solid transparent;
}
.notification-item-menu:hover {
    background: var(--gray-dark, #f8f9fa);
}
.notification-item-menu.lida {
    opacity: 0.6;
}
.notification-item-menu.alta {
    border-left-color: #dc3545;
}
.notification-item-menu.media {
    border-left-color: #ffc107;
}
.notification-item-menu.baixa {
    border-left-color: #17a2b8;
}
.notif-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
    flex-shrink: 0;
    font-size: 0.85rem;
}
.notif-icon.alta {
    background: rgba(220, 53, 69, 0.15);
    color: #dc3545;
}
.notif-icon.media {
    background: rgba(255, 193, 7, 0.15);
    color: #856404;
}
.notif-icon.baixa {
    background: rgba(23, 162, 184, 0.15);
    color: #17a2b8;
}
.notif-content {
    flex: 1;
    min-width: 0;
}
.notif-title {
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--text-color, #333);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.notif-time {
    font-size: 0.75rem;
    color: #6c757d;
    margin-top: 2px;
}
.notification-footer {
    display: block;
    padding: 12px 15px;
    background: var(--gray-dark, #f8f9fa);
    border-top: 1px solid var(--border-color, #dee2e6);
    text-align: center;
    color: #006400;
    text-decoration: none;
    font-size: 0.9rem;
}
.notification-footer:hover {
    background: #006400;
    color: #fff;
}

/* Animação do sino quando há notificações */
@keyframes bellShake {
    0%, 100% { transform: rotate(0); }
    25% { transform: rotate(10deg); }
    50% { transform: rotate(-10deg); }
    75% { transform: rotate(5deg); }
}
.nav-notification .fa-bell {
    animation: bellShake 0.5s ease-in-out;
}
.nav-notification:hover .fa-bell {
    animation: bellShake 0.3s ease-in-out infinite;
}
</style>