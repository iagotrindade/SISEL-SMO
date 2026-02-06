<?php
if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Especialidade.php';
include_once '../dao/EspecialidadeDAO.php';
include_once '../dao/LogDAO.php';

// Validação de perfil
if($_SESSION['perfil_smo'] != "admin")
{
    erro($BASE_URL, 2, 97831645328, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");
    exit();
}

$logDAO = new LogDAO($conexao);
$especialidadeDAO = new EspecialidadeDAO($conexao);

// Obtém dados do POST
$rm = filtra_campo_post('rm');
$nome = filtra_campo_post('nome');
$crip = filtra_campo_post('crip');

// Valida criptografia
if($crip != hash('sha256', $_SESSION['chave']. "especialidade"))
{
    erro($BASE_URL, 2, 97485677, $pagina_atual, "criptografia_invalida", "Não foi possível cadastrar a especialidade!");
    exit();
}

// Validações
if(empty($nome))
{
    erro($BASE_URL, 1, 2364578568857, $pagina_atual, "empty(nome)", "O Campo NOME é obrigatório!");
    exit();
}

// Verifica se especialidade já existe
$especialidade_encontrada = $especialidadeDAO->findByNome($nome);

if($especialidade_encontrada)
{
    erro($BASE_URL, 1, 234647887, $pagina_atual, "especialidade_ja_cadastrada", "Especialidade: $nome já está cadastrada no sistema!");
    exit();
}

// Cria objeto Especialidade
$especialidade_cadastrar = new Especialidade($nome);
$especialidade_cadastrar->setRm($rm);

// Insere no banco
$data = $especialidadeDAO->insert($especialidade_cadastrar);

if($data)
{
    // Registra no log
    $alteracao = "Cadastrou a especialidade $nome";
    $alteracao_detalhada = print_r($data, true);
    $logDAO->insertLog(1005, "especialidade", $data['id_adicionado'], $alteracao, $alteracao_detalhada);
}
else
{
    erro($BASE_URL, 3, 998796, $pagina_atual, "especialidade_nao_cadastrada", "Não foi possível cadastrar a especialidade!");
    exit();
}

// Redireciona para listagem
$_SESSION['mensagem'] = "Especialidade cadastrada com sucesso";
header("Location: ../especialidades.php");
