<?php
if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Arquivo.php';
include_once '../dao/ArquivoDAO.php';
include_once '../dao/LogDAO.php';
include_once '../models/Obrigatorio.php';
include_once '../models/Om.php';
include_once '../dao/ObrigatorioDAO.php';

$logDAO = new logDAO($conexao);
$arquivoDAO = new ArquivoDAO($conexao);
$obrigatorioDAO = new ObrigatorioDAO($conexao);


$crip = filtra_campo_post('crip');
$crip_url = filtra_campo_post('crip_url');
$chave = filtra_campo_post('chave');
$id_arquivo = filtra_campo_post('id_arquivo');
$id_obrigatorio = filtra_campo_post('id_obrigatorio');

if($crip != hash('sha256', $id_arquivo."criptografia_arquivo"))
{
    erro($BASE_URL, 3, 85476345345, $pagina_atual, "crip_invalida", "Não foi possível apagar o arquivo!");
    exit();
}

if($chave != $_SESSION['chave'])
    erro($BASE_URL, 3, 534687457, $pagina_atual, "SESSION[chave]==null", null);

$obrigatorio = $obrigatorioDAO->findById($id_obrigatorio);
if(!$obrigatorio)
    erro($BASE_URL, 3, 32536376, $pagina_atual, "!id_obrigatorio", null);

$data = $arquivoDAO->deleteId($id_arquivo);

if($data)
{
    $alteracao = "Apagou o arquivo ID: " . $id_arquivo . " do obrigatório " . $obrigatorio->getNomecompleto() . " e CPF " . $obrigatorio->getCpf();;
    $logDAO->insertLog(2002, "arquivo", $id_arquivo, $alteracao, null);
}
else 
{
    erro($BASE_URL, 3, 326457457, $pagina_atual, "arquivo_nao_apagado", "Não foi possível apagar a arquivo!");
    exit();
}

$_SESSION['mensagem_arquivo_apagado'] = "Arquivo apagado com sucesso!";

header ("Location: ../obrigatorio.php?crip=$crip_url&id_obrigatorio=$id_obrigatorio&aba=7");


?>