<?php	
    include_once '../funcoes.php';

    session_start();

    include_once $BASE_URL.'/dao/conecta_banco.php';
    include_once $BASE_URL.'/models/Usuario.php';
    include_once $BASE_URL.'/dao/UsuarioDAO.php';
    include_once $BASE_URL.'/dao/LogDAO.php';

    $logDAO = new logDAO($conexao);
    $usuarioDAO = new UsuarioDAO($conexao);
    $usuario = $usuarioDAO->findById($_SESSION['id_usuario_smo']);

    if(!isset($_SESSION['chave']))  erro($BASE_URL, 2, 3267645, $pagina_atual, "!isset(SESSION['chave']", "Tente fazer o login novamente!");

    $senha1  = filtra_campo_post("senha1");
    $senha2 = filtra_campo_post("senha2");
    $criptografia = filtra_campo_post ("crip");

    if($criptografia != hash('sha256', $_SESSION['chave']."nova_senha"))  erro($BASE_URL, 2, 2364534, $pagina_atual, "criptografia_invalida", "Tente fazer o login novamente!");
    
    if($senha1 == null || $senha2 == null) erro($BASE_URL, 1, 26347457, $pagina_atual, "senha_null", "Os dois campos são obrigatórios!");
    if($senha1 != $senha2) erro($BASE_URL, 1, 32345745, $pagina_atual, "senha_null", "As duas senhas devem são iguais!");
    
    if(strlen($senha1) < 8) erro($BASE_URL, 1, 2353467, $pagina_atual, "senha<8char", "O Campo de senha deve ter pelo menos 8 caracteres!");
    if(!preg_match('/[A-Z]/', $senha1)) erro($BASE_URL, 1, 63483268, $pagina_atual, "senha!=maiuscula", "A senha deve ter pelo menos uma letra MAIÚSCULA!");
    if(!preg_match('/[0-9]/', $senha1)) erro($BASE_URL, 1, 78569036, $pagina_atual, "senha!=numero", "A senha deve ter pelo menos uma letra um NÚMERO!");
    if(!preg_match('/[$*&@#]/', $senha1)) erro($BASE_URL, 1, 904376563, $pagina_atual, "senha!=numero", "A senha deve ter pelo menos um caracter especial!");
   
    $senha = $senha1."senha_smo_criptografada";
    $senha = hash('sha256', $senha);


    $usuario->setSenha($senha);
    $alterou_senha = $usuarioDAO->update_pass($usuario);
    $troca_senha_padrao = $usuarioDAO->alterou_senha_padrao($usuario);

    if($alterou_senha && $troca_senha_padrao)
    {
        $alteracao = "Usuário ".$_SESSION['usuario_smo']." alterou a sua senha padrão";
        $insere_log = $logDAO->insertLog("3005", "log", $_SESSION['id_usuario_smo'] , "Login", $alteracao);
        $_SESSION['trocar_senha_smo'] = false;

        if ($usuario->getPerfil() == 'operador') 
        {
            header("Location: ../distribuidos_om_1_fase.php");
        }
        else {
            header("Location: ../obrigatorios.php");
        }
    }
    else
        erro($BASE_URL, 3, 236457856, $pagina_atual, "erro_senha_padrao", "Algo errado não deu certo!");
?>



