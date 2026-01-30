<?php

if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Usuario.php';
include_once '../dao/UsuarioDAO.php';
include_once '../dao/LogDAO.php';

if($_SESSION['perfil_smo'] != "admin")
{
    erro($BASE_URL, 2, 2353264634, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");
    exit();
}

$logDAO = new logDAO($conexao);
$usuarioDAO = new UsuarioDAO($conexao);

$id_usuario_edita = (int)filtra_campo_post('id_usuario_edita');
$senha1 = filtra_campo_post('senha1');
$senha2 = filtra_campo_post('senha2');
$crip = filtra_campo_post('crip');

if($crip != hash('sha256', $_SESSION['chave']. "usuario")) erro($BASE_URL, 2, 57343456, $pagina_atual, "criptografia_invalida", "Não foi possível atualizar a senha do usuário!");

if(empty($senha1) || empty($senha2)) erro($BASE_URL, 1, 5746363476, $pagina_atual, "empty(senha1)", "Os dois campos de senha são obrigatórios");

if($senha1 != $senha2) erro($BASE_URL, 1, 3463474775, $pagina_atual, "senhas_diferentes", "As duas senhas devem ser iguais!");

if(strlen($senha1) < 8) erro($BASE_URL, 1, 2353467, $pagina_atual, "senha<8char", "O Campo de senha deve ter pelo menos 8 caracteres!");

if(!preg_match('/[A-Z]/', $senha1)) erro($BASE_URL, 1, 63483268, $pagina_atual, "senha!=maiuscula", "A senha deve ter pelo menos uma letra MAIÚSCULA!");

if(!preg_match('/[0-9]/', $senha1)) erro($BASE_URL, 1, 78569036, $pagina_atual, "senha!=numero", "A senha deve ter pelo menos uma letra um NÚMERO!");

if(!preg_match('/[$*&@#]/', $senha1)) erro($BASE_URL, 1, 904376563, $pagina_atual, "senha!=numero", "A senha deve ter pelo menos um caracter especial!");

$senha = $senha1."senha_smo_criptografada";
$senha =  hash('sha256', $senha);

$usuario_editar = $usuarioDAO->findById($id_usuario_edita);

$usuario_editar->setSenha($senha);
$data = $usuarioDAO->update_pass($usuario_editar);

if($data)
{
    $alteracao = "Alterou a senha do usuário " . $usuario_editar->getUsuario();
    $alteracao_detalahada = print_r($data, true);
    $logDAO->insertLog(3002, "usuario", $id_usuario_edita, $alteracao, $alteracao_detalahada);
}
else 
{
    erro($BASE_URL, 3, 7534764, $pagina_atual, "usuario_nao_atualizado", "Não foi possível atualizar o usuário!");
    exit();
}

$_SESSION['mensagem'] = "SENHA atualizada com Sucesso";
header ("Location: ../usuarios.php");


?>