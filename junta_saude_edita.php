<?php
include_once 'header.php';
include_once 'dao/JuntaDAO.php';

if (!isset($_SESSION['id_usuario_smo'])) {
  erro($BASE_URL, 2, 63216754, $pagina_atual, "usuario_nao_logado", "Página não encontrada!");
  exit();
}
if (!isset($_SESSION['mensagem'])) $_SESSION['mensagem'] = null;

$juntasDAO = new JuntaDAO($conexao);
$todas_juntas = $juntasDAO->findAllAtivos();
(int)$id_junta_editavel = $_GET['id_junta'];

$junta_editavel = $juntasDAO->findById($id_junta_editavel);

$presidente =  $junta_editavel['presidente'];
$membro_1 =  $junta_editavel['membro_1'];
$membro_2 =  $junta_editavel['membro_2'];
$secao =  $junta_editavel['secao'];
$data =  $junta_editavel['data'];
$data_tratada = trata_data($data);
$cidade = $junta_editavel['cidade'];

?>

<main id="main">
  <section class="breadcrumbs">
    <div class="container">
      <div class="section-title" data-aos="fade-up">
        <h1><b>Edição de Junta de Saúde</b></h1>
      </div>
    </div>
  </section>
</main>

<?php if ($_SESSION['mensagem'] != null): ?>
  <center>
    <font color="green" size="2px"><?php echo $_SESSION['mensagem'];
                                    $_SESSION['mensagem'] = null; ?></font>
  </center>
<?php endif; ?>

<section id="contact" class="contact">
  <div class="container">
    <div class="row  justify-content-center">
      <div class="col-lg-12">

        <form action="controller/junta_saude_edita.php" method="post" role="form" class="card">

          <div class="row">
            <div class="col-md-4 form-group">
              <input type="text" class="form-control" name="presidente" placeholder="Presidente" value="<?php echo $presidente ?>" required>
            </div>

            <div class="col-md-4 form-group">
              <input type="text" class="form-control" name="membro_1" placeholder="Membro 1" value="<?php echo $membro_1 ?>" required>
            </div>

            <div class="col-md-4 form-group">
              <input type="text" class="form-control" name="membro_2" placeholder="Membro 2" value="<?php echo $membro_2 ?>" required>
            </div>

            <div class="col-md-4 form-group">
              <input type="text" class="form-control" name="secao" placeholder="Sessão" value="<?php echo $secao ?>" required>
            </div>

            <div class="col-md-4 form-group">
              <input type="text" class="form-control" name="data" placeholder="Data" value="<?php echo $data_tratada ?>" required>
            </div>

            <div class="col-md-4 form-group">
              <input type="text" class="form-control" name="cidade" placeholder="Cidade" value="<?php echo $cidade ?>" required>
            </div>

            <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave'] . "junta"); ?>" required>
            <input name="id_junta" hidden value="<?php echo $id_junta_editavel; ?>">

            <div class="text-center"><button type="submit">Concluir Edição</button></div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>


<section id="contact" class="contact">
  <div class="container">
    <table id="tabela_dinamica" class="display responsive nowrap" style="width:100%">
      <thead>
        <tr>
          <th>Presidente</th>
          <th>Membro 1</th>
          <th>Membro 2</th>
          <th>Sessão</th>
          <th>Data</th>
          <th>Cidade</th>
          <th>Editar</th>
          <th>Apagar</th>

        </tr>
      </thead>
      <tbody>
        <?php
        if ($todas_juntas)
          foreach ($todas_juntas as $junta) {
            $criptografia = hash('sha256', $junta['id'] . "criptografia");
            echo "  
                        <tr>
                            <td> " . $junta['presidente'] . " </td>
                            <td> " . $junta['membro_1'] . " </td>
                            <td> " . $junta['membro_2'] . " </td>
                            <td> " . $junta['secao'] . " </td>
                            <td> " . trata_data($junta['data']) . " </td>
                            <td> " . $junta['cidade'] . " </td>
                            <td> <a href ='junta_saude_edita.php?crip=$criptografia&id_junta=" . $junta['id'] . "'><center><i class='fas fa-edit'></i></center></a> </td>
                            <td> <a href ='controller/junta_apaga.php?crip=$criptografia&id_junta=" . $junta['id'] . "'><center><i class='fas fa-trash-alt'></i></center></a> </td>
                        </tr>
                          ";
          }
        ?>
      </tbody>
    </table>
  </div>
</section>

<script type="text/javascript">
  $(document).ready(function() {
    $('#tabela_dinamica').DataTable({
      "aaSorting": []
    });
  });
</script>


<?php
include_once 'footer.php';
?>