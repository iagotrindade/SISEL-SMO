<?php
// =========================================================================
// DEBUG AJAX - Verificar o que está sendo recebido e enviado
// =========================================================================

ob_start();
ob_clean();

header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', '1');

if (!isset($_SESSION)) session_start();

try {
    include_once '../dao/conecta_banco.php';
    include_once '../dao/ObrigatorioDAO.php';
    include_once '../funcoes.php';
} catch (Exception $e) {
    die(json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE));
}

// Verificar permissão
if (!isset($_SESSION['perfil_smo']) || $_SESSION['perfil_smo'] != "admin") {
    die(json_encode([
        'error' => 'Sem permissão',
        'perfil' => $_SESSION['perfil_smo'] ?? 'não definido',
        'session' => $_SESSION
    ], JSON_UNESCAPED_UNICODE));
}

// PEGA PARÂMETROS
$draw = isset($_GET['draw']) ? intval($_GET['draw']) : 1;
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$length = isset($_GET['length']) ? intval($_GET['length']) : 50;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// DEBUG: Log dos parâmetros
$debug_info = [
    'draw' => $draw,
    'start' => $start,
    'length' => $length,
    'search_term' => $search,
    'get_params' => $_GET,
    'session_id_om' => $_SESSION['id_om_smo'] ?? 'NÃO ENCONTRADO',
    'session_perfil' => $_SESSION['perfil_smo'] ?? 'NÃO ENCONTRADO'
];

try {
    $ObrigatorioDAO = new ObrigatorioDAO($conexao);
    
    // Testar método 1: Sem filtros
    $all_records = $ObrigatorioDAO->findAtivosComFiltros([]);
    $total_geral = is_array($all_records) ? count($all_records) : 0;
    
    // Testar método 2: Com busca
    $records_com_busca = $ObrigatorioDAO->findAtivosComFiltrosComBusca([], $search);
    $total_com_busca = is_array($records_com_busca) ? count($records_com_busca) : 0;
    
    // Testar método 3: Com paginação e busca
    $records_paginados = $ObrigatorioDAO->findAtivosComFiltrosPagevelComBusca([], $start, $length, $search);
    $total_paginados = is_array($records_paginados) ? count($records_paginados) : 0;
    
    $debug_info['total_geral'] = $total_geral;
    $debug_info['total_com_busca'] = $total_com_busca;
    $debug_info['total_paginados'] = $total_paginados;
    $debug_info['registros_retornados'] = $records_paginados;
    
    $response = [
        'draw' => $draw,
        'recordsTotal' => $total_geral,
        'recordsFiltered' => $total_com_busca,
        'data' => [],
        'debug' => $debug_info
    ];
    
    // Montar dados
    if (!empty($records_paginados)) {
        foreach ($records_paginados as $row) {
            if (!isset($row['id']) || !isset($row['nome_completo'])) continue;
            
            $criptografia = hash('sha256', $row['id'] . "criptografia");
            
            $cor = "#FFDEAD";
            if (isset($row['situacao_militar']) && strpos($row['situacao_militar'], "Quite") !== false) {
                $cor = "#98FB98";
            }
            
            $anos = array_filter([
                $row['ano_residencia_espe_1'] ?? '',
                $row['ano_residencia_espe_2'] ?? '',
                $row['ano_residencia_espe_3'] ?? ''
            ]);
            $ano_residencia = !empty($anos) ? max($anos) : '';
            
            $especialidade = $row['especialidade_1'] ?? ($row['especialidade_2'] ?? ($row['especialidade_3'] ?? ''));
            
            $data_nascimento = isset($row['data_nascimento']) && !empty($row['data_nascimento']) 
                ? trata_data($row['data_nascimento']) 
                : '';
            
            $transferencia = '';
            if ((isset($row['rm_origem_fisemi']) && $row['rm_origem_fisemi']) && 
                (isset($row['rm_destino_fisemi']) && $row['rm_destino_fisemi'])) {
                $transferencia = $row['rm_origem_fisemi'] . "ª à " . $row['rm_destino_fisemi'] . "ª";
            }
            
            $response['data'][] = [
                'nome' => "<a href='obrigatorio.php?crip=$criptografia&id_obrigatorio=" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['nome_completo']) . "</a>",
                'cpf' => htmlspecialchars($row['cpf'] ?? ''),
                'formacao' => htmlspecialchars(($row['formacao'] ?? '') . " / " . ($row['ano_formacao'] ?? '')),
                'instituicao' => htmlspecialchars($row['nome_instituicao_ensino'] ?? ''),
                'ano_residencia' => htmlspecialchars($ano_residencia),
                'especialidade' => htmlspecialchars($especialidade),
                'nascimento' => htmlspecialchars($data_nascimento),
                'situacao_militar' => htmlspecialchars($row['situacao_militar'] ?? ''),
                'transferencia' => htmlspecialchars($transferencia),
                'cor_fundo' => $cor
            ];
        }
    }
    
    ob_clean();
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit();
    
} catch (Exception $e) {
    ob_clean();
    echo json_encode([
        'error' => 'Exception: ' . $e->getMessage(),
        'trace' => $e->getTraceAsString(),
        'debug' => $debug_info
    ], JSON_UNESCAPED_UNICODE);
    exit();
}
