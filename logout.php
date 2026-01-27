<?php

session_start();

include_once 'dao/LogDAO.php';
include_once 'dao/conecta_banco.php';

$logDAO = new LogDAO($conexao);

$logDAO->insertLog(9002, "log", $_SESSION['id_usuario_smo'], "Logout", "Usuário " . $_SESSION['usuario_smo'] . " fez Logout");

session_destroy();

header ("Location: index.php");

?>
