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
$data_revisao_medica = filtra_campo_post('data_revisao_medica');
$resultado_revisao_medica_complementar = filtra_campo_post('resultado_revisao_medica_complementar');
$cid_revisao_medica = filtra_campo_post('cid_revisao_medica');
$observacao_revisao_medica = filtra_campo_post('observacao_revisao_medica');
$data_revisao_medica = trata_data($data_revisao_medica);

if($crip != hash('sha256', $_SESSION['chave']. "obrigatorio")) erro($BASE_URL, 2, 3463463464, $pagina_atual, "criptografia_invalida", "Não foi possível editar o usuário!");


$obrigatorio_editar = $obrigatorioDAO->findById($id_obrigatorio_edita);


try
{
   // $om_1_fase = new OM();
   // $om_1_fase->setId($id_om_1_fase);
    $obrigatorio_editar->setData_revisao_medica($data_revisao_medica);
    $obrigatorio_editar->setResultadoRevisaoMedicaComplementar($resultado_revisao_medica_complementar);
    $obrigatorio_editar->setCid_revisao_medica($cid_revisao_medica);
    $obrigatorio_editar->setObs_revisao_medica($observacao_revisao_medica);
}
catch(Exception $e) 
{
    erro($BASE_URL, 3, 235778, $pagina_atual, "catch", $e->getMessage());
}

$data = $obrigatorioDAO->update_revisao_medica($obrigatorio_editar);

if($data)
{
    $alteracao = "Atualizou a Revisão Médica do obrigatório " . $obrigatorio_editar;
    $alteracao_detalahada = print_r($data, true);
    $logDAO->insertLog(3011, "obrigatorio", $id_obrigatorio_edita, $alteracao, $alteracao_detalahada);
}
else 
{   
    erro($BASE_URL, 3, 67421675921464752, $pagina_atual, "revisao_medica_nao_atualizada", "Não foi possível atualizar a Distribuição!");
}

$_SESSION['mensagem'] = "Obrigatório ATUALIZADO com sucesso";

header ("Location: ../obrigatorio_om.php?crip=$crip&id_obrigatorio=$id_obrigatorio_edita");



?>