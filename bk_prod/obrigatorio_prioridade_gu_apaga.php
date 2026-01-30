<?php

if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Obrigatorio.php';
include_once '../dao/ObrigatorioDAO.php';
include_once '../dao/LogDAO.php';


$logDAO = new LogDAO($conexao);
$obrigatorioDAO = new ObrigatorioDAO($conexao);

$id_obrigatorio = filtra_campo_post('id_obrigatorio'); 

$obrigatorio = $obrigatorioDAO->findById($id_obrigatorio);
$data = $obrigatorioDAO->deleteListaPrioridades($id_obrigatorio);

if($data)
{
    $alteracao = "Apagou a lista Gu Prioridades do " . $obrigatorio->getNomeCompleto() . " de CPF: " . $obrigatorio->getCpf();
    $alteracao_detalahada = print_r($data, true);
    $logDAO->insertLog(2005, "obrigatorio_x_guarnicao", $id_obrigatorio, $alteracao, $alteracao_detalahada);
}
else 
    erro($BASE_URL, 3, 2526457578, $pagina_atual, "prioridades_nao_apagadas", "Não foi possível apagar as prioridades!");


//$id_adicionado = $data['id_adicionado'];
$crip_url = hash('sha256', $id_obrigatorio."criptografia");
$_SESSION['mensagem'] = "Prioridades Apagadas";


header ("Location: ../obrigatorio.php?crip=$crip_url&id_obrigatorio=$id_obrigatorio&aba=5");


?>