<?php

if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../dao/JuntaDAO.php';
include_once '../dao/LogDAO.php';

if($_SESSION['perfil_smo'] != "admin")
{
    erro($BASE_URL, 2, 235346346, $pagina_atual, "junta!admin", "Não foi possível acessar a página!");
    exit();
}

$juntaDAO = new JuntaDAO($conexao);
$logDAO = new LogDAO($conexao);

$id_junta = (int)filtra_campo_get('id_junta');
$crip = filtra_campo_get('crip');

if($crip != hash('sha256', $id_junta. "criptografia"))
{
    erro($BASE_URL, 2, 4585686, $pagina_atual, "criptografia_invalida", "Não foi possível apagar a Junta!");
    exit();
}

$junta_apaga = $juntaDAO->findAllAtivos();

if(!$junta_apaga)
{
    erro($BASE_URL, 3, 968567, $pagina_atual, "junta_nao_encontrado", "Junta não pode ser apagada!");
    exit();
}

$retorno = $juntaDAO->deleteId($id_junta);

if($retorno)
{
    $alteracao = "Apagou a Junta de ID ".$id_junta;
    $alteracao_detalahada = $junta_apaga;
    $logDAO->insertLog(2004, "junta", $id_junta, $alteracao, $alteracao_detalahada);
    $_SESSION['mensagem'] = "Junta APAGADA com Sucesso";
    header ("Location: ../junta_saude_cadastra.php");
}
else 
{
   erro($BASE_URL, 3, 56856956, $pagina_atual, "junta_nao_apagado", "Junta não pode ser apagada!");
   exit();
}

?>