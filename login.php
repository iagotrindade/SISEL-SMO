<?php
include_once 'header.php';
?>

<section id="contact" class="contact" style="padding: 4rem 0;">
    <div class="container">
        <div class="row mt-5 justify-content-center" data-aos="fade-up">
            <div class="col-lg-4">
                <div class="card" style="position: relative; overflow: hidden;">

                    <!-- Efeito visual de fundo -->
                    <div style="position: absolute; top: -50%; right: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(0,255,136,0.1) 0%, transparent 70%); pointer-events: none;"></div>

                    <div style="position: relative; z-index: 1;">
                        
                        <?php if(isset($_SESSION['mensagem_sucesso'])): ?>
                        <!-- Mensagem de Sucesso Reset de Senha -->
                        <div style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border-left: 4px solid #28a745; padding: 1.25rem; border-radius: 10px; margin-bottom: 2rem; box-shadow: 0 2px 8px rgba(40, 167, 69, 0.2);" data-aos="fade-down">
                            <div style="display: flex; align-items: start; gap: 1rem;">
                                <div style="flex-shrink: 0;">
                                    <div style="width: 50px; height: 50px; background: #28a745; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-check-circle" style="font-size: 1.75rem; color: white;"></i>
                                    </div>
                                </div>
                                <div style="flex: 1;">
                                    <h5 style="color: #155724; margin: 0 0 0.5rem 0; font-weight: 700;">
                                        <i class="bi bi-shield-check"></i> <?php echo $_SESSION['mensagem_sucesso']; ?>
                                    </h5>
                                    <p style="color: #155724; margin: 0 0 0.75rem 0; font-size: 0.95rem; line-height: 1.5;">
                                        <?php echo $_SESSION['mensagem_detalhes']; ?>
                                    </p>
                                    <?php if(isset($_SESSION['usuario_resetado'])): ?>
                                    <div style="background: white; padding: 0.75rem; border-radius: 6px; margin-top: 0.75rem;">
                                        <small style="color: #155724; display: block; line-height: 1.6;">
                                            <strong><i class="bi bi-person-fill"></i> Usuário:</strong> <?php echo $_SESSION['usuario_resetado']; ?><br>
                                            <strong><i class="bi bi-person-badge"></i> Nome:</strong> <?php echo $_SESSION['nome_resetado']; ?>
                                        </small>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php 
                            unset($_SESSION['mensagem_sucesso']);
                            unset($_SESSION['mensagem_detalhes']);
                            unset($_SESSION['usuario_resetado']);
                            unset($_SESSION['nome_resetado']);
                        endif; 
                        ?>

                        <!-- Logo/Ícone -->
                        <div style="text-align: center; margin-bottom: 2rem;">
                            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #006400, #228b22); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto; box-shadow: var(--shadow-lg);">
                                <i class="fa fa-stethoscope" style="font-size: 2.5rem; color: white;"></i>
                            </div>
                            <h3 style="margin-top: 1.5rem; color: var(--text-primary); font-weight: 700;">
                                Login
                            </h3>
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.5rem;">
                                Digite suas credenciais para acessar
                            </p>
                        </div>

                        <form action="controller/login.php" method="post" role="form">
                            <div class="form-group" style="margin-bottom: 1.5rem;">
                                <label for="usuario" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                    <i class="bi bi-person-fill" style="color: #228b22;"></i>
                                    <span>Usuário</span>
                                </label>
                                <div style="position: relative;">
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="usuario"
                                        id="usuario"
                                        placeholder="Digite seu usuário"
                                        required
                                        autocomplete="username"
                                        style="padding-left: 2.75rem;">
                                    <i class="bi bi-person" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 2rem;">
                                <label for="senha" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                    <i class="bi bi-key-fill" style="color: #228b22;"></i>
                                    <span>Senha</span>
                                </label>
                                <div style="position: relative;">
                                    <input
                                        type="password"
                                        class="form-control"
                                        name="senha"
                                        id="senha"
                                        placeholder="Digite sua senha"
                                        required
                                        autocomplete="current-password"
                                        style="padding-left: 2.75rem;">
                                    <i class="bi bi-lock" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                                </div>
                            </div>

                            <input name="crip" type="hidden" value="<?php echo hash('sha256', $_SESSION['chave'] . "criptografia"); ?>">

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary w-100" style="padding: 1rem; font-size: 1rem; font-weight: 700;">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                    ENTRAR
                                </button>
                            </div>

                            <!-- Link de recuperação de senha -->
                            <div style="margin-top: 1.5rem; text-align: center;">
                                <a href="reset_senha.php" style="color: var(--text-secondary); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease;" onmouseover="this.style.color='#228b22'" onmouseout="this.style.color='var(--text-secondary)'">
                                    <i class="bi bi-key-fill"></i>
                                    Esqueci minha senha
                                </a>
                            </div>

                            <!-- Informações adicionais -->
                            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color); text-align: center;">
                                <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0;">
                                    <i class="bi bi-shield-check me-1"></i>
                                    Conexão segura e criptografada
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
                            <span style="color: var(--text-secondary); font-size: 0.9rem;">Ambiente Seguro</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="bi bi-lightning-fill" style="color: var(--accent-gold); font-size: 1.2rem;"></i>
                            <span style="color: var(--text-secondary); font-size: 0.9rem;">Acesso Rápido</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="bi bi-shield-fill-check" style="color: var(--info); font-size: 1.2rem;"></i>
                            <span style="color: var(--text-secondary); font-size: 0.9rem;">Dados Protegidos</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Auto-focus no campo de usuário ao carregar a página
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('usuario').focus();
    });

    // Validação básica antes do envio
    document.querySelector('form').addEventListener('submit', function(e) {
        const usuario = document.getElementById('usuario').value.trim();
        const senha = document.getElementById('senha').value;

        if (usuario.length < 3) {
            e.preventDefault();
            alert('O usuário deve ter pelo menos 3 caracteres');
            return false;
        }

        if (senha.length < 4) {
            e.preventDefault();
            alert('A senha deve ter pelo menos 4 caracteres');
            return false;
        }
    });
</script>

<?php
include_once 'footer.php';
?>