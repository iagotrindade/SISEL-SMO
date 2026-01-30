<?php
// =========================================================================
// ARQUIVO: controller/pre_distribuicao_ajax.php
// Processa requisições AJAX do DataTables para pre_distribuicao.php
// =========================================================================

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');

error_reporting(0);
ini_set('display_errors', '0');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    include_once '../dao/conecta_banco.php';
    include_once '../dao/ObrigatorioDAO.php';
    include_once '../dao/AuxiliarDAO.php';
    include_once '../funcoes.php';
} catch (Exception $e) {
    http_response_code(500);
    die(json_encode(['error' => 'Erro ao incluir arquivos'], JSON_UNESCAPED_UNICODE));
}

// Verificar permissão
if (!isset($_SESSION['perfil_smo']) || $_SESSION['perfil_smo'] != "admin" || !isset($_SESSION['id_usuario_smo'])) {
    http_response_code(403);
    die(json_encode(['error' => 'Sem permissão'], JSON_UNESCAPED_UNICODE));
}

try {
    $ObrigatorioDAO = new ObrigatorioDAO($conexao);
    $AuxiliarDAO = new AuxiliarDAO($conexao);

    // ==================== PARÂMETROS DATATABLE ====================
    $draw = isset($_GET['draw']) ? intval($_GET['draw']) : 1;
    $start = isset($_GET['start']) ? intval($_GET['start']) : 0;
    $length = isset($_GET['length']) ? intval($_GET['length']) : 25;

    // Busca global
    $search_term = '';
    if (isset($_GET['search']['value']) && !empty($_GET['search']['value'])) {
        $search_term = trim($_GET['search']['value']);
    }

    // Ordenação
    $orderColumn = 'nome_completo';
    $orderDir = 'ASC';
    if (isset($_GET['order'][0])) {
        $columnIndex = intval($_GET['order'][0]['column']);
        $columns = ['', 'nome_completo', 'formacao', '', 'nome_instituicao_ensino', 'especialidade_1', '', 'data_nascimento', 'situacao_militar', '', '', '', ''];
        if (isset($columns[$columnIndex]) && !empty($columns[$columnIndex])) {
            $orderColumn = $columns[$columnIndex];
        }
        $orderDir = ($_GET['order'][0]['dir'] === 'desc') ? 'DESC' : 'ASC';
    }

    // ==================== PREPARAR FILTROS ====================
    $filters = [];

    // Filtro: Voluntário (texto simples, não array)
    if (isset($_GET['voluntario_filtro']) && !empty($_GET['voluntario_filtro'])) {
        $filters['voluntario_filtro'] = [$_GET['voluntario_filtro']];
    }

    // Filtro: Dependentes
    if (isset($_GET['dependentes_filtro']) && !empty($_GET['dependentes_filtro'])) {
        $filters['dependentes_filtro'] = $_GET['dependentes_filtro'];
    }

    // Filtro: Faculdade
    if (isset($_GET['faculdade_filtro']) && is_array($_GET['faculdade_filtro'])) {
        $faculty = array_filter($_GET['faculdade_filtro']);
        if (!empty($faculty)) {
            $filters['faculdade_filtro'] = array_values($faculty);
        }
    }

    // Filtro: JISE
    if (isset($_GET['jise_filtro']) && is_array($_GET['jise_filtro'])) {
        $jise = array_filter($_GET['jise_filtro']);
        if (!empty($jise)) {
            $filters['jise_filtro'] = array_values($jise);
        }
    }

    // Filtro: JISR
    if (isset($_GET['jisr_filtro']) && is_array($_GET['jisr_filtro'])) {
        $jisr = array_filter($_GET['jisr_filtro']);
        if (!empty($jisr)) {
            $filters['jisr_filtro'] = array_values($jisr);
        }
    }

    // Filtro: Distribuição
    if (isset($_GET['distribuicao_filtro']) && is_array($_GET['distribuicao_filtro'])) {
        $dist = array_filter($_GET['distribuicao_filtro']);
        if (!empty($dist)) {
            $filters['distribuicao_filtro'] = array_values($dist);
        }
    }

    // Filtro: OM 1ª Fase
    if (isset($_GET['om_1_fase_filtro']) && is_array($_GET['om_1_fase_filtro'])) {
        $om = array_filter($_GET['om_1_fase_filtro']);
        if (!empty($om)) {
            $filters['om_1_fase_filtro'] = array_values($om);
        }
    }

    // Filtro: Resultado Revisão
    if (isset($_GET['resultado_revisao_filtro']) && is_array($_GET['resultado_revisao_filtro'])) {
        $rev = array_filter($_GET['resultado_revisao_filtro']);
        if (!empty($rev)) {
            $filters['resultado_revisao_filtro'] = array_values($rev);
        }
    }

    // Filtro: Data Seleção Geral
    if (isset($_GET['sel_geral_filtro']) && is_array($_GET['sel_geral_filtro'])) {
        $sel = array_filter($_GET['sel_geral_filtro']);
        if (!empty($sel)) {
            $filters['sel_geral_filtro'] = array_values($sel);
        }
    }

    // Filtro: Incorporação
    if (isset($_GET['incorporacao_filtro']) && is_array($_GET['incorporacao_filtro'])) {
        $incorp = array_filter($_GET['incorporacao_filtro']);
        if (!empty($incorp)) {
            $filters['incorporacao_filtro'] = array_values($incorp);
        }
    }

    // Filtro: Situação Militar
    if (isset($_GET['situacao_militar']) && is_array($_GET['situacao_militar'])) {
        $situacao = array_filter($_GET['situacao_militar']);
        if (!empty($situacao)) {
            $filters['situacao_militar'] = array_values($situacao);
        }
    }

    // Filtro: RM Destino (texto simples)
    if (isset($_GET['rm_destino']) && !empty($_GET['rm_destino'])) {
        $filters['rm_destino_filtro'] = [$_GET['rm_destino']];
    }

    // Filtro: Especialidade
    if (isset($_GET['especialidade_filtro']) && is_array($_GET['especialidade_filtro'])) {
        $espec = array_filter($_GET['especialidade_filtro']);
        if (!empty($espec)) {
            $filters['especialidade_filtro'] = array_values($espec);
        }
    }

    // Filtro: Prioridade Força (texto simples)
    if (isset($_GET['prioridade_forca_filtro']) && !empty($_GET['prioridade_forca_filtro'])) {
        $filters['prioridade_forca_filtro'] = [$_GET['prioridade_forca_filtro']];
    }

    // Filtro: Formação
    if (isset($_GET['formacao_filtro']) && !empty($_GET['formacao_filtro'])) {
        $filters['formacao_filtro'] = $_GET['formacao_filtro'];
    }

    // Filtro: Compareceu Designação
    if (isset($_GET['compareceu_designacao_filtro']) && !empty($_GET['compareceu_designacao_filtro'])) {
        $filters['compareceu_designacao_filtro'] = $_GET['compareceu_designacao_filtro'];
    }

    // Filtro: Local Compareceu Designação
    if (isset($_GET['local_compareceu_designacao_filtro']) && !empty($_GET['local_compareceu_designacao_filtro'])) {
        $filters['local_compareceu_designacao_filtro'] = $_GET['local_compareceu_designacao_filtro'];
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

    // Filtro: Comparecimento Designação
    if (isset($_GET['comp_designacao_filtro']) && is_array($_GET['comp_designacao_filtro'])) {
        $comp = array_filter($_GET['comp_designacao_filtro']);
        if (!empty($comp)) {
            $filters['comp_designacao_filtro'] = $comp;
        }
    }

    // ==================== BUSCAR DADOS ====================
    $recordsTotal = $ObrigatorioDAO->countAtivos();
    $recordsFiltered = $ObrigatorioDAO->countAtivosComFiltros($filters, $search_term);
    $results = $ObrigatorioDAO->findAtivosComFiltrosPaginado($filters, $start, $length, $search_term, $orderColumn, $orderDir);

    // ==================== MONTAR RESPOSTA ====================
    $data = [];

    if (!empty($results)) {
        foreach ($results as $row) {
            $criptografia = hash('sha256', $row['id'] . "criptografia");

            // Cor de fundo baseada na situação militar
            $cor = "rgba(255, 222, 173, 0.25)";
            if (isset($row['situacao_militar']) && strpos($row['situacao_militar'], "Quite") !== false) {
                $cor = "rgba(0, 100, 0, 0.10)";
            }

            // Ano de residência mais recente
            $anos = array_filter([
                $row['ano_residencia_espe_1'] ?? '',
                $row['ano_residencia_espe_2'] ?? '',
                $row['ano_residencia_espe_3'] ?? ''
            ]);
            $ano_residencia = !empty($anos) ? max($anos) : '';

            // Especialidade (pegar a primeira preenchida)
            $especialidade = $row['especialidade_1'] ?? ($row['especialidade_2'] ?? ($row['especialidade_3'] ?? ''));

            // Calcular dias de vida
            $diasDeVida = '';
            if (!empty($row['data_nascimento'])) {
                $hoje = date("Y-m-d");
                $diasDeVida = floor((strtotime($hoje) - strtotime($row['data_nascimento'])) / (60 * 60 * 24));
            }

            // Transferência FISEMI
            $transferencia = '';
            if (!empty($row['rm_origem_fisemi']) && !empty($row['rm_destino_fisemi'])) {
                $transferencia = $row['rm_origem_fisemi'] . "ª à " . $row['rm_destino_fisemi'] . "ª";
            }

            // OM 1ª Fase
            $om_1_fase = $row['abreviatura_om_1_fase'] ?? '';

            // Compareceu designação
            $compareceu_desig = mb_strtoupper($row['compareceu_designacao'] ?? '');

            $data[] = [
                'DT_RowStyle' => "background-color: $cor",
                'checkbox' => "<input type='checkbox' name='ids[]' value='" . htmlspecialchars($row['id']) . "'>",
                'nome' => "<a href='obrigatorio.php?crip=$criptografia&id_obrigatorio=" . htmlspecialchars($row['id']) . "'><font color='black'>" . htmlspecialchars($row['nome_completo'] ?? '') . "</font></a>",
                'formacao' => htmlspecialchars(($row['formacao'] ?? '') . " / " . ($row['ano_formacao'] ?? '')),
                'om_1_fase' => htmlspecialchars($om_1_fase),
                'instituicao' => htmlspecialchars($row['nome_instituicao_ensino'] ?? ''),
                'especialidade' => htmlspecialchars($especialidade),
                'ano_residencia' => htmlspecialchars($ano_residencia),
                'dias_vida' => htmlspecialchars($diasDeVida),
                'situacao_militar' => htmlspecialchars($row['situacao_militar'] ?? ''),
                'compareceu_desig' => htmlspecialchars($compareceu_desig),
                'distribuicao' => htmlspecialchars($row['distribuicao'] ?? ''),
                'prioridade_gu' => '', // Seria necessário uma query adicional para isso
                'transferencia' => htmlspecialchars($transferencia)
            ];
        }
    }

    // ==================== RESPOSTA JSON ====================
    echo json_encode([
        "draw" => $draw,
        "recordsTotal" => $recordsTotal,
        "recordsFiltered" => $recordsFiltered,
        "data" => $data
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro no servidor',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
