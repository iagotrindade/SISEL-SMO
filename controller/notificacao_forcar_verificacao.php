<?php
/**
 * Controller para forçar verificação de notificações
 * Apenas para administradores
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario_smo'])) {
    echo json_encode(['success' => false, 'error' => 'Não autorizado']);
    exit();
}

// Apenas admins podem forçar verificação
if ($_SESSION['perfil_smo'] != 'admin') {
    echo json_encode(['success' => false, 'error' => 'Apenas administradores podem forçar verificação']);
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

// Força a verificação ignorando o limite de 23h
$resultado = $notificacaoDAO->gerarNotificacoesAutomaticas(true);

echo json_encode([
    'success' => true,
    'resultado' => $resultado,
    'mensagem' => "Verificação concluída. {$resultado['adiamentos_vencendo']} adiamento(s) e {$resultado['incorporacoes_sem_revisao']} incorporação(ões) identificadas."
]);
?>
