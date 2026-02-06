<?php
include_once 'header.php';
include_once 'dao/conecta_banco.php';
include_once 'models/Graduacao.php';
include_once 'dao/GraduacaoDAO.php';

$graduacaoDAO = new GraduacaoDAO($conexao);
$todas_graduacoes = $graduacaoDAO->findAllAtivos();

if (!isset($_SESSION['id_usuario_smo'])) {
    erro($BASE_URL, 2, 27899758, $pagina_atual, "usuario_nao_logado", "Página não encontrada!");
    exit();
}
if ($_SESSION['perfil_smo'] != "admin") {
    erro($BASE_URL, 2, 643197827195, $pagina_atual, "perfil!=admin", "Sem permissão!");
    exit();
}
if (!isset($_SESSION['mensagem'])) $_SESSION['mensagem'] = null;

$crip = null;
$tela_editar = false;
$destino = "graduacao_cadastra.php";
$botao = "Cadastrar";
$graduacao_edita = null;
$id_graduacao_edita = null;

if (isset($_GET['crip']) && (isset($_GET['id']))) {
    $crip = filtra_campo_get('crip');
    $id = (int)filtra_campo_get('id');
    if ($crip == hash('sha256', $id . "criptografia"))
        $graduacao_edita = $graduacaoDAO->findById($id);
    if ($graduacao_edita) {
        $id_graduacao_edita = $id;
        $tela_editar = true;
        $botao = "Atualizar";
        $destino = "graduacao_edita.php";
    }
}
?>

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h1><i class="fas fa-graduation-cap"></i> Graduações</h1>
            </div>
        </div>
    </section>
</main>

<?php if ($_SESSION['mensagem']): ?>
    <div class="container mt-4">
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
                <h5 class="mb-0">
                    <i class="fas fa-<?php echo $tela_editar ? 'edit' : 'plus'; ?> me-2"></i>
                    <?php echo $tela_editar ? 'Editar Graduação' : 'Cadastrar Nova Graduação'; ?>
                </h5>
            </div>
            <div class="card-body">
                <form action="controller/<?php echo $destino ?>" method="post" role="form">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">RM</label>
                            <select name="rm" class="form-control">
                                <option value="">Selecione a RM (opcional)</option>
                                <option <?php if ($graduacao_edita && $graduacao_edita->getRm() == 1) echo " selected "; ?> value="1">1ª RM</option>
                                <option <?php if ($graduacao_edita && $graduacao_edita->getRm() == 2) echo " selected "; ?> value="2">2ª RM</option>
                                <option <?php if ($graduacao_edita && $graduacao_edita->getRm() == 3) echo " selected "; ?> value="3">3ª RM</option>
                                <option <?php if ($graduacao_edita && $graduacao_edita->getRm() == 4) echo " selected "; ?> value="4">4ª RM</option>
                                <option <?php if ($graduacao_edita && $graduacao_edita->getRm() == 5) echo " selected "; ?> value="5">5ª RM</option>
                                <option <?php if ($graduacao_edita && $graduacao_edita->getRm() == 6) echo " selected "; ?> value="6">6ª RM</option>
                                <option <?php if ($graduacao_edita && $graduacao_edita->getRm() == 7) echo " selected "; ?> value="7">7ª RM</option>
                                <option <?php if ($graduacao_edita && $graduacao_edita->getRm() == 8) echo " selected "; ?> value="8">8ª RM</option>
                                <option <?php if ($graduacao_edita && $graduacao_edita->getRm() == 9) echo " selected "; ?> value="9">9ª RM</option>
                                <option <?php if ($graduacao_edita && $graduacao_edita->getRm() == 10) echo " selected "; ?> value="10">10ª RM</option>
                                <option <?php if ($graduacao_edita && $graduacao_edita->getRm() == 11) echo " selected "; ?> value="11">11ª RM</option>
                                <option <?php if ($graduacao_edita && $graduacao_edita->getRm() == 12) echo " selected "; ?> value="12">12ª RM</option>
                            </select>
                        </div>

                        <div class="col-md-9">
                            <label class="form-label fw-semibold">Nome da Graduação</label>
                            <input type="text" class="form-control" name="nome" value="<?php if ($graduacao_edita) echo $graduacao_edita->getNome(); ?>" placeholder="Nome da Graduação" required>
                        </div>
                    </div>

                    <input name="crip" hidden value="<?php echo hash('sha256', $_SESSION['chave'] . "graduacao"); ?>">
                    <input name="id_graduacao_edita" hidden value="<?php echo $id_graduacao_edita ?>">

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-<?php echo $tela_editar ? 'save' : 'plus'; ?> me-2"></i><?php echo $botao ?>
                        </button>
                        <?php if ($tela_editar): ?>
                        <a href="graduacoes.php" class="btn btn-secondary ms-2">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section id="contact" class="contact">
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-light mb-4">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Graduações Cadastradas
                </h5>
            </div>
            <div class="card-body p-0">
                <table id="tabela_dinamica" class="table table-hover mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>RM</th>
                            <th>Nome</th>
                            <th class="text-center">Editar</th>
                            <th class="text-center">Apagar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($todas_graduacoes)
                            foreach ($todas_graduacoes as $graduacao) {
                                $criptografia = hash('sha256', $graduacao->getId() . "criptografia");
                                $rm_display = $graduacao->getRm() ? $graduacao->getRm() . "ª RM" : "-";
                                echo "
                                <tr>
                                    <td>" . htmlspecialchars($graduacao->getId()) . "</td>
                                    <td>" . htmlspecialchars($rm_display) . "</td>
                                    <td>" . htmlspecialchars($graduacao->getNome()) . "</td>
                                    <td class='text-center'><a href='" . $_SERVER['PHP_SELF'] . "?crip=$criptografia&id=" . $graduacao->getId() . "' class='btn btn-sm' style='color: #006400; border-color: #006400;' title='Editar'><i class='fas fa-edit'></i></a></td>
                                    <td class='text-center'><a href='controller/graduacao_apaga.php?crip=$criptografia&id_graduacao=" . $graduacao->getId() . "' class='btn btn-sm' style='color: #006400; border-color: #006400;' title='Apagar'><i class='fas fa-trash-alt'></i></a></td>
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
