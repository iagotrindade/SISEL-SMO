<?php

if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Obrigatorio.php';
include_once '../models/Om.php';
include_once '../dao/ObrigatorioDAO.php';
include_once '../dao/LogDAO.php';

if($_SESSION['perfil_smo'] != "admin")
{
    erro($BASE_URL, 2, 34634322, $pagina_atual, "obrigatorio!admin", "Não foi possível acessar a página!");
    exit();
}

$obrigatorioDAO = new obrigatorioDAO($conexao);
$logDAO = new LogDAO($conexao);

$id_obrigatorio = (int)filtra_campo_get('id_obrigatorio');
$crip = filtra_campo_get('crip');

if($crip != hash('sha256', $id_obrigatorio. "criptografia"))
{
    erro($BASE_URL, 2, 36427575, $pagina_atual, "criptografia_invalida", "Não foi possível apagar o obrigatório!");
    exit();
}

$obrigatorio_apaga = $obrigatorioDAO->findById($id_obrigatorio);

if(!$obrigatorio_apaga)
{
    erro($BASE_URL, 3, 568468, $pagina_atual, "obrigatorio_nao_encontrado", "obrigatório não pode ser apagado!");
    exit();
}

if($obrigatorio_apaga->getApagado())
{
    erro($BASE_URL, 3, 346756756, $pagina_atual, "obrigatorio_ja_apagado", "obrigatório não pode ser apagado!");
    exit();
}

$retorno = $obrigatorioDAO->deleteId($obrigatorio_apaga->getId());

if($retorno)
{
    $alteracao = "Apagou o obrigatório ".$obrigatorio_apaga;
    $alteracao_detalahada = $obrigatorio_apaga;
    $logDAO->insertLog(2003, "obrigatorio", $obrigatorio_apaga->getId(), $alteracao, $alteracao_detalahada);
    $_SESSION['mensagem'] = "Obrigatório APAGADO com Sucesso";
    header ("Location: ../obrigatorios.php");
}
else 
{
   erro($BASE_URL, 3, 2353656, $pagina_atual, "obrigatorio_nao_apagado", "obrigatório não pode ser apagado!");
   exit();
}

?>