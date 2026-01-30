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

?>

<main id="main">
  <section class="breadcrumbs">
    <div class="container">
      <div class="section-title" data-aos="fade-up">
        <h1><b><i class="fa fa-stethoscope"></i> Cadastro de Junta de Saúde</b></h1>
      </div>
    </div>
  </section>
</main>

<?php if ($_SESSION['mensagem'] != null): ?>
  <div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
      <i class="fas fa-check-circle me-2"></i>
      <div><?php echo $_SESSION['mensagem']; $_SESSION['mensagem'] = null; ?></div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
  </div>
<?php endif; ?>

<section id="contact" class="contact">
  <div class="container">
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-light">
        <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Nova Junta de Saúde</h5>
      </div>
      <div class="card-body">
        <form action="controller/junta_saude_cadastra.php" method="post" role="form">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label fw-semibold">Presidente <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="presidente" placeholder="Nome do Presidente" required>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Membro 1 <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="membro_1" placeholder="Nome do Membro 1" required>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Membro 2 <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="membro_2" placeholder="Nome do Membro 2" required>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Sessão <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="secao" placeholder="Número da Sessão" required>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Data <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="data" placeholder="DD/MM/AAAA" required>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Cidade <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="cidade" placeholder="Nome da Cidade" required>
            </div>
          </div>

          <input name="crip" type="hidden" value="<?php echo hash('sha256', $_SESSION['chave'] . "junta"); ?>">

          <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
              <i class="fas fa-save me-2"></i>Cadastrar Junta
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<section id="contact" class="contact">
  <div class="container">
    <div class="card shadow-sm">
      <div class="card-header bg-light">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Juntas de Saúde Cadastradas</h5>
      </div>
      <div class="card-body p-0">
        <table id="tabela_dinamica" class="table table-hover mb-0" style="width:100%">
          <thead>
            <tr>
              <th>Presidente</th>
              <th>Membro 1</th>
              <th>Membro 2</th>
              <th>Sessão</th>
              <th>Data</th>
              <th>Cidade</th>
              <th class="text-center">Editar</th>
              <th class="text-center">Apagar</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($todas_juntas)
              foreach ($todas_juntas as $junta) {
                $criptografia = hash('sha256', $junta['id'] . "criptografia");
                echo "
                <tr>
                    <td>" . htmlspecialchars($junta['presidente']) . "</td>
                    <td>" . htmlspecialchars($junta['membro_1']) . "</td>
                    <td>" . htmlspecialchars($junta['membro_2']) . "</td>
                    <td>" . htmlspecialchars($junta['secao']) . "</td>
                    <td>" . trata_data($junta['data']) . "</td>
                    <td>" . htmlspecialchars($junta['cidade']) . "</td>
                    <td class='text-center'><a href='junta_saude_edita.php?crip=$criptografia&id_junta=" . $junta['id'] . "' class='btn btn-sm' style='color: #006400; border-color: #006400;' title='Editar'><i class='fas fa-edit'></i></a></td>
                    <td class='text-center'><a href='controller/junta_apaga.php?crip=$criptografia&id_junta=" . $junta['id'] . "' class='btn btn-sm' style='color: #006400; border-color: #006400;' title='Apagar' onclick=\"return confirm('Tem certeza que deseja apagar esta junta?')\"><i class='fas fa-trash-alt'></i></a></td>
                </tr>
                ";
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
  $(document).ready(function() {
    $('#tabela_dinamica').DataTable({
      "aaSorting": [],
      "responsive": true,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json"
      }
    });
  });
</script>
<?php
include_once 'footer.php';
?>