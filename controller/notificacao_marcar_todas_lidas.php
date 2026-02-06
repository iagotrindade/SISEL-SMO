<?php
/**
 * Controller para marcar todas as notificações como lidas
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario_smo'])) {
    echo json_encode(['success' => false, 'error' => 'Não autorizado']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Requisição inválida']);
    exit();
}

include_once '../dao/conecta_banco.php';
include_once '../models/Notificacao.php';
include_once '../dao/NotificacaoDAO.php';

$notificacaoDAO = new NotificacaoDAO($conexao);
$idUsuario = $_SESSION['id_usuario_smo'];
$perfil = $_SESSION['perfil_smo'];
$idOm = ($perfil == 'operador' && !empty($_SESSION['id_om_smo'])) ? (int)$_SESSION['id_om_smo'] : null;

$result = $notificacaoDAO->marcarTodasComoLidas($idUsuario, $perfil, $idOm);

echo json_encode(['success' => $result]);
?>
