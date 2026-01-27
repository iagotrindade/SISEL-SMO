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
$id_om_1_fase = filtra_campo_post('id_om_1_fase');
$data_incorporacao = filtra_campo_post('data_incorporacao');
$om_2_fase = filtra_campo_post('om_2_fase');
$incorporacao = filtra_campo_post('incorporacao');
$bar_om_1_fase = filtra_campo_post('bar_om_1_fase');

// Tratamento de dadas
$data_incorporacao = trata_data($data_incorporacao);

if($crip != hash('sha256', $_SESSION['chave']. "obrigatorio"))
    erro($BASE_URL, 2, 9965653214, $pagina_atual, "criptografia_invalida", "Não foi possível editar o usuário!");

/* if(empty($nome_completo))
    erro($BASE_URL, 1, 253253446, $pagina_atual, "empty(nome_completo)", "O Campo NOME COMPLETO é obrigatório!");
*/


$obrigatorio_editar = $obrigatorioDAO->findById($id_obrigatorio_edita);

try
{

    $om_1_fase = new OM();
    $om_1_fase->setId($id_om_1_fase);
    $obrigatorio_editar->setDataIncorporacao($data_incorporacao);
    $obrigatorio_editar->setOm1Fase($om_1_fase);
    $obrigatorio_editar->setOm2Fase($om_2_fase);
    $obrigatorio_editar->setIncorporacao($incorporacao);
    $obrigatorio_editar->setBar_om_1_fase($bar_om_1_fase);
}
catch(Exception $e) 
{
    erro($BASE_URL, 3, 235778, $pagina_atual, "catch", $e->getMessage());
}

$data = $obrigatorioDAO->update_incorporacao_om($obrigatorio_editar);

if($data)
{
    $alteracao = "Atualizou a Distribuição do obrigatório " . $obrigatorio_editar;
    $alteracao_detalahada = print_r($data, true);
    $logDAO->insertLog(3011, "obrigatorio", $id_obrigatorio_edita, $alteracao, $alteracao_detalahada);
}
else 
{   
    erro($BASE_URL, 3, 982565, $pagina_atual, "distribuicao_nao_atualizada", "Não foi possível atualizar a Distribuição!");
}

$_SESSION['mensagem'] = "Obrigatório ATUALIZADO com sucesso";

header ("Location: ../obrigatorio_om.php?crip=$crip_url&id_obrigatorio=$id_obrigatorio_edita");



?>