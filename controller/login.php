<?php	
    include_once '../funcoes.php';

    session_start();

    include_once $BASE_URL.'/dao/conecta_banco.php';
    include_once $BASE_URL.'/models/Usuario.php';
    include_once $BASE_URL.'/dao/UsuarioDAO.php';
    include_once $BASE_URL.'/dao/LogDAO.php';

    $logDAO = new logDAO($conexao);
    $usuarioDAO = new UsuarioDAO($conexao);
    $data_atual = date("Y-m-d");

    if(!isset($_SESSION['chave']))  erro($BASE_URL, 2, 623572332, $pagina_atual, "!isset(SESSION['chave']", "Tente fazer o login novamente!");

    $usuario  = filtra_campo_post("usuario");
    $senha = filtra_campo_post("senha");
    $criptografia = filtra_campo_post ("crip");

    if($criptografia != hash('sha256', $_SESSION['chave']."criptografia"))  erro($BASE_URL, 2, 13623467247, $pagina_atual, "criptografia_invalida", "Tente fazer o login novamente!");
    if($usuario == null) erro($BASE_URL, 1, 3346474, $pagina_atual, "usuario_null", "O campo Usuário é obrigatório!");
    if($senha == null)   erro($BASE_URL, 1, 679672, $pagina_atual, "senha_null", "O campo Senha é obrigatório!");
   
    $ip = $_SERVER['REMOTE_ADDR'];
    $usuario_login = new Usuario($usuario);

    if($ip != null)
    {
        $quantidade_logins = $logDAO->quantidadeTentativasLogin($ip);  
        if($quantidade_logins && count($quantidade_logins) >= 25)
        {
            erro($BASE_URL, 2, 252323, $pagina_atual, "ip_bloqueado", "O limite de tentativas diárias foi atingido!");
            exit();
        }
    }
    else
        erro($BASE_URL, 3, 45685678, $pagina_atual, "ip==null", "Não foi possível fazer o seu login! <br>Tente em outro dispositivo!");


    $senha = $senha."senha_smo_criptografada";
    $senha = hash('sha256', $senha);

    $usuario_login = $usuarioDAO->findByLogin($usuario, $senha);  


    if($usuario_login) 
    {
        if($usuario_login->getValidade() != null && ($data_atual > $usuario_login->getValidade())) erro($BASE_URL, 1, 235464576, $pagina_atual, "senha_null", "Seu usuário expirou");


        $_SESSION['id_usuario_smo'] = $usuario_login->getId();
        $_SESSION['usuario_smo'] = $usuario_login->getUsuario();
        $_SESSION['perfil_smo'] = $usuario_login->getPerfil();
        $_SESSION['trocar_senha_smo'] = $usuario_login->getTrocarSenha();
        $_SESSION['posto_grad_smo'] = $usuario_login->getPostoGrad();
        $_SESSION['nome_guerra_smo'] = $usuario_login->getNomeGuerra();
        $_SESSION['id_om_smo'] = $usuario_login->getIdOm();
        $_SESSION['rm_smo'] = $usuario_login->getRm();

        $alteracao = "Usuário ".$usuario_login->getUsuario()." fez login";
        $insere_log = $logDAO->insertLog("9001", "log", $usuario_login->getId() , "Login", "Usuário " . $usuario_login->getUsuario() . " fez Login");

        if($usuario_login->getTrocarSenha())
        {
            header("Location: ../altera_senha.php");
            exit();
        }

        if ($usuario_login->getPerfil() == 'operador') 
        {
            header("Location: ../distribuidos_om_1_fase.php");
        }
        else {
            header("Location: ../index.php");
        }
      
    }
    else
    {
        $insere_tentativa = $usuarioDAO->tentativa_login($usuario);
        erro($BASE_URL, 1, 2364234457, $pagina_atual, "login_senha_invalidos", "Usuário ou senha inválida");
    }
?>



