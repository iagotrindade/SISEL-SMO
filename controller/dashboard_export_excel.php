<?php
/**
 * Endpoint para exportar dados do Dashboard para Excel (CSV)
 * Gera arquivo CSV com BOM para compatibilidade com Excel e caracteres especiais
 */

// Inicia sessão primeiro - antes de qualquer output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['id_usuario_smo']) || !isset($_SESSION['perfil_smo']) || $_SESSION['perfil_smo'] != 'admin') {
    header('HTTP/1.1 403 Forbidden');
    echo 'Não autorizado';
    exit();
}

// Includes usando caminho relativo
include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Obrigatorio.php';
include_once '../dao/ObrigatorioDAO.php';

$obrigatorioDAO = new ObrigatorioDAO($conexao);

// Pega parâmetros
$ano = isset($_GET['ano']) ? (int)$_GET['ano'] : (int)date('Y');
$periodo = isset($_GET['periodo']) ? $_GET['periodo'] : 'ano';
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'todos';

// Valida o ano
$anoAtual = (int)date('Y');
if ($ano < 2020 || $ano > $anoAtual + 1) {
    $ano = $anoAtual;
}

// Converte período para meses
$mesesFiltro = [];
$nomePeriodo = 'Ano Completo';
switch ($periodo) {
    case '1sem':
        $mesesFiltro = [1, 2, 3, 4, 5, 6];
        $nomePeriodo = '1º Semestre';
        break;
    case '2sem':
        $mesesFiltro = [7, 8, 9, 10, 11, 12];
        $nomePeriodo = '2º Semestre';
        break;
    case '1tri':
        $mesesFiltro = [1, 2, 3];
        $nomePeriodo = '1º Trimestre';
        break;
    case '2tri':
        $mesesFiltro = [4, 5, 6];
        $nomePeriodo = '2º Trimestre';
        break;
    case '3tri':
        $mesesFiltro = [7, 8, 9];
        $nomePeriodo = '3º Trimestre';
        break;
    case '4tri':
        $mesesFiltro = [10, 11, 12];
        $nomePeriodo = '4º Trimestre';
        break;
    default:
        $mesesFiltro = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
}

// Busca os dados
$dados = [];

if ($tipo == 'todos' || $tipo == 'especialidade') {
    $dados['especialidades'] = $obrigatorioDAO->getEstatisticasPorEspecialidade($ano, $mesesFiltro);
}
if ($tipo == 'todos' || $tipo == 'situacao') {
    $dados['situacao_militar'] = $obrigatorioDAO->getEstatisticasPorSituacaoMilitar($ano, $mesesFiltro);
}
if ($tipo == 'todos' || $tipo == 'revisao') {
    $dados['resultado_revisao'] = $obrigatorioDAO->getEstatisticasPorResultadoRevisao($ano, $mesesFiltro);
}
if ($tipo == 'todos' || $tipo == 'mensal') {
    $dados['evolucao_mensal'] = $obrigatorioDAO->getEstatisticasMensais($ano, $mesesFiltro);
}

// Nome do arquivo
$nomeArquivo = "dashboard_smo_{$ano}_{$periodo}_" . date('Y-m-d_H-i-s') . ".csv";

// Headers para download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $nomeArquivo . '"');
header('Pragma: no-cache');
header('Expires: 0');

// Abre output
$output = fopen('php://output', 'w');

// BOM para UTF-8 (necessário para Excel reconhecer caracteres especiais)
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Cabeçalho do relatório
fputcsv($output, ['RELATÓRIO DASHBOARD ANALÍTICO - SMO'], ';');
fputcsv($output, ['Ano: ' . $ano], ';');
fputcsv($output, ['Período: ' . $nomePeriodo], ';');
fputcsv($output, ['Gerado em: ' . date('d/m/Y H:i:s')], ';');
fputcsv($output, [''], ';');

// Especialidades
if (isset($dados['especialidades'])) {
    fputcsv($output, ['DISTRIBUIÇÃO POR ESPECIALIDADE'], ';');
    fputcsv($output, ['Especialidade', 'Quantidade'], ';');

    for ($i = 0; $i < count($dados['especialidades']['labels']); $i++) {
        fputcsv($output, [
            $dados['especialidades']['labels'][$i],
            $dados['especialidades']['data'][$i]
        ], ';');
    }

    fputcsv($output, ['Total', array_sum($dados['especialidades']['data'])], ';');
    fputcsv($output, [''], ';');
}

// Situação Militar
if (isset($dados['situacao_militar'])) {
    fputcsv($output, ['SITUAÇÃO MILITAR'], ';');
    fputcsv($output, ['Situação', 'Quantidade'], ';');

    for ($i = 0; $i < count($dados['situacao_militar']['labels']); $i++) {
        fputcsv($output, [
            $dados['situacao_militar']['labels'][$i],
            $dados['situacao_militar']['data'][$i]
        ], ';');
    }

    fputcsv($output, ['Total', array_sum($dados['situacao_militar']['data'])], ';');
    fputcsv($output, [''], ';');
}

// Resultado Revisão Médica
if (isset($dados['resultado_revisao'])) {
    fputcsv($output, ['RESULTADO REVISÃO MÉDICA'], ';');
    fputcsv($output, ['Resultado', 'Quantidade'], ';');

    for ($i = 0; $i < count($dados['resultado_revisao']['labels']); $i++) {
        fputcsv($output, [
            $dados['resultado_revisao']['labels'][$i],
            $dados['resultado_revisao']['data'][$i]
        ], ';');
    }

    fputcsv($output, ['Total', array_sum($dados['resultado_revisao']['data'])], ';');
    fputcsv($output, [''], ';');
}

// Evolução Mensal
if (isset($dados['evolucao_mensal'])) {
    fputcsv($output, ['INCORPORAÇÕES POR MÊS'], ';');
    fputcsv($output, ['Mês', 'Quantidade'], ';');

    for ($i = 0; $i < count($dados['evolucao_mensal']['labels']); $i++) {
        fputcsv($output, [
            $dados['evolucao_mensal']['labels'][$i],
            $dados['evolucao_mensal']['data'][$i]
        ], ';');
    }

    fputcsv($output, ['Total', array_sum($dados['evolucao_mensal']['data'])], ';');
}

fclose($output);
exit();
?>
