<?php

if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Usuario.php';
include_once '../dao/UsuarioDAO.php';
include_once '../dao/LogDAO.php';

$logDAO = new logDAO($conexao);
$usuarioDAO = new UsuarioDAO($conexao);

$id_om = filtra_campo_post('id_om');
$nome_completo = filtra_campo_post('nome_completo');
$nome_guerra = filtra_campo_post('nome_guerra');
$usuario = filtra_campo_post('usuario');
$cpf = filtra_campo_post('cpf');
$mail = filtra_campo_post('mail');
$telefone = filtra_campo_post('telefone');
$posto_grad = filtra_campo_post('posto_grad');
$validade = filtra_campo_post('data_validade');
$perfil = filtra_campo_post('perfil');
$crip = filtra_campo_post('crip');

if(!empty($validade)) $validade = trata_data($validade);

if($crip != hash('sha256', $_SESSION['chave']. "usuario"))
{
    erro($BASE_URL, 2, 97485676, $pagina_atual, "criptografia_invalida", "Não foi possível cadastrar o usuário!");
    exit();
}

$cpf = $cpf = str_replace('.','',$cpf);
$cpf = $cpf = str_replace('-','',$cpf);


if($cpf != null && !valida_cpf($cpf))
{
    erro($BASE_URL, 1, 965745456, $pagina_atual, "cpf_invalido", "O Campo CPF é inválido!");
    exit();
}

if(empty($nome_guerra))
{
    erro($BASE_URL, 1, 2364578568856, $pagina_atual, "empty(nome_guerra)", "O Campo NOME DE GUERRA é obrigatório!");
    exit();
}
if(empty($nome_completo))
{
    erro($BASE_URL, 1, 235235, $pagina_atual, "empty(nome_completo)", "O Campo NOME COMPLETO é obrigatório!");
    exit();
}
if(empty($usuario))
{
    erro($BASE_URL, 1, 8537457, $pagina_atual, "empty(usuario)", "O Campo USUÁRIO para acessar o sistema é obrigatório!");
    exit();
}

if(empty($perfil))
{
    erro($BASE_URL, 1, 945674575, $pagina_atual, "empty(perfil)", "O Campo PERFIL é obrigatório!");
    exit();
}
if(empty($telefone))
{
    erro($BASE_URL, 1, 235325, $pagina_atual, "empty(telefone)", "O Campo TELEFONE é obrigatório!");
    exit();
}
if(empty($mail))
{
    erro($BASE_URL, 1, 235235, $pagina_atual, "empty(mail)", "O Campo E-MAIL é obrigatório!");
    exit();
}

if(!filter_var($mail, FILTER_VALIDATE_EMAIL))
{
    erro($BASE_URL, 1, 9568567, $pagina_atual, "mail_invalido", "O E-Mail é invalido");
    exit();
}


$usuario_encontrado = $usuarioDAO->findByUsuario($usuario);

if($usuario_encontrado)
{
    erro($BASE_URL, 1, 234647886, $pagina_atual, "usuario_ja_cadastrado", "Usuário: $usuario já está cadastrado no sistema!");
    exit();
}

$senha = "123@smo";
$senha = $senha."senha_smo_criptografada";
$senha =  hash('sha256', $senha);

$usuario_cadastrar = new Usuario($usuario);

$usuario_cadastrar->setIdOm($id_om);
$usuario_cadastrar->setNomeGuerra($nome_guerra);
$usuario_cadastrar->setNomeCompleto($nome_completo);
$usuario_cadastrar->setUsuario($usuario);
$usuario_cadastrar->setCPF($cpf);
$usuario_cadastrar->setPerfil($perfil);
$usuario_cadastrar->setTelefone($telefone);
$usuario_cadastrar->setMail($mail);
$usuario_cadastrar->setPostoGrad($posto_grad);
$usuario_cadastrar->setSenha($senha);
$usuario_cadastrar->setValidade($validade);

$data = $usuarioDAO->insert($usuario_cadastrar);

if($data)
{
    $alteracao = "Cadastrou o usuário $usuario";
    $alteracao_detalahada = print_r($data, true);
    $logDAO->insertLog(1001, "usuario", $data['id_adicionado'], $alteracao, $alteracao_detalahada);
}
else 
{
    erro($BASE_URL, 3, 998795, $pagina_atual, "usuario_nao_cadastrado", "Não foi possível cadastrar o usuário!");
    exit();
}

$_SESSION['mensagem'] = "Usuário Cadastrado com sucesso";
header ("Location: ../usuarios.php");


?>