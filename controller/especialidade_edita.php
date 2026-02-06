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
    erro($BASE_URL, 2, 97831645329, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");
    exit();
}

$logDAO = new LogDAO($conexao);
$especialidadeDAO = new EspecialidadeDAO($conexao);

// Obtém dados do POST
$id_especialidade_edita = filtra_campo_post('id_especialidade_edita');
$rm = filtra_campo_post('rm');
$nome = filtra_campo_post('nome');
$crip = filtra_campo_post('crip');

// Valida criptografia
if($crip != hash('sha256', $_SESSION['chave']. "especialidade"))
{
    erro($BASE_URL, 2, 4375458569, $pagina_atual, "criptografia_invalida", "Não foi possível editar a especialidade!");
    exit();
}

// Validações
if(empty($nome))
{
    erro($BASE_URL, 1, 856856857, $pagina_atual, "empty(nome)", "O Campo NOME é obrigatório!");
    exit();
}

// Busca especialidade atual
$especialidade_atual = $especialidadeDAO->findById($id_especialidade_edita);

if(!$especialidade_atual)
{
    erro($BASE_URL, 3, 968568, $pagina_atual, "especialidade_nao_encontrada", "Especialidade não encontrada!");
    exit();
}

// Verifica se já existe outra especialidade com o mesmo nome
$especialidade_encontrada = $especialidadeDAO->findByNome($nome);
if($especialidade_encontrada && $especialidade_encontrada->getId() != $id_especialidade_edita)
{
    erro($BASE_URL, 1, 234647888, $pagina_atual, "especialidade_ja_cadastrada", "Já existe outra especialidade com o nome: $nome!");
    exit();
}

// Cria objeto com dados atualizados
$especialidade_editar = new Especialidade($nome);
$especialidade_editar->setId($id_especialidade_edita);
$especialidade_editar->setRm($rm);

// Atualiza no banco
$data = $especialidadeDAO->update($especialidade_editar);

if($data)
{
    // Registra no log
    $alteracao = "Editou a especialidade " . $especialidade_editar->getNome();
    $alteracao_detalhada = print_r($data, true);
    $logDAO->insertLog(3005, "especialidade", $data['id_atualizado'], $alteracao, $alteracao_detalhada);
}
else
{
    erro($BASE_URL, 3, 346635, $pagina_atual, "especialidade_nao_atualizada", "Não foi possível atualizar a especialidade!");
    exit();
}

// Redireciona para listagem
$_SESSION['mensagem'] = "Especialidade ATUALIZADA com sucesso";
header("Location: ../especialidades.php");
