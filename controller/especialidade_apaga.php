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
    erro($BASE_URL, 2, 235346347, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");
    exit();
}

$especialidadeDAO = new EspecialidadeDAO($conexao);
$logDAO = new LogDAO($conexao);

// Obtém ID do GET
$id_especialidade = (int)filtra_campo_get('id_especialidade');
$crip = filtra_campo_get('crip');

// Valida criptografia
if($crip != hash('sha256', $id_especialidade. "criptografia"))
{
    erro($BASE_URL, 2, 4585687, $pagina_atual, "criptografia_invalida", "Não foi possível apagar a especialidade!");
    exit();
}

// Busca especialidade
$especialidade_apaga = $especialidadeDAO->findById($id_especialidade);

if(!$especialidade_apaga)
{
    erro($BASE_URL, 3, 968569, $pagina_atual, "especialidade_nao_encontrada", "Especialidade não pode ser apagada!");
    exit();
}

// Apaga (soft delete)
$retorno = $especialidadeDAO->deleteId($especialidade_apaga->getId());

if($retorno)
{
    // Registra no log
    $alteracao = "Apagou a especialidade " . $especialidade_apaga->getNome();
    $alteracao_detalhada = $especialidade_apaga;
    $logDAO->insertLog(2005, "especialidade", $especialidade_apaga->getId(), $alteracao, $alteracao_detalhada);

    $_SESSION['mensagem'] = "Especialidade APAGADA com sucesso";
    header("Location: ../especialidades.php");
}
else
{
    erro($BASE_URL, 3, 56856957, $pagina_atual, "especialidade_nao_apagada", "Especialidade não pode ser apagada!");
    exit();
}
