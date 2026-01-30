<?php
include_once 'header.php';
include 'dao/AuxiliarDAO.php';

if (!isset($_SESSION['id_usuario_smo'])) {
  erro($BASE_URL, 2, 63216754, $pagina_atual, "usuario_nao_logado", "Página não encontrada!");
  exit();
}
if (!isset($_SESSION['mensagem'])) $_SESSION['mensagem'] = null;

$especDAO = new AuxiliarDAO($conexao);
$todas_espec = $especDAO->findAllEspec();

?>

<main id="main">
  <section class="breadcrumbs">
    <div class="container">
      <div class="section-title" data-aos="fade-up">
        <h1><b><i class="fas fa-user-md"></i> Cadastro de Obrigatório</b></h1>
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
            <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Dados do Obrigatório</h5>
          </div>
          <div class="card-body">
            <form action="controller/obrigatorio_cadastra.php" method="post" role="form">

              <div class="row g-3">
                <div class="col-lg-3">
                  <label class="form-label fw-semibold">Nome Completo <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="nome_completo" placeholder="Nome Completo" required>
                </div>

                <div class="col-lg-3">
                  <label class="form-label fw-semibold">CPF <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" onclick="cursorCPF()" id="cpf" name="cpf" placeholder="000.000.000-00" required>
                </div>

                <div class="col-lg-3">
                  <label class="form-label fw-semibold">E-Mail</label>
                  <input type="text" class="form-control" name="mail" placeholder="email@exemplo.com">
                </div>

                <div class="col-lg-3">
                  <label class="form-label fw-semibold">Identidade</label>
                  <input type="text" class="form-control" name="identidade" placeholder="Nº Identidade">
                </div>

                <div class="col-md-3">
                  <label class="form-label fw-semibold">Prioridade das Forças</label>
                  <select name="prioridade_forca" class="form-select">
                    <option value="">Selecione...</option>
                    <option value="EMA">EMA</option>
                    <option value="EAM">EAM</option>
                    <option value="MAE">MAE</option>
                    <option value="MEA">MEA</option>
                    <option value="AEM">AEM</option>
                    <option value="AME">AME</option>
                  </select>
                </div>

                <div class="col-md-3">
                  <label class="form-label fw-semibold">Data de Nascimento</label>
                  <input type="text" class="form-control" onclick="cursorDataNascimento()" id='data_nascimento' name="data_nascimento" placeholder="DD/MM/AAAA">
                </div>

                <div class="col-md-3">
                  <label class="form-label fw-semibold">Nacionalidade</label>
                  <select name="nacionalidade" class="form-select">
                    <option value="">Selecione...</option>
                    <option value="BRASILEIRA">BRASILEIRA</option>
                    <option value="ESTRANGEIRA">ESTRANGEIRA</option>
                  </select>
                </div>

                <div class="col-md-3">
                  <label class="form-label fw-semibold">Nome do Pai</label>
                  <input type="text" class="form-control" name="nome_pai" placeholder="Nome completo do pai">
                </div>

                <div class="col-md-3">
                  <label class="form-label fw-semibold">Nome da Mãe</label>
                  <input type="text" class="form-control" name="nome_mae" placeholder="Nome completo da mãe">
                </div>

                <div class="col-md-3">
                  <label class="form-label fw-semibold">Endereço Completo</label>
                  <input type="text" class="form-control" name="endereco" placeholder="Rua, número, bairro, cidade">
                </div>

                <div class="col-md-3">
                  <label class="form-label fw-semibold">Telefone</label>
                  <input type="text" class="form-control" name="telefone" placeholder="(99) 99999-9999">
                </div>

                <div class="col-md-3">
                  <label class="form-label fw-semibold">Estado Civil</label>
                  <select name="estado_civil" class="form-select">
                    <option value="">Selecione...</option>
                    <option value="SOLTEIRO">SOLTEIRO</option>
                    <option value="CASADO">CASADO</option>
                    <option value="UNIÃO ESTÁVEL">UNIÃO ESTÁVEL</option>
                    <option value="DIVORCIADO">DIVORCIADO</option>
                    <option value="VIÚVO">VIÚVO</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-semibold">Dependentes</label>
                  <select class="form-select" name="dependentes">
                    <option value="">Selecione...</option>
                    <?php
                    for ($i = 0; $i <= 10; $i++) {
                      echo '<option value="' . $i . '">' . $i . '</option>';
                    }
                    ?>
                  </select>
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-semibold">Instituição de Ensino - Cidade</label>
                  <select class="form-select" name="nome_instituicao_ensino">
                    <option value="">Selecione...</option>
                    <option value="FURG - RIO GRANDE">FURG - RIO GRANDE</option>
                    <option value="UFPEL - PELOTAS">UFPEL - PELOTAS</option>
                    <option value="UCPEL - PELOTAS">UCPEL - PELOTAS</option>
                    <option value="UPF - PASSO FUNDO">UPF - PASSO FUNDO</option>
                    <option value="UFFS - PASSO FUNDO">UFFS - PASSO FUNDO</option>
                    <option value="ATITUS - PASSO FUNDO (IMED)">ATITUS - PASSO FUNDO (IMED)</option>
                    <option value="UCS - CAXIAS DO SUL">UCS - CAXIAS DO SUL</option>
                    <option value="UNIVATES - LAJEADO">UNIVATES - LAJEADO</option>
                    <option value="UFCSPA - PORTO ALEGRE">UFCSPA - PORTO ALEGRE</option>
                    <option value="ULBRA - CANOAS">ULBRA - CANOAS</option>
                    <option value="PUCRS - PORTO ALEGRE">PUCRS - PORTO ALEGRE</option>
                    <option value="UFRGS - PORTO ALEGRE">UFRGS - PORTO ALEGRE</option>
                    <option value="UNISINOS - SÃO LEOPOLDO">UNISINOS - SÃO LEOPOLDO</option>
                    <option value="UNIPAMPA - URUGUAIANA">UNIPAMPA - URUGUAIANA</option>
                    <option value="UFSM - SANTA MARIA">UFSM - SANTA MARIA</option>
                    <option value="UFN - SANTA MARIA">UFN - SANTA MARIA</option>
                    <option value="UNISC - SANTA CRUZ DO SUL">UNISC - SANTA CRUZ DO SUL</option>
                    <option value="URI - ERECHIM">URI - ERECHIM</option>
                    <option value="FEEVALE - NOVO HAMBURGO">FEEVALE - NOVO HAMBURGO</option>
                    <option value="UNIJUÍ - IJUÍ">UNIJUÍ - IJUÍ</option>
                    <option value="GRADUADO EM FACULDADE FORA RS">GRADUADO EM FACULDADE FORA RS</option>
                  </select>
                </div>

                <div class="col-md-4">
                  <label class="form-label fw-semibold">Documento Militar</label>
                  <select name="documento_militar" class="form-select">
                    <option value="">Selecione...</option>
                    <option value="Certificado de Isenção (CI)">Certificado de Isenção (CI)</option>
                    <option value="Certificado de Dispensa de Incorporação (CDI)">Certificado de Dispensa de Incorporação (CDI)</option>
                    <option value="Certificado de Alistamento Militar (CAM)">Certificado de Alistamento Militar (CAM)</option>
                    <option value="Certidão de Situação Militar (CSM)">Certidão de Situação Militar (CSM)</option>
                    <option value="Certificado de Reservista Militar 1ª Categoria (CRM)">Certificado de Reservista Militar 1ª Categoria (CRM)</option>
                    <option value="Certificado de Reservista Militar 2ª Categoria (CRM)">Certificado de Reservista Militar 2ª Categoria (CRM)</option>
                  </select>
                </div>

                <div class="col-md-4">
                  <label class="form-label fw-semibold">Formação</label>
                  <select class="form-select" name="formacao">
                    <option value="">Selecione...</option>
                    <option value="MÉDICO - Generalista">MÉDICO - Generalista</option>
                    <option value="DENTISTA - Cirurgião Dentista">DENTISTA - Cirurgião Dentista</option>
                  </select>
                </div>

                <div class="col-md-4">
                  <label class="form-label fw-semibold">Ano de Formação</label>
                  <select class="form-select" name="ano_formacao">
                    <option value="">Selecione...</option>
                    <?php
                    $ano_atual = date("Y");
                    for ($i = $ano_atual; $i >= $ano_atual - 10; $i--) {
                      echo '<option value="' . $i . '">' . $i . '</option>';
                    }
                    ?>
                  </select>
                </div>
              </div>

              <input name="crip" type="hidden" value="<?php echo hash('sha256', $_SESSION['chave'] . "obrigatorio"); ?>">

              <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                  <i class="fas fa-save me-2"></i>Cadastrar Obrigatório
                </button>
              </div>

            </form>
          </div>
        </div>
  </div>
</section>


<?php
include_once 'footer.php';
?>