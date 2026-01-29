<?php
    include_once 'header.php';

    include_once 'funcoes.php';

    include_once $BASE_URL.'/dao/conecta_banco.php';
    include_once $BASE_URL.'/models/Usuario.php';
    include_once $BASE_URL.'/dao/UsuarioDAO.php';

    if(isset($_SESSION['id_usuario_smo']))
    {
      $usuarioDAO = new UsuarioDAO($conexao);
      $usuario = $usuarioDAO->findById($_SESSION['id_usuario_smo']);
      if(!$usuario->getTrocarSenha())
          erro($BASE_URL, 3, 23465678, $pagina_atual, "trocar_senha==0", "Página não encontrada!");
    }
    else erro($BASE_URL, 3, 256347457, $pagina_atual, "SESSION['id_usuario_smo']==null", "Página não encontrada!");

?>

<section id="contact" class="contact" style="padding: 4rem 0;">
    <div class="container">
        <div class="row mt-5 justify-content-center" data-aos="fade-up">
            <div class="col-lg-4">
                <div class="card" style="position: relative; overflow: hidden;">

                    <!-- Efeito visual de fundo -->
                    <div style="position: absolute; top: -50%; right: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(0,255,136,0.1) 0%, transparent 70%); pointer-events: none;"></div>

                    <div style="position: relative; z-index: 1;">

                        <!-- Logo/Ícone -->
                        <div style="text-align: center; margin-bottom: 2rem;">
                            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #006400, #228b22); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto; box-shadow: var(--shadow-lg);">
                                <i class="bi bi-key-fill" style="font-size: 2.5rem; color: white;"></i>
                            </div>
                            <h3 style="margin-top: 1.5rem; color: var(--text-primary); font-weight: 700;">
                                Alterar Senha
                            </h3>
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.5rem;">
                                Crie uma nova senha segura para sua conta
                            </p>
                        </div>

                        <!-- Requisitos de senha -->
                        <div style="background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%); border-left: 4px solid #ffc107; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem;">
                            <p style="color: #856404; margin: 0 0 0.5rem 0; font-weight: 600; font-size: 0.9rem;">
                                <i class="bi bi-info-circle-fill"></i> A senha deve conter:
                            </p>
                            <ul style="color: #856404; margin: 0; padding-left: 1.5rem; font-size: 0.85rem; line-height: 1.8;">
                                <li>Pelo menos 8 caracteres</li>
                                <li>Uma letra maiúscula</li>
                                <li>Um número</li>
                                <li>Um caractere especial</li>
                            </ul>
                        </div>

                        <form action="controller/altera_senha.php" method="post" role="form">
                            <div class="form-group" style="margin-bottom: 1.5rem;">
                                <label for="senha1" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                    <i class="bi bi-lock-fill" style="color: #228b22;"></i>
                                    <span>Nova Senha</span>
                                </label>
                                <div style="position: relative;">
                                    <input
                                        type="password"
                                        class="form-control"
                                        name="senha1"
                                        id="senha1"
                                        placeholder="Digite sua nova senha"
                                        required
                                        autocomplete="new-password"
                                        style="padding-left: 2.75rem;">
                                    <i class="bi bi-lock" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 2rem;">
                                <label for="senha2" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                    <i class="bi bi-shield-lock-fill" style="color: #228b22;"></i>
                                    <span>Confirmar Senha</span>
                                </label>
                                <div style="position: relative;">
                                    <input
                                        type="password"
                                        class="form-control"
                                        name="senha2"
                                        id="senha2"
                                        placeholder="Repita a nova senha"
                                        required
                                        autocomplete="new-password"
                                        style="padding-left: 2.75rem;">
                                    <i class="bi bi-shield-lock" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                                </div>
                            </div>

                            <input name="crip" type="hidden" value="<?php echo hash('sha256', $_SESSION['chave']."nova_senha"); ?>">

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary w-100" style="padding: 1rem; font-size: 1rem; font-weight: 700;">
                                    <i class="bi bi-check-circle"></i>
                                    ALTERAR SENHA
                                </button>
                            </div>

                            <!-- Informações adicionais -->
                            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color); text-align: center;">
                                <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0;">
                                    <i class="bi bi-shield-check me-1"></i>
                                    Sua senha será armazenada de forma segura
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Card de informações -->
                <div class="card" style="margin-top: 2rem; padding: 1.5rem; text-align: center;" data-aos="fade-up" data-aos-delay="200">
                    <div style="display: flex; align-items: center; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--success); font-size: 1.2rem;"></i>
                            <span style="color: var(--text-secondary); font-size: 0.9rem;">Senha Forte</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="bi bi-shield-fill-check" style="color: var(--info); font-size: 1.2rem;"></i>
                            <span style="color: var(--text-secondary); font-size: 0.9rem;">Criptografia Segura</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Auto-focus no campo de senha ao carregar a página
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('senha1').focus();
    });

    // Validação antes do envio
    document.querySelector('form').addEventListener('submit', function(e) {
        const senha1 = document.getElementById('senha1').value;
        const senha2 = document.getElementById('senha2').value;

        // Verificar se as senhas coincidem
        if (senha1 !== senha2) {
            e.preventDefault();
            alert('As senhas não coincidem!');
            return false;
        }

        // Verificar requisitos mínimos
        const regex = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]).{8,}$/;
        if (!regex.test(senha1)) {
            e.preventDefault();
            alert('A senha deve ter pelo menos 8 caracteres, uma letra maiúscula, um número e um caractere especial.');
            return false;
        }
    });
</script>

<?php
  include_once 'footer.php';
?>