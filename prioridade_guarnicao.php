<?php
    include_once 'header.php';
    include 'dao/AuxiliarDAO.php';
    include_once 'dao/ObrigatorioDAO.php';

    if(!isset($_SESSION['id_usuario_smo'])){ erro($BASE_URL, 2, 63216754, $pagina_atual, "usuario_nao_logado", "Página não encontrada!"); exit();}
    if(!isset($_SESSION['mensagem'])) $_SESSION['mensagem'] = null;


    $AuxiliarDAO = new AuxiliarDAO($conexao);
    $obrigatorioDAO = new ObrigatorioDAO($conexao);
    $todas_gu = $AuxiliarDAO->findAllGuarnicao();

    $id_obrigatorio = 25; 
    $prioridade_guarnicao = $obrigatorioDAO->findAllGuarnicaoPrioridade($id_obrigatorio);
 
?>

<main id="main">
    <section class="breadcrumbs" >
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h1><b>Prioridade de Guarnições</b></h1>
            </div>
        </div>
    </section>
</main>

<center><font color="green" size="4px"><?php echo $_SESSION['mensagem']; $_SESSION['mensagem'] = null; ?></font></center>

<!-- TABELA PARA ESCOLHER AS CIDADES -->

<section id="contact" class="contact">
      <div class="container">
        <div class="row  justify-content-center" >
            <div class="col-lg-12">
              
                <form action="controller/prioridade_guarnicao_cadastra.php" method="post" role="form" class="card">
                  <div class="row">
                  <div class="col-md-4 form-group">
                  
                        <b>1ª Prioridade</b>
                        <select id="combobox" name="id_guarnicao" class="form-control">
                            <option value="nomegu">Selecione a Opção</option>
                            <?php   
                                foreach ($todas_gu as $guarnicao) 
                                {
                                  $imprimegu = true;
                                  if($prioridade_guarnicao)
                                  foreach ($prioridade_guarnicao as $value) 
                                  {
                                    if ($value['id_guarnicao'] == $guarnicao['id']) 
                                    {
                                        $imprimegu = false;
                                        break; 
                                    }
                                  }
                                  if($imprimegu)
                                  echo "<option value='" . $guarnicao['id'] . "' >" . $guarnicao['nome'] . "</option>";
                                }
                            ?>
                        </select>
                        <input hidden id="nomegu" name="nomegu">
                        <script>
                              // Captura o evento de alteração da combobox
                              document.getElementById('combobox').addEventListener('change', function() {
                              // Obtém o valor e o texto da opção selecionada
                              var selectedOption = this.options[this.selectedIndex];
                              var optionValue = selectedOption.value;
                              var optionText = selectedOption.text;
                              // Exibe o valor selecionado na div
                              document.getElementById('nomegu').value = optionText;
                            });
                        </script>
                  </div>
                  </div>
                  <input hidden type="text" value="25" name="id_obrigatorio">
                <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave']."obrigatorio"); ?>">
                <div class="text-center"><button type="submit">Definir</button></div>
            </form>
          </div>
        </div>
      </div>
     
      <!--  LISTA DAS CIDADES PRIORIZADAS   -->

      <section id="contact" class="contact">
      <div class="container">
        <div class="row  justify-content-center" >
            <div class="col-lg-12">
                <form action="controller/prioridade_guarnicao_apaga.php" method="post" role="form" class="card" name="nomegu">
                  <div class="row">
                  <div class="col-md-4 form-group" >
                  <b>Prioridades: </b> 
                  </div>      
                  </div>  
                  
                  <div class="row">
                  <div class="col-md-4 form-group" >
                  <b>
                    <?php 
                    if($prioridade_guarnicao)
                    {
                      foreach ($prioridade_guarnicao as $value) 
                      {
                        echo $value['prioridade'] . "ª ";
                        echo $value['guarnicao'];
                        echo "<br>";
                        $nomegu = $value['guarnicao'];
                      }
                    }
                    else echo "Selecine sua Primeira Prioridade";
                    ?>
                  </b>  
                  </div>      
                  </div>  
                  <input hidden type="text" value="25" name="id_obrigatorio">
                  <div class="text-center"><button type="submit">Apagar</button></div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </section>



    

  
<?php 
  include_once 'footer.php';
?>