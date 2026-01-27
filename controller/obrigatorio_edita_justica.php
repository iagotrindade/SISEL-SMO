<?php

if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Obrigatorio.php';
include_once '../models/Om.php';
include_once '../dao/ObrigatorioDAO.php';
include_once '../dao/LogDAO.php';

if($_SESSION['perfil_smo'] != "admin")
{    
    erro($BASE_URL, 2, 2536466, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");
    exit();
}

$logDAO = new logDAO($conexao);
$obrigatorioDAO = new ObrigatorioDAO($conexao);

$id_obrigatorio_edita = (int)filtra_campo_post('id_obrigatorio');
$crip_url = filtra_campo_post('crip_url');
$crip = filtra_campo_post('crip');
$data_proxima_apresentacao = filtra_campo_post('data_proxima_apresentacao');
$numero_acao = filtra_campo_post('numero_acao');
$transitou_julgado = filtra_campo_post('transitou_julgado');
$data_liminar = filtra_campo_post('data_liminar');
$favoravel = filtra_campo_post('favoravel');
$convocado = filtra_campo_post('convocado');
$observacao = filtra_campo_post('observacao');
$situacao_militar = filtra_campo_post('situacao_militar');

// Tratamento de dadas
$data_proxima_apresentacao = trata_data($data_proxima_apresentacao);
$data_liminar = trata_data($data_liminar);


if($crip != hash('sha256', $_SESSION['chave']. "obrigatorio"))
    erro($BASE_URL, 2, 4411125, $pagina_atual, "criptografia_invalida", "Não foi possível editar o usuário!");

if(isset($nome_completo) && empty($nome_completo))
    erro($BASE_URL, 1, 253253446, $pagina_atual, "empty(nome_completo)", "O Campo NOME COMPLETO é obrigatório!");

if(!empty($cpf))
{
    $cpf = $cpf = str_replace('.','',$cpf);
    $cpf = $cpf = str_replace('-','',$cpf);

    if(!valida_cpf($cpf))
        erro($BASE_URL, 1, 47345734578, $pagina_atual, "cpf_invalido", "O Campo CPF é inválido!");
}

$obrigatorio_editar = $obrigatorioDAO->findById($id_obrigatorio_edita);

try
{
    $obrigatorio_editar->setDataProximaApresentacao($data_proxima_apresentacao);
    $obrigatorio_editar->setNumeroAcao($numero_acao);
    $obrigatorio_editar->setTransitouJulgado($transitou_julgado);
    $obrigatorio_editar->setDataLiminar($data_liminar);
    $obrigatorio_editar->setFavoravel($favoravel);
    $obrigatorio_editar->setConvocado($convocado);
    $obrigatorio_editar->setObservacao($observacao);
    $obrigatorio_editar->setSituacaoMilitar($situacao_militar);
}

catch(Exception $e) 
{
    erro($BASE_URL, 3, 35868458, $pagina_atual, "catch", $e->getMessage());
}

$data = $obrigatorioDAO->update($obrigatorio_editar);

if($data)
{
    $alteracao = "Atualizou a Justiça do obrigatório " . $obrigatorio_editar;
    $alteracao_detalahada = print_r($data, true);
    $logDAO->insertLog(3009, "obrigatorio", $id_obrigatorio_edita, $alteracao, $alteracao_detalahada);
}
else 
{   
    erro($BASE_URL, 3, 98786248, $pagina_atual, "justica_nao_atualizado", "Não foi possível atualizar a Justiça do Obrigatório!");
}

$_SESSION['mensagem'] = "Obrigatório ATUALIZADO com sucesso";

header ("Location: ../obrigatorio.php?crip=$crip_url&id_obrigatorio=$id_obrigatorio_edita&aba=3");



?>