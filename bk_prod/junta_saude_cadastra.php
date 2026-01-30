<?php

if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../dao/LogDAO.php';
include_once '../dao/JuntaDAO.php';

$logDAO = new logDAO($conexao);
$juntaDAO = new JuntaDAO($conexao);

$presidente = filtra_campo_post('presidente');
$membro_1 = filtra_campo_post('membro_1');
$membro_2 = filtra_campo_post('membro_2');
$secao = filtra_campo_post('secao');
$data = filtra_campo_post('data');
$cidade = filtra_campo_post('cidade');
$crip = filtra_campo_post('crip');

if($crip != hash('sha256', $_SESSION['chave']. "junta"))
    erro($BASE_URL, 2, 97485676, $pagina_atual, "criptografia_invalida", "Não foi possível cadastrar a Junta!");

if(empty($presidente) || empty($membro_1) || empty($membro_2) || empty($secao) || empty($data) || empty($cidade))
    erro($BASE_URL, 1, 2364578568856, $pagina_atual, "empty(nome_guerra)", "Todos os campos são obrigatórios!");

$data = trata_data($data);

if($juntaDAO->findByData($data))
    erro($BASE_URL, 2, 235256, $pagina_atual, "data_ja_existe", "A data $data já esta cadastrada no sistema!");

$retorno = $juntaDAO->insert($presidente, $membro_1, $membro_2, $secao, $data, $cidade);


if($retorno)
{
    $alteracao = "Cadastrou a Junta de Saude com Data de $data";
    $alteracao_detalahada = print_r($retorno, true);
    $logDAO->insertLog(1003, "exame_medico", $retorno['id_adicionado'], $alteracao, $alteracao_detalahada);
}
else 
{
    erro($BASE_URL, 3, 66322356, $pagina_atual, "junta_nao_cadastrada", "Não foi possível cadastrar a Junta!");
}

$_SESSION['mensagem'] = "Junta de Saúde cadastrada com sucesso";
header ("Location: ../junta_saude_cadastra.php");


?>