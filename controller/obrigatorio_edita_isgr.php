<?php

if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Obrigatorio.php';
include_once '../models/Om.php';
include_once '../dao/ObrigatorioDAO.php';
include_once '../dao/LogDAO.php';


if($_SESSION['perfil_smo'] != "admin" && $_SESSION['perfil_smo'] != "operador")
{    
    erro($BASE_URL, 2, 7412593, $pagina_atual, "usuario!admin!operador", "Não foi possível acessar a página!");
    exit();
}

$logDAO = new logDAO($conexao);
$obrigatorioDAO = new ObrigatorioDAO($conexao);

$id_obrigatorio_edita = (int)filtra_campo_post('id_obrigatorio');
$crip_url = filtra_campo_post('crip_url');
$crip = filtra_campo_post('crip');
$data_isgr = filtra_campo_post('data_isgr');
$cid_isgr = filtra_campo_post('cid_isgr');
$observacao_isgr = filtra_campo_post('observacao_isgr');
$resultado_isgr = filtra_campo_post('resultado_isgr');

$data_isgr = trata_data($data_isgr);

if($crip != hash('sha256', $_SESSION['chave']. "obrigatorio")) erro($BASE_URL, 2, 3463463464, $pagina_atual, "criptografia_invalida", "Não foi possível editar o usuário!");

$obrigatorio_editar = $obrigatorioDAO->findById($id_obrigatorio_edita);


try
{
    $obrigatorio_editar->setData_isgr($data_isgr);
    $obrigatorio_editar->setCid_isgr($cid_isgr);
    $obrigatorio_editar->setObservacao_isgr($observacao_isgr);
    $obrigatorio_editar->setResultadoIsgr($resultado_isgr);
}
catch(Exception $e) 
{
    erro($BASE_URL, 3, 235778, $pagina_atual, "catch", $e->getMessage());
}

$data = $obrigatorioDAO->update_isgr($obrigatorio_editar);

if($data)
{
    $alteracao = "Atualizou o ISGR do obrigatório " . $obrigatorio_editar;
    $alteracao_detalahada = print_r($data, true);
    $logDAO->insertLog(3011, "obrigatorio", $id_obrigatorio_edita, $alteracao, $alteracao_detalahada);
}
else 
{   
    erro($BASE_URL, 3, 316973456, $pagina_atual, "isgr_nao_atualizada", "Não foi possível atualizar a ISGR!");
}

$_SESSION['mensagem'] = "Obrigatório ATUALIZADO com sucesso";

header ("Location: ../obrigatorio_om.php?crip=$crip&id_obrigatorio=$id_obrigatorio_edita");



?>