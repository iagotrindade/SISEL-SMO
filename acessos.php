<?php
include_once 'header.php';
include_once $BASE_URL . '/dao/AcessoDAO.php';

if (!isset($_SESSION['id_usuario_smo'])) {
    erro($BASE_URL, 2, 24586666, $pagina_atual, "usuario_nao_logado", "Página não encontrada!");
    exit();
}
if ($_SESSION['perfil_smo'] != 'admin') {
    erro($BASE_URL, 2, 23734765, $pagina_atual, "usuario_nao_logado", "Página não encontrada!");
    exit();
}

// Limite
$limite = 1000;
if (isset($_GET['limite'])) $limite = (int)filtra_campo_get('limite');
if ($limite > 10000) $limite = 10000;

$acessoDAO = new acessoDAO($conexao);
$todos_acessos = $acessoDAO->findAll($limite);
?>

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h1><b><i class="bi bi-clipboard-data"></i> Acessos</b></h1>
            </div>
        </div>
    </section>
</main>

<section id="contact" class="contact">
    <div class="container">

        <form class="mb-4" name="fomulario" action="<?php $_SERVER['PHP_SELF'] ?>" method="get">
            <div class="row">
                <div class="col-9">
                    <select onchange="fomulario.submit()" name="limite" class="form-control">
                        <option <?php if ($limite == 1000) echo "selected" ?> value="1000">Últimos 1000 Registros</option>
                        <option <?php if ($limite == 3000) echo "selected" ?> value="3000">Últimos 3000 Registros</option>
                        <option <?php if ($limite == 5000) echo "selected" ?> value="5000">Últimos 5000 Registros</option>
                        <option <?php if ($limite == 10000) echo "selected" ?> value="10000">Últimos 10000 Registros</option>
                    </select>
                </div>
                <div class="col-3">
                    <button type="submit" style="width:100%" class="btn btn-primary btn-block">PESQUISAR <i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <table id="tabela_dinamica" style="font-size: 14px" class="display responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Página</th>
                    <th>Data</th>
                    <th>Sistema</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>

                <?php
                if ($todos_acessos) {
                    foreach ($todos_acessos as $linha) {
                        $data = null;
                        if ($linha['data'] != null)
                            $data = trata_data_hora($linha['data']);

                        echo '
                            <tr>
                            <td>' . $linha['usuario'] . '</td>
                            <td>' . $linha['pagina'] . '</td>
                            <td>' . $data . '</td>
                            <td>' . $linha['sistema'] . '</td>
                            <td>' . $linha['ip'] . '</td>
                            </tr>';
                    }
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