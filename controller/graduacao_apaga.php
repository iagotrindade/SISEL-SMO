<?php
if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Graduacao.php';
include_once '../dao/GraduacaoDAO.php';
include_once '../dao/LogDAO.php';

// Validação de perfil
if($_SESSION['perfil_smo'] != "admin")
{
    erro($BASE_URL, 2, 235346348, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");
    exit();
}

$graduacaoDAO = new GraduacaoDAO($conexao);
$logDAO = new LogDAO($conexao);

// Obtém ID do GET
$id_graduacao = (int)filtra_campo_get('id_graduacao');
$crip = filtra_campo_get('crip');

// Valida criptografia
if($crip != hash('sha256', $id_graduacao. "criptografia"))
{
    erro($BASE_URL, 2, 4585688, $pagina_atual, "criptografia_invalida", "Não foi possível apagar a graduação!");
    exit();
}

// Busca graduação
$graduacao_apaga = $graduacaoDAO->findById($id_graduacao);

if(!$graduacao_apaga)
{
    erro($BASE_URL, 3, 968571, $pagina_atual, "graduacao_nao_encontrada", "Graduação não pode ser apagada!");
    exit();
}

// Apaga (soft delete)
$retorno = $graduacaoDAO->deleteId($graduacao_apaga->getId());

if($retorno)
{
    // Registra no log
    $alteracao = "Apagou a graduação " . $graduacao_apaga->getNome();
    $alteracao_detalhada = print_r($graduacao_apaga, true);
    $logDAO->insertLog(2006, "graduacao", $graduacao_apaga->getId(), $alteracao, $alteracao_detalhada);

    $_SESSION['mensagem'] = "Graduação APAGADA com sucesso";
    header("Location: ../graduacoes.php");
}
else
{
    erro($BASE_URL, 3, 56856958, $pagina_atual, "graduacao_nao_apagada", "Graduação não pode ser apagada!");
    exit();
}
