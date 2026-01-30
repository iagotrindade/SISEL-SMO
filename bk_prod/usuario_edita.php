<?php

if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Usuario.php';
include_once '../dao/UsuarioDAO.php';
include_once '../dao/LogDAO.php';

if($_SESSION['perfil_smo'] != "admin")
{
    erro($BASE_URL, 2, 97831645327, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");
    exit();
}

$logDAO = new logDAO($conexao);
$usuarioDAO = new UsuarioDAO($conexao);

$id_usuario_edita = filtra_campo_post('id_usuario_edita');
$nome_guerra = filtra_campo_post('nome_guerra');
$cpf = filtra_campo_post('cpf');
$nome_completo = filtra_campo_post('nome_completo');
$mail = filtra_campo_post('mail');
$telefone = filtra_campo_post('telefone');
$posto_grad = filtra_campo_post('posto_grad');
$validade = filtra_campo_post('data_validade');
$perfil = filtra_campo_post('perfil');
$id_om = filtra_campo_post('id_om');
$crip = filtra_campo_post('crip');

$cpf = removerCaracteresEspeciais($cpf);

if($crip != hash('sha256', $_SESSION['chave']. "usuario"))  erro($BASE_URL, 2, 4375458568, $pagina_atual, "criptografia_invalida", "Não foi possível editar o usuário!");
if(empty($nome_guerra))   erro($BASE_URL, 1, 856856856, $pagina_atual, "empty(nome)", "O Campo NOME DE GUERRA é obrigatório!");
if(empty($nome_completo)) erro($BASE_URL, 1, 253253446, $pagina_atual, "empty(nome_completo)", "O Campo NOME COMPLETO é obrigatório!");
if(empty($posto_grad))    erro($BASE_URL, 1, 6845865, $pagina_atual, "empty(posto_grad)", "O Campo Posto/Graduação é obrigatório!");
if(empty($telefone))      erro($BASE_URL, 1, 7568658, $pagina_atual, "empty(telefone)", "O Campo TELEFONE é obrigatório!");
if(empty($perfil))        erro($BASE_URL, 1, 25346754, $pagina_atual, "empty(perfil)", "O Campo Perfil é obrigatório!");
if(empty($mail))          erro($BASE_URL, 1, 25235356, $pagina_atual, "empty(mail)", "O Campo E-MAIL é obrigatório!");
if(empty($id_om))          erro($BASE_URL, 1, 96633266, $pagina_atual, "empty(om)", "O Campo OM é obrigatório!");

if(!empty($mail))
{
    if(!filter_var($mail, FILTER_VALIDATE_EMAIL))
    {
        erro($BASE_URL, 1, 35683576, $pagina_atual, "mail_invalido", "O E-Mail é invalido");
        exit();
    }
}

if(!empty($validade)) $validade = trata_data($validade);

$nome_usuario = $usuarioDAO->findById($id_usuario_edita);

$usuario_editar = new Usuario($nome_usuario->getUsuario());
$usuario_editar->setId($nome_usuario->getId());
$usuario_editar->setNomeGuerra($nome_guerra);
$usuario_editar->setNomeCompleto($nome_completo);
$usuario_editar->setCpf($cpf);
$usuario_editar->setPostoGrad($posto_grad);
$usuario_editar->setTelefone($telefone);
$usuario_editar->setMail($mail);
$usuario_editar->setValidade($validade);
$usuario_editar->setPerfil($perfil);
$usuario_editar->setIdOm($id_om);
$data = $usuarioDAO->update($usuario_editar);

if($data)
{
    $alteracao = "Editou o usuário " . $usuario_editar->getUsuario();
    $alteracao_detalahada = print_r($data, true);
    $logDAO->insertLog(3001, "usuario", $data['id_adicionado'], $alteracao, $alteracao_detalahada);
}
else 
{
    erro($BASE_URL, 3, 346634, $pagina_atual, "usuario_nao_atualizado", "Não foi possível atualizar o usuário!");
    exit();
}

$_SESSION['mensagem'] = "Usuário ATUALIZADO com Sucesso";
header ("Location: ../usuarios.php");


?>