<?php

if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Usuario.php';
include_once '../dao/UsuarioDAO.php';
include_once '../dao/LogDAO.php';


if($_SESSION['perfil_smo'] != "admin") erro($BASE_URL, 2, 235346346, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");

$usuarioDAO = new UsuarioDAO($conexao);
$logDAO = new LogDAO($conexao);

$id_usuario = (int)filtra_campo_get('id_usuario');
$crip = filtra_campo_get('crip');

if($crip != hash('sha256', $id_usuario. "criptografia"))
{
    erro($BASE_URL, 2, 4585686, $pagina_atual, "criptografia_invalida", "Não foi possível apagar o usuário!");
    exit();
}

$usuario_apaga = $usuarioDAO->findById($id_usuario);

if(!$usuario_apaga)
{
    erro($BASE_URL, 3, 968567, $pagina_atual, "usuario_nao_encontrado", "Usuário não pode ser apagado!");
    exit();
}

if($usuario_apaga->getApagado())
{
    erro($BASE_URL, 3, 895686, $pagina_atual, "usuario_ja_apagado", "Usuário não pode ser apagado!");
    exit();
}

$retorno = $usuarioDAO->deleteId($usuario_apaga->getId());

if($retorno)
{
    $alteracao = "Apagou o usuário ".$usuario_apaga->getUsuario();
    $alteracao_detalahada = $usuario_apaga;
    $logDAO->insertLog(2001, "usuario", $usuario_apaga->getId(), $alteracao, $alteracao_detalahada);
    $_SESSION['mensagem'] = "Usuário APAGADO com Sucesso";
    header ("Location: ../usuarios.php");
}
else 
{
   erro($BASE_URL, 3, 56856956, $pagina_atual, "usuario_nao_apagado", "Usuário não pode ser apagado!");
   exit();
}

?>