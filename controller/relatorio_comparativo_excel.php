<?php
/**
 * Endpoint para exportar Relatório Comparativo para Excel (CSV)
 * Versão Excel do mpdf/relatorio_comparativo.php
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['id_usuario_smo']) || !isset($_SESSION['perfil_smo']) || $_SESSION['perfil_smo'] != 'admin') {
    header('HTTP/1.1 403 Forbidden');
    echo 'Não autorizado';
    exit();
}

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Obrigatorio.php';
include_once '../models/Om.php';
include_once '../dao/ObrigatorioDAO.php';
include_once '../dao/OmDAO.php';
include_once '../dao/LogDAO.php';

$ObrigatorioDAO = new ObrigatorioDAO($conexao);
$omDAO = new OmDAO($conexao);
$logDAO = new LogDAO($conexao);

// Parâmetros do formulário
$ano1 = isset($_POST['ano1']) ? (int)$_POST['ano1'] : (int)date('Y');
$periodo1 = isset($_POST['periodo1']) ? $_POST['periodo1'] : 'ano';
$ano2 = isset($_POST['ano2']) ? (int)$_POST['ano2'] : (int)date('Y') - 1;
$periodo2 = isset($_POST['periodo2']) ? $_POST['periodo2'] : 'ano';
$tipoComparacao = isset($_POST['tipo_comparacao']) ? $_POST['tipo_comparacao'] : 'geral';
$exibirVariacao = isset($_POST['exibir_variacao']) ? $_POST['exibir_variacao'] == 'sim' : true;

// Função para converter período em meses e nome
function getPeriodoInfo($periodo) {
    switch ($periodo) {
        case '1sem':
            return ['meses' => [1, 2, 3, 4, 5, 6], 'nome' => '1º Semestre'];
        case '2sem':
            return ['meses' => [7, 8, 9, 10, 11, 12], 'nome' => '2º Semestre'];
        default:
            return ['meses' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], 'nome' => 'Ano Completo'];
    }
}

// Função para calcular variação
function calcularVariacao($valor1, $valor2) {
    if ($valor2 == 0) return $valor1 > 0 ? 100 : 0;
    return round((($valor1 - $valor2) / $valor2) * 100, 1);
}

$info1 = getPeriodoInfo($periodo1);
$info2 = getPeriodoInfo($periodo2);
$meses1 = $info1['meses'];
$meses2 = $info2['meses'];
$nomePeriodo1 = "$ano1 - {$info1['nome']}";
$nomePeriodo2 = "$ano2 - {$info2['nome']}";

// Nome do arquivo
$nomeArquivo = "relatorio_comparativo_{$ano1}_{$ano2}_" . date('Y-m-d_H-i-s') . ".csv";

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
fputcsv($output, ['RELATÓRIO COMPARATIVO'], ';');
fputcsv($output, [$nomePeriodo1 . ' vs ' . $nomePeriodo2], ';');
fputcsv($output, ['Gerado em: ' . date('d/m/Y H:i:s')], ';');
fputcsv($output, ['Gerado por: ' . $_SESSION['nome_guerra_smo']], ';');
fputcsv($output, [''], ';');

// Comparativo Geral
if ($tipoComparacao == 'geral' || $tipoComparacao == 'todos') {
    $dados = $ObrigatorioDAO->getEstatisticasComparativas($ano1, $meses1, $ano2, $meses2);

    fputcsv($output, ['1. COMPARATIVO GERAL'], ';');

    if ($exibirVariacao) {
        fputcsv($output, ['Indicador', $nomePeriodo1, $nomePeriodo2, 'Variação'], ';');
    } else {
        fputcsv($output, ['Indicador', $nomePeriodo1, $nomePeriodo2], ';');
    }

    $indicadores = [
        ['label' => 'Total de Obrigatórios', 'key' => 'total'],
        ['label' => 'Incorporados', 'key' => 'incorporados'],
        ['label' => 'Aptos', 'key' => 'aptos'],
        ['label' => 'Inaptos', 'key' => 'inaptos'],
        ['label' => 'Pendentes Revisão', 'key' => 'pendentes']
    ];

    foreach ($indicadores as $ind) {
        $v1 = $dados['periodo1'][$ind['key']];
        $v2 = $dados['periodo2'][$ind['key']];
        $variacao = calcularVariacao($v1, $v2);
        $sinal = $variacao >= 0 ? '+' : '';

        if ($exibirVariacao) {
            fputcsv($output, [$ind['label'], $v1, $v2, $sinal . $variacao . '%'], ';');
        } else {
            fputcsv($output, [$ind['label'], $v1, $v2], ';');
        }
    }

    // Taxa de aprovação
    $taxaAprov1 = $dados['periodo1']['total'] > 0 ? round(($dados['periodo1']['aptos'] / $dados['periodo1']['total']) * 100, 1) : 0;
    $taxaAprov2 = $dados['periodo2']['total'] > 0 ? round(($dados['periodo2']['aptos'] / $dados['periodo2']['total']) * 100, 1) : 0;
    $variacaoTaxa = $taxaAprov1 - $taxaAprov2;
    $sinalTaxa = $variacaoTaxa >= 0 ? '+' : '';

    if ($exibirVariacao) {
        fputcsv($output, ['Taxa de Aprovação', $taxaAprov1 . '%', $taxaAprov2 . '%', $sinalTaxa . round($variacaoTaxa, 1) . ' p.p.'], ';');
    } else {
        fputcsv($output, ['Taxa de Aprovação', $taxaAprov1 . '%', $taxaAprov2 . '%'], ';');
    }

    fputcsv($output, [''], ';');
}

// Comparativo por Especialidade
if ($tipoComparacao == 'especialidade' || $tipoComparacao == 'geral') {
    $esp1 = $ObrigatorioDAO->getEstatisticasPorEspecialidade($ano1, $meses1);
    $esp2 = $ObrigatorioDAO->getEstatisticasPorEspecialidade($ano2, $meses2);

    $todasEsp = array_unique(array_merge($esp1['labels'], $esp2['labels']));

    fputcsv($output, ['2. COMPARATIVO POR ESPECIALIDADE'], ';');

    if ($exibirVariacao) {
        fputcsv($output, ['Especialidade', $nomePeriodo1, $nomePeriodo2, 'Variação'], ';');
    } else {
        fputcsv($output, ['Especialidade', $nomePeriodo1, $nomePeriodo2], ';');
    }

    foreach ($todasEsp as $esp) {
        $idx1 = array_search($esp, $esp1['labels']);
        $idx2 = array_search($esp, $esp2['labels']);

        $v1 = $idx1 !== false ? $esp1['data'][$idx1] : 0;
        $v2 = $idx2 !== false ? $esp2['data'][$idx2] : 0;
        $variacao = calcularVariacao($v1, $v2);
        $sinal = $variacao >= 0 ? '+' : '';

        if ($exibirVariacao) {
            fputcsv($output, [$esp, $v1, $v2, $sinal . $variacao . '%'], ';');
        } else {
            fputcsv($output, [$esp, $v1, $v2], ';');
        }
    }

    fputcsv($output, [''], ';');
}

// Comparativo por OM
if ($tipoComparacao == 'om') {
    $om1 = $ObrigatorioDAO->getEstatisticasPorOM($ano1, $meses1);
    $om2 = $ObrigatorioDAO->getEstatisticasPorOM($ano2, $meses2);

    $todasOM = array_unique(array_merge($om1['labels'], $om2['labels']));

    fputcsv($output, ['2. COMPARATIVO POR ORGANIZAÇÃO MILITAR'], ';');

    if ($exibirVariacao) {
        fputcsv($output, ['OM', $nomePeriodo1, $nomePeriodo2, 'Variação'], ';');
    } else {
        fputcsv($output, ['OM', $nomePeriodo1, $nomePeriodo2], ';');
    }

    foreach ($todasOM as $om) {
        $idx1 = array_search($om, $om1['labels']);
        $idx2 = array_search($om, $om2['labels']);

        $v1 = $idx1 !== false ? $om1['data'][$idx1] : 0;
        $v2 = $idx2 !== false ? $om2['data'][$idx2] : 0;
        $variacao = calcularVariacao($v1, $v2);
        $sinal = $variacao >= 0 ? '+' : '';

        if ($exibirVariacao) {
            fputcsv($output, [$om, $v1, $v2, $sinal . $variacao . '%'], ';');
        } else {
            fputcsv($output, [$om, $v1, $v2], ';');
        }
    }

    fputcsv($output, [''], ';');
}

// Comparativo por Situação Militar
if ($tipoComparacao == 'situacao') {
    $sit1 = $ObrigatorioDAO->getEstatisticasPorSituacaoMilitar($ano1, $meses1);
    $sit2 = $ObrigatorioDAO->getEstatisticasPorSituacaoMilitar($ano2, $meses2);

    $todasSit = array_unique(array_merge($sit1['labels'], $sit2['labels']));

    fputcsv($output, ['2. COMPARATIVO POR SITUAÇÃO MILITAR'], ';');

    if ($exibirVariacao) {
        fputcsv($output, ['Situação', $nomePeriodo1, $nomePeriodo2, 'Variação'], ';');
    } else {
        fputcsv($output, ['Situação', $nomePeriodo1, $nomePeriodo2], ';');
    }

    foreach ($todasSit as $sit) {
        $idx1 = array_search($sit, $sit1['labels']);
        $idx2 = array_search($sit, $sit2['labels']);

        $v1 = $idx1 !== false ? $sit1['data'][$idx1] : 0;
        $v2 = $idx2 !== false ? $sit2['data'][$idx2] : 0;
        $variacao = calcularVariacao($v1, $v2);
        $sinal = $variacao >= 0 ? '+' : '';

        if ($exibirVariacao) {
            fputcsv($output, [$sit, $v1, $v2, $sinal . $variacao . '%'], ';');
        } else {
            fputcsv($output, [$sit, $v1, $v2], ';');
        }
    }

    fputcsv($output, [''], ';');
}

fclose($output);

// Log
$logDAO->insertLog(4012, "Excel", null, "Exportou Relatório Comparativo Excel - $nomePeriodo1 vs $nomePeriodo2", "Relatório comparativo exportado para Excel.", null);

exit();
?>
