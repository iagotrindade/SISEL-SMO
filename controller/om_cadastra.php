<?php
if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Om.php';
include_once '../dao/OmDAO.php';
include_once '../dao/LogDAO.php';

// Validação de perfil
if($_SESSION['perfil_smo'] != "admin")
{
    erro($BASE_URL, 2, 97831645334, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");
    exit();
}

$logDAO = new LogDAO($conexao);
$omDAO = new OmDAO($conexao);

// Obtém dados do POST
$rm = filtra_campo_post('rm');
$fase = filtra_campo_post('fase');
$codom = filtra_campo_post('codom');
$nome = filtra_campo_post('nome');
$abreviatura = filtra_campo_post('abreviatura');
$telefone = filtra_campo_post('telefone');
$endereco = filtra_campo_post('endereco');
$cidade = filtra_campo_post('cidade');
$cep = filtra_campo_post('cep');
$crip = filtra_campo_post('crip');

// Valida criptografia
if($crip != hash('sha256', $_SESSION['chave']. "om"))
{
    erro($BASE_URL, 2, 97485680, $pagina_atual, "criptografia_invalida", "Não foi possível cadastrar a OM!");
    exit();
}

// Validações
if(empty($nome))
{
    erro($BASE_URL, 1, 2364578568860, $pagina_atual, "empty(nome)", "O Campo NOME é obrigatório!");
    exit();
}

// Verifica se OM já existe pelo nome
$om_encontrada = $omDAO->findByNome($nome);

if($om_encontrada)
{
    erro($BASE_URL, 1, 234647893, $pagina_atual, "om_ja_cadastrada", "OM: $nome já está cadastrada no sistema!");
    exit();
}

// Cria objeto OM
$om_cadastrar = new OM();
$om_cadastrar->setRm($rm);
$om_cadastrar->setFase($fase);
$om_cadastrar->setCodom($codom);
$om_cadastrar->setNome($nome);
$om_cadastrar->setAbreviatura($abreviatura);
$om_cadastrar->setTelefone($telefone);
$om_cadastrar->setEndereco($endereco);
$om_cadastrar->setCidade($cidade);
$om_cadastrar->setCep($cep);

// Insere no banco
$data = $omDAO->insert($om_cadastrar);

if($data)
{
    // Registra no log
    $alteracao = "Cadastrou a OM $nome";
    $alteracao_detalhada = print_r($data, true);
    $logDAO->insertLog(1008, "om", $data['id_adicionado'], $alteracao, $alteracao_detalhada);
}
else
{
    erro($BASE_URL, 3, 998799, $pagina_atual, "om_nao_cadastrada", "Não foi possível cadastrar a OM!");
    exit();
}

// Redireciona para listagem
$_SESSION['mensagem'] = "OM cadastrada com sucesso";
header("Location: ../oms.php");
