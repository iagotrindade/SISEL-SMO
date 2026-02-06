<?php
/**
 * Controller para contar notificações não lidas (AJAX)
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario_smo'])) {
    echo json_encode(['total' => 0, 'alta' => 0, 'media' => 0, 'baixa' => 0]);
    exit();
}

include_once '../dao/conecta_banco.php';
include_once '../models/Notificacao.php';
include_once '../dao/NotificacaoDAO.php';

$notificacaoDAO = new NotificacaoDAO($conexao);
$perfil = $_SESSION['perfil_smo'];
$idOm = ($perfil == 'operador' && !empty($_SESSION['id_om_smo'])) ? (int)$_SESSION['id_om_smo'] : null;

$total = $notificacaoDAO->countNaoLidas($perfil, $idOm);
$porCriticidade = $notificacaoDAO->countPorCriticidade($perfil, $idOm);

echo json_encode([
    'total' => $total,
    'alta' => $porCriticidade['alta'],
    'media' => $porCriticidade['media'],
    'baixa' => $porCriticidade['baixa']
]);
?>
