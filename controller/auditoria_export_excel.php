<?php
/**
 * Endpoint para exportar dados de Auditoria para Excel (CSV)
 * Reutiliza a mesma lógica de filtros da auditoria.php
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../dao/conecta_banco.php';
include_once '../dao/LogDAO.php';
include_once '../funcoes.php';

// Verificar permissão - apenas admin
if (!isset($_SESSION['perfil_smo']) || $_SESSION['perfil_smo'] != "admin" || !isset($_SESSION['id_usuario_smo'])) {
    header('HTTP/1.1 403 Forbidden');
    echo 'Não autorizado';
    exit();
}

$logDAO = new LogDAO($conexao);

// ==================== PREPARAR FILTROS ====================
$filtros = [];

// Filtro: Data início
if (isset($_GET['data_inicio']) && !empty($_GET['data_inicio'])) {
    $filtros['data_inicio'] = $_GET['data_inicio'];
}

// Filtro: Data fim
if (isset($_GET['data_fim']) && !empty($_GET['data_fim'])) {
    $filtros['data_fim'] = $_GET['data_fim'];
}

// Filtro: Tipo de operação
if (isset($_GET['tipo_operacao']) && !empty($_GET['tipo_operacao'])) {
    $filtros['tipo_operacao'] = $_GET['tipo_operacao'];
}

// Filtro: Tabela
if (isset($_GET['tabela']) && !empty($_GET['tabela'])) {
    $filtros['tabela'] = $_GET['tabela'];
}

// Filtro: Usuário
if (isset($_GET['usuario']) && !empty($_GET['usuario'])) {
    $filtros['usuario'] = $_GET['usuario'];
}

// Filtro: Código específico
if (isset($_GET['codigo']) && !empty($_GET['codigo'])) {
    $filtros['codigo'] = (int)$_GET['codigo'];
}

// Filtro: Busca geral
if (isset($_GET['busca']) && !empty($_GET['busca'])) {
    $filtros['busca'] = $_GET['busca'];
}

// Busca todos os registros (limite alto para exportação)
$logs = $logDAO->findComFiltros($filtros, 50000);

// Nome do arquivo
$nomeArquivo = "auditoria_" . date('Y-m-d_H-i-s') . ".csv";

// Headers para download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $nomeArquivo . '"');
header('Pragma: no-cache');
header('Expires: 0');

// Abre output
$output = fopen('php://output', 'w');

// BOM para UTF-8
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Cabeçalho do relatório
fputcsv($output, ['RELATÓRIO DE AUDITORIA - SMO'], ';');
fputcsv($output, ['Gerado em: ' . date('d/m/Y H:i:s')], ';');
fputcsv($output, ['Total de registros: ' . count($logs)], ';');

// Informações dos filtros aplicados
$filtrosAplicados = [];
if (!empty($filtros['data_inicio'])) {
    $filtrosAplicados[] = 'Data início: ' . date('d/m/Y', strtotime($filtros['data_inicio']));
}
if (!empty($filtros['data_fim'])) {
    $filtrosAplicados[] = 'Data fim: ' . date('d/m/Y', strtotime($filtros['data_fim']));
}
if (!empty($filtros['tipo_operacao'])) {
    $filtrosAplicados[] = 'Tipo: ' . $filtros['tipo_operacao'];
}
if (!empty($filtros['tabela'])) {
    $filtrosAplicados[] = 'Tabela: ' . $filtros['tabela'];
}
if (!empty($filtros['usuario'])) {
    $filtrosAplicados[] = 'Usuário: ' . $filtros['usuario'];
}
if (!empty($filtros['codigo'])) {
    $filtrosAplicados[] = 'Código: ' . $filtros['codigo'];
}
if (!empty($filtros['busca'])) {
    $filtrosAplicados[] = 'Busca: ' . $filtros['busca'];
}

if (!empty($filtrosAplicados)) {
    fputcsv($output, ['Filtros: ' . implode(' | ', $filtrosAplicados)], ';');
}
fputcsv($output, [''], ';');

// Cabeçalhos das colunas
fputcsv($output, [
    'ID',
    'Data/Hora',
    'Usuário',
    'Código',
    'Tipo Operação',
    'Descrição Operação',
    'Tabela',
    'ID Alterado',
    'Alteração',
    'IP',
    'Sistema'
], ';');

// Dados
foreach ($logs as $log) {
    // Determina o tipo de operação
    $codigo = (int)$log['codigo'];
    $operacaoInfo = LogDAO::getOperacaoInfo($codigo);

    // Formata a data
    $dataFormatada = '';
    if (!empty($log['data'])) {
        $dataFormatada = date('d/m/Y H:i:s', strtotime($log['data']));
    }

    // Nome do usuário
    $nomeUsuario = $log['nome_guerra'] ?? $log['usuario'] ?? 'Sistema';

    fputcsv($output, [
        $log['id'] ?? '',
        $dataFormatada,
        $nomeUsuario,
        $log['codigo'] ?? '',
        $operacaoInfo['tipo'],
        $operacaoInfo['descricao'],
        $log['tabela'] ?? '',
        $log['id_alterado'] ?? '',
        $log['alteracao'] ?? '',
        $log['ip'] ?? '',
        $log['sistema'] ?? ''
    ], ';');
}

fclose($output);
exit();
?>
