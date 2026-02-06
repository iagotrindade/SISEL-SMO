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
    erro($BASE_URL, 2, 97831645335, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");
    exit();
}

$logDAO = new LogDAO($conexao);
$omDAO = new OmDAO($conexao);

// Obtém dados do POST
$id_om_edita = filtra_campo_post('id_om_edita');
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
    erro($BASE_URL, 2, 4375458572, $pagina_atual, "criptografia_invalida", "Não foi possível editar a OM!");
    exit();
}

// Validações
if(empty($nome))
{
    erro($BASE_URL, 1, 856856860, $pagina_atual, "empty(nome)", "O Campo NOME é obrigatório!");
    exit();
}

// Busca OM atual
$om_atual = $omDAO->findById($id_om_edita);

if(!$om_atual)
{
    erro($BASE_URL, 3, 968574, $pagina_atual, "om_nao_encontrada", "OM não encontrada!");
    exit();
}

// Verifica se já existe outra OM com o mesmo nome
$om_encontrada = $omDAO->findByNome($nome);
if($om_encontrada && $om_encontrada->getId() != $id_om_edita)
{
    erro($BASE_URL, 1, 234647894, $pagina_atual, "om_ja_cadastrada", "Já existe outra OM com o nome: $nome!");
    exit();
}

// Cria objeto com dados atualizados
$om_editar = new OM();
$om_editar->setId($id_om_edita);
$om_editar->setRm($rm);
$om_editar->setFase($fase);
$om_editar->setCodom($codom);
$om_editar->setNome($nome);
$om_editar->setAbreviatura($abreviatura);
$om_editar->setTelefone($telefone);
$om_editar->setEndereco($endereco);
$om_editar->setCidade($cidade);
$om_editar->setCep($cep);

// Atualiza no banco
$data = $omDAO->update($om_editar);

if($data)
{
    // Registra no log
    $alteracao = "Editou a OM " . $om_editar->getNome();
    $alteracao_detalhada = print_r($data, true);
    $logDAO->insertLog(3008, "om", $data['id_atualizado'], $alteracao, $alteracao_detalhada);
}
else
{
    erro($BASE_URL, 3, 346638, $pagina_atual, "om_nao_atualizada", "Não foi possível atualizar a OM!");
    exit();
}

// Redireciona para listagem
$_SESSION['mensagem'] = "OM ATUALIZADA com sucesso";
header("Location: ../oms.php");
