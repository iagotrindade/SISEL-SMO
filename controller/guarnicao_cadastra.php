<?php
if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Guarnicao.php';
include_once '../dao/GuarnicaoDAO.php';
include_once '../dao/LogDAO.php';

// Validação de perfil
if($_SESSION['perfil_smo'] != "admin")
{
    erro($BASE_URL, 2, 97831645332, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");
    exit();
}

$logDAO = new LogDAO($conexao);
$guarnicaoDAO = new GuarnicaoDAO($conexao);

// Obtém dados do POST
$rm = filtra_campo_post('rm');
$nome = filtra_campo_post('nome');
$crip = filtra_campo_post('crip');

// Valida criptografia
if($crip != hash('sha256', $_SESSION['chave']. "guarnicao"))
{
    erro($BASE_URL, 2, 97485679, $pagina_atual, "criptografia_invalida", "Não foi possível cadastrar a guarnição!");
    exit();
}

// Validações
if(empty($nome))
{
    erro($BASE_URL, 1, 2364578568859, $pagina_atual, "empty(nome)", "O Campo NOME é obrigatório!");
    exit();
}

// Verifica se guarnição já existe
$guarnicao_encontrada = $guarnicaoDAO->findByNome($nome);

if($guarnicao_encontrada)
{
    erro($BASE_URL, 1, 234647891, $pagina_atual, "guarnicao_ja_cadastrada", "Guarnição: $nome já está cadastrada no sistema!");
    exit();
}

// Cria objeto Guarnicao
$guarnicao_cadastrar = new Guarnicao($nome);
$guarnicao_cadastrar->setRm($rm);

// Insere no banco
$data = $guarnicaoDAO->insert($guarnicao_cadastrar);

if($data)
{
    // Registra no log
    $alteracao = "Cadastrou a guarnição $nome";
    $alteracao_detalhada = print_r($data, true);
    $logDAO->insertLog(1007, "guarnicao", $data['id_adicionado'], $alteracao, $alteracao_detalhada);
}
else
{
    erro($BASE_URL, 3, 998798, $pagina_atual, "guarnicao_nao_cadastrada", "Não foi possível cadastrar a guarnição!");
    exit();
}

// Redireciona para listagem
$_SESSION['mensagem'] = "Guarnição cadastrada com sucesso";
header("Location: ../guarnicoes.php");
