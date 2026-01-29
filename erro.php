<?php
include_once 'header.php';
?>

<section class="breadcrumbs">
  <div class="section-title container" data-aos="fade-up">
    <h1>Erro</h1>
  </div>
</section>
<section id="contact" class="contact">
  <div class="container">
    <center data-aos="fade-up">
      <img src="imagens/erro.png" height="50px">

      <h2 class="h4 mb-0">
        <font color="red"><?php if (isset($_SESSION['erro_retorno_usuario'])) echo $_SESSION['erro_retorno_usuario'] ?></font>
      </h2>
      <br>
      <?php echo "<font size='3'>Erro: ";
      if (isset($_SESSION['erro_codigo'])) echo $_SESSION['erro_codigo'] ?>
      <br>
      <br>
      <a href="javascript:history.back()"><button class="btn btn-secondary">VOLTAR</button></a>
      <a href="/smo/login.php"><button class="btn btn-primary">IR PARA LOGIN</button></a>
    </center>
  </div>
</section>

<?php
include_once 'footer.php';
?>