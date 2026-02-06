<?php
/**
 * Controller para arquivar notificação
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario_smo'])) {
    echo json_encode(['success' => false, 'error' => 'Não autorizado']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    echo json_encode(['success' => false, 'error' => 'Requisição inválida']);
    exit();
}

include_once '../dao/conecta_banco.php';
include_once '../models/Notificacao.php';
include_once '../dao/NotificacaoDAO.php';

$notificacaoDAO = new NotificacaoDAO($conexao);
$id = (int)$_POST['id'];

$result = $notificacaoDAO->arquivar($id);

echo json_encode(['success' => $result]);
?>
