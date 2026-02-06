<?php
/**
 * Endpoint AJAX para dados do Dashboard do Operador
 * Retorna estatísticas específicas da OM em formato JSON
 */

include_once '../funcoes.php';

session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario_smo'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Não autorizado']);
    exit();
}

// Verifica se é operador
if ($_SESSION['perfil_smo'] != 'operador') {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Acesso negado']);
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

// ID da OM do operador
$id_om = $_SESSION['id_om_smo'];

// Busca todas as estatísticas da OM
$response = [
    'ano' => $ano,
    'id_om' => $id_om,
    'total_designados' => $obrigatorioDAO->countDesignadosOM($id_om, $ano),
    'total_aptos' => $obrigatorioDAO->countAptosOM($id_om, $ano),
    'total_inaptos' => $obrigatorioDAO->countInaptosOM($id_om, $ano),
    'pendentes_revisao' => $obrigatorioDAO->countPendentesRevisaoOM($id_om, $ano),
    'resultado_revisao' => $obrigatorioDAO->getEstatisticasResultadoRevisaoOM($id_om, $ano),
    'especialidades' => $obrigatorioDAO->getEstatisticasEspecialidadeOM($id_om, $ano),
    'distribuicao' => $obrigatorioDAO->getEstatisticasDistribuicaoOM($id_om, $ano)
];

// Retorna JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
