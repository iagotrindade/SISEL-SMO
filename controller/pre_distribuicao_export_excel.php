<?php
/**
 * Endpoint para exportar dados de Pré-Distribuição para Excel (CSV)
 * Reutiliza a mesma lógica de filtros do pre_distribuicao_ajax.php
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../dao/conecta_banco.php';
include_once '../dao/ObrigatorioDAO.php';
include_once '../funcoes.php';

// Verificar permissão (admin)
if (!isset($_SESSION['perfil_smo']) || $_SESSION['perfil_smo'] != "admin" || !isset($_SESSION['id_usuario_smo'])) {
    header('HTTP/1.1 403 Forbidden');
    echo 'Não autorizado';
    exit();
}

$ObrigatorioDAO = new ObrigatorioDAO($conexao);

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

// Filtro: Guarnição (prioridade de guarnição)
$guarnicao_filtro_ids = [];
if (isset($_GET['guarnicao_filtro']) && is_array($_GET['guarnicao_filtro'])) {
    $guarnicao = array_filter($_GET['guarnicao_filtro']);
    if (!empty($guarnicao)) {
        $filters['guarnicao_filtro'] = array_values($guarnicao);
        $guarnicao_filtro_ids = array_values($guarnicao);
    }
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

// Busca todos os registros (sem paginação - limite alto)
$results = $ObrigatorioDAO->findAtivosComFiltrosPaginado($filters, 0, 50000, '', 'nome_completo', 'ASC');

// Nome do arquivo
$nomeArquivo = "pre_distribuicao_" . date('Y-m-d_H-i-s') . ".csv";

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
fputcsv($output, ['RELATÓRIO DE PRÉ-DISTRIBUIÇÃO - SMO'], ';');
fputcsv($output, ['Gerado em: ' . date('d/m/Y H:i:s')], ';');
fputcsv($output, ['Total de registros: ' . count($results)], ';');
fputcsv($output, [''], ';');

// Cabeçalhos das colunas
fputcsv($output, [
    'Nome',
    'Formação / Ano',
    'OM 1ª Fase',
    'Instituição',
    'Especialidade',
    'Ano Residência',
    'Dias de Vida',
    'Situação Militar',
    'Compareceu Designação',
    'Distribuição',
    'Prioridade Guarnição',
    'Transferência FISEMI',
    'JISE',
    'JISR',
    'Resultado Revisão',
    'Telefone',
    'E-mail'
], ';');

// Dados
foreach ($results as $row) {
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

    // Buscar prioridades de guarnição (só mostra se o filtro estiver ativo)
    $prioridade_gu_str = '';
    if (!empty($guarnicao_filtro_ids)) {
        $prioridades_gu = $ObrigatorioDAO->findAllGuarnicaoPrioridade($row['id']);
        if ($prioridades_gu && is_array($prioridades_gu)) {
            $partes = [];
            foreach ($prioridades_gu as $pgu) {
                // Mostrar apenas as guarnições que estão no filtro
                if (in_array($pgu['id_guarnicao'], $guarnicao_filtro_ids)) {
                    $partes[] = $pgu['prioridade'] . 'ª ' . $pgu['guarnicao'];
                }
            }
            $prioridade_gu_str = implode(', ', $partes);
        }
    }

    fputcsv($output, [
        $row['nome_completo'] ?? '',
        ($row['formacao'] ?? '') . ' / ' . ($row['ano_formacao'] ?? ''),
        $row['abreviatura_om_1_fase'] ?? '',
        $row['nome_instituicao_ensino'] ?? '',
        $especialidade,
        $ano_residencia,
        $diasDeVida,
        $row['situacao_militar'] ?? '',
        mb_strtoupper($row['compareceu_designacao'] ?? ''),
        $row['distribuicao'] ?? '',
        $prioridade_gu_str,
        $transferencia,
        $row['jise'] ?? '',
        $row['jisr'] ?? '',
        $row['resultado_revisao_medica_complementar'] ?? '',
        $row['telefone'] ?? '',
        $row['mail'] ?? ''
    ], ';');
}

fclose($output);
exit();
?>
