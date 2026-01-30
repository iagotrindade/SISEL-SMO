<?php
include_once 'header.php';

if (!isset($_SESSION['id_usuario_smo'])) {
    erro($BASE_URL, 2, 23574575, $pagina_atual, "usuario_nao_logado", "Página não encontrada!");
    exit();
}
if ($_SESSION['perfil_smo'] != 'admin') {
    erro($BASE_URL, 2, 47345645, $pagina_atual, "perfil!=admin", "Página não encontrada!");
    exit();
}

// Limite
$limite = 1000;
if (isset($_GET['limite'])) $limite = (int)filtra_campo_get('limite');
if ($limite > 10000) $limite = 10000;

// Usuário
$usuario_pesquisa = null;
if (isset($_GET['usuario'])) $usuario_pesquisa = filtra_campo_get('usuario');
if ($usuario_pesquisa == '') $usuario_pesquisa = null;

// Código
$codigo_pesquisa = null;
if (isset($_GET['codigo'])) $codigo_pesquisa = (int)filtra_campo_get('codigo');
if ($codigo_pesquisa == 0) $codigo_pesquisa = null;

$logDAO = new logDAO($conexao);


if ($codigo_pesquisa != null || $usuario_pesquisa != null)
    $logs_pesquisa = $logDAO->findAll();
else
    $logs_pesquisa = $logDAO->findAllLimite($limite);
?>

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h1><b><i class="fas fa-user-shield"></i> Auditoria</b></h1>
            </div>
        </div>
    </section>
</main>

<section id="contact" class="contact">
    <div class="container">

        <form name="fomulario" action="auditoria.php" method="get">
            <div class="row">
                <div class="col-3">
                    <select onchange="fomulario.submit()" name="limite" class="form-control">
                        <option <?php if ($limite == 1000) echo "selected" ?> value="1000">Últimos 1000 Registros</option>
                        <option <?php if ($limite == 3000) echo "selected" ?> value="3000">Últimos 3000 Registros</option>
                        <option <?php if ($limite == 5000) echo "selected" ?> value="5000">Últimos 5000 Registros</option>
                        <option <?php if ($limite == 10000) echo "selected" ?> value="10000">Últimos 10000 Registros</option>
                    </select>
                </div>
                <div class="col-3">
                    <input name="codigo" maxlength="7" placeholder="Código" value="<?php echo $codigo_pesquisa ?>" class="form-control">
                </div>
                <div class="col-3">
                    <input name="usuario" maxlength="50" placeholder="Usuário" value="<?php echo $usuario_pesquisa ?>" class="form-control">
                </div>
                <div class="col-3">
                    <button type="submit" style="width:100%" class="btn btn-primary btn-block">PESQUISAR <i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <br>
        <hr>
        <br>

        <table id="tabela_dinamica" class="display responsive nowrap" style="width:100%">
            <thead>
                <tr>
                <tr>
                    <th>Usuário</th>
                    <th>Alteração</th>
                    <th>Data</th>
                    <th>COD</th>
                    <th>Sistema</th>
                    <th>IP</th>
                </tr>
                </tr>
            </thead>
            <tbody>

                <?php
                if ($logs_pesquisa) {
                    foreach ($logs_pesquisa as $linha) {
                        $data_log = null;
                        if ($linha['data'] != null)
                            $data_log = trata_data_hora($linha['data']);

                        if ($codigo_pesquisa != null && $codigo_pesquisa != $linha['codigo']) continue;
                        if ($usuario_pesquisa != null && $usuario_pesquisa != $linha['usuario']) continue;

                        echo '
                            <tr>
                            <td>' . $linha['usuario'] . '</td>
                            <td>' . $linha['alteracao'] . '</td>
                            <td>' . $data_log . '</td>
                            <td>' . $linha['codigo'] . '</td>
                            <td>' . $linha['sistema'] . '</td>
                            <td>' . $linha['ip'] . '</td>
                            </tr>';
                    }
                }
                ?>
            </tbody>
        </table>


        <header class="text-center mb-3">
            <br>
            <h1>Tabela de Códigos</h1>
        </header>

        <table id="tabela_dinamica2" class="display responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Operação</th>
                    <th>Detalhamento</th>
                    <th>Código</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>LOGIN</td>
                    <td>Usuário fez LOGIN</td>
                    <td>9001</td>
                </tr>
                <tr>
                    <td>LOGOUT</td>
                    <td>Usuário fez LOGOUT</td>
                    <td>9002</td>
                </tr>

                <!-- INSERT -->
                <tr>
                    <td>INSERT</td>
                    <td>Cadastrou um usuário</td>
                    <td>1001</td>
                </tr>
                <tr>
                    <td>INSERT</td>
                    <td>Inseriu um arquivo para o obrigatório</td>
                    <td>1002</td>
                </tr>

                <tr>
                    <td>INSERT</td>
                    <td>Cadastrou uma Junta de Saúde</td>
                    <td>1003</td>
                </tr>

                <tr>
                    <td>INSERT</td>
                    <td>Cadastrou um Obrigatório</td>
                    <td>1004</td>
                </tr>

                <tr>
                    <td>INSERT</td>
                    <td>Cadastrou um Ofício</td>
                    <td>1005</td>
                </tr>
                <tr>
                    <td>INSERT</td>
                    <td>Definiu uma Gu Prioridade</td>
                    <td>1006</td>
                </tr>


                <!-- DELETE -->
                <tr>
                    <td>DELETE</td>
                    <td>Apagou um Usuário</td>
                    <td>2001</td>
                </tr>
                <tr>
                    <td>DELETE</td>
                    <td>Apagou um arquivo</td>
                    <td>2002</td>
                </tr>
                <tr>
                    <td>DELETE</td>
                    <td>Apagou um obrigatório</td>
                    <td>2003</td>
                </tr>
                <tr>
                    <td>DELETE</td>
                    <td>Apagou uma Junta</td>
                    <td>2004</td>
                </tr>
                <tr>
                    <td>DELETE</td>
                    <td>Apagou a lista de prioridades</td>
                    <td>2005</td>
                </tr>

                <!-- UPDATE -->
                <tr>
                    <td>UPDATE</td>
                    <td>Atualizou um Usuário</td>
                    <td>3001</td>
                </tr>
                <tr>
                    <td>UPDATE</td>
                    <td>Atualizou a senha de um Usuário</td>
                    <td>3002</td>
                </tr>
                <tr>
                    <td>UPDATE</td>
                    <td>Atualizou um obrigatório</td>
                    <td>3003</td>
                </tr>
                <tr>
                    <td>UPDATE</td>
                    <td>Atualizou um ofício</td>
                    <td>3004</td>
                </tr>
                <tr>
                    <td>UPDATE</td>
                    <td>Alterou a sua senha padrão</td>
                    <td>3005</td>
                </tr>
                <tr>
                    <td>UPDATE</td>
                    <td>Atualizou o obrigatório pela pré distribuição</td>
                    <td>3006</td>
                </tr>

                <tr>
                    <td>UPDATE</td>
                    <td>Atualizou a Identificação do Obrigatório</td>
                    <td>3007</td>
                </tr>

                <tr>
                    <td>UPDATE</td>
                    <td>Atualizou o FISEMI do Obrigatório</td>
                    <td>3008</td>
                </tr>
                <tr>
                    <td>UPDATE</td>
                    <td>Atualizou a Justiça do Obrigatório</td>
                    <td>3009</td>
                </tr>
                <tr>
                    <td>UPDATE</td>
                    <td>Atualizou o Adiamento do Obrigatório</td>
                    <td>3010</td>
                </tr>
                <tr>
                    <td>UPDATE</td>
                    <td>Atualizou a Distribuição do Obrigatório</td>
                    <td>3011</td>
                </tr>

                <tr>
                    <td>UPDATE</td>
                    <td>Editou a Junta de Saúde</td>
                    <td>3012</td>
                </tr>

                <!-- PDF -->
                <tr>
                    <td>GERAR PDF</td>
                    <td>Gerou o relatório JISE individual do obrigatório</td>
                    <td>4001</td>
                </tr>
                <tr>
                    <td>GERAR PDF</td>
                    <td>Gerou o relatório JISR individual do obrigatório</td>
                    <td>4002</td>
                </tr>
                <tr>
                    <td>GERAR PDF</td>
                    <td>Gerou a Ficha do Obrigatório</td>
                    <td>4003</td>
                </tr>
                <tr>
                    <td>GERAR PDF</td>
                    <td>Gerou o Ofício do Obrigatório</td>
                    <td>4004</td>
                </tr>
                <tr>
                    <td>GERAR PDF</td>
                    <td>Gerou um Comparecimento JISE/JISR por Data</td>
                    <td>4005</td>
                </tr>
                <tr>
                    <td>GERAR PDF</td>
                    <td>Gerou uma lista de presença</td>
                    <td>4006</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tabela_dinamica').DataTable({
            "aaSorting": []
        });
        $('#tabela_dinamica2').DataTable({
            order: [
                [0, "desc"]
            ]
        });
    });
</script>

<?php
include_once 'footer.php';
?>