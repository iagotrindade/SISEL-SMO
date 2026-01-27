<?php

if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Obrigatorio.php';
include_once '../models/Om.php';
include_once '../dao/ObrigatorioDAO.php';
include_once '../dao/LogDAO.php';

$logDAO = new logDAO($conexao);
$obrigatorioDAO = new obrigatorioDAO($conexao);

$nome_completo = filtra_campo_post('nome_completo');
$cpf = filtra_campo_post('cpf');
$mail = filtra_campo_post('mail');
$identidade = filtra_campo_post('identidade');
$prioridade_forca = filtra_campo_post('prioridade_forca');
$data_nascimento = filtra_campo_post('data_nascimento');
$nacionalidade = filtra_campo_post('nacionalidade');
$nome_pai = filtra_campo_post('nome_pai');
$nome_mae = filtra_campo_post('nome_mae');
$endereco = filtra_campo_post('endereco');
$telefone = filtra_campo_post('telefone');
$estado_civil = filtra_campo_post('estado_civil');
$dependentes = filtra_campo_post('dependentes');
$nome_instituito_ensino = filtra_campo_post('nome_instituicao_ensino');
$ano_formacao = filtra_campo_post('ano_formacao');
$formacao = filtra_campo_post('formacao');
$documento_militar = filtra_campo_post('documento_militar');

$crip = filtra_campo_post('crip');

if($crip != hash('sha256', $_SESSION['chave']. "obrigatorio"))
{
    erro($BASE_URL, 2, 97485676, $pagina_atual, "criptografia_invalida", "Não foi possível cadastrar o usuário!");
    exit();
}

$cpf = $cpf = str_replace('.','',$cpf);
$cpf = $cpf = str_replace('-','',$cpf);


if($cpf != null && !valida_cpf($cpf))
{
    erro($BASE_URL, 1, 965745456, $pagina_atual, "cpf_invalido", "O Campo CPF é inválido!");
    exit();
}

if(empty($cpf))
{
    erro($BASE_URL, 1, 23346346, $pagina_atual, "empty(cpf)", "O Campo NOME CPF é obrigatório!");
    exit();
}

if(empty($nome_completo))
{
    erro($BASE_URL, 1, 23563467, $pagina_atual, "empty(nome_completo)", "O Campo NOME COMPLETO é obrigatório!");
    exit();
}

if(!empty($mail) && !filter_var($mail, FILTER_VALIDATE_EMAIL))
{
    erro($BASE_URL, 1, 9568567, $pagina_atual, "mail_invalido", "O E-Mail é invalido");
    exit();
}

if($data_nascimento != null) $data_nascimento = trata_data($data_nascimento);


$obrigatorio_encontrado = $obrigatorioDAO->findByCPF($cpf);

if($obrigatorio_encontrado)
{
    $nome_completo = $obrigatorio_encontrado->getNomeCompleto();
    erro($BASE_URL, 1, 234647886, $pagina_atual, "obrigatorio_ja_cadastrado", "Obrigatório $nome_completo já está cadastrado no sistema!");
    exit();
}

$obrigatorio_cadastrar = new obrigatorio($cpf);

$obrigatorio_cadastrar->setIdOm($_SESSION['id_om_smo']);
$obrigatorio_cadastrar->setNomeCompleto($nome_completo);
$obrigatorio_cadastrar->setCPF($cpf);
$obrigatorio_cadastrar->setMail($mail);
$obrigatorio_cadastrar->setIdentidade($identidade);
$obrigatorio_cadastrar->setPrioridadeForca($prioridade_forca);
$obrigatorio_cadastrar->setDataNascimento($data_nascimento);
$obrigatorio_cadastrar->setNacionalidade($nacionalidade);
$obrigatorio_cadastrar->setNomePai($nome_pai);
$obrigatorio_cadastrar->setNomeMae($nome_mae);
$obrigatorio_cadastrar->setEndereco($endereco);
$obrigatorio_cadastrar->setTelefone($telefone);
$obrigatorio_cadastrar->setEstadoCivil($estado_civil);
$obrigatorio_cadastrar->setDependentes($dependentes);
$obrigatorio_cadastrar->setNomeInstitutoEnsino($nome_instituito_ensino);
$obrigatorio_cadastrar->setAnoFormacao($ano_formacao);
$obrigatorio_cadastrar->setFormacao($formacao);
$obrigatorio_cadastrar->setDocumentoMilitar($documento_militar);


$data = $obrigatorioDAO->insert($obrigatorio_cadastrar);

if($data)
{    
    $obrigatorio_cadastrar->setId($data['id_adicionado']);
    $alteracao = "Cadastrou um Obrigatório $obrigatorio_cadastrar";
    $alteracao_detalahada = print_r($data, true);
    $logDAO->insertLog(1004, "obrigatorio", $data['id_adicionado'], $alteracao, $alteracao_detalahada);
}
else 
{
   
    erro($BASE_URL, 3, 9978456, $pagina_atual, "obrigatorio_nao_cadastrado", "Não foi possível cadastrar o Obrigatório!");
    exit();
}
$id_adicionado = $data['id_adicionado'];
$crip_url = hash('sha256', $id_adicionado."criptografia");
//$_SESSION['mensagem'] = "Usuário Cadastrado com sucesso";
header ("Location: ../obrigatorio.php?crip=$crip_url&id_obrigatorio=$id_adicionado");


?>