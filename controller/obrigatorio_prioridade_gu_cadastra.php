<?php

if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Obrigatorio.php';
include_once '../dao/ObrigatorioDAO.php';
include_once '../dao/LogDAO.php';


$logDAO = new LogDAO($conexao);
$obrigatorioDAO = new ObrigatorioDAO($conexao);

$id_guarnicao = filtra_campo_post('id_guarnicao'); 
$id_obrigatorio = filtra_campo_post('id_obrigatorio'); 
$prioridade = $obrigatorioDAO->findPrioridade($id_obrigatorio);
$nomegu = filtra_campo_post('nomegu');

if ($prioridade == null)
    $prioridade = 1;
else 
    $prioridade = (int)$prioridade['prioridade'] + 1;


$data = $obrigatorioDAO->insertObrigatorioXGu($id_obrigatorio, $id_guarnicao, $prioridade);
$obrigatorio = $obrigatorioDAO->findById($id_obrigatorio);
$cpfobrigatorio = $obrigatorio->getCpf();


if($data)
{
    $alteracao = "Definiu Gu $nomegu como ". $data['prioridade']  ."ª Prioridade para o Obrigatório CPF: $cpfobrigatorio";
    $alteracao_detalahada = print_r($data, true);
    $logDAO->insertLog(1006, "obrigatorio_x_guarnicao", $id_obrigatorio, $alteracao, $alteracao_detalahada);
}
else 
{
    erro($BASE_URL, 3, 3267568, $pagina_atual, "prioridade_nao_cadastrada", "Não foi possível cadastrar a prioridade!");
    exit();
}

$crip_url = hash('sha256', $id_obrigatorio."criptografia");
$_SESSION['mensagem'] = "Prioridade Definida";

header ("Location: ../obrigatorio.php?crip=$crip_url&id_obrigatorio=$id_obrigatorio&aba=5");


?>