<?php
/**
 * Aba de Histórico de Alterações do Obrigatório
 * Exibe uma timeline visual com todas as modificações feitas no registro
 *
 * Variáveis disponíveis do obrigatorio.php:
 * - $historico_obrigatorio (array de logs)
 * - $obrigatorio (objeto Obrigatorio)
 * - $id_obrigatorio (int)
 */
?>

<style>
/* CSS mínimo para timeline - estrutura que o Bootstrap não cobre */
.timeline-historico {
    position: relative;
    padding: 20px 0 0 0;
}
.timeline-historico::before {
    content: '';
    position: absolute;
    left: 30px;
    top: 0;
    bottom: 0;
    width: 3px;
    background: #dee2e6;
    border-radius: 3px;
}
.timeline-item {
    position: relative;
    padding-left: 70px;
    padding-bottom: 20px;
}
.timeline-item:last-child { padding-bottom: 0; }
.timeline-icon {
    position: absolute;
    left: 15px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    z-index: 1;
}
.timeline-date-label {
    display: inline-block;
    margin-left: 55px;
    margin-bottom: 15px;
}
/* Cor padrão do sistema */
.cor-sistema { color: #006400; }
.bg-sistema { background-color: #006400; }

/* Cores dos ícones da timeline harmonizadas com o sistema */
.timeline-icon.bg-success { background-color: #006400 !important; }
.timeline-icon.bg-primary { background-color: #228b22 !important; }
.timeline-icon.bg-danger { background-color: #8b0000 !important; }
.timeline-icon.bg-warning { background-color: #b8860b !important; }
.timeline-icon.bg-secondary { background-color: #556b2f !important; }
.timeline-icon.bg-info { background-color: #2f4f4f !important; }

/* Badges de tipo harmonizados */
.badge.bg-opacity-10.text-success { color: #006400 !important; background-color: rgba(0,100,0,0.15) !important; }
.badge.bg-opacity-10.text-primary { color: #228b22 !important; background-color: rgba(34,139,34,0.15) !important; }
.badge.bg-opacity-10.text-danger { color: #8b0000 !important; background-color: rgba(139,0,0,0.15) !important; }
.badge.bg-opacity-10.text-warning { color: #8b6914 !important; background-color: rgba(184,134,11,0.15) !important; }
.badge.bg-opacity-10.text-secondary { color: #556b2f !important; background-color: rgba(85,107,47,0.15) !important; }
.badge.bg-opacity-10.text-info { color: #2f4f4f !important; background-color: rgba(47,79,79,0.15) !important; }
</style>

<?php
// $historico_obrigatorio vem do obrigatorio.php via LogDAO::findByIdObrigatorio()
// $usuarioDAO já está disponível no escopo do obrigatorio.php

// Pré-carregar nomes de usuários para evitar múltiplas queries
$usuarios_cache = [];
if ($historico_obrigatorio) {
    foreach ($historico_obrigatorio as $log) {
        $id_usuario = $log['id_usuario'] ?? null;
        if ($id_usuario && !isset($usuarios_cache[$id_usuario])) {
            $usuario = $usuarioDAO->findById($id_usuario);
            $usuarios_cache[$id_usuario] = $usuario ? $usuario->getNomeGuerra() : 'Sistema';
        }
    }
}
?>

<div class="card border-0">
    <div class="card-header bg-white border-bottom">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">
                <i class="fas fa-history cor-sistema me-2"></i>
                Histórico de Alterações
            </h5>
            <?php if ($historico_obrigatorio): ?>
            <span class="badge bg-sistema">
                <?php echo count($historico_obrigatorio); ?> registro(s)
            </span>
            <?php endif; ?>
        </div>
    </div>

    <div class="card-body">
        <?php if ($historico_obrigatorio && count($historico_obrigatorio) > 0): ?>

            <?php
            // Calcular estatísticas
            $total_inserts = 0;
            $total_updates = 0;
            $total_deletes = 0;
            $total_pdfs = 0;

            foreach ($historico_obrigatorio as $log) {
                $codigo = (int)$log['codigo'];
                if ($codigo >= 1000 && $codigo < 2000) $total_inserts++;
                elseif ($codigo >= 2000 && $codigo < 3000) $total_deletes++;
                elseif ($codigo >= 3000 && $codigo < 4000) $total_updates++;
                elseif ($codigo >= 4000 && $codigo < 5000) $total_pdfs++;
            }
            ?>

            <!-- Estatísticas -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card bg-light border text-center p-3">
                        <div class="fs-3 fw-bold text-success"><?php echo $total_inserts; ?></div>
                        <small class="text-muted text-uppercase">Cadastros</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card bg-light border text-center p-3">
                        <div class="fs-3 fw-bold text-primary"><?php echo $total_updates; ?></div>
                        <small class="text-muted text-uppercase">Atualizações</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card bg-light border text-center p-3">
                        <div class="fs-3 fw-bold text-danger"><?php echo $total_deletes; ?></div>
                        <small class="text-muted text-uppercase">Exclusões</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card bg-light border text-center p-3">
                        <div class="fs-3 fw-bold text-secondary"><?php echo $total_pdfs; ?></div>
                        <small class="text-muted text-uppercase">PDFs Gerados</small>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="timeline-historico">
                <?php
                $data_anterior = null;

                foreach ($historico_obrigatorio as $log):
                    $operacao = LogDAO::getOperacaoInfo((int)$log['codigo']);
                    $data_log = $log['data'] ? date('d/m/Y', strtotime($log['data'])) : '';
                    $hora_log = $log['data'] ? date('H:i', strtotime($log['data'])) : '';

                    // Agrupar por data
                    if ($data_log != $data_anterior):
                        $data_anterior = $data_log;

                        // Formatar data de forma amigável
                        $hoje = date('d/m/Y');
                        $ontem = date('d/m/Y', strtotime('-1 day'));

                        if ($data_log == $hoje) {
                            $data_formatada = 'Hoje';
                        } elseif ($data_log == $ontem) {
                            $data_formatada = 'Ontem';
                        } else {
                            $data_formatada = $data_log;
                        }
                ?>
                    <span class="timeline-date-label badge bg-sistema rounded-pill shadow-sm">
                        <i class="far fa-calendar-alt me-1"></i>
                        <?php echo $data_formatada; ?>
                    </span>
                <?php endif; ?>

                <div class="timeline-item">
                    <div class="timeline-icon bg-<?php echo $operacao['cor']; ?> shadow-sm">
                        <i class="fas <?php echo $operacao['icone']; ?>"></i>
                    </div>
                    <div class="card border shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                                <h6 class="fw-semibold mb-0"><?php echo htmlspecialchars($log['alteracao']); ?></h6>
                                <span class="badge bg-<?php echo $operacao['cor']; ?> bg-opacity-10 text-<?php echo $operacao['cor']; ?> text-uppercase small">
                                    <?php echo $operacao['tipo']; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-wrap gap-3 small text-muted">
                                <span title="Usuário">
                                    <i class="fas fa-user me-1"></i>
                                    <?php echo htmlspecialchars($usuarios_cache[$log['id_usuario']] ?? 'Sistema'); ?>
                                </span>
                                <span title="Horário">
                                    <i class="far fa-clock me-1"></i>
                                    <?php echo $hora_log; ?>
                                </span>
                                <?php if (!empty($log['ip'])): ?>
                                <span title="IP">
                                    <i class="fas fa-network-wired me-1"></i>
                                    <?php echo htmlspecialchars($log['ip']); ?>
                                </span>
                                <?php endif; ?>
                                <span title="Código da Operação">
                                    <i class="fas fa-hashtag me-1"></i>
                                    <?php echo $log['codigo']; ?>
                                </span>
                            </div>

                            <?php if (!empty($log['alteracao_detalhada'])): ?>
                            <div class="mt-2 pt-2 border-top small text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                <?php echo nl2br(htmlspecialchars($log['alteracao_detalhada'])); ?>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($log['sistema'])): ?>
                            <div class="mt-2 small text-muted">
                                <i class="fas fa-desktop me-1"></i>
                                <?php echo htmlspecialchars($log['sistema']); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <!-- Estado vazio -->
            <div class="text-center py-5 text-muted">
                <i class="fas fa-history fa-4x mb-3 opacity-25"></i>
                <h5>Nenhum histórico encontrado</h5>
                <p class="mb-3">
                    Não há registros de alterações para este obrigatório.
                </p>
                <small>
                    <i class="fas fa-info-circle me-1"></i>
                    O histórico é registrado automaticamente a partir de novas edições.<br>
                    Cadastros anteriores à implementação do sistema de auditoria não possuem histórico.
                </small>
            </div>
        <?php endif; ?>
    </div>
</div>
