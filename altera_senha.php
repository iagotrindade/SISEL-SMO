<?php
    include_once 'header.php';

    include_once 'funcoes.php';

    include_once $BASE_URL.'/dao/conecta_banco.php';
    include_once $BASE_URL.'/models/Usuario.php';
    include_once $BASE_URL.'/dao/UsuarioDAO.php';

    if(isset($_SESSION['id_usuario_smo']))
    {
      $usuarioDAO = new UsuarioDAO($conexao);
      $usuario = $usuarioDAO->findById($_SESSION['id_usuario_smo']);
      if(!$usuario->getTrocarSenha())
          erro($BASE_URL, 3, 23465678, $pagina_atual, "trocar_senha==0", "Página não encontrada!");
    }
    else erro($BASE_URL, 3, 256347457, $pagina_atual, "SESSION['id_usuario_smo']==null", "Página não encontrada!");

?>

    <section class="breadcrumbs" >
        <div class="section-title" data-aos="fade-up">
          <h1><font color = "green">ALTERAR SENHA</font></h1>
        </div>
    </section>
    <section id="contact" class="contact">
      <div class="container">

      <center><font color="red"><b>A senha deve ter pelo menos </b>
          <br>
          8 caracteres
          <br>
          uma letra MAIÚSCULA
          <br>
          um número
          <br>
          um caractere especial
      </font></center>

        <div class="row mt-5 justify-content-center" data-aos="fade-up">
            <div class="col-lg-3">
                <form action="controller/altera_senha.php" method="post" role="form" class="card">
                <div class="row">
                    <div class="col-md-12 form-group">
                      <input type="password" class="form-control" name="senha1"  placeholder="NOVA SENHA" required>
                    </div>
                    <div class="col-md-12 form-group">
                      <input type="password" class="form-control" name="senha2"  placeholder="REPITA A SENHA" required>
                    </div>
                </div>
                    <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave']."nova_senha"); ?>">
                    <div class="text-center"><button type="submit">ENTRAR</button></div>
            </form>
          </div>
        </div>
      </div>
    </section>

  
<?php 
  include_once 'footer.php';
?>