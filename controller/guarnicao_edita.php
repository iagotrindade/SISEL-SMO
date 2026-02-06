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
    erro($BASE_URL, 2, 97831645333, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");
    exit();
}

$logDAO = new LogDAO($conexao);
$guarnicaoDAO = new GuarnicaoDAO($conexao);

// Obtém dados do POST
$id_guarnicao_edita = filtra_campo_post('id_guarnicao_edita');
$rm = filtra_campo_post('rm');
$nome = filtra_campo_post('nome');
$crip = filtra_campo_post('crip');

// Valida criptografia
if($crip != hash('sha256', $_SESSION['chave']. "guarnicao"))
{
    erro($BASE_URL, 2, 4375458571, $pagina_atual, "criptografia_invalida", "Não foi possível editar a guarnição!");
    exit();
}

// Validações
if(empty($nome))
{
    erro($BASE_URL, 1, 856856859, $pagina_atual, "empty(nome)", "O Campo NOME é obrigatório!");
    exit();
}

// Busca guarnição atual
$guarnicao_atual = $guarnicaoDAO->findById($id_guarnicao_edita);

if(!$guarnicao_atual)
{
    erro($BASE_URL, 3, 968572, $pagina_atual, "guarnicao_nao_encontrada", "Guarnição não encontrada!");
    exit();
}

// Verifica se já existe outra guarnição com o mesmo nome
$guarnicao_encontrada = $guarnicaoDAO->findByNome($nome);
if($guarnicao_encontrada && $guarnicao_encontrada->getId() != $id_guarnicao_edita)
{
    erro($BASE_URL, 1, 234647892, $pagina_atual, "guarnicao_ja_cadastrada", "Já existe outra guarnição com o nome: $nome!");
    exit();
}

// Cria objeto com dados atualizados
$guarnicao_editar = new Guarnicao($nome);
$guarnicao_editar->setId($id_guarnicao_edita);
$guarnicao_editar->setRm($rm);

// Atualiza no banco
$data = $guarnicaoDAO->update($guarnicao_editar);

if($data)
{
    // Registra no log
    $alteracao = "Editou a guarnição " . $guarnicao_editar->getNome();
    $alteracao_detalhada = print_r($data, true);
    $logDAO->insertLog(3007, "guarnicao", $data['id_atualizado'], $alteracao, $alteracao_detalhada);
}
else
{
    erro($BASE_URL, 3, 346637, $pagina_atual, "guarnicao_nao_atualizada", "Não foi possível atualizar a guarnição!");
    exit();
}

// Redireciona para listagem
$_SESSION['mensagem'] = "Guarnição ATUALIZADA com sucesso";
header("Location: ../guarnicoes.php");
