<?php
ob_start();

if (!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Obrigatorio.php';
include_once '../models/Om.php';
include_once '../dao/ObrigatorioDAO.php';
include_once '../dao/OmDAO.php';
include_once '../dao/LogDAO.php';
include("mpdf60/mpdf.php");

$mpdf = new mPDF('C', 'A4-P', 0, '', 15, 15, 16, 16, 9, 9);
$mpdf->SetDisplayMode('fullpage');

if ($_SESSION['perfil_smo'] != "admin") {
    erro($BASE_URL, 2, 6549874951, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");
}

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

$info1 = getPeriodoInfo($periodo1);
$info2 = getPeriodoInfo($periodo2);
$meses1 = $info1['meses'];
$meses2 = $info2['meses'];
$nomePeriodo1 = "$ano1 - {$info1['nome']}";
$nomePeriodo2 = "$ano2 - {$info2['nome']}";

// Função para calcular variação
function calcularVariacao($valor1, $valor2) {
    if ($valor2 == 0) return $valor1 > 0 ? 100 : 0;
    return round((($valor1 - $valor2) / $valor2) * 100, 1);
}

// Função para formatar variação com cor
function formatarVariacao($variacao) {
    $cor = $variacao >= 0 ? '#28a745' : '#dc3545';
    $sinal = $variacao >= 0 ? '+' : '';
    return "<span style='color: $cor; font-weight: bold;'>$sinal{$variacao}%</span>";
}

// CSS do relatório
$css = "
<style>
    body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10pt; color: #333; }
    .cabecalho { text-align: center; margin-bottom: 15px; }
    .cabecalho img { margin-bottom: 5px; }
    .cabecalho-texto { font-size: 9pt; line-height: 1.3; }
    .titulo-principal { font-size: 14pt; font-weight: bold; text-align: center; margin: 10px 0; color: #006400; }
    .subtitulo { font-size: 11pt; text-align: center; margin-bottom: 15px; color: #666; }
    .secao-titulo { font-size: 11pt; font-weight: bold; color: #006400; border-bottom: 2px solid #006400; padding-bottom: 5px; margin: 15px 0 10px 0; }
    .tabela { width: 100%; border-collapse: collapse; margin: 10px 0; }
    .tabela th { background-color: #006400; color: white; padding: 8px; text-align: center; font-size: 9pt; }
    .tabela td { padding: 6px 8px; border-bottom: 1px solid #ddd; font-size: 9pt; }
    .tabela tr:nth-child(even) { background-color: #f9f9f9; }
    .tabela-comparativo { width: 100%; margin: 15px 0; }
    .tabela-comparativo td { padding: 15px; text-align: center; vertical-align: middle; }
    .periodo-box { background: #f8f9fa; border-radius: 5px; padding: 15px; margin: 5px; }
    .periodo-titulo { font-size: 10pt; font-weight: bold; color: #006400; margin-bottom: 10px; }
    .valor-grande { font-size: 28pt; font-weight: bold; color: #006400; }
    .valor-label { font-size: 8pt; color: #666; }
    .variacao-box { background: #e9ecef; border-radius: 50%; width: 60px; height: 60px; line-height: 60px; margin: 0 auto; font-weight: bold; }
    .rodape { font-size: 8pt; color: #999; text-align: center; margin-top: 15px; border-top: 1px solid #ddd; padding-top: 8px; }
    .barra-comparativo { height: 25px; border-radius: 3px; margin: 5px 0; position: relative; }
    .barra-label { position: absolute; right: 5px; top: 3px; font-size: 9pt; color: white; font-weight: bold; }
    .legenda { display: inline-block; margin-right: 15px; }
    .legenda-cor { display: inline-block; width: 15px; height: 15px; border-radius: 3px; vertical-align: middle; margin-right: 5px; }
    .pagebreak { page-break-before: always; }
    .destaque-positivo { color: #28a745; }
    .destaque-negativo { color: #dc3545; }
</style>";

// Cabeçalho
$html = $css . "
<div class='cabecalho'>
    <img src='../imagens/brasao.png' width='60'>
    <div class='cabecalho-texto'>
        MINISTÉRIO DA DEFESA - EXÉRCITO BRASILEIRO<br>
        COMANDO MILITAR DO SUL - COMANDO DA 3ª REGIÃO MILITAR
    </div>
</div>

<div class='titulo-principal'>RELATÓRIO COMPARATIVO</div>
<div class='subtitulo'>$nomePeriodo1 vs $nomePeriodo2 | Gerado em: " . date('d/m/Y H:i') . "</div>

<p style='text-align: center;'>
    <span class='legenda'><span class='legenda-cor' style='background: #006400;'></span> $nomePeriodo1</span>
    <span class='legenda'><span class='legenda-cor' style='background: #0d6efd;'></span> $nomePeriodo2</span>
</p>";

// Comparativo Geral
if ($tipoComparacao == 'geral' || $tipoComparacao == 'todos') {
    // Busca estatísticas dos dois períodos
    $dados = $ObrigatorioDAO->getEstatisticasComparativas($ano1, $meses1, $ano2, $meses2);

    $html .= "
    <div class='secao-titulo'>1. COMPARATIVO GERAL</div>
    <table class='tabela'>
        <tr>
            <th style='width: 25%;'>Indicador</th>
            <th style='width: 25%;'>$nomePeriodo1</th>
            <th style='width: 25%;'>$nomePeriodo2</th>";

    if ($exibirVariacao) {
        $html .= "<th style='width: 25%;'>Variação</th>";
    }
    $html .= "</tr>";

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

        $html .= "
        <tr>
            <td><strong>{$ind['label']}</strong></td>
            <td style='text-align: center; font-weight: bold; color: #006400;'>$v1</td>
            <td style='text-align: center; font-weight: bold; color: #0d6efd;'>$v2</td>";

        if ($exibirVariacao) {
            $html .= "<td style='text-align: center;'>" . formatarVariacao($variacao) . "</td>";
        }
        $html .= "</tr>";
    }

    // Taxa de aprovação
    $taxaAprov1 = $dados['periodo1']['total'] > 0 ? round(($dados['periodo1']['aptos'] / $dados['periodo1']['total']) * 100, 1) : 0;
    $taxaAprov2 = $dados['periodo2']['total'] > 0 ? round(($dados['periodo2']['aptos'] / $dados['periodo2']['total']) * 100, 1) : 0;
    $variacaoTaxa = $taxaAprov1 - $taxaAprov2;

    $html .= "
        <tr style='background: #f0f7f0;'>
            <td><strong>Taxa de Aprovação</strong></td>
            <td style='text-align: center; font-weight: bold; color: #006400;'>$taxaAprov1%</td>
            <td style='text-align: center; font-weight: bold; color: #0d6efd;'>$taxaAprov2%</td>";

    if ($exibirVariacao) {
        $corTaxa = $variacaoTaxa >= 0 ? '#28a745' : '#dc3545';
        $sinalTaxa = $variacaoTaxa >= 0 ? '+' : '';
        $html .= "<td style='text-align: center;'><span style='color: $corTaxa; font-weight: bold;'>{$sinalTaxa}" . round($variacaoTaxa, 1) . " p.p.</span></td>";
    }
    $html .= "</tr></table>";

    // Gráfico de barras visual
    $maxVal = max($dados['periodo1']['total'], $dados['periodo2']['total'], 1);
    $largura1 = round(($dados['periodo1']['total'] / $maxVal) * 100);
    $largura2 = round(($dados['periodo2']['total'] / $maxVal) * 100);

    $html .= "
    <div class='secao-titulo'>Visualização Comparativa - Total de Obrigatórios</div>
    <div style='margin: 10px 0;'>
        <p style='margin: 5px 0;'><strong>$nomePeriodo1</strong></p>
        <div style='background: #e9ecef; border-radius: 3px; width: 100%;'>
            <div class='barra-comparativo' style='width: {$largura1}%; background: #006400;'>
                <span class='barra-label'>{$dados['periodo1']['total']}</span>
            </div>
        </div>
        <p style='margin: 5px 0;'><strong>$nomePeriodo2</strong></p>
        <div style='background: #e9ecef; border-radius: 3px; width: 100%;'>
            <div class='barra-comparativo' style='width: {$largura2}%; background: #0d6efd;'>
                <span class='barra-label'>{$dados['periodo2']['total']}</span>
            </div>
        </div>
    </div>";
}

// Comparativo por Especialidade
if ($tipoComparacao == 'especialidade' || $tipoComparacao == 'geral') {
    $esp1 = $ObrigatorioDAO->getEstatisticasPorEspecialidade($ano1, $meses1);
    $esp2 = $ObrigatorioDAO->getEstatisticasPorEspecialidade($ano2, $meses2);

    // Combina as especialidades
    $todasEsp = array_unique(array_merge($esp1['labels'], $esp2['labels']));

    $html .= "
    <div class='pagebreak'></div>
    <div class='secao-titulo'>2. COMPARATIVO POR ESPECIALIDADE</div>
    <table class='tabela'>
        <tr>
            <th style='width: 40%;'>Especialidade</th>
            <th style='width: 20%;'>$nomePeriodo1</th>
            <th style='width: 20%;'>$nomePeriodo2</th>";

    if ($exibirVariacao) {
        $html .= "<th style='width: 20%;'>Variação</th>";
    }
    $html .= "</tr>";

    foreach ($todasEsp as $esp) {
        $idx1 = array_search($esp, $esp1['labels']);
        $idx2 = array_search($esp, $esp2['labels']);

        $v1 = $idx1 !== false ? $esp1['data'][$idx1] : 0;
        $v2 = $idx2 !== false ? $esp2['data'][$idx2] : 0;
        $variacao = calcularVariacao($v1, $v2);

        $html .= "
        <tr>
            <td>" . htmlspecialchars($esp) . "</td>
            <td style='text-align: center;'>$v1</td>
            <td style='text-align: center;'>$v2</td>";

        if ($exibirVariacao) {
            $html .= "<td style='text-align: center;'>" . formatarVariacao($variacao) . "</td>";
        }
        $html .= "</tr>";
    }

    $html .= "</table>";
}

// Comparativo por OM
if ($tipoComparacao == 'om') {
    $om1 = $ObrigatorioDAO->getEstatisticasPorOM($ano1, $meses1);
    $om2 = $ObrigatorioDAO->getEstatisticasPorOM($ano2, $meses2);

    // Combina as OMs
    $todasOM = array_unique(array_merge($om1['labels'], $om2['labels']));

    $html .= "
    <div class='secao-titulo'>2. COMPARATIVO POR ORGANIZAÇÃO MILITAR</div>
    <table class='tabela'>
        <tr>
            <th style='width: 40%;'>OM</th>
            <th style='width: 20%;'>$nomePeriodo1</th>
            <th style='width: 20%;'>$nomePeriodo2</th>";

    if ($exibirVariacao) {
        $html .= "<th style='width: 20%;'>Variação</th>";
    }
    $html .= "</tr>";

    foreach ($todasOM as $om) {
        $idx1 = array_search($om, $om1['labels']);
        $idx2 = array_search($om, $om2['labels']);

        $v1 = $idx1 !== false ? $om1['data'][$idx1] : 0;
        $v2 = $idx2 !== false ? $om2['data'][$idx2] : 0;
        $variacao = calcularVariacao($v1, $v2);

        $html .= "
        <tr>
            <td>" . htmlspecialchars($om) . "</td>
            <td style='text-align: center;'>$v1</td>
            <td style='text-align: center;'>$v2</td>";

        if ($exibirVariacao) {
            $html .= "<td style='text-align: center;'>" . formatarVariacao($variacao) . "</td>";
        }
        $html .= "</tr>";
    }

    $html .= "</table>";
}

// Comparativo por Situação Militar
if ($tipoComparacao == 'situacao') {
    $sit1 = $ObrigatorioDAO->getEstatisticasPorSituacaoMilitar($ano1, $meses1);
    $sit2 = $ObrigatorioDAO->getEstatisticasPorSituacaoMilitar($ano2, $meses2);

    // Combina as situações
    $todasSit = array_unique(array_merge($sit1['labels'], $sit2['labels']));

    $html .= "
    <div class='secao-titulo'>2. COMPARATIVO POR SITUAÇÃO MILITAR</div>
    <table class='tabela'>
        <tr>
            <th style='width: 40%;'>Situação</th>
            <th style='width: 20%;'>$nomePeriodo1</th>
            <th style='width: 20%;'>$nomePeriodo2</th>";

    if ($exibirVariacao) {
        $html .= "<th style='width: 20%;'>Variação</th>";
    }
    $html .= "</tr>";

    foreach ($todasSit as $sit) {
        $idx1 = array_search($sit, $sit1['labels']);
        $idx2 = array_search($sit, $sit2['labels']);

        $v1 = $idx1 !== false ? $sit1['data'][$idx1] : 0;
        $v2 = $idx2 !== false ? $sit2['data'][$idx2] : 0;
        $variacao = calcularVariacao($v1, $v2);

        $html .= "
        <tr>
            <td>" . htmlspecialchars($sit) . "</td>
            <td style='text-align: center;'>$v1</td>
            <td style='text-align: center;'>$v2</td>";

        if ($exibirVariacao) {
            $html .= "<td style='text-align: center;'>" . formatarVariacao($variacao) . "</td>";
        }
        $html .= "</tr>";
    }

    $html .= "</table>";
}

// Rodapé
$html .= "
<div class='rodape'>
    Relatório gerado pelo Sistema SISEL-SMO | 3ª Região Militar<br>
    Gerado em " . date('d/m/Y H:i:s') . " por " . $_SESSION['nome_guerra_smo']
. "</div>";

$mpdf->WriteHTML($html);

// Log
$logDAO->insertLog(4012, "PDF", null, "Gerou Relatório Comparativo - $nomePeriodo1 vs $nomePeriodo2", "Relatório comparativo gerado.", null);

ob_end_clean();
$nomeArquivo = "relatorio_comparativo_{$ano1}_{$ano2}_" . date('Ymd_His') . ".pdf";
$mpdf->Output($nomeArquivo, 'I');
?>
