<?php
if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Graduacao.php';
include_once '../dao/GraduacaoDAO.php';
include_once '../dao/LogDAO.php';

// Validação de perfil
if($_SESSION['perfil_smo'] != "admin")
{
    erro($BASE_URL, 2, 97831645330, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");
    exit();
}

$logDAO = new LogDAO($conexao);
$graduacaoDAO = new GraduacaoDAO($conexao);

// Obtém dados do POST
$rm = filtra_campo_post('rm');
$nome = filtra_campo_post('nome');
$crip = filtra_campo_post('crip');

// Valida criptografia
if($crip != hash('sha256', $_SESSION['chave']. "graduacao"))
{
    erro($BASE_URL, 2, 97485678, $pagina_atual, "criptografia_invalida", "Não foi possível cadastrar a graduação!");
    exit();
}

// Validações
if(empty($nome))
{
    erro($BASE_URL, 1, 2364578568858, $pagina_atual, "empty(nome)", "O Campo NOME é obrigatório!");
    exit();
}

// Verifica se graduação já existe
$graduacao_encontrada = $graduacaoDAO->findByNome($nome);

if($graduacao_encontrada)
{
    erro($BASE_URL, 1, 234647889, $pagina_atual, "graduacao_ja_cadastrada", "Graduação: $nome já está cadastrada no sistema!");
    exit();
}

// Cria objeto Graduacao
$graduacao_cadastrar = new Graduacao($nome);
$graduacao_cadastrar->setRm($rm);

// Insere no banco
$data = $graduacaoDAO->insert($graduacao_cadastrar);

if($data)
{
    // Registra no log
    $alteracao = "Cadastrou a graduação $nome";
    $alteracao_detalhada = print_r($data, true);
    $logDAO->insertLog(1006, "graduacao", $data['id_adicionado'], $alteracao, $alteracao_detalhada);
}
else
{
    erro($BASE_URL, 3, 998797, $pagina_atual, "graduacao_nao_cadastrada", "Não foi possível cadastrar a graduação!");
    exit();
}

// Redireciona para listagem
$_SESSION['mensagem'] = "Graduação cadastrada com sucesso";
header("Location: ../graduacoes.php");
