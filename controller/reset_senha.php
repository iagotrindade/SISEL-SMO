<?php	
    include_once '../funcoes.php';

    session_start();

    include_once $BASE_URL.'/dao/conecta_banco.php';
    include_once $BASE_URL.'/models/Usuario.php';
    include_once $BASE_URL.'/dao/UsuarioDAO.php';
    include_once $BASE_URL.'/dao/LogDAO.php';

    $logDAO = new logDAO($conexao);
    $usuarioDAO = new UsuarioDAO($conexao);

    if(!isset($_SESSION['chave']))  
        erro($BASE_URL, 2, 623572332, $pagina_atual, "!isset(SESSION['chave']", "Tente fazer o reset novamente!");

    $email = filtra_campo_post("email");
    $cpf = filtra_campo_post("cpf");
    $criptografia = filtra_campo_post("crip");

    if($criptografia != hash('sha256', $_SESSION['chave']."criptografia"))  
        erro($BASE_URL, 2, 13623467247, $pagina_atual, "criptografia_invalida", "Tente fazer o reset novamente!");
    
    if($email == null) 
        erro($BASE_URL, 1, 3346474, $pagina_atual, "email_null", "O campo E-mail é obrigatório!");
    
    if($cpf == null) 
        erro($BASE_URL, 1, 3346475, $pagina_atual, "cpf_null", "O campo CPF é obrigatório!");

    // Remove caracteres não numéricos do CPF
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    // Validação básica do CPF (11 dígitos)
    if(strlen($cpf) != 11) 
        erro($BASE_URL, 1, 3346476, $pagina_atual, "cpf_invalido", "CPF deve conter 11 dígitos!");

    // Converte email para maiúsculo para consistência
    $email = strtoupper($email);

    // Busca o usuário pelo email e CPF
    $usuario_encontrado = $usuarioDAO->findByEmailCPF($email, $cpf);

    if(!$usuario_encontrado) 
    {
        erro($BASE_URL, 1, 7234567, $pagina_atual, "dados_nao_encontrados", "E-mail ou CPF não conferem com nossos registros!");
    }

    // Define a nova senha padrão: 123@smo
    $senha_padrao = "123@smo";
    $senha_criptografada = $senha_padrao . "senha_smo_criptografada";
    $senha_criptografada = hash('sha256', $senha_criptografada);

    // Reseta a senha do usuário
    $usuario_encontrado->setSenha($senha_criptografada);
    
    // Cria uma sessão temporária para o update_pass
    $id_temp = $_SESSION['id_usuario_smo'] ?? 0;
    $_SESSION['id_usuario_smo'] = $usuario_encontrado->getId();
    
    $resultado_update = $usuarioDAO->update_pass($usuario_encontrado);
    
    // Restaura a sessão anterior
    if($id_temp > 0) {
        $_SESSION['id_usuario_smo'] = $id_temp;
    } else {
        unset($_SESSION['id_usuario_smo']);
    }

    if(!$resultado_update)
    {
        erro($BASE_URL, 2, 8234568, $pagina_atual, "erro_update_senha", "Erro ao resetar a senha. Tente novamente!");
    }

    // Força o usuário a trocar a senha no próximo login
    $resultado_trocar = $usuarioDAO->forcar_troca_senha($usuario_encontrado);

    if(!$resultado_trocar)
    {
        erro($BASE_URL, 2, 9234569, $pagina_atual, "erro_trocar_senha", "Senha resetada, mas erro ao definir flag de troca!");
    }

    // Registra no log com sucesso (usando o ID do usuário resetado temporariamente)
    $_SESSION['id_usuario_smo'] = $usuario_encontrado->getId();
    
    $ip = $_SERVER['REMOTE_ADDR'];
    $insere_log = $logDAO->insertLog(
        "9002", 
        "reset_senha", 
        $usuario_encontrado->getId(), 
        "Reset de Senha", 
        "Senha do usuário " . $usuario_encontrado->getUsuario() . " (" . $usuario_encontrado->getNomeGuerra() . ") foi resetada via tela de login (IP: " . $ip . ")"
    );
    
    // Remove a sessão temporária
    unset($_SESSION['id_usuario_smo']);

    // Define mensagem de sucesso na sessão
    $_SESSION['mensagem_sucesso'] = "Senha resetada com sucesso!";
    $_SESSION['mensagem_detalhes'] = "Sua nova senha é: <strong>123@smo</strong><br>Você será obrigado a alterá-la no primeiro acesso.";
    $_SESSION['usuario_resetado'] = $usuario_encontrado->getUsuario();
    $_SESSION['nome_resetado'] = $usuario_encontrado->getNomeGuerra();

    // Redireciona para o login
    header("Location: ../login.php");
    exit();
?>
