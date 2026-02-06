<?php
/**
 * Endpoint para exportar Relatório Consolidado para Excel (CSV)
 * Versão Excel do mpdf/relatorio_consolidado.php
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
$logDAO = new LogDAO($conexao);

// Parâmetros do formulário
$ano = isset($_POST['ano']) ? (int)$_POST['ano'] : (int)date('Y');
$periodo = isset($_POST['periodo']) ? $_POST['periodo'] : 'ano';
$secoes = isset($_POST['secoes']) ? $_POST['secoes'] : ['resumo', 'especialidades', 'situacao', 'oms', 'revisao', 'mensal'];

// Converte período para meses e nome
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

// Busca estatísticas
$estatisticasEspecialidade = $ObrigatorioDAO->getEstatisticasPorEspecialidade($ano, $mesesFiltro);
$estatisticasSituacao = $ObrigatorioDAO->getEstatisticasPorSituacaoMilitar($ano, $mesesFiltro);
$estatisticasRevisao = $ObrigatorioDAO->getEstatisticasPorResultadoRevisao($ano, $mesesFiltro);
$estatisticasMensal = $ObrigatorioDAO->getEstatisticasMensais($ano, $mesesFiltro);
$estatisticasOM = $ObrigatorioDAO->getEstatisticasPorOM($ano, $mesesFiltro);

// Totais
$totalObrigatorios = array_sum($estatisticasSituacao['data']);
$totalDesignados = $ObrigatorioDAO->countDesignados($ano, $mesesFiltro);
$pendentesRevisao = $ObrigatorioDAO->countPendentesRevisaoMedica($ano, $mesesFiltro);
$totalAptos = $ObrigatorioDAO->countAptos($ano, $mesesFiltro);
$totalIncorporados = $ObrigatorioDAO->countIncorporadosPorAnoSelecao($ano, $mesesFiltro);
$aguardandoIncorporacao = $ObrigatorioDAO->countAguardandoIncorporacao($ano, $mesesFiltro);
$totalInaptos = $ObrigatorioDAO->countInaptos($ano, $mesesFiltro);
$aguardandoDistribuicao = $totalObrigatorios - $totalDesignados;

// Nome do arquivo
$nomeArquivo = "relatorio_consolidado_{$ano}_{$periodo}_" . date('Y-m-d_H-i-s') . ".csv";

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
fputcsv($output, ['RELATÓRIO CONSOLIDADO - SERVIÇO MILITAR OBRIGATÓRIO'], ';');
fputcsv($output, ['Ano: ' . $ano], ';');
fputcsv($output, ['Período: ' . $nomePeriodo], ';');
fputcsv($output, ['Gerado em: ' . date('d/m/Y H:i:s')], ';');
fputcsv($output, ['Gerado por: ' . $_SESSION['nome_guerra_smo']], ';');
fputcsv($output, [''], ';');

// Seção: Resumo Executivo
if (in_array('resumo', $secoes)) {
    fputcsv($output, ['1. RESUMO EXECUTIVO'], ';');
    fputcsv($output, ['Indicador', 'Quantidade'], ';');
    fputcsv($output, ['Total de Obrigatórios', $totalObrigatorios], ';');
    fputcsv($output, ['Designados p/ OM', $totalDesignados], ';');
    fputcsv($output, ['Aguardando Distribuição', $aguardandoDistribuicao], ';');
    fputcsv($output, ['Pendentes Revisão', $pendentesRevisao], ';');
    fputcsv($output, ['Aptos', $totalAptos], ';');
    fputcsv($output, ['Incorporados', $totalIncorporados], ';');
    fputcsv($output, ['Aguardando Incorporação', $aguardandoIncorporacao], ';');
    fputcsv($output, ['Inaptos', $totalInaptos], ';');
    fputcsv($output, [''], ';');
}

// Seção: Por Especialidade
if (in_array('especialidades', $secoes) && !empty($estatisticasEspecialidade['labels'])) {
    $totalEsp = array_sum($estatisticasEspecialidade['data']);

    fputcsv($output, ['2. DISTRIBUIÇÃO POR ESPECIALIDADE'], ';');
    fputcsv($output, ['Especialidade', 'Quantidade', 'Percentual'], ';');

    for ($i = 0; $i < count($estatisticasEspecialidade['labels']); $i++) {
        $label = $estatisticasEspecialidade['labels'][$i];
        $valor = $estatisticasEspecialidade['data'][$i];
        $percentual = $totalEsp > 0 ? round(($valor / $totalEsp) * 100, 1) . '%' : '0%';
        fputcsv($output, [$label, $valor, $percentual], ';');
    }
    fputcsv($output, ['TOTAL', $totalEsp, '100%'], ';');
    fputcsv($output, [''], ';');
}

// Seção: Situação Militar
if (in_array('situacao', $secoes) && !empty($estatisticasSituacao['labels'])) {
    $totalSit = array_sum($estatisticasSituacao['data']);

    fputcsv($output, ['3. SITUAÇÃO MILITAR'], ';');
    fputcsv($output, ['Situação', 'Quantidade', 'Percentual'], ';');

    for ($i = 0; $i < count($estatisticasSituacao['labels']); $i++) {
        $label = $estatisticasSituacao['labels'][$i];
        $valor = $estatisticasSituacao['data'][$i];
        $percentual = $totalSit > 0 ? round(($valor / $totalSit) * 100, 1) . '%' : '0%';
        fputcsv($output, [$label, $valor, $percentual], ';');
    }
    fputcsv($output, ['TOTAL', $totalSit, '100%'], ';');
    fputcsv($output, [''], ';');
}

// Seção: Por OM
if (in_array('oms', $secoes) && !empty($estatisticasOM['labels'])) {
    $totalOM = array_sum($estatisticasOM['data']);

    fputcsv($output, ['4. DISTRIBUIÇÃO POR OM'], ';');
    fputcsv($output, ['Organização Militar', 'Quantidade', 'Percentual'], ';');

    for ($i = 0; $i < count($estatisticasOM['labels']); $i++) {
        $label = $estatisticasOM['labels'][$i];
        $valor = $estatisticasOM['data'][$i];
        $percentual = $totalOM > 0 ? round(($valor / $totalOM) * 100, 1) . '%' : '0%';
        fputcsv($output, [$label, $valor, $percentual], ';');
    }
    fputcsv($output, ['TOTAL', $totalOM, '100%'], ';');
    fputcsv($output, [''], ';');
}

// Seção: Revisão Médica
if (in_array('revisao', $secoes) && !empty($estatisticasRevisao['labels'])) {
    $totalRev = array_sum($estatisticasRevisao['data']);

    fputcsv($output, ['5. RESULTADO DA REVISÃO MÉDICA'], ';');
    fputcsv($output, ['Resultado', 'Quantidade', 'Percentual'], ';');

    for ($i = 0; $i < count($estatisticasRevisao['labels']); $i++) {
        $label = $estatisticasRevisao['labels'][$i];
        $valor = $estatisticasRevisao['data'][$i];
        $percentual = $totalRev > 0 ? round(($valor / $totalRev) * 100, 1) . '%' : '0%';
        fputcsv($output, [$label, $valor, $percentual], ';');
    }
    fputcsv($output, ['TOTAL', $totalRev, '100%'], ';');
    fputcsv($output, [''], ';');
}

// Seção: Evolução Mensal
if (in_array('mensal', $secoes) && !empty($estatisticasMensal['labels'])) {
    $totalMensal = array_sum($estatisticasMensal['data']);

    fputcsv($output, ['6. INCORPORAÇÕES POR MÊS'], ';');
    fputcsv($output, ['Mês', 'Quantidade', 'Percentual'], ';');

    for ($i = 0; $i < count($estatisticasMensal['labels']); $i++) {
        $label = $estatisticasMensal['labels'][$i];
        $valor = $estatisticasMensal['data'][$i];
        $percentual = $totalMensal > 0 ? round(($valor / $totalMensal) * 100, 1) . '%' : '0%';
        fputcsv($output, [$label, $valor, $percentual], ';');
    }
    fputcsv($output, ['TOTAL', $totalMensal, '100%'], ';');
    fputcsv($output, [''], ';');
}

// Listagem completa (se selecionado)
if (in_array('listagem', $secoes)) {
    $obrigatorios = $ObrigatorioDAO->findAllAtivosPorAno($ano, $mesesFiltro);

    if (!empty($obrigatorios)) {
        fputcsv($output, ['7. LISTAGEM COMPLETA DE OBRIGATÓRIOS'], ';');
        fputcsv($output, ['Total de registros: ' . count($obrigatorios)], ';');
        fputcsv($output, [''], ';');
        fputcsv($output, ['Nº', 'Nome', 'CPF', 'Especialidade', 'OM', 'Situação', 'Revisão'], ';');

        $contador = 1;
        foreach ($obrigatorios as $obr) {
            fputcsv($output, [
                $contador,
                $obr->getNomeCompleto(),
                $obr->getCPF(),
                $obr->getEspecialidade() ?? 'N/I',
                $obr->getOm1Fase() ? $obr->getOm1Fase()->getAbreviatura() : 'N/D',
                $obr->getSituacaoMilitar() ?? 'N/I',
                $obr->getResultadoRevisaoMedicaComplementar() ?? 'PENDENTE'
            ], ';');
            $contador++;
        }
    }
}

fclose($output);

// Log
$logDAO->insertLog(4010, "Excel", null, "Exportou Relatório Consolidado Excel - Ano: $ano, Período: $nomePeriodo", "Relatório consolidado exportado para Excel.", null);

exit();
?>
