<?php
/**
 * Endpoint para exportar Relatório por OM para Excel (CSV)
 * Versão Excel do mpdf/relatorio_por_om.php
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
$omsIds = isset($_POST['oms']) ? $_POST['oms'] : [];
$ano = isset($_POST['ano']) ? (int)$_POST['ano'] : (int)date('Y');
$incluir = isset($_POST['incluir']) ? $_POST['incluir'] : ['estatisticas', 'especialidades', 'listagem'];

// Se "todas" estiver selecionado, busca todas as OMs
$todasOMs = false;
if (in_array('todas', $omsIds) || empty($omsIds)) {
    $todasOMs = true;
    $oms = $omDAO->findAllAtivos();
} else {
    $oms = [];
    foreach ($omsIds as $omId) {
        $om = $omDAO->findById($omId);
        if ($om) $oms[] = $om;
    }
}

// Nome do arquivo
$nomeArquivo = "relatorio_por_om_{$ano}_" . date('Y-m-d_H-i-s') . ".csv";

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
fputcsv($output, ['RELATÓRIO POR ORGANIZAÇÃO MILITAR'], ';');
fputcsv($output, ['Ano: ' . $ano], ';');
fputcsv($output, [$todasOMs ? 'Todas as OMs' : count($oms) . ' OM(s) selecionada(s)'], ';');
fputcsv($output, ['Gerado em: ' . date('d/m/Y H:i:s')], ';');
fputcsv($output, ['Gerado por: ' . $_SESSION['nome_guerra_smo']], ';');
fputcsv($output, [''], ';');

// Comparativo entre OMs (se selecionado e múltiplas OMs)
if (in_array('comparativo', $incluir) && count($oms) > 1) {
    fputcsv($output, ['COMPARATIVO ENTRE OMs'], ';');
    fputcsv($output, ['Organização Militar', 'Designados', 'Aptos', 'Inaptos', 'Pendentes', '% Aptos'], ';');

    $totalGeral = ['designados' => 0, 'aptos' => 0, 'inaptos' => 0, 'pendentes' => 0];

    foreach ($oms as $om) {
        $designados = $ObrigatorioDAO->countDesignadosOM($om->getId(), $ano);
        $aptos = $ObrigatorioDAO->countAptosOM($om->getId(), $ano);
        $inaptos = $ObrigatorioDAO->countInaptosOM($om->getId(), $ano);
        $pendentes = $ObrigatorioDAO->countPendentesRevisaoOM($om->getId(), $ano);
        $percAptos = $designados > 0 ? round(($aptos / $designados) * 100, 1) . '%' : '0%';

        $totalGeral['designados'] += $designados;
        $totalGeral['aptos'] += $aptos;
        $totalGeral['inaptos'] += $inaptos;
        $totalGeral['pendentes'] += $pendentes;

        fputcsv($output, [
            $om->getAbreviatura() . ' - ' . $om->getNome(),
            $designados,
            $aptos,
            $inaptos,
            $pendentes,
            $percAptos
        ], ';');
    }

    $percTotalAptos = $totalGeral['designados'] > 0 ? round(($totalGeral['aptos'] / $totalGeral['designados']) * 100, 1) . '%' : '0%';
    fputcsv($output, [
        'TOTAL GERAL',
        $totalGeral['designados'],
        $totalGeral['aptos'],
        $totalGeral['inaptos'],
        $totalGeral['pendentes'],
        $percTotalAptos
    ], ';');
    fputcsv($output, [''], ';');
}

// Detalhes por OM
foreach ($oms as $om) {
    $omNome = $om->getAbreviatura() . ' - ' . $om->getNome();

    fputcsv($output, [''], ';');
    fputcsv($output, ['========================================'], ';');
    fputcsv($output, [$omNome], ';');
    fputcsv($output, ['========================================'], ';');

    // Informações da OM
    fputcsv($output, ['Cidade: ' . ($om->getCidade() ?? 'N/I')], ';');
    fputcsv($output, ['Telefone: ' . ($om->getTelefone() ?? 'N/I')], ';');
    fputcsv($output, ['Endereço: ' . ($om->getEndereco() ?? 'N/I')], ';');
    fputcsv($output, [''], ';');

    // Estatísticas da OM
    if (in_array('estatisticas', $incluir)) {
        $designados = $ObrigatorioDAO->countDesignadosOM($om->getId(), $ano);
        $aptos = $ObrigatorioDAO->countAptosOM($om->getId(), $ano);
        $inaptos = $ObrigatorioDAO->countInaptosOM($om->getId(), $ano);
        $pendentes = $ObrigatorioDAO->countPendentesRevisaoOM($om->getId(), $ano);

        fputcsv($output, ['ESTATÍSTICAS GERAIS'], ';');
        fputcsv($output, ['Indicador', 'Quantidade'], ';');
        fputcsv($output, ['Designados', $designados], ';');
        fputcsv($output, ['Aptos', $aptos], ';');
        fputcsv($output, ['Inaptos', $inaptos], ';');
        fputcsv($output, ['Pendentes Revisão', $pendentes], ';');
        fputcsv($output, [''], ';');
    }

    // Especialidades da OM
    if (in_array('especialidades', $incluir)) {
        $estatEsp = $ObrigatorioDAO->getEstatisticasEspecialidadeOM($om->getId(), $ano);

        if (!empty($estatEsp['labels'])) {
            $totalEsp = array_sum($estatEsp['data']);

            fputcsv($output, ['DISTRIBUIÇÃO POR ESPECIALIDADE'], ';');
            fputcsv($output, ['Especialidade', 'Quantidade', 'Percentual'], ';');

            for ($i = 0; $i < count($estatEsp['labels']); $i++) {
                $label = $estatEsp['labels'][$i];
                $valor = $estatEsp['data'][$i];
                $percentual = $totalEsp > 0 ? round(($valor / $totalEsp) * 100, 1) . '%' : '0%';
                fputcsv($output, [$label, $valor, $percentual], ';');
            }
            fputcsv($output, ['TOTAL', $totalEsp, '100%'], ';');
            fputcsv($output, [''], ';');
        }
    }

    // Listagem de designados
    if (in_array('listagem', $incluir)) {
        $obrigatorios = $ObrigatorioDAO->findByOMsAno($om->getId(), $ano);

        if (!empty($obrigatorios)) {
            fputcsv($output, ['LISTAGEM DE DESIGNADOS (' . count($obrigatorios) . ' registro(s))'], ';');
            fputcsv($output, ['Nº', 'Nome', 'CPF', 'Especialidade', 'Distribuição', 'Revisão'], ';');

            $contador = 1;
            foreach ($obrigatorios as $obr) {
                fputcsv($output, [
                    $contador,
                    $obr->getNomeCompleto(),
                    $obr->getCPF(),
                    $obr->getEspecialidade() ?? 'N/I',
                    $obr->getDistribuicao() ?? 'N/D',
                    $obr->getResultadoRevisaoMedicaComplementar() ?? 'PENDENTE'
                ], ';');
                $contador++;
            }
            fputcsv($output, [''], ';');
        } else {
            fputcsv($output, ['Nenhum designado encontrado para esta OM no período selecionado.'], ';');
            fputcsv($output, [''], ';');
        }
    }
}

fclose($output);

// Log
$logDAO->insertLog(4011, "Excel", null, "Exportou Relatório por OM Excel - Ano: $ano, OMs: " . count($oms), "Relatório por OM exportado para Excel.", null);

exit();
?>
