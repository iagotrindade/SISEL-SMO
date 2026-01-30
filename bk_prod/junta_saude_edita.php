<?php

if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../dao/LogDAO.php';
include_once '../dao/JuntaDAO.php';

$logDAO = new logDAO($conexao);
$juntaDAO = new JuntaDAO($conexao);

$id_junta = filtra_campo_post('id_junta');
$secao = filtra_campo_post('secao');
$data = filtra_campo_post('data');
$data = trata_data($data);
$cidade = filtra_campo_post('cidade');
$presidente = filtra_campo_post('presidente');
$membro_1 = filtra_campo_post('membro_1');
$membro_2 = filtra_campo_post('membro_2');
$crip = filtra_campo_post('crip');


$junta_editar = $juntaDAO->update($id_junta, $secao, $data, $cidade, $presidente, $membro_1, $membro_2);

if($crip != hash('sha256', $_SESSION['chave']. "junta"))
    erro($BASE_URL, 2, 97485676, $pagina_atual, "criptografia_invalida", "Não foi possível cadastrar a Junta!");


$junta_editada = $juntaDAO->findById($id_junta);
$junta_editada_nome = $junta_editada['presidente']; 

if($junta_editar)
{
    $alteracao = "Atualizou a Junta de Saude $junta_editada_nome com Data de $data";
    $alteracao_detalahada = print_r($junta_editada, true);
    $logDAO->insertLog(3012, "usuario", $junta_editada['id_adicionado'], $alteracao, $alteracao_detalahada);
}
else 
{
    erro($BASE_URL, 3, 9639996, $pagina_atual, "junta_nao_editada", "Não foi possível Editar a Junta!");
}

$_SESSION['mensagem'] = "Junta de Saúde Editada com sucesso";
header ("Location: ../junta_saude_cadastra.php");


?>