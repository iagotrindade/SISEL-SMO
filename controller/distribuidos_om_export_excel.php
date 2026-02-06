<?php
/**
 * Endpoint para exportar dados de Distribuídos OM 1ª Fase para Excel (CSV)
 * Utiliza a mesma lógica de filtros do distribuidos_om_1_fase.php
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../dao/conecta_banco.php';
include_once '../models/Obrigatorio.php';
include_once '../models/Om.php';
include_once '../dao/ObrigatorioDAO.php';
include_once '../dao/ConfiguracaoDAO.php';
include_once '../funcoes.php';

// Verificar permissão (operador)
if (!isset($_SESSION['perfil_smo']) || $_SESSION['perfil_smo'] != "operador" || !isset($_SESSION['id_usuario_smo'])) {
    header('HTTP/1.1 403 Forbidden');
    echo 'Não autorizado';
    exit();
}

// Verificar se a visualização está liberada
$configuracaoDAO = new ConfiguracaoDAO($conexao);
$visualizacao_liberada = $configuracaoDAO->isAtivo('visualizacao_distribuidos_om_1_fase');

if (!$visualizacao_liberada) {
    header('HTTP/1.1 403 Forbidden');
    echo 'Visualização não liberada';
    exit();
}

$ObrigatorioDAO = new ObrigatorioDAO($conexao);
$todos_obrigatorios_da_om = $ObrigatorioDAO->findAllAtivosdaOM();

// Filtros
$distribuicao_filtro = isset($_GET['distribuicao_filtro']) ? $_GET['distribuicao_filtro'] : null;
$resultado_revisao_medica_filtro = isset($_GET['resultado_revisao_medica_filtro']) ? $_GET['resultado_revisao_medica_filtro'] : null;

// Nome do arquivo
$nomeArquivo = "distribuidos_om_" . date('Y-m-d_H-i-s') . ".csv";

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
fputcsv($output, ['RELATÓRIO DE DISTRIBUÍDOS OM 1ª FASE - SMO'], ';');
fputcsv($output, ['Gerado em: ' . date('d/m/Y H:i:s')], ';');
fputcsv($output, [''], ';');

// Cabeçalhos das colunas
fputcsv($output, [
    'Nome',
    'CPF',
    'Formação / Ano',
    'Grad Facul',
    'Ano Conc Resid',
    'Especialidade',
    'Distribuição',
    'Revisão Médica',
    'Incorporação',
    'Situação Militar'
], ';');

// Situações militares a ignorar
$situacoes_ignorar = [
    null,
    "Em Dia - JUDICIAL",
    "Em Débito - REFRATÁRIO",
    "Em Débito - INSUBMISSO",
    "Em Dia - B1 - INSP SAU - RETORNAR PRÓXIMA SELEÇÃO",
    "Quite SMO - EXCESSO - CONTINGENTE",
    "Quite SMO - EXCESSO - INCAPAZ SAÚDE",
    "Quite SMO - DESOBRIGADO - MAIOR 38 ANOS",
    "Quite SMO - DESOBRIGADO - JÁ RESERVISTA",
    "Quite SMO - DESOBRIGADO - NATURALIZADO",
    "Quite SMO - CONVOCADO"
];

// Distribuições a ignorar
$distribuicoes_ignorar = [null, "EXCESSO CONTINGENTE", "MARINHA", "FORÇA AÉREA"];

// JISE/JISR inaptos
$jise_jisr_inaptos = ["B1", "B2", "C"];

$totalRegistros = 0;

// Dados
if ($todos_obrigatorios_da_om) {
    foreach ($todos_obrigatorios_da_om as $obrigatorio) {
        // Aplicar filtros
        if ($distribuicao_filtro && ($obrigatorio->getDistribuicao() != $distribuicao_filtro || $obrigatorio->getDistribuicao() == null)) continue;
        if ($resultado_revisao_medica_filtro && ($obrigatorio->getResultadoRevisaoMedicaComplementar() != $resultado_revisao_medica_filtro || $obrigatorio->getResultadoRevisaoMedicaComplementar() == null)) continue;

        // Ignorar situações específicas
        if (in_array($obrigatorio->getSituacaoMilitar(), $situacoes_ignorar)) continue;

        // Ignorar distribuições específicas
        if (in_array($obrigatorio->getDistribuicao(), $distribuicoes_ignorar)) continue;

        // Verificar JISE
        if ($obrigatorio->getJise() == null) continue;

        // Verificar JISR e JISE inaptos
        if ($obrigatorio->getJisr() == null && in_array($obrigatorio->getJise(), $jise_jisr_inaptos)) continue;
        if (in_array($obrigatorio->getJisr(), $jise_jisr_inaptos)) continue;

        fputcsv($output, [
            $obrigatorio->getNomeCompleto(),
            $obrigatorio->getCPF(),
            $obrigatorio->getFormacao() . ' / ' . $obrigatorio->getAnoFormacao(),
            $obrigatorio->getNomeInstitutoEnsino(),
            $obrigatorio->imprime_ano_res_mais_recente(),
            $obrigatorio->imprime_ultima_especialidade(),
            $obrigatorio->getDistribuicao(),
            $obrigatorio->getResultadoRevisaoMedicaComplementar(),
            $obrigatorio->getIncorporacao(),
            $obrigatorio->getSituacaoMilitar()
        ], ';');

        $totalRegistros++;
    }
}

fclose($output);
exit();
?>
