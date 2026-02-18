<?php
/**
 * Aba de Timeline Visual do Obrigatório
 * Exibe todas as datas importantes em ordem cronológica
 *
 * Variáveis disponíveis do obrigatorio.php:
 * - $obrigatorio (objeto Obrigatorio)
 * - $id_obrigatorio (int)
 */

// Definir eventos com suas datas, labels, ícones e cores
$eventos_config = [
    ['getter' => 'getDataNascimento',                   'label' => 'Nascimento',                      'icone' => 'fa-baby',            'cor' => 'secondary', 'categoria' => 'Pessoal'],
    ['getter' => 'getDataCadastro',                      'label' => 'Cadastro no Sistema',              'icone' => 'fa-plus-circle',     'cor' => 'secondary', 'categoria' => 'Sistema'],
    ['getter' => 'getDataSelecaoGeral',                  'label' => 'Seleção Geral',                    'icone' => 'fa-clipboard-list',  'cor' => 'primary',   'categoria' => 'Seleção'],
    ['getter' => 'getDataComparecimentoSelecaoGeral',    'label' => 'Comparecimento Seleção Geral',     'icone' => 'fa-user-check',      'cor' => 'primary',   'categoria' => 'Seleção'],
    ['getter' => 'getDataJisea1',                        'label' => 'JISE A1',                          'icone' => 'fa-stethoscope',     'cor' => 'info',      'categoria' => 'Saúde'],
    ['getter' => 'getDataJisr',                          'label' => 'JISR',                             'icone' => 'fa-stethoscope',     'cor' => 'info',      'categoria' => 'Saúde'],
    ['getter' => 'getData_revisao_medica',               'label' => 'Revisão Médica',                   'icone' => 'fa-notes-medical',   'cor' => 'info',      'categoria' => 'Saúde'],
    ['getter' => 'getData_isgr',                         'label' => 'ISGR',                             'icone' => 'fa-file-medical',    'cor' => 'info',      'categoria' => 'Saúde'],
    ['getter' => 'getDataComparecimentoDesignacao',      'label' => 'Comparecimento Designação',        'icone' => 'fa-map-marked-alt',  'cor' => 'primary',   'categoria' => 'Distribuição'],
    ['getter' => 'getDataSelecaoComplementar',           'label' => 'Seleção Complementar',             'icone' => 'fa-clipboard-check', 'cor' => 'primary',   'categoria' => 'Seleção'],
    ['getter' => 'getInicioAdiamento',                   'label' => 'Início do Adiamento',              'icone' => 'fa-pause-circle',    'cor' => 'warning',   'categoria' => 'Adiamento'],
    ['getter' => 'getFimAdiamento',                      'label' => 'Fim do Adiamento',                 'icone' => 'fa-play-circle',     'cor' => 'warning',   'categoria' => 'Adiamento'],
    ['getter' => 'getDataLiminar',                       'label' => 'Data da Liminar',                  'icone' => 'fa-gavel',           'cor' => 'danger',    'categoria' => 'Justiça'],
    ['getter' => 'getDataIncorporacao',                  'label' => 'Incorporação',                     'icone' => 'fa-flag',            'cor' => 'success',   'categoria' => 'Incorporação'],
    ['getter' => 'getDataProximaApresentacao',           'label' => 'Próxima Apresentação',             'icone' => 'fa-calendar-day',    'cor' => 'warning',   'categoria' => 'Apresentação'],
];

// Coletar eventos com datas preenchidas
$eventos = [];
$hoje = new DateTime();

foreach ($eventos_config as $config) {
    $getter = $config['getter'];
    if (method_exists($obrigatorio, $getter)) {
        $data_valor = $obrigatorio->$getter();
        if (!empty($data_valor) && $data_valor != '0000-00-00') {
            $data_obj = DateTime::createFromFormat('Y-m-d', $data_valor);
            if (!$data_obj) $data_obj = DateTime::createFromFormat('d/m/Y', $data_valor);
            if ($data_obj) {
                $diff_dias = (int)$hoje->diff($data_obj)->format('%r%a');
                if ($diff_dias <= 0) {
                    $status = 'concluido';
                } elseif ($diff_dias <= 30) {
                    $status = 'proximo';
                } else {
                    $status = 'pendente';
                }

                $eventos[] = [
                    'data' => $data_obj,
                    'data_formatada' => $data_obj->format('d/m/Y'),
                    'label' => $config['label'],
                    'icone' => $config['icone'],
                    'cor' => $config['cor'],
                    'categoria' => $config['categoria'],
                    'status' => $status,
                    'diff_dias' => $diff_dias,
                ];
            }
        }
    }
}

// Ordenar cronologicamente
usort($eventos, function($a, $b) {
    return $a['data'] <=> $b['data'];
});

// Contadores
$total_concluidos = count(array_filter($eventos, fn($e) => $e['status'] === 'concluido'));
$total_proximos = count(array_filter($eventos, fn($e) => $e['status'] === 'proximo'));
$total_pendentes = count(array_filter($eventos, fn($e) => $e['status'] === 'pendente'));
?>

<style>
.timeline-visual {
    position: relative;
    padding: 20px 0 0 0;
}
.timeline-visual::before {
    content: '';
    position: absolute;
    left: 30px;
    top: 0;
    bottom: 0;
    width: 3px;
    background: #dee2e6;
    border-radius: 3px;
}
.tl-item {
    position: relative;
    padding-left: 70px;
    padding-bottom: 20px;
}
.tl-item:last-child { padding-bottom: 0; }
.tl-icon {
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
.tl-year-label {
    display: inline-block;
    margin-left: 55px;
    margin-bottom: 15px;
}

/* Cores harmonizadas com o sistema */
.cor-sistema { color: #006400; }
.bg-sistema { background-color: #006400; }
.tl-icon.bg-success { background-color: #006400 !important; }
.tl-icon.bg-primary { background-color: #228b22 !important; }
.tl-icon.bg-danger { background-color: #8b0000 !important; }
.tl-icon.bg-warning { background-color: #b8860b !important; }
.tl-icon.bg-secondary { background-color: #556b2f !important; }
.tl-icon.bg-info { background-color: #2f4f4f !important; }

/* Status visual */
.tl-item.status-proximo .card { border-left: 3px solid #b8860b; }
.tl-item.status-pendente .card { border-left: 3px solid #dee2e6; opacity: 0.8; }
.tl-item.status-concluido .card { border-left: 3px solid #006400; }

/* Badge de categoria */
.badge-categoria { font-size: 0.7em; font-weight: 500; }
</style>

<div class="card border-0">
    <div class="card-header bg-white border-bottom">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">
                <i class="fas fa-stream cor-sistema me-2"></i>
                Timeline do Obrigatório
            </h5>
            <?php if ($eventos): ?>
            <span class="badge bg-sistema">
                <?php echo count($eventos); ?> evento(s)
            </span>
            <?php endif; ?>
        </div>
    </div>

    <div class="card-body">
        <?php if ($eventos && count($eventos) > 0): ?>

            <!-- Resumo -->
            <div class="row g-3 mb-4">
                <div class="col-4">
                    <div class="card bg-light border text-center p-3">
                        <div class="fs-3 fw-bold text-success"><?php echo $total_concluidos; ?></div>
                        <small class="text-muted text-uppercase">Concluídos</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card bg-light border text-center p-3">
                        <div class="fs-3 fw-bold text-warning"><?php echo $total_proximos; ?></div>
                        <small class="text-muted text-uppercase">Próximos</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card bg-light border text-center p-3">
                        <div class="fs-3 fw-bold text-secondary"><?php echo $total_pendentes; ?></div>
                        <small class="text-muted text-uppercase">Pendentes</small>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="timeline-visual">
                <?php
                $ano_anterior = null;

                foreach ($eventos as $evento):
                    $ano_evento = $evento['data']->format('Y');

                    // Separador por ano
                    if ($ano_evento != $ano_anterior):
                        $ano_anterior = $ano_evento;
                ?>
                    <span class="tl-year-label badge bg-sistema rounded-pill shadow-sm">
                        <i class="far fa-calendar-alt me-1"></i>
                        <?php echo $ano_evento; ?>
                    </span>
                <?php endif; ?>

                <div class="tl-item status-<?php echo $evento['status']; ?>">
                    <div class="tl-icon bg-<?php echo $evento['cor']; ?> shadow-sm">
                        <i class="fas <?php echo $evento['icone']; ?>"></i>
                    </div>
                    <div class="card border shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-1">
                                <h6 class="fw-semibold mb-0"><?php echo htmlspecialchars($evento['label']); ?></h6>
                                <div class="d-flex gap-2 align-items-center">
                                    <span class="badge badge-categoria bg-<?php echo $evento['cor']; ?> bg-opacity-10 text-<?php echo $evento['cor']; ?>">
                                        <?php echo $evento['categoria']; ?>
                                    </span>
                                    <?php if ($evento['status'] === 'concluido'): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success"><i class="fas fa-check me-1"></i>Concluído</span>
                                    <?php elseif ($evento['status'] === 'proximo'): ?>
                                        <span class="badge bg-warning bg-opacity-10 text-warning"><i class="fas fa-exclamation-triangle me-1"></i>Em <?php echo $evento['diff_dias']; ?> dia(s)</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary"><i class="far fa-clock me-1"></i>Pendente</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="small text-muted">
                                <i class="far fa-calendar me-1"></i>
                                <?php echo $evento['data_formatada']; ?>
                                <?php if ($evento['status'] === 'concluido' && $evento['diff_dias'] < 0): ?>
                                    <span class="ms-2">(<?php echo abs($evento['diff_dias']); ?> dia(s) atrás)</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <!-- Estado vazio -->
            <div class="text-center py-5 text-muted">
                <i class="fas fa-stream fa-4x mb-3 opacity-25"></i>
                <h5>Nenhum evento na timeline</h5>
                <p class="mb-3">
                    Não há datas registradas para este obrigatório.
                </p>
                <small>
                    <i class="fas fa-info-circle me-1"></i>
                    A timeline é preenchida automaticamente conforme datas são cadastradas nas demais abas.
                </small>
            </div>
        <?php endif; ?>
    </div>
</div>
