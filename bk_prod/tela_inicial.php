<?php
    include_once 'header.php';
    include_once 'dao/conecta_banco.php';
    include_once 'models/Usuario.php';
    include_once 'dao/UsuarioDAO.php';

    if(!isset($_SESSION['id_usuario_smo'])){ erro($BASE_URL, 2, 236325634, $pagina_atual, "usuario_nao_logado", "Página não encontrada!"); exit();}
    if(!isset($_SESSION['mensagem'])) $_SESSION['mensagem'] = null;
    
?>

<main id="main">
    <section class="breadcrumbs" >
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h1><b>Tela Inicial</b></h1>
            </div>
        </div>
    </section>
</main>

<br>
<center><b> <?php echo saudacoes() . " " . $_SESSION['posto_grad_smo'] . " " . $_SESSION['nome_guerra_smo'] ?> </b></center>
<br>

<section id="services" class="services section-bg">
      <div class="container">

        <div class="row">
          <div class="col-md-4" data-aos="fade-up">
            <div class="icon-box icon-box-pink">
              <div class="icon"><i class="bx bxl-dribbble"></i></div>
              <h4 class="title"><a href="">Obrigatórios disponíveis</a></h4>
              <p class="description"><?php  echo "144"?></p>
            </div>
          </div>
          <div class="col-md-4" data-aos="fade-up">
            <div class="icon-box icon-box-pink">
              <div class="icon"><i class="bx bxl-dribbble"></i></div>
              <h4 class="title"><a href="">Histórico</a></h4>
              <p class="description"><?php  echo "144"?></p>
            </div>
          </div>
          <div class="col-md-4" data-aos="fade-up">
            <div class="icon-box icon-box-pink">
              <div class="icon"><i class="bx bxl-dribbble"></i></div>
              <h4 class="title"><a href="">Outras informações</a></h4>
              <p class="description"><?php  echo "144"?></p>
            </div>
          </div>

         
        </div>

      </div>
    </section>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>


<center><font color="green" size="6px"><?php echo $_SESSION['mensagem']; $_SESSION['mensagem'] = null; ?></font></center>





<script type="text/javascript">
    $(document).ready(function() {
    $('#tabela_dinamica').DataTable();
} );
</script>

  
<?php 
  include_once 'footer.php';
?>