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
    erro($BASE_URL, 2, 998563, $pagina_atual, "usuario!admin!operador", "Não foi possível acessar a página!");
    exit();
}


$logDAO = new logDAO($conexao);
$obrigatorioDAO = new ObrigatorioDAO($conexao);

$id_obrigatorio_edita = (int)filtra_campo_post('id_obrigatorio');
$crip_url = filtra_campo_post('crip_url');
$crip = filtra_campo_post('crip');
$solicitou_adiamento = filtra_campo_post('solicitou_adiamento');
$inicio_adiamento = filtra_campo_post('inicio_adiamento');
$fim_adiamento = filtra_campo_post('fim_adiamento');
$especialidade_adiamento = filtra_campo_post('especialidade_adiamento');
$observacao = filtra_campo_post('observacao');
$situacao_militar = filtra_campo_post('situacao_militar');

// Tratamento de dadas
$inicio_adiamento = trata_data($inicio_adiamento);
$fim_adiamento = trata_data($fim_adiamento);
$data_proxima_apresentacao = trata_data($data_proxima_apresentacao);

if($crip != hash('sha256', $_SESSION['chave']. "obrigatorio"))
    erro($BASE_URL, 2, 9633939, $pagina_atual, "criptografia_invalida", "Não foi possível editar o usuário!");

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
    $obrigatorio_editar->setSolicitouAdiamento($solicitou_adiamento);
    $obrigatorio_editar->setInicioAdiamento($inicio_adiamento);
    $obrigatorio_editar->setFimAdiamento($fim_adiamento);
    $obrigatorio_editar->setEspecialidadeAdiamento($especialidade_adiamento);
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
    $alteracao = "Atualizou o adiamento do obrigatório " . $obrigatorio_editar;
    $alteracao_detalahada = print_r($data, true);
    $logDAO->insertLog(3010, "obrigatorio", $id_obrigatorio_edita, $alteracao, $alteracao_detalahada);
}
else 
{   
    erro($BASE_URL, 3, 98786248, $pagina_atual, "adiamento_nao_atualizado", "Não foi possível atualizar o adiamento!");
}

$_SESSION['mensagem'] = "Adiamento ATUALIZADO com sucesso";

header ("Location: ../obrigatorio.php?crip=$crip_url&id_obrigatorio=$id_obrigatorio_edita&aba=4");



?>