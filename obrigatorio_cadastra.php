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
        <h1><b><i class="bi bi-person-plus-fill"></i> Cadastro de Obrigatório</b></h1>
      </div>
    </div>
  </section>
</main>

<?php if ($_SESSION['mensagem'] != null): ?>
  <center>
    <font color="green" size="6px"><?php echo $_SESSION['mensagem'];
                                    $_SESSION['mensagem'] = null; ?></font>
  </center>
<?php endif; ?>

<section id="contact" class="contact">
  <div class="container">
    <div class="row  justify-content-center">
      <div class="">
        
        <form action="controller/obrigatorio_cadastra.php" method="post" role="form" class="card">

          <div class="row">
            <div class="col-lg-3 form-group">
              <input type="text" class="form-control" name="nome_completo" placeholder="Nome Completo (OBRIGATÓRIO)" required>
            </div>

            <div class="col-lg-3 form-group">
              <input type="text" class="form-control" onclick="cursorCPF()" id="cpf" name="cpf" placeholder="CPF (OBRIGATÓRIO)" required>
            </div>

            <div class="col-lg-3 form-group">
              <input type="text" class="form-control" name="mail" placeholder="E-Mail">
            </div>

            <div class="col-lg-3 form-group">
              <input type="text" class="form-control" name="identidade" placeholder="Identidade">
            </div>

            <div class="col-md-3 form-group">
              <select name="prioridade_forca" class="form-control">
                <option value="">Prioridade das Forças</option>
                <option value="EMA">EMA</option>
                <option value="EAM">EAM</option>
                <option value="MAE">MAE</option>
                <option value="MEA">MEA</option>
                <option value="AEM">AEM</option>
                <option value="AME">AME</option>
              </select>
            </div>

            <div class="col-md-3 form-group">
              <input type="text" class="form-control" onclick="cursorDataNascimento()" id='data_nascimento' name="data_nascimento" placeholder="Data de nascimento">
            </div>

            <div class="col-md-3 form-group">

              <select name="nacionalidade" class="form-control">
                <option value="">Nacionalidade</option>
                <option value="BRASILEIRA">BRASILEIRA</option>
                <option value="ESTRANGEIRA">ESTRANGEIRA</option>
              </select>
            </div>

            <div class="col-md-3 form-group">
              <input type="text" class="form-control" name="nome_pai" placeholder="Nome do Pai">
            </div>

            <div class="col-md-3 form-group">
              <input type="text" class="form-control" name="nome_mae" placeholder="Nome da Mãe">
            </div>

            <div class="col-md-3 form-group">
              <input type="text" class="form-control" name="endereco" placeholder="Endereço Completo">
            </div>

            <div class="col-md-3 form-group">
              <input type="text" class="form-control" name="telefone" placeholder="(99) 99999-9999">
            </div>

            <div class="col-md-3 form-group">
              <select name="estado_civil" class="form-control">
                <option value="">Estado Civil</option>
                <option value="SOLTEIRO">SOLTEIRO</option>
                <option value="CASADO">CASADO</option>
                <option value="UNIÃO ESTÁVEL">UNIÃO ESTÁVEL</option>
                <option value="DIVORCIADO">DIVORCIADO</option>
                <option value="VIÚVO">VIÚVO</option>
              </select>
            </div>

            <div class="col-md-6 form-group">
              <select class="form-control" name="dependentes">
                <option value="">Dependentes</option>
                <?php
                for ($i = 0; $i <= 10; $i++) {
                  echo '<option value="' . $i . '">' . $i . '</option>';
                }
                ?>
              </select>
            </div>

            <div class="col-md-6 form-group">
              <select class="form-control" name="nome_instituicao_ensino" placeholder="Nome da Instituição de Ensino - Graduação">
                <option value="">Nome IE - Cidade</option>
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

            <div class="col-md-4 form-group">
              <select name="documento_militar" class="form-control">
                <option value="">Documento Militar</option>
                <option value="Certificado de Isenção (CI)">Certificado de Isenção (CI)</option>
                <option value="Certificado de Dispensa de Incorporação (CDI)">Certificado de Dispensa de Incorporação (CDI)</option>
                <option value="Certificado de Alistamento Militar (CAM)">Certificado de Alistamento Militar (CAM)</option>
                <option value="Certidão de Situação Militar (CSM)">Certidão de Situação Militar (CSM)</option>
                <option value="Certificado de Reservista Militar 1ª Categoria (CRM)">Certificado de Reservista Militar 1ª Categoria (CRM)</option>
                <option value="Certificado de Reservista Militar 2ª Categoria (CRM)">Certificado de Reservista Militar 2ª Categoria (CRM)</option>
              </select>
            </div>


            <div class="col-md-4 form-group">
              <select class="form-control" name="formacao" placeholder="Formação">
                <option value="">Selecione a Formação</option>
                <option value="MÉDICO - Generalista">MÉDICO - Generalista</option>
                <option value="DENTISTA - Cirurgião Dentista">DENTISTA - Cirurgião Dentista</option>

              </select>
            </div>

            <div class="col-md-4 form-group">
              <select class="form-control" name="ano_formacao">
                <option value="">Ano de Formação</option>
                <?php
                $ano_atual = date("Y");
                for ($i = $ano_atual; $i >= $ano_atual - 10; $i--) {
                  echo '<option value="' . $i . '">' . $i . '</option>';
                }
                ?>
            </div>
            </select>
          </div>




      </div>

      <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave'] . "obrigatorio"); ?>">
      <div class="text-center"><button type="submit">Cadastrar</button></div>

      </form>
    </div>
  </div>
  </div>
</section>


<?php
include_once 'footer.php';
?>