<?php
// =========================================================================
// ARQUIVO: controller/obrigatorios_ajax.php
// Processa requisições AJAX do DataTables para obrigatorios.php
// COM DEBUG DETALHADO
// =========================================================================

ob_start();
ob_clean();

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

error_reporting(E_ALL);
ini_set('display_errors', '0');

if (!isset($_SESSION)) session_start();

try {
    include_once '../dao/conecta_banco.php';
    include_once '../dao/ObrigatorioDAO.php';
    include_once '../funcoes.php';
} catch (Exception $e) {
    http_response_code(500);
    die(json_encode(['error' => 'Erro ao incluir arquivos', 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE));
}

// Verificar permissão
if (!isset($_SESSION['perfil_smo']) || $_SESSION['perfil_smo'] != "admin" || !isset($_SESSION['id_usuario_smo'])) {
    http_response_code(403);
    die(json_encode([
        'error' => 'Sem permissão',
        'perfil' => $_SESSION['perfil_smo'] ?? 'não definido'
    ], JSON_UNESCAPED_UNICODE));
}

try {
    $ObrigatorioDAO = new ObrigatorioDAO($conexao);

    // ==================== PARÂMETROS DATATABLE ====================
    $draw = isset($_GET['draw']) ? intval($_GET['draw']) : 1;
    $start = isset($_GET['start']) ? intval($_GET['start']) : 0;
    $length = isset($_GET['length']) ? intval($_GET['length']) : 50;
    
    // BUSCA: DataTables envia como string simples
    $search_term = '';
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search_term = trim($_GET['search']);
    }

    // ==================== PREPARAR FILTROS ====================
    $filters = [];
    
    // LOG: O que foi recebido
    $debug_received = [
        'draw' => $draw,
        'start' => $start,
        'length' => $length,
        'search' => $search_term,
        'get_params' => $_GET
    ];

    // Filtro: Voluntário
    if (isset($_GET['voluntario_filtro']) && is_array($_GET['voluntario_filtro'])) {
        $voluntary = array_filter($_GET['voluntario_filtro']);
        if (!empty($voluntary)) {
            $filters['voluntario_filtro'] = $voluntary;
        }
    }

    // Filtro: Dependentes
    if (isset($_GET['dependentes_filtro']) && !empty($_GET['dependentes_filtro'])) {
        $filters['dependentes_filtro'] = $_GET['dependentes_filtro'];
    }

    // Filtro: Faculdade
    if (isset($_GET['faculdade_filtro']) && is_array($_GET['faculdade_filtro'])) {
        $faculty = array_filter($_GET['faculdade_filtro']);
        if (!empty($faculty)) {
            $filters['faculdade_filtro'] = $faculty;
        }
    }

    // Filtro: JISE
    if (isset($_GET['jise_filtro']) && is_array($_GET['jise_filtro'])) {
        $jise = array_filter($_GET['jise_filtro']);
        if (!empty($jise)) {
            $filters['jise_filtro'] = $jise;
        }
    }

    // Filtro: JISR
    if (isset($_GET['jisr_filtro']) && is_array($_GET['jisr_filtro'])) {
        $jisr = array_filter($_GET['jisr_filtro']);
        if (!empty($jisr)) {
            $filters['jisr_filtro'] = $jisr;
        }
    }

    // Filtro: Distribuição
    if (isset($_GET['distribuicao_filtro']) && is_array($_GET['distribuicao_filtro'])) {
        $dist = array_filter($_GET['distribuicao_filtro']);
        if (!empty($dist)) {
            $filters['distribuicao_filtro'] = $dist;
        }
    }

    // Filtro: OM 1ª Fase
    if (isset($_GET['om_1_fase_filtro']) && is_array($_GET['om_1_fase_filtro'])) {
        $om = array_filter($_GET['om_1_fase_filtro']);
        if (!empty($om)) {
            $filters['om_1_fase_filtro'] = $om;
        }
    }

    // Filtro: Resultado Revisão
    if (isset($_GET['resultado_revisao_filtro']) && is_array($_GET['resultado_revisao_filtro'])) {
        $rev = array_filter($_GET['resultado_revisao_filtro']);
        if (!empty($rev)) {
            $filters['resultado_revisao_filtro'] = $rev;
        }
    }

    // Filtro: ISGRev
    if (isset($_GET['isgr_filtro']) && is_array($_GET['isgr_filtro'])) {
        $isgr = array_filter($_GET['isgr_filtro']);
        if (!empty($isgr)) {
            $filters['isgr_filtro'] = $isgr;
        }
    }

    // Filtro: Data Seleção Geral
    if (isset($_GET['sel_geral_filtro']) && is_array($_GET['sel_geral_filtro'])) {
        $sel = array_filter($_GET['sel_geral_filtro']);
        if (!empty($sel)) {
            $filters['sel_geral_filtro'] = $sel;
        }
    }

    // Filtro: Comparecimento Designação
    if (isset($_GET['comp_designacao_filtro']) && is_array($_GET['comp_designacao_filtro'])) {
        $comp = array_filter($_GET['comp_designacao_filtro']);
        if (!empty($comp)) {
            $filters['comp_designacao_filtro'] = $comp;
        }
    }

    // Filtro: Incorporação
    if (isset($_GET['incorporacao_filtro']) && is_array($_GET['incorporacao_filtro'])) {
        $incorp = array_filter($_GET['incorporacao_filtro']);
        if (!empty($incorp)) {
            $filters['incorporacao_filtro'] = $incorp;
        }
    }

    // Filtro: Seleção Complementar
    if (isset($_GET['sel_complementar_filtro']) && is_array($_GET['sel_complementar_filtro'])) {
        $selcomp = array_filter($_GET['sel_complementar_filtro']);
        if (!empty($selcomp)) {
            $filters['sel_complementar_filtro'] = $selcomp;
        }
    }

    // Filtro: Situação Militar
    if (isset($_GET['situacao_militar']) && is_array($_GET['situacao_militar'])) {
        $situacao = array_filter($_GET['situacao_militar']);
        if (!empty($situacao)) {
            $filters['situacao_militar'] = $situacao;
        }
    }

    // Filtro: RM Destino
    if (isset($_GET['rm_destino']) && is_array($_GET['rm_destino'])) {
        $rm = array_filter($_GET['rm_destino']);
        if (!empty($rm)) {
            $filters['rm_destino_filtro'] = $rm;
        }
    }

    // Filtro: Especialidade
    if (isset($_GET['especialidade_filtro']) && is_array($_GET['especialidade_filtro'])) {
        $espec = array_filter($_GET['especialidade_filtro']);
        if (!empty($espec)) {
            $filters['especialidade_filtro'] = $espec;
        }
    }

    // Filtro: Prioridade Força
    if (isset($_GET['prioridade_forca_filtro']) && is_array($_GET['prioridade_forca_filtro'])) {
        $prioridade = array_filter($_GET['prioridade_forca_filtro']);
        if (!empty($prioridade)) {
            $filters['prioridade_forca_filtro'] = $prioridade;
        }
    }

    // Processar filtro de semestre
    if (isset($_GET['data_selecao_geral_semestre_filtro']) && !empty($_GET['data_selecao_geral_semestre_filtro'])) {
        $semestre = $_GET['data_selecao_geral_semestre_filtro'];
        $ranges = [
            'um_semestre_vintetres' => ['2023-01-01', '2023-06-30'],
            'dois_semestre_vintetres' => ['2023-07-01', '2023-12-31'],
            'um_semestre_vintequatro' => ['2024-01-01', '2024-06-30'],
            'dois_semestre_vintequatro' => ['2024-07-01', '2024-12-31'],
            'um_semestre_vintecinco' => ['2025-01-01', '2025-06-30'],
            'dois_semestre_vintecinco' => ['2025-07-01', '2025-12-31'],
            'um_semestre_vinteseis' => ['2026-01-01', '2026-06-30'],
            'dois_semestre_vinteseis' => ['2026-07-01', '2026-12-31']
        ];
        
        if (isset($ranges[$semestre])) {
            $filters['data_semestre'] = $ranges[$semestre];
        }
    }

    // ==================== TESTE: Sem filtros ====================
    $test_sem_filtros = $ObrigatorioDAO->findAtivosComFiltrosPagevelComBusca([], $start, $length, '');
    $test_sem_filtros_count = is_array($test_sem_filtros) ? count($test_sem_filtros) : 0;

    // ==================== TESTE: Com filtros ====================
    $test_com_filtros = $ObrigatorioDAO->findAtivosComFiltrosPagevelComBusca($filters, $start, $length, $search_term);
    $test_com_filtros_count = is_array($test_com_filtros) ? count($test_com_filtros) : 0;

    // ==================== CONTAR ====================
    $total_with_filters = $ObrigatorioDAO->findAtivosComFiltrosComBusca($filters, $search_term);
    $recordsFiltered = is_array($total_with_filters) ? count($total_with_filters) : 0;

    $total_all = $ObrigatorioDAO->findAtivosComFiltros([]);
    $recordsTotal = is_array($total_all) ? count($total_all) : 0;

    // ==================== MONTAR RESPOSTA ====================
    $data = [];
    
    if (!empty($test_com_filtros)) {
        foreach ($test_com_filtros as $row) {
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

            $data[] = [
                'DT_RowClass' => '',
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

    // ==================== RESPOSTA JSON ====================
    $response = [
        "draw" => $draw,
        "recordsTotal" => $recordsTotal,
        "recordsFiltered" => $recordsFiltered,
        "data" => $data,
        "debug" => [
            "filters_recebidos" => $filters,
            "search_term" => $search_term,
            "test_sem_filtros_count" => $test_sem_filtros_count,
            "test_com_filtros_count" => $test_com_filtros_count,
            "total_com_filtros_busca" => $recordsFiltered,
            "debug_received" => $debug_received
        ]
    ];

    $json_response = json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
    if ($json_response === false) {
        http_response_code(500);
        die(json_encode([
            'error' => 'Erro ao codificar JSON',
            'json_error' => json_last_error_msg()
        ], JSON_UNESCAPED_UNICODE));
    }

    ob_clean();
    echo $json_response;
    exit();

} catch (Exception $e) {
    http_response_code(500);
    
    $error_response = [
        'error' => 'Erro no servidor',
        'message' => $e->getMessage(),
        'line' => $e->getLine(),
        'file' => basename($e->getFile()),
        'trace' => $e->getTraceAsString()
    ];
    
    ob_clean();
    echo json_encode($error_response, JSON_UNESCAPED_UNICODE);
    exit();
}
?>
