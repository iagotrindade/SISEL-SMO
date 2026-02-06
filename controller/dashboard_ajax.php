<?php
/**
 * Endpoint AJAX para dados do Dashboard Analítico
 * Retorna estatísticas em formato JSON para os gráficos
 */

include_once '../funcoes.php';

session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario_smo'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Não autorizado']);
    exit();
}

include_once $BASE_URL . '/dao/conecta_banco.php';
include_once $BASE_URL . '/models/Obrigatorio.php';
include_once $BASE_URL . '/dao/ObrigatorioDAO.php';

$obrigatorioDAO = new ObrigatorioDAO($conexao);

// Pega o ano do parâmetro ou usa o ano atual
$ano = isset($_GET['ano']) ? (int)$_GET['ano'] : (int)date('Y');

// Valida o ano (entre 2020 e ano atual + 1)
$anoAtual = (int)date('Y');
if ($ano < 2020 || $ano > $anoAtual + 1) {
    $ano = $anoAtual;
}

// Pega o período do parâmetro ou usa o ano completo
$periodo = isset($_GET['periodo']) ? $_GET['periodo'] : 'ano';

// Converte período para meses
$mesesFiltro = [];
switch ($periodo) {
    case '1sem':
        $mesesFiltro = [1, 2, 3, 4, 5, 6];
        break;
    case '2sem':
        $mesesFiltro = [7, 8, 9, 10, 11, 12];
        break;
    case '1tri':
        $mesesFiltro = [1, 2, 3];
        break;
    case '2tri':
        $mesesFiltro = [4, 5, 6];
        break;
    case '3tri':
        $mesesFiltro = [7, 8, 9];
        break;
    case '4tri':
        $mesesFiltro = [10, 11, 12];
        break;
    default:
        $mesesFiltro = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
}

// Busca todas as estatísticas
$response = [
    'ano' => $ano,
    'periodo' => $periodo,
    'especialidades' => $obrigatorioDAO->getEstatisticasPorEspecialidade($ano, $mesesFiltro),
    'situacao_militar' => $obrigatorioDAO->getEstatisticasPorSituacaoMilitar($ano, $mesesFiltro),
    'resultado_revisao' => $obrigatorioDAO->getEstatisticasPorResultadoRevisao($ano, $mesesFiltro),
    'evolucao_mensal' => $obrigatorioDAO->getEstatisticasMensais($ano, $mesesFiltro),
    'por_om' => $obrigatorioDAO->getEstatisticasPorOM($ano, $mesesFiltro),
    'jise' => $obrigatorioDAO->getEstatisticasPorJISE($ano, $mesesFiltro),
    // Estatísticas gerais
    'incorporados' => $obrigatorioDAO->countIncorporados($ano, $mesesFiltro),
    // Indicadores de pendências (apenas para admin)
    'indicadores' => ($_SESSION['perfil_smo'] == 'admin') ? [
        'pendentes_distribuicao' => $obrigatorioDAO->countPendentesDistribuicao($ano, $mesesFiltro),
        'pendentes_revisao' => $obrigatorioDAO->countPendentesRevisaoMedica($ano, $mesesFiltro),
        'adiamentos_ativos' => $obrigatorioDAO->countAdiamentosAtivos($ano, $mesesFiltro),
        'total_inaptos' => $obrigatorioDAO->countInaptos($ano, $mesesFiltro)
    ] : null
];

// Retorna JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
