<?php
include_once 'header.php';

if (!isset($_SESSION['id_usuario_smo'])) {
    erro($BASE_URL, 2, 23574575, $pagina_atual, "usuario_nao_logado", "Página não encontrada!");
    exit();
}
if ($_SESSION['perfil_smo'] != 'admin') {
    erro($BASE_URL, 2, 47345645, $pagina_atual, "perfil!=admin", "Página não encontrada!");
    exit();
}

$logDAO = new logDAO($conexao);

// Parâmetros de filtro
$filtros = [];
$limite = 1000;

if (isset($_GET['limite'])) $limite = (int)filtra_campo_get('limite');
if ($limite > 10000) $limite = 10000;

if (isset($_GET['data_inicio']) && !empty($_GET['data_inicio'])) {
    $filtros['data_inicio'] = filtra_campo_get('data_inicio');
}
if (isset($_GET['data_fim']) && !empty($_GET['data_fim'])) {
    $filtros['data_fim'] = filtra_campo_get('data_fim');
}
if (isset($_GET['tipo_operacao']) && !empty($_GET['tipo_operacao'])) {
    $filtros['tipo_operacao'] = filtra_campo_get('tipo_operacao');
}
if (isset($_GET['codigo']) && !empty($_GET['codigo'])) {
    $filtros['codigo'] = (int)filtra_campo_get('codigo');
}
if (isset($_GET['usuario']) && !empty($_GET['usuario'])) {
    $filtros['usuario'] = filtra_campo_get('usuario');
}
if (isset($_GET['tabela']) && !empty($_GET['tabela'])) {
    $filtros['tabela'] = filtra_campo_get('tabela');
}
if (isset($_GET['busca']) && !empty($_GET['busca'])) {
    $filtros['busca'] = filtra_campo_get('busca');
}

// Busca logs com filtros
$logs = $logDAO->findComFiltros($filtros, $limite);

// Estatísticas
$estatisticas = $logDAO->countPorTipoOperacao($filtros);

// Lista de tabelas para o filtro
$tabelas = $logDAO->getTabelasDistintas();

// Verificar se há filtros ativos
$temFiltrosAtivos = !empty($filtros);
?>

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="section-title">
                <h1><b><i class="fas fa-user-shield"></i> Auditoria</b></h1>
            </div>
        </div>
    </section>
</main>

<section id="contact" class="contact">
    <div class="container">

        <!-- Cards de Estatísticas -->
        <div class="row g-3 mb-4">
            <div class="col-md-2">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body py-3">
                        <div class="h4 fw-bold text-primary mb-0"><?= number_format($estatisticas['total'] ?? 0) ?></div>
                        <small class="text-muted">Total</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card h-100 border-0 shadow-sm text-center" style="border-left: 3px solid #28a745 !important;">
                    <div class="card-body py-3">
                        <div class="h4 fw-bold text-success mb-0"><?= number_format($estatisticas['inserts'] ?? 0) ?></div>
                        <small class="text-muted">Inserções</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card h-100 border-0 shadow-sm text-center" style="border-left: 3px solid #007bff !important;">
                    <div class="card-body py-3">
                        <div class="h4 fw-bold text-primary mb-0"><?= number_format($estatisticas['updates'] ?? 0) ?></div>
                        <small class="text-muted">Alterações</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card h-100 border-0 shadow-sm text-center" style="border-left: 3px solid #dc3545 !important;">
                    <div class="card-body py-3">
                        <div class="h4 fw-bold text-danger mb-0"><?= number_format($estatisticas['deletes'] ?? 0) ?></div>
                        <small class="text-muted">Exclusões</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card h-100 border-0 shadow-sm text-center" style="border-left: 3px solid #6c757d !important;">
                    <div class="card-body py-3">
                        <div class="h4 fw-bold text-secondary mb-0"><?= number_format($estatisticas['pdfs'] ?? 0) ?></div>
                        <small class="text-muted">PDFs</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card h-100 border-0 shadow-sm text-center" style="border-left: 3px solid #17a2b8 !important;">
                    <div class="card-body py-3">
                        <div class="h4 fw-bold text-info mb-0"><?= number_format($estatisticas['logins'] ?? 0) ?></div>
                        <small class="text-muted">Logins</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card de Filtros -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filtros Avançados</h5>
                <div class="btn-group">
                    <?php if ($temFiltrosAtivos): ?>
                        <a href="auditoria.php" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Limpar
                        </a>
                    <?php endif; ?>
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="exportarExcel()">
                        <i class="fas fa-file-excel me-1"></i>Exportar Excel
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form name="formFiltros" action="auditoria.php" method="get">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-2">
                            <label class="form-label fw-semibold">Data Início</label>
                            <input type="date" name="data_inicio" class="form-control" value="<?= $filtros['data_inicio'] ?? '' ?>">
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label fw-semibold">Data Fim</label>
                            <input type="date" name="data_fim" class="form-control" value="<?= $filtros['data_fim'] ?? '' ?>">
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label fw-semibold">Tipo Operação</label>
                            <select name="tipo_operacao" class="form-select">
                                <option value="">Todos</option>
                                <option value="INSERT" <?= (isset($filtros['tipo_operacao']) && $filtros['tipo_operacao'] == 'INSERT') ? 'selected' : '' ?>>Inserção</option>
                                <option value="UPDATE" <?= (isset($filtros['tipo_operacao']) && $filtros['tipo_operacao'] == 'UPDATE') ? 'selected' : '' ?>>Alteração</option>
                                <option value="DELETE" <?= (isset($filtros['tipo_operacao']) && $filtros['tipo_operacao'] == 'DELETE') ? 'selected' : '' ?>>Exclusão</option>
                                <option value="PDF" <?= (isset($filtros['tipo_operacao']) && $filtros['tipo_operacao'] == 'PDF') ? 'selected' : '' ?>>PDF</option>
                                <option value="LOGIN" <?= (isset($filtros['tipo_operacao']) && $filtros['tipo_operacao'] == 'LOGIN') ? 'selected' : '' ?>>Login/Logout</option>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label fw-semibold">Tabela</label>
                            <select name="tabela" class="form-select">
                                <option value="">Todas</option>
                                <?php foreach ($tabelas as $tabela): ?>
                                    <option value="<?= htmlspecialchars($tabela) ?>" <?= (isset($filtros['tabela']) && $filtros['tabela'] == $tabela) ? 'selected' : '' ?>><?= htmlspecialchars($tabela) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label fw-semibold">Usuário</label>
                            <input type="text" name="usuario" class="form-control" placeholder="Nome ou login" value="<?= htmlspecialchars($filtros['usuario'] ?? '') ?>">
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label fw-semibold">Registros</label>
                            <select name="limite" class="form-select">
                                <option value="1000" <?= $limite == 1000 ? 'selected' : '' ?>>1.000</option>
                                <option value="3000" <?= $limite == 3000 ? 'selected' : '' ?>>3.000</option>
                                <option value="5000" <?= $limite == 5000 ? 'selected' : '' ?>>5.000</option>
                                <option value="10000" <?= $limite == 10000 ? 'selected' : '' ?>>10.000</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-lg-2">
                            <label class="form-label fw-semibold">Código</label>
                            <input type="number" name="codigo" class="form-control" placeholder="Ex: 3003" value="<?= $filtros['codigo'] ?? '' ?>">
                        </div>
                        <div class="col-lg-8">
                            <label class="form-label fw-semibold">Busca na Alteração</label>
                            <input type="text" name="busca" class="form-control" placeholder="Pesquisar no texto da alteração..." value="<?= htmlspecialchars($filtros['busca'] ?? '') ?>">
                        </div>
                        <div class="col-lg-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-1"></i>Pesquisar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabela de Logs -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Registros de Auditoria (<?= count($logs) ?>)</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="tabela_auditoria" class="table table-hover mb-0" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">Tipo</th>
                                <th>Usuário</th>
                                <th>Descrição</th>
                                <th>Tabela</th>
                                <th>Data/Hora</th>
                                <th>IP</th>
                                <th style="width: 50px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($logs): ?>
                                <?php foreach ($logs as $log):
                                    $info = LogDAO::getOperacaoInfo($log['codigo']);
                                    $dataLog = $log['data'] ? trata_data_hora($log['data']) : '-';
                                ?>
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-<?= $info['cor'] ?>" title="<?= $info['tipo'] ?>">
                                                <i class="fas <?= $info['icone'] ?>"></i>
                                            </span>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($log['usuario'] ?? 'Sistema') ?></strong>
                                            <?php if (!empty($log['nome_guerra'])): ?>
                                                <br><small class="text-muted"><?= htmlspecialchars($log['nome_guerra']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($log['alteracao']) ?>
                                            <?php if (!empty($log['alteracao_detalhada'])): ?>
                                                <button type="button" class="btn btn-sm btn-link p-0 ms-1" onclick="verDetalhes(<?= $log['id'] ?>)" title="Ver detalhes">
                                                    <i class="fas fa-info-circle"></i>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                        <td><span class="badge bg-secondary"><?= htmlspecialchars($log['tabela'] ?? '-') ?></span></td>
                                        <td><?= $dataLog ?></td>
                                        <td><small><?= htmlspecialchars($log['ip'] ?? '-') ?></small></td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <?php if (!empty($log['id_alterado']) && $log['tabela'] == 'obrigatorio'):
                                                    $cripLog = hash('sha256', $log['id_alterado'] . "criptografia");
                                                ?>
                                                    <a href="obrigatorio.php?crip=<?= $cripLog ?>&id_obrigatorio=<?= $log['id_alterado'] ?>" class="btn btn-outline-success" title="Ver registro">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-primary" onclick="verHistorico(<?= $log['id_alterado'] ?>)" title="Ver histórico">
                                                        <i class="fas fa-history"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">Nenhum registro encontrado com os filtros selecionados.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tabela de Códigos (Colapsável) -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-light">
                <a data-bs-toggle="collapse" href="#tabelaCodigos" role="button" aria-expanded="false" class="text-decoration-none text-dark d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-book me-2"></i>Tabela de Códigos</h5>
                    <i class="fas fa-chevron-down"></i>
                </a>
            </div>
            <div class="collapse" id="tabelaCodigos">
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Código</th>
                                <th>Operação</th>
                                <th>Descrição</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td><span class="badge bg-info">9001</span></td><td>LOGIN</td><td>Usuário fez LOGIN</td></tr>
                            <tr><td><span class="badge bg-secondary">9002</span></td><td>LOGOUT</td><td>Usuário fez LOGOUT</td></tr>
                            <tr><td><span class="badge bg-success">1001</span></td><td>INSERT</td><td>Cadastrou um usuário</td></tr>
                            <tr><td><span class="badge bg-success">1002</span></td><td>INSERT</td><td>Inseriu um arquivo</td></tr>
                            <tr><td><span class="badge bg-success">1003</span></td><td>INSERT</td><td>Cadastrou Junta de Saúde</td></tr>
                            <tr><td><span class="badge bg-success">1004</span></td><td>INSERT</td><td>Cadastrou um Obrigatório</td></tr>
                            <tr><td><span class="badge bg-danger">2001</span></td><td>DELETE</td><td>Apagou um Usuário</td></tr>
                            <tr><td><span class="badge bg-danger">2002</span></td><td>DELETE</td><td>Apagou um arquivo</td></tr>
                            <tr><td><span class="badge bg-danger">2003</span></td><td>DELETE</td><td>Apagou um obrigatório</td></tr>
                            <tr><td><span class="badge bg-primary">3001</span></td><td>UPDATE</td><td>Atualizou um Usuário</td></tr>
                            <tr><td><span class="badge bg-primary">3003</span></td><td>UPDATE</td><td>Atualizou um obrigatório</td></tr>
                            <tr><td><span class="badge bg-primary">3006</span></td><td>UPDATE</td><td>Pré-distribuição</td></tr>
                            <tr><td><span class="badge bg-primary">3007</span></td><td>UPDATE</td><td>Identificação</td></tr>
                            <tr><td><span class="badge bg-primary">3010</span></td><td>UPDATE</td><td>Adiamento</td></tr>
                            <tr><td><span class="badge bg-primary">3011</span></td><td>UPDATE</td><td>Distribuição</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Modal de Detalhes -->
<div class="modal fade" id="modalDetalhes" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Detalhes da Alteração</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalDetalhesBody">
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Histórico Completo -->
<div class="modal fade" id="modalHistorico" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-history me-2"></i>Histórico de Alterações</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalHistoricoBody">
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos para visualização de diff */
.diff-container {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin-top: 10px;
}
.diff-header {
    font-weight: 600;
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid #dee2e6;
}
.diff-row {
    display: flex;
    margin-bottom: 8px;
    padding: 8px;
    background: white;
    border-radius: 4px;
    border: 1px solid #e9ecef;
}
.diff-row.changed {
    border-left: 3px solid #ffc107;
}
.diff-row.added {
    border-left: 3px solid #28a745;
    background: #d4edda;
}
.diff-row.removed {
    border-left: 3px solid #dc3545;
    background: #f8d7da;
}
.diff-field {
    flex: 0 0 200px;
    font-weight: 600;
    color: #495057;
    padding-right: 15px;
}
.diff-values {
    flex: 1;
    display: flex;
    gap: 20px;
}
.diff-old, .diff-new {
    flex: 1;
    padding: 5px 10px;
    border-radius: 4px;
    font-family: monospace;
    font-size: 0.9rem;
    word-break: break-word;
}
.diff-old {
    background: #ffecec;
    color: #c53030;
    text-decoration: line-through;
}
.diff-new {
    background: #e6ffed;
    color: #22863a;
}
.diff-arrow {
    display: flex;
    align-items: center;
    color: #6c757d;
}
.timeline-item {
    position: relative;
    padding-left: 30px;
    padding-bottom: 20px;
    border-left: 2px solid #dee2e6;
}
.timeline-item:last-child {
    border-left: 2px solid transparent;
}
.timeline-item::before {
    content: '';
    position: absolute;
    left: -6px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #007bff;
    border: 2px solid white;
}
.timeline-item.insert::before { background: #28a745; }
.timeline-item.update::before { background: #007bff; }
.timeline-item.delete::before { background: #dc3545; }
.timeline-date {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 5px;
}
.timeline-content {
    background: #f8f9fa;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #e9ecef;
}
.diff-legend {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
    font-size: 0.85rem;
}
.diff-legend span {
    display: flex;
    align-items: center;
    gap: 5px;
}
.diff-legend .legend-box {
    width: 16px;
    height: 16px;
    border-radius: 3px;
}
.legend-changed { background: #fff3cd; border: 1px solid #ffc107; }
.legend-added { background: #d4edda; border: 1px solid #28a745; }
.legend-removed { background: #f8d7da; border: 1px solid #dc3545; }
</style>

<!-- Dados para exportação -->
<script>
const logsData = <?= json_encode($logs ?: []) ?>;

// Mapeamento de nomes de campos para exibição amigável
const fieldLabels = {
    'nome_completo': 'Nome Completo',
    'nome_guerra': 'Nome de Guerra',
    'cpf': 'CPF',
    'situacao_militar': 'Situação Militar',
    'data_nascimento': 'Data de Nascimento',
    'telefone': 'Telefone',
    'mail': 'E-mail',
    'formacao': 'Formação',
    'especialidade_1': 'Especialidade',
    'instituicao_ensino': 'Instituição de Ensino',
    'ano_residencia': 'Ano Residência',
    'voluntario': 'Voluntário',
    'dependentes': 'Dependentes',
    'jise': 'JISE',
    'jisr': 'JISR',
    'resultado_jise': 'Resultado JISE',
    'resultado_jisr': 'Resultado JISR',
    'data_selecao_geral': 'Data Seleção Geral',
    'resultado_selecao_geral': 'Resultado Seleção Geral',
    'data_selecao_complementar': 'Data Seleção Complementar',
    'resultado_selecao_complementar': 'Resultado Seleção Complementar',
    'om_1_fase': 'OM 1ª Fase',
    'local_apresentacao': 'Local de Apresentação',
    'data_incorporacao': 'Data Incorporação',
    'rm_destino': 'RM Destino',
    'cidade_destino': 'Cidade Destino',
    'om_destino': 'OM Destino',
    'inicio_adiamento': 'Início Adiamento',
    'fim_adiamento': 'Fim Adiamento',
    'motivo_adiamento': 'Motivo Adiamento',
    'data_revisao_medica': 'Data Revisão Médica',
    'resultado_revisao_medica': 'Resultado Revisão Médica',
    'resultado_revisao_medica_complementar': 'Resultado Revisão Médica Complementar',
    'prioridade_forca': 'Prioridade Força',
    'observacao': 'Observação',
    'status': 'Status'
};

$(document).ready(function() {
    $('#tabela_auditoria').DataTable({
        "order": [],
        "pageLength": 50,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json"
        }
    });
});

function getFieldLabel(field) {
    return fieldLabels[field] || field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
}

function formatValue(value) {
    if (value === null || value === undefined || value === '') return '<em class="text-muted">vazio</em>';
    if (typeof value === 'boolean') return value ? 'Sim' : 'Não';
    return escapeHtml(String(value));
}

function renderDiff(detalhes) {
    let html = '';

    // Verifica se tem estrutura de antes/depois
    if (detalhes.antes && detalhes.depois) {
        html += `
            <div class="diff-legend">
                <span><div class="legend-box legend-changed"></div> Alterado</span>
                <span><div class="legend-box legend-added"></div> Adicionado</span>
                <span><div class="legend-box legend-removed"></div> Removido</span>
            </div>
        `;

        html += '<div class="diff-container">';
        html += '<div class="diff-header"><i class="fas fa-exchange-alt me-2"></i>Comparação de Valores</div>';

        // Junta todas as chaves
        const allKeys = new Set([...Object.keys(detalhes.antes), ...Object.keys(detalhes.depois)]);

        allKeys.forEach(key => {
            const oldVal = detalhes.antes[key];
            const newVal = detalhes.depois[key];

            // Ignora se ambos são nulos/vazios
            if ((oldVal === null || oldVal === '' || oldVal === undefined) &&
                (newVal === null || newVal === '' || newVal === undefined)) {
                return;
            }

            // Determina o tipo de mudança
            let rowClass = '';
            if (!(key in detalhes.antes)) {
                rowClass = 'added';
            } else if (!(key in detalhes.depois)) {
                rowClass = 'removed';
            } else if (oldVal !== newVal) {
                rowClass = 'changed';
            } else {
                return; // Valores iguais, não mostra
            }

            html += `
                <div class="diff-row ${rowClass}">
                    <div class="diff-field">${getFieldLabel(key)}</div>
                    <div class="diff-values">
                        <div class="diff-old">${formatValue(oldVal)}</div>
                        <div class="diff-arrow"><i class="fas fa-arrow-right"></i></div>
                        <div class="diff-new">${formatValue(newVal)}</div>
                    </div>
                </div>
            `;
        });

        html += '</div>';
    }
    // Verifica se tem estrutura de campos alterados (formato simplificado)
    else if (detalhes.campos_alterados || Array.isArray(detalhes)) {
        const campos = detalhes.campos_alterados || detalhes;

        html += '<div class="diff-container">';
        html += '<div class="diff-header"><i class="fas fa-list me-2"></i>Campos Alterados</div>';

        if (Array.isArray(campos)) {
            campos.forEach(campo => {
                if (typeof campo === 'object' && campo.campo) {
                    html += `
                        <div class="diff-row changed">
                            <div class="diff-field">${getFieldLabel(campo.campo)}</div>
                            <div class="diff-values">
                                <div class="diff-old">${formatValue(campo.antes || campo.de)}</div>
                                <div class="diff-arrow"><i class="fas fa-arrow-right"></i></div>
                                <div class="diff-new">${formatValue(campo.depois || campo.para)}</div>
                            </div>
                        </div>
                    `;
                } else {
                    html += `<div class="diff-row"><div class="diff-field">${escapeHtml(String(campo))}</div></div>`;
                }
            });
        }

        html += '</div>';
    }
    // Formato genérico - exibe como JSON formatado
    else {
        html += '<div class="diff-container">';
        html += '<div class="diff-header"><i class="fas fa-code me-2"></i>Dados da Alteração</div>';
        html += '<pre class="mb-0 p-3 bg-white rounded" style="font-size: 0.85rem; max-height: 400px; overflow: auto;">' +
                escapeHtml(JSON.stringify(detalhes, null, 2)) + '</pre>';
        html += '</div>';
    }

    return html;
}

function verDetalhes(id) {
    const log = logsData.find(l => l.id == id);
    if (log) {
        let html = '';

        // Informações básicas
        html += '<div class="row mb-3">';
        html += '<div class="col-md-3"><strong>Código:</strong> <span class="badge bg-secondary">' + log.codigo + '</span></div>';
        html += '<div class="col-md-3"><strong>Tabela:</strong> <span class="badge bg-info">' + (log.tabela || '-') + '</span></div>';
        html += '<div class="col-md-3"><strong>ID Registro:</strong> ' + (log.id_alterado || '-') + '</div>';
        html += '<div class="col-md-3"><strong>IP:</strong> ' + (log.ip || '-') + '</div>';
        html += '</div>';

        html += '<div class="alert alert-light border mb-3">';
        html += '<strong><i class="fas fa-edit me-2"></i>Alteração:</strong> ' + escapeHtml(log.alteracao || '-');
        html += '</div>';

        if (log.alteracao_detalhada) {
            try {
                const detalhes = JSON.parse(log.alteracao_detalhada);
                html += renderDiff(detalhes);
            } catch (e) {
                html += '<div class="diff-container">';
                html += '<div class="diff-header"><i class="fas fa-file-alt me-2"></i>Detalhes</div>';
                html += '<pre class="mb-0 p-3 bg-white rounded">' + escapeHtml(log.alteracao_detalhada) + '</pre>';
                html += '</div>';
            }
        }

        // Botão para ver histórico completo se for obrigatório
        if (log.tabela === 'obrigatorio' && log.id_alterado) {
            html += `
                <div class="mt-3 pt-3 border-top">
                    <button type="button" class="btn btn-outline-primary" onclick="verHistorico(${log.id_alterado})">
                        <i class="fas fa-history me-2"></i>Ver Histórico Completo deste Registro
                    </button>
                </div>
            `;
        }

        document.getElementById('modalDetalhesBody').innerHTML = html;
        new bootstrap.Modal(document.getElementById('modalDetalhes')).show();
    }
}

function verHistorico(idObrigatorio) {
    // Fecha o modal atual
    const modalDetalhes = bootstrap.Modal.getInstance(document.getElementById('modalDetalhes'));
    if (modalDetalhes) modalDetalhes.hide();

    // Mostra loading no modal de histórico
    document.getElementById('modalHistoricoBody').innerHTML = `
        <div class="text-center py-4">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p class="mt-2">Carregando histórico...</p>
        </div>
    `;
    new bootstrap.Modal(document.getElementById('modalHistorico')).show();

    // Busca histórico via AJAX
    fetch('controller/auditoria_historico_ajax.php?id_obrigatorio=' + idObrigatorio)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.historico.length > 0) {
                let html = `
                    <div class="mb-3">
                        <strong>Registro:</strong> ${data.info.nome || 'ID ' + idObrigatorio}
                        <span class="badge bg-secondary ms-2">${data.historico.length} alterações</span>
                    </div>
                    <div class="timeline">
                `;

                data.historico.forEach((item, index) => {
                    const tipoClass = item.tipo_operacao.toLowerCase();
                    html += `
                        <div class="timeline-item ${tipoClass}">
                            <div class="timeline-date">
                                <i class="fas fa-clock me-1"></i>${item.data_formatada}
                                <span class="badge bg-${getBadgeColor(item.tipo_operacao)} ms-2">${item.tipo_operacao}</span>
                            </div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>${escapeHtml(item.nome_guerra || item.usuario || 'Sistema')}</strong>
                                        <p class="mb-0 mt-1">${escapeHtml(item.alteracao)}</p>
                                    </div>
                                    ${item.alteracao_detalhada ? `
                                        <button class="btn btn-sm btn-outline-secondary" onclick="toggleTimelineDetail(${item.id})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    ` : ''}
                                </div>
                                <div id="timeline-detail-${item.id}" class="mt-2" style="display: none;">
                                    ${item.alteracao_detalhada ? renderDiffFromString(item.alteracao_detalhada) : ''}
                                </div>
                            </div>
                        </div>
                    `;
                });

                html += '</div>';
                document.getElementById('modalHistoricoBody').innerHTML = html;
            } else {
                document.getElementById('modalHistoricoBody').innerHTML = `
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>Nenhum histórico encontrado para este registro.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            document.getElementById('modalHistoricoBody').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Erro ao carregar histórico: ${error.message}
                </div>
            `;
        });
}

function toggleTimelineDetail(id) {
    const el = document.getElementById('timeline-detail-' + id);
    if (el) {
        el.style.display = el.style.display === 'none' ? 'block' : 'none';
    }
}

function renderDiffFromString(str) {
    try {
        const detalhes = JSON.parse(str);
        return renderDiff(detalhes);
    } catch (e) {
        return '<pre class="mb-0 p-2 bg-light rounded small">' + escapeHtml(str) + '</pre>';
    }
}

function getBadgeColor(tipo) {
    const colors = {
        'INSERT': 'success',
        'UPDATE': 'primary',
        'DELETE': 'danger',
        'PDF': 'secondary',
        'LOGIN': 'info',
        'LOGOUT': 'secondary'
    };
    return colors[tipo] || 'dark';
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function exportarExcel() {
    // Monta a URL com os filtros atuais
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'excel');
    window.location.href = 'controller/auditoria_export_excel.php?' + params.toString();
}
</script>

<?php include_once 'footer.php'; ?>
