<?php
    include_once 'header.php';
    include_once 'dao/conecta_banco.php';
    include_once 'models/Obrigatorio.php';
    include_once 'models/Om.php';
    include_once 'models/Arquivo.php';
    include_once 'models/Oficio.php';
    include_once 'dao/ObrigatorioDAO.php';
    include_once 'dao/ArquivoDAO.php';
    include_once 'dao/AuxiliarDAO.php';
    include_once 'dao/OficioDAO.php';

    if(!isset($_SESSION['id_usuario_smo'])){ erro($BASE_URL, 2, 63216754, $pagina_atual, "usuario_nao_logado", "Página não encontrada!"); exit();}
?>

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h1><b>Relatório de comparecimento na JISE/JISR</b></h1>
            </div>
        </div>
    </section>
</main>

<section id="contact" class="contact">
      <div class="container">
        <div class="row  justify-content-center" >
            <div class="col-lg-12">

            <form action="mpdf/relatorio_determinada_data.php" method="POST" target="_blank" role="form" class="card">
                <center> <b><font size="5" color="green">RELATÓRIO JISE/JISR</font></b></center>
                <br>
                <div class="row">  
                    <div class="col-md-4 form-group"></div>
                    <div class="col-md-4 form-group">
                        <b>Data</b>
                        <input type="text" class="form-control" name="data_sel_geral">
                    </div>
                    <div class="col-md-4 form-group"></div>
                </div>
                <div class="text-center">
                    <br>
                    <button type="submit" style="width: 100%;" class="btn btn-primary btn-block">GERAR RELATÓRIO JISE/JISR</button>
                </div>                      
                <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave']."criptografia"); ?>">
            </form>
          </div>
        </div>
      </div>
    </section>
  
<?php 
  include_once 'footer.php';
?>