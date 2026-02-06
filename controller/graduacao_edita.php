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
    erro($BASE_URL, 2, 97831645331, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");
    exit();
}

$logDAO = new LogDAO($conexao);
$graduacaoDAO = new GraduacaoDAO($conexao);

// Obtém dados do POST
$id_graduacao_edita = filtra_campo_post('id_graduacao_edita');
$rm = filtra_campo_post('rm');
$nome = filtra_campo_post('nome');
$crip = filtra_campo_post('crip');

// Valida criptografia
if($crip != hash('sha256', $_SESSION['chave']. "graduacao"))
{
    erro($BASE_URL, 2, 4375458570, $pagina_atual, "criptografia_invalida", "Não foi possível editar a graduação!");
    exit();
}

// Validações
if(empty($nome))
{
    erro($BASE_URL, 1, 856856858, $pagina_atual, "empty(nome)", "O Campo NOME é obrigatório!");
    exit();
}

// Busca graduação atual
$graduacao_atual = $graduacaoDAO->findById($id_graduacao_edita);

if(!$graduacao_atual)
{
    erro($BASE_URL, 3, 968570, $pagina_atual, "graduacao_nao_encontrada", "Graduação não encontrada!");
    exit();
}

// Verifica se já existe outra graduação com o mesmo nome
$graduacao_encontrada = $graduacaoDAO->findByNome($nome);
if($graduacao_encontrada && $graduacao_encontrada->getId() != $id_graduacao_edita)
{
    erro($BASE_URL, 1, 234647890, $pagina_atual, "graduacao_ja_cadastrada", "Já existe outra graduação com o nome: $nome!");
    exit();
}

// Cria objeto com dados atualizados
$graduacao_editar = new Graduacao($nome);
$graduacao_editar->setId($id_graduacao_edita);
$graduacao_editar->setRm($rm);

// Atualiza no banco
$data = $graduacaoDAO->update($graduacao_editar);

if($data)
{
    // Registra no log
    $alteracao = "Editou a graduação " . $graduacao_editar->getNome();
    $alteracao_detalhada = print_r($data, true);
    $logDAO->insertLog(3006, "graduacao", $data['id_atualizado'], $alteracao, $alteracao_detalhada);
}
else
{
    erro($BASE_URL, 3, 346636, $pagina_atual, "graduacao_nao_atualizada", "Não foi possível atualizar a graduação!");
    exit();
}

// Redireciona para listagem
$_SESSION['mensagem'] = "Graduação ATUALIZADA com sucesso";
header("Location: ../graduacoes.php");
