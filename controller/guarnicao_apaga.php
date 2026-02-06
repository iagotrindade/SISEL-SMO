<?php
if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Guarnicao.php';
include_once '../dao/GuarnicaoDAO.php';
include_once '../dao/LogDAO.php';

// Validação de perfil
if($_SESSION['perfil_smo'] != "admin")
{
    erro($BASE_URL, 2, 235346349, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");
    exit();
}

$guarnicaoDAO = new GuarnicaoDAO($conexao);
$logDAO = new LogDAO($conexao);

// Obtém ID do GET
$id_guarnicao = (int)filtra_campo_get('id_guarnicao');
$crip = filtra_campo_get('crip');

// Valida criptografia
if($crip != hash('sha256', $id_guarnicao. "criptografia"))
{
    erro($BASE_URL, 2, 4585689, $pagina_atual, "criptografia_invalida", "Não foi possível apagar a guarnição!");
    exit();
}

// Busca guarnição
$guarnicao_apaga = $guarnicaoDAO->findById($id_guarnicao);

if(!$guarnicao_apaga)
{
    erro($BASE_URL, 3, 968573, $pagina_atual, "guarnicao_nao_encontrada", "Guarnição não pode ser apagada!");
    exit();
}

// Apaga (soft delete)
$retorno = $guarnicaoDAO->deleteId($guarnicao_apaga->getId());

if($retorno)
{
    // Registra no log
    $alteracao = "Apagou a guarnição " . $guarnicao_apaga->getNome();
    $alteracao_detalhada = print_r($guarnicao_apaga, true);
    $logDAO->insertLog(2007, "guarnicao", $guarnicao_apaga->getId(), $alteracao, $alteracao_detalhada);

    $_SESSION['mensagem'] = "Guarnição APAGADA com sucesso";
    header("Location: ../guarnicoes.php");
}
else
{
    erro($BASE_URL, 3, 56856959, $pagina_atual, "guarnicao_nao_apagada", "Guarnição não pode ser apagada!");
    exit();
}
