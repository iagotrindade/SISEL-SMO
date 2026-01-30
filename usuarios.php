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

<?php if ($_SESSION['mensagem']): ?>
    <div class="container">
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div><?php echo $_SESSION['mensagem']; $_SESSION['mensagem'] = null; ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    </div>
<?php endif; ?>

<section id="contact" class="contact" <?php if ($tela_senha) echo " hidden " ?>>
    <div class="container">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-<?php echo $tela_editar ? 'edit' : 'user-plus'; ?> me-2"></i>
                    <?php echo $tela_editar ? 'Editar Usuário' : 'Cadastrar Novo Usuário'; ?>
                </h5>
            </div>
            <div class="card-body">
                <form action="controller/<?php echo $destino ?>" method="post" role="form">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Nome Completo</label>
                            <input type="text" class="form-control" name="nome_completo" value="<?php if ($usuario_edita) echo $usuario_edita->getNomeCompleto(); ?>" placeholder="Nome Completo" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Nome de Guerra</label>
                            <input type="text" class="form-control" name="nome_guerra" value="<?php if ($usuario_edita) echo $usuario_edita->getNomeGuerra(); ?>" placeholder="Nome de Guerra" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Usuário</label>
                            <input type="text" class="form-control" name="usuario" <?php if ($usuario_edita) echo " disabled " ?> value="<?php if ($usuario_edita) echo $usuario_edita->getUsuario(); ?>" placeholder="Usuário para acessar o sistema" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">CPF</label>
                            <input type="text" class="form-control" name="cpf" value="<?php if ($usuario_edita) echo $usuario_edita->getCPF(); ?>" placeholder="CPF" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">E-Mail</label>
                            <input type="text" class="form-control" name="mail" value="<?php if ($usuario_edita) echo $usuario_edita->getMail(); ?>" placeholder="E-Mail" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Posto/Graduação</label>
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

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Telefone</label>
                            <input type="text" class="form-control" id="telefone" name="telefone" value="<?php if ($usuario_edita) echo $usuario_edita->getTelefone(); ?>" placeholder="Telefone" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Data de Validade</label>
                            <input type="text" class="form-control" name="data_validade" value="<?php if ($usuario_edita) echo $usuario_edita->imprimeValidade(); ?>" placeholder="Data de validade" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Perfil</label>
                            <select name="perfil" class="form-control" required>
                                <option value="">Selecione o Perfil</option>
                                <option <?php if ($usuario_edita && $usuario_edita->getPerfil() == "admin") echo " selected " ?> value="admin">Administrador</option>
                                <option <?php if ($usuario_edita && $usuario_edita->getPerfil() == "operador") echo " selected " ?> value="operador">Operador de OM</option>
                                <option <?php if ($usuario_edita && $usuario_edita->getPerfil() == "consulta") echo " selected " ?> value="consulta">Consulta</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">OM</label>
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

                        <?php if (!$tela_editar): ?>
                        <div class="col-md-12">
                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                <i class="fas fa-info-circle me-2"></i>
                                <span>A senha padrão será <strong>123@smo</strong></span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave'] . "usuario"); ?>">
                    <input name="id_usuario_edita" hidden value="<?php echo  $id_usuario_edita ?>">

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-<?php echo $tela_editar ? 'save' : 'plus'; ?> me-2"></i><?php echo $botao ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section id="contact" class="contact" <?php if (!$tela_senha) echo " hidden " ?>>
    <div class="container">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-key me-2"></i>Alterar Senha
                </h5>
            </div>
            <div class="card-body">
                <form action="controller/usuario_altera_senha.php" method="post" role="form">
                    <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave'] . "usuario"); ?>">
                    <input name="id_usuario_edita" hidden value="<?php echo  $id_usuario_edita ?>">

                    <div class="alert alert-warning d-flex align-items-start mb-4" role="alert">
                        <i class="fas fa-exclamation-triangle me-3 mt-1"></i>
                        <div>
                            <strong>A senha deve conter:</strong>
                            <ul class="mb-0 mt-2">
                                <li>8 caracteres</li>
                                <li>Uma letra MAIÚSCULA</li>
                                <li>Um número</li>
                                <li>Um caractere especial</li>
                            </ul>
                        </div>
                    </div>

                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <div class="mb-2">
                                <label class="form-label fw-semibold">Militar</label>
                                <p class="form-control-plaintext"><?php if (isset($usuario_edita)) echo $usuario_edita->getPostoGrad() . " " . $usuario_edita->getNomeGuerra(); ?></p>
                            </div>
                            <div>
                                <label class="form-label fw-semibold">Usuário</label>
                                <p class="form-control-plaintext"><?php if (isset($usuario_edita)) echo $usuario_edita->getUsuario(); ?></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Nova Senha</label>
                            <input type="password" class="form-control" name="senha1" placeholder="Digite a nova senha">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Confirmar Senha</label>
                            <input type="password" class="form-control" name="senha2" placeholder="Repita a nova senha">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i>Atualizar Senha
                            </button>
                        </div>
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
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>Usuários Cadastrados
                </h5>
            </div>
            <div class="card-body p-0">
                <table id="tabela_dinamica" class="table table-hover mb-0" style="width:100%">
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
                            <th class="text-center">Senha</th>
                            <th class="text-center">Editar</th>
                            <th class="text-center">Apagar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($todos_usuarios)
                            foreach ($todos_usuarios as $usuario) {
                                $letra = "";
                                if ($usuario->getPerfil() == 'admin') $letra = "A";

                                $criptografia = hash('sha256', $usuario->getId() . "criptografia");
                                echo "
                                <tr>
                                    <td>" . htmlspecialchars($usuario->getPostoGrad() . " " . $usuario->getNomeGuerra()) . "</td>
                                    <td>" . htmlspecialchars($usuario->getUsuario()) . "</td>
                                    <td>" . htmlspecialchars($usuario->getCPF()) . "</td>
                                    <td>" . htmlspecialchars($usuario->getTelefone()) . "</td>
                                    <td>" . htmlspecialchars($usuario->getMail()) . "</td>
                                    <td>" . htmlspecialchars($usuario->getAbreviatura_om()) . "</td>
                                    <td>" . htmlspecialchars($usuario->getPerfil()) . "</td>
                                    <td>" . htmlspecialchars($usuario->imprimeValidade()) . "</td>
                                    <td class='text-center'><a href='" . $_SERVER['PHP_SELF'] . "?crip=$criptografia&senha=1&id=" . $usuario->getId() . "' class='btn btn-sm btn-outline-secondary' title='Alterar Senha'><i class='fas fa-key'></i></a></td>
                                    <td class='text-center'><a href='" . $_SERVER['PHP_SELF'] . "?crip=$criptografia&id=" . $usuario->getId() . "' class='btn btn-sm btn-outline-primary' title='Editar'><i class='fas fa-edit'></i></a></td>
                                    <td class='text-center'><a href='controller/usuario_apaga.php?crip=$criptografia&id_usuario=" . $usuario->getId() . "' class='btn btn-sm btn-outline-danger' title='Apagar'><i class='fas fa-trash-alt'></i></a></td>
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