<?php
if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Om.php';
include_once '../dao/OmDAO.php';
include_once '../dao/LogDAO.php';

// Validação de perfil
if($_SESSION['perfil_smo'] != "admin")
{
    erro($BASE_URL, 2, 235346350, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");
    exit();
}

$omDAO = new OmDAO($conexao);
$logDAO = new LogDAO($conexao);

// Obtém ID do GET
$id_om = (int)filtra_campo_get('id_om');
$crip = filtra_campo_get('crip');

// Valida criptografia
if($crip != hash('sha256', $id_om. "criptografia"))
{
    erro($BASE_URL, 2, 4585690, $pagina_atual, "criptografia_invalida", "Não foi possível apagar a OM!");
    exit();
}

// Busca OM
$om_apaga = $omDAO->findById($id_om);

if(!$om_apaga)
{
    erro($BASE_URL, 3, 968575, $pagina_atual, "om_nao_encontrada", "OM não pode ser apagada!");
    exit();
}

// Apaga (soft delete)
$retorno = $omDAO->deleteId($om_apaga->getId());

if($retorno)
{
    // Registra no log
    $alteracao = "Apagou a OM " . $om_apaga->getNome();
    $alteracao_detalhada = print_r($om_apaga, true);
    $logDAO->insertLog(2008, "om", $om_apaga->getId(), $alteracao, $alteracao_detalhada);

    $_SESSION['mensagem'] = "OM APAGADA com sucesso";
    header("Location: ../oms.php");
}
else
{
    erro($BASE_URL, 3, 56856960, $pagina_atual, "om_nao_apagada", "OM não pode ser apagada!");
    exit();
}
