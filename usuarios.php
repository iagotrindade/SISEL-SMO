<?php
include_once 'header.php';
include_once 'dao/conecta_banco.php';
include_once 'models/Usuario.php';
include_once 'dao/UsuarioDAO.php';
include_once 'dao/AuxiliarDAO.php';

$usuarioDAO = new UsuarioDAO($conexao);
$AuxiliarDAO = new AuxiliarDAO($conexao);
$todas_oms_1_fase = $AuxiliarDAO->findAllOM1Fase();
$todos_usuarios = $usuarioDAO->findAllAtivos();

if (!isset($_SESSION['id_usuario_smo'])) {
    erro($BASE_URL, 2, 27899756, $pagina_atual, "usuario_nao_logado", "Página não encontrada!");
    exit();
}
if ($_SESSION['perfil_smo'] != "admin") {
    erro($BASE_URL, 2, 643197827193, $pagina_atual, "perfil!=admin", "Sem permissão!");
    exit();
}
if (!isset($_SESSION['mensagem'])) $_SESSION['mensagem'] = null;

$crip = null;
$id_usuario = null;
$tela_editar = false;
$tela_senha = false;
$destino = "usuario_cadastra.php";
$botao = "Cadastrar";
$usuario_edita = null;
$id_usuario_edita = null;

if (isset($_GET['crip']) && (isset($_GET['id']))) {
    $crip = filtra_campo_get('crip');
    $id = (int)filtra_campo_get('id');
    if ($crip == hash('sha256', $id . "criptografia"))
        $usuario_edita = $usuarioDAO->findById($id);
    if ($usuario_edita) {
        $id_usuario_edita = $id;
        if (isset($_GET['senha']) && $_GET['senha'] == 1)
            $tela_senha = true;
        else {
            $tela_editar = true;
            $botao = "Atualizar";
            $destino = "usuario_edita.php";
        }
    }
}
?>

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h1><b><i class="bi bi-person-plus-fill"></i> Usuários</b></h1>
            </div>
        </div>
    </section>
</main>

<?php
if ($_SESSION['mensagem']) : ?>
    <center>
        <font color="green" size="6px"><?php echo $_SESSION['mensagem'];
                                        $_SESSION['mensagem'] = null; ?></font>
    </center>

<?php endif; ?>

<section id="contact" class="contact" <?php if ($tela_senha) echo " hidden " ?>>
    <div class="container">
        <div class="row  justify-content-center">
            <div class="col-lg-12">
                <form action="controller/<?php echo $destino ?>" method="post" role="form" class="card">

                    <div class="row">
                        <div class="col-md-3 form-group">
                            <input type="text" class="form-control" name="nome_completo" value="<?php if ($usuario_edita) echo $usuario_edita->getNomeCompleto(); ?>" placeholder="Nome Completo" required>
                        </div>

                        <div class="col-md-3 form-group">
                            <input type="text" class="form-control" name="nome_guerra" value="<?php if ($usuario_edita) echo $usuario_edita->getNomeGuerra(); ?>" placeholder="Nome de Guerra" required>
                        </div>


                        <div class="col-md-3 form-group">
                            <input type="text" class="form-control" name="usuario" <?php if ($usuario_edita) echo " disabled " ?> value="<?php if ($usuario_edita) echo $usuario_edita->getUsuario(); ?>" placeholder="Usuário para acessar o sistema" required>
                        </div>

                        <div class="col-md-3 form-group">
                            <input type="text" class="form-control" name="cpf" value="<?php if ($usuario_edita) echo $usuario_edita->getCPF(); ?>" placeholder="CPF" required>
                        </div>

                        <div class="col-md-3 form-group">
                            <input type="text" class="form-control" name="mail" value="<?php if ($usuario_edita) echo $usuario_edita->getMail(); ?>" placeholder="E-Mail" required>
                        </div>


                        <div class="col-md-3 form-group">
                            <select name="posto_grad" class="form-control" required>
                                <option value="">Selecione o Posto/Graduação</option>
                                <option <?php if ($usuario_edita && $usuario_edita->getPostoGrad() == "Sd") echo " selected " ?> value="Sd">Soldado</option>
                                <option <?php if ($usuario_edita && $usuario_edita->getPostoGrad() == "Cb") echo " selected " ?> value="Cb">Cabo</option>
                                <option <?php if ($usuario_edita && $usuario_edita->getPostoGrad() == "3º Sgt") echo " selected " ?> value="3º Sgt">3º Sargento</option>
                                <option <?php if ($usuario_edita && $usuario_edita->getPostoGrad() == "2º Sgt") echo " selected " ?> value="2º Sgt">2º Sargento</option>
                                <option <?php if ($usuario_edita && $usuario_edita->getPostoGrad() == "1º Sgt") echo " selected " ?> value="1º Sgt">1º Sargento</option>
                                <option <?php if ($usuario_edita && $usuario_edita->getPostoGrad() == "ST") echo " selected " ?> value="ST">Sub Tenente</option>
                                <option <?php if ($usuario_edita && $usuario_edita->getPostoGrad() == "2º Ten") echo " selected " ?> value="2º Ten">2º Tenente</option>
                                <option <?php if ($usuario_edita && $usuario_edita->getPostoGrad() == "1º Ten") echo " selected " ?> value="1º Ten">1º Tenente</option>
                                <option <?php if ($usuario_edita && $usuario_edita->getPostoGrad() == "Cap") echo " selected " ?> value="Cap">Capitão</option>
                                <option <?php if ($usuario_edita && $usuario_edita->getPostoGrad() == "Maj") echo " selected " ?> value="Maj">Major</option>
                                <option <?php if ($usuario_edita && $usuario_edita->getPostoGrad() == "TC") echo " selected " ?> value="TC">Ten Coronel</option>
                                <option <?php if ($usuario_edita && $usuario_edita->getPostoGrad() == "Cel") echo " selected " ?> value="Cel">Coronel</option>
                                <option <?php if ($usuario_edita && $usuario_edita->getPostoGrad() == "Gen") echo " selected " ?> value="Gen">General</option>
                            </select>
                        </div>

                        <div class="col-md-3 form-group">
                            <input type="text" class="form-control" id="telefone" name="telefone" value="<?php if ($usuario_edita) echo $usuario_edita->getTelefone(); ?>" placeholder="Telefone" required>
                        </div>

                        <div class="col-md-3 form-group">
                            <input type="text" class="form-control" name="data_validade" value="<?php if ($usuario_edita) echo $usuario_edita->imprimeValidade(); ?>" placeholder="Data de validade" required>
                        </div>

                        <div class="col-md-6 form-group">
                            <select name="perfil" class="form-control" required>
                                <option value="">Selecione o Perfil</option>
                                <option <?php if ($usuario_edita && $usuario_edita->getPerfil() == "admin") echo " selected " ?> value="admin">Administrador</option>
                                <option <?php if ($usuario_edita && $usuario_edita->getPerfil() == "operador") echo " selected " ?> value="operador">Operador de OM</option>
                                <option <?php if ($usuario_edita && $usuario_edita->getPerfil() == "consulta") echo " selected " ?> value="consulta">Consulta</option>
                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <select name="id_om" style="width: 100%" class="form-control" required>
                                <option value="">Seleciona a OM</option>
                                <?php
                                foreach ($todas_oms_1_fase as $value) {
                                    if ($usuario_edita && $usuario_edita->getIdOm() == $value['id'])  echo "<option value='" . $value['id'] . "' selected>" . $value['abreviatura'] . "</option>";
                                    else echo "<option value='" . $value['id'] . "' >" . $value['abreviatura'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-12 form-group">
                            <br>
                            <div style="display: block;
                                        padding: 1rem 1.5rem;
                                        border-radius: var(--border-radius);
                                        margin: 1rem auto;
                                        max-width: 600px;
                                        text-align: center;
                                        font-weight: 600;
                                        animation: slideDown 0.3s ease;
                                        background: rgba(63, 185, 80, 0.15);
                                        color: var(--success) !important;
                                        border: 1px solid rgba(63, 185, 80, 0.3);">
                                A senha padrão será 123@smo
                            </div>
                        </div>

                    </div>

                    <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave'] . "usuario"); ?>">
                    <input name="id_usuario_edita" hidden value="<?php echo  $id_usuario_edita ?>">

                    <div class="text-center"><button type="submit"><?php echo $botao ?></button></div>

                </form>
            </div>
        </div>
    </div>
</section>

<section id="contact" class="contact" <?php if (!$tela_senha) echo " hidden " ?>>
    <div class="container">
        <div class="row  justify-content-center ">
            <div class="col-lg-12">
                <form action="controller/usuario_altera_senha.php" method="post" role="form" class="card">
                    <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave'] . "usuario"); ?>">
                    <input name="id_usuario_edita" hidden value="<?php echo  $id_usuario_edita ?>">

                    <center>
                        <font color="red"><b>A senha deve ter pelo menos </b>
                            <br>
                            8 caracteres
                            <br>
                            uma letra MAIÚSCULA
                            <br>
                            um número
                            <br>
                            um caractere especial

                        </font>
                    </center><br>

                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label>Militar: <?php if (isset($usuario_edita)) echo $usuario_edita->getPostoGrad() . " " . $usuario_edita->getNomeGuerra()  ?></label>
                            <label>Usuário: <?php if (isset($usuario_edita)) echo $usuario_edita->getUsuario()  ?></label>
                        </div>
                        <div class="col-md-3 form-group">
                            <input type="password" class="form-control" name="senha1" placeholder="Digite a nova senha">
                        </div>
                        <div class="col-md-3 form-group">
                            <input type="password" class="form-control" name="senha2" placeholder="Repita a nova senha">
                        </div>
                        <div class="col-md-3 form-group">
                            <div class="text-center"><button type="submit">Atualizar senha</button></div>
                        </div>
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
                    <th>Nome Guerra</th>
                    <th>Usuário</th>
                    <th>CPF</th>
                    <th>Telefone</th>
                    <th>E-Mail</th>
                    <th>OM</th>
                    <th>Perfil</th>
                    <th>Validade</th>
                    <th>Senha</th>
                    <th>Editar</th>
                    <th>Apagar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($todos_usuarios)
                    foreach ($todos_usuarios as $usuario) {
                        //if($_SESSION['perfil_smo'] != 'admin' && $usuario->getPerfil() == 'admin') continue;

                        $letra = "";
                        if ($usuario->getPerfil() == 'admin') $letra = "A";

                        $criptografia = hash('sha256', $usuario->getId() . "criptografia");
                        echo "  
                        <tr>
                            <td> " . $usuario->getPostoGrad() . " " . $usuario->getNomeGuerra() . " </td>
                            <td> " . $usuario->getUsuario() . " </td>
                            <td> " . $usuario->getCPF() . " </td>
                            <td> " . $usuario->getTelefone() . " </td>
                            <td> " . $usuario->getMail() . " </td>
                            <td> " . $usuario->getAbreviatura_om() . " </td>
                            <td> " . $usuario->getPerfil() . " </td>
                            <td> " . $usuario->imprimeValidade() . " </td>
                            <td> <a href ='" . $_SERVER['PHP_SELF'] . "?crip=$criptografia&senha=1&id=" . $usuario->getId() . "'><center><i class='fas fa-key'></i></center></a> </td>                            
                            <td> <a href ='" . $_SERVER['PHP_SELF'] . "?crip=$criptografia&id=" . $usuario->getId() . "'><center><i class='fas fa-edit'></i></center></a> </td>
                            <td> <a href ='controller/usuario_apaga.php?crip=$criptografia&id_usuario=" . $usuario->getId() . "'><center><i class='fas fa-trash-alt'></i></center></a> </td>
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