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
$data_comparecimento_designacao = filtra_campo_post('data_comparecimento_designacao');
$data_proxima_apresentacao = filtra_campo_post('data_proxima_apresentacao');
$distribuicao = filtra_campo_post('distribuicao');
$data_selecao_complementar = filtra_campo_post('data_selecao_complementar');
$id_om_1_fase = filtra_campo_post('id_om_1_fase');
$observacao = filtra_campo_post('observacao');
$situacao_militar = filtra_campo_post('situacao_militar');
$compareceu_designacao = filtra_campo_post('compareceu_designacao');
$local_compareceu_designacao = filtra_campo_post('local_compareceu_designacao');

// Tratamento de dadas
$data_comparecimento_designacao = trata_data($data_comparecimento_designacao);
$data_proxima_apresentacao = trata_data($data_proxima_apresentacao);
$data_selecao_complementar = trata_data($data_selecao_complementar);
$data_incorporacao = trata_data($data_incorporacao);

if($crip != hash('sha256', $_SESSION['chave']. "obrigatorio"))
    erro($BASE_URL, 2, 4444555687, $pagina_atual, "criptografia_invalida", "Não foi possível editar o usuário!");

/* if(empty($nome_completo))
    erro($BASE_URL, 1, 253253446, $pagina_atual, "empty(nome_completo)", "O Campo NOME COMPLETO é obrigatório!");
*/

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
    $om_1_fase = new OM();
    $om_1_fase->setId($id_om_1_fase);
    $obrigatorio_editar->setDataProximaApresentacao($data_proxima_apresentacao);
    $obrigatorio_editar->setDataComparecimentoDesignacao($data_comparecimento_designacao);
    $obrigatorio_editar->setDistribuicao($distribuicao);
    $obrigatorio_editar->setDataSelecaoComplementar($data_selecao_complementar);
    $obrigatorio_editar->setOm1Fase($om_1_fase);
    $obrigatorio_editar->setCompareceuDesignacao($compareceu_designacao);
    $obrigatorio_editar->setLocalCompareceuDesignacao($local_compareceu_designacao);
    $obrigatorio_editar->setObservacao($observacao);
    $obrigatorio_editar->setSituacaoMilitar($situacao_militar);
}
catch(Exception $e) 
{
    erro($BASE_URL, 3, 235778, $pagina_atual, "catch", $e->getMessage());
}

$data = $obrigatorioDAO->update($obrigatorio_editar);

if($data)
{
    $alteracao = "Atualizou a Distribuição do obrigatório " . $obrigatorio_editar;
    $alteracao_detalahada = print_r($data, true);
    $logDAO->insertLog(3011, "obrigatorio", $id_obrigatorio_edita, $alteracao, $alteracao_detalahada);
}
else 
{   
    erro($BASE_URL, 3, 654654165, $pagina_atual, "distribuicao_nao_atualizada", "Não foi possível atualizar a Distribuição!");
}

$_SESSION['mensagem'] = "Obrigatório ATUALIZADO com sucesso";

header ("Location: ../obrigatorio.php?crip=$crip_url&id_obrigatorio=$id_obrigatorio_edita&aba=5");



?>