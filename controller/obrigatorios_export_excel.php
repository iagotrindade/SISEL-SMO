<?php
/**
 * Endpoint para exportar dados de Obrigatórios para Excel (CSV)
 * Reutiliza a mesma lógica de filtros do obrigatorios_ajax.php
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../dao/conecta_banco.php';
include_once '../dao/ObrigatorioDAO.php';
include_once '../funcoes.php';

// Verificar permissão
if (!isset($_SESSION['perfil_smo']) || $_SESSION['perfil_smo'] != "admin" || !isset($_SESSION['id_usuario_smo'])) {
    header('HTTP/1.1 403 Forbidden');
    echo 'Não autorizado';
    exit();
}

$ObrigatorioDAO = new ObrigatorioDAO($conexao);

// ==================== PREPARAR FILTROS ====================
$filters = [];

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
    $result = array_filter($_GET['resultado_revisao_filtro']);
    if (!empty($result)) {
        $filters['resultado_revisao_filtro'] = $result;
    }
}

// Filtro: ISGR
if (isset($_GET['isgr_filtro']) && is_array($_GET['isgr_filtro'])) {
    $isgr = array_filter($_GET['isgr_filtro']);
    if (!empty($isgr)) {
        $filters['isgr_filtro'] = $isgr;
    }
}

// Filtro: Seleção Geral
if (isset($_GET['sel_geral_filtro']) && is_array($_GET['sel_geral_filtro'])) {
    $sg = array_filter($_GET['sel_geral_filtro']);
    if (!empty($sg)) {
        $filters['sel_geral_filtro'] = $sg;
    }
}

// Filtro: Compareceu Designação
if (isset($_GET['comp_designacao_filtro']) && is_array($_GET['comp_designacao_filtro'])) {
    $cd = array_filter($_GET['comp_designacao_filtro']);
    if (!empty($cd)) {
        $filters['comp_designacao_filtro'] = $cd;
    }
}

// Filtro: Incorporação
if (isset($_GET['incorporacao_filtro']) && is_array($_GET['incorporacao_filtro'])) {
    $inc = array_filter($_GET['incorporacao_filtro']);
    if (!empty($inc)) {
        $filters['incorporacao_filtro'] = $inc;
    }
}

// Filtro: Seleção Complementar
if (isset($_GET['sel_complementar_filtro']) && is_array($_GET['sel_complementar_filtro'])) {
    $sc = array_filter($_GET['sel_complementar_filtro']);
    if (!empty($sc)) {
        $filters['sel_complementar_filtro'] = $sc;
    }
}

// Filtro: Situação Militar
if (isset($_GET['situacao_militar']) && is_array($_GET['situacao_militar'])) {
    $sm = array_filter($_GET['situacao_militar']);
    if (!empty($sm)) {
        $filters['situacao_militar'] = $sm;
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
    $esp = array_filter($_GET['especialidade_filtro']);
    if (!empty($esp)) {
        $filters['especialidade_filtro'] = $esp;
    }
}

// Filtro: Prioridade Força
if (isset($_GET['prioridade_forca_filtro']) && is_array($_GET['prioridade_forca_filtro'])) {
    $pf = array_filter($_GET['prioridade_forca_filtro']);
    if (!empty($pf)) {
        $filters['prioridade_forca_filtro'] = $pf;
    }
}

// Filtro: Semestre Seleção Geral
if (isset($_GET['data_selecao_geral_semestre_filtro']) && !empty($_GET['data_selecao_geral_semestre_filtro'])) {
    $semestre = $_GET['data_selecao_geral_semestre_filtro'];
    // Se vier como array, pega o primeiro valor
    if (is_array($semestre)) {
        $semestre = reset($semestre);
    }
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

// Busca todos os registros (sem paginação - limite alto)
$results = $ObrigatorioDAO->findAtivosComFiltrosPaginado($filters, 0, 50000, '', 'nome_completo', 'ASC');

// Nome do arquivo
$nomeArquivo = "obrigatorios_" . date('Y-m-d_H-i-s') . ".csv";

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
fputcsv($output, ['RELATÓRIO DE OBRIGATÓRIOS - SMO'], ';');
fputcsv($output, ['Gerado em: ' . date('d/m/Y H:i:s')], ';');
fputcsv($output, ['Total de registros: ' . count($results)], ';');
fputcsv($output, [''], ';');

// Cabeçalhos das colunas
fputcsv($output, [
    'Nome',
    'CPF',
    'Formação',
    'Instituição',
    'Ano Residência',
    'Especialidade',
    'Data Nascimento',
    'Situação Militar',
    'OM 1ª Fase',
    'JISE',
    'JISR',
    'Voluntário',
    'Dependentes',
    'Resultado Revisão',
    'Data Seleção Geral',
    'Telefone',
    'E-mail'
], ';');

// Dados
foreach ($results as $row) {
    fputcsv($output, [
        $row['nome_completo'] ?? '',
        $row['cpf'] ?? '',
        $row['formacao'] ?? '',
        $row['nome_instituicao_ensino'] ?? '',
        $row['ano_residencia'] ?? '',
        $row['especialidade_1'] ?? '',
        isset($row['data_nascimento']) ? date('d/m/Y', strtotime($row['data_nascimento'])) : '',
        $row['situacao_militar'] ?? '',
        $row['abreviatura_om_1_fase'] ?? '',
        $row['jise'] ?? '',
        $row['jisr'] ?? '',
        $row['voluntario'] ?? '',
        $row['dependentes'] ?? '',
        $row['resultado_revisao_medica_complementar'] ?? '',
        isset($row['data_selecao_geral']) ? date('d/m/Y', strtotime($row['data_selecao_geral'])) : '',
        $row['telefone'] ?? '',
        $row['mail'] ?? ''
    ], ';');
}

fclose($output);
exit();
?>
