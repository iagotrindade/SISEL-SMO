<?php

session_start();

include_once '../dao/LogDAO.php';
include_once '../dao/conecta_banco.php';

// Verifica se a sessão existe antes de registrar o log
if (isset($_SESSION['id_usuario_smo']) && isset($_SESSION['usuario_smo'])) {
    $logDAO = new LogDAO($conexao);
    $logDAO->insertLog(9002, "log", $_SESSION['id_usuario_smo'], "Logout", "Usuário " . $_SESSION['usuario_smo'] . " fez Logout");
}

session_destroy();

header("Location: ../login.php");
exit();

?>
