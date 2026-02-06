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
    <div class="text-center" data-aos="fade-up">
      <img src="imagens/erro.png" height="50px" alt="Erro" class="mb-3">

      <h2 class="h4 mb-3 text-danger">
        <?php if (isset($_SESSION['erro_retorno_usuario'])) echo $_SESSION['erro_retorno_usuario'] ?>
      </h2>

      <p class="text-muted mb-4">
        <?php echo "Erro: ";
        if (isset($_SESSION['erro_codigo'])) echo $_SESSION['erro_codigo'] ?>
      </p>

<?php
      $pagina_retorno = isset($_SESSION['erro_pagina_retorno']) ? $_SESSION['erro_pagina_retorno'] : '/smo/login.php';
      unset($_SESSION['erro_pagina_retorno']);
      ?>
      <a href="<?php echo $pagina_retorno; ?>" class="btn btn-secondary me-2">VOLTAR</a>
      <a href="/smo/login.php" class="btn btn-primary">IR PARA LOGIN</a>
    </div>
  </div>
</section>

<?php
include_once 'footer.php';
?>