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
                        <!-- Logo/Ícone -->
                        <div style="text-align: center; margin-bottom: 2rem;">
                            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #006400, #228b22); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto; box-shadow: var(--shadow-lg);">
                                <i class="bi bi-key-fill" style="font-size: 2.5rem; color: white;"></i>
                            </div>
                            <h3 style="margin-top: 1.5rem; color: var(--text-primary); font-weight: 700;">
                                Recuperar Senha
                            </h3>
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.5rem;">
                                Preencha os dados abaixo para resetar sua senha
                            </p>
                        </div>

                        <form action="controller/reset_senha.php" method="post" role="form" id="formResetSenha">
                            
                            <!-- Campo Email -->
                            <div class="form-group" style="margin-bottom: 1.5rem;">
                                <label for="email" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                    <i class="bi bi-envelope-fill" style="color: #228b22;"></i>
                                    <span>E-mail</span>
                                </label>
                                <div style="position: relative;">
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="email"
                                        id="email"
                                        placeholder="Digite seu e-mail cadastrado"
                                        required
                                        autocomplete="email"
                                        style="padding-left: 2.75rem; text-transform: uppercase;">
                                    <i class="bi bi-envelope" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                                </div>
                            </div>

                            <!-- Campo CPF -->
                            <div class="form-group" style="margin-bottom: 2rem;">
                                <label for="cpf" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                    <i class="bi bi-person-vcard-fill" style="color: #228b22;"></i>
                                    <span>CPF</span>
                                </label>
                                <div style="position: relative;">
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="cpf"
                                        id="cpf"
                                        placeholder="Digite seu CPF (somente números)"
                                        required
                                        maxlength="11"
                                        style="padding-left: 2.75rem;">
                                    <i class="bi bi-card-text" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                                </div>
                                <small style="color: var(--text-muted); display: block; margin-top: 0.5rem;">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Digite apenas os números do CPF (11 dígitos)
                                </small>
                            </div>

                            <input name="crip" type="hidden" value="<?php echo hash('sha256', $_SESSION['chave'] . "criptografia"); ?>">

                            <!-- Informação sobre nova senha -->
                            <div style="background: #e8f5e9; border-left: 4px solid #228b22; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                                <small style="color: #1b5e20; display: block; line-height: 1.5;">
                                    <i class="bi bi-info-circle-fill me-1"></i>
                                    Após a validação, sua senha será resetada para: <strong>123@smo</strong>
                                </small>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary w-100" style="padding: 1rem; font-size: 1rem; font-weight: 700;">
                                    <i class="bi bi-arrow-clockwise"></i>
                                    RESETAR SENHA
                                </button>
                            </div>

                            <div style="margin-top: 1.5rem; text-align: center;">
                                <a href="login.php" style="color: var(--text-secondary); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease;" onmouseover="this.style.color='#228b22'" onmouseout="this.style.color='var(--text-secondary)'">
                                    <i class="bi bi-arrow-left"></i>
                                    Voltar para o login
                                </a>
                            </div>

                            <!-- Informações de segurança -->
                            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
                                <div class="alert alert-warning" style="margin: 0; padding: 1rem; border-radius: 8px; background-color: #fff3cd; border: 1px solid #ffc107;">
                                    <div style="display: flex; align-items: start; gap: 0.75rem;">
                                        <i class="bi bi-shield-lock-fill" style="color: #856404; font-size: 1.25rem;"></i>
                                        <div style="flex: 1;">
                                            <strong style="color: #856404; display: block; margin-bottom: 0.5rem;">Segurança:</strong>
                                            <small style="color: #856404; line-height: 1.5; display: block;">
                                                • Seus dados serão validados com os registros do sistema<br>
                                                • Após o reset, você será obrigado a alterar a senha no primeiro acesso<br>
                                                • Esta operação será registrada no log de auditoria
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Auto-focus no campo de email ao carregar a página
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('email').focus();
        
        // Converte email para maiúscula
        const emailInput = document.getElementById('email');
        emailInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    });

    // Máscara e validação do CPF
    const cpfInput = document.getElementById('cpf');
    
    cpfInput.addEventListener('input', function(e) {
        // Remove tudo que não é número
        let value = e.target.value.replace(/\D/g, '');
        
        // Limita a 11 dígitos
        if (value.length > 11) {
            value = value.substr(0, 11);
        }
        
        e.target.value = value;
    });

    // Validação do CPF
    function validarCPF(cpf) {
        cpf = cpf.replace(/[^\d]+/g, '');
        
        if (cpf.length !== 11) return false;
        
        // Verifica se todos os dígitos são iguais
        if (/^(\d)\1+$/.test(cpf)) return false;
        
        // Validação do primeiro dígito verificador
        let soma = 0;
        for (let i = 0; i < 9; i++) {
            soma += parseInt(cpf.charAt(i)) * (10 - i);
        }
        let resto = 11 - (soma % 11);
        let digitoVerificador1 = resto === 10 || resto === 11 ? 0 : resto;
        
        if (digitoVerificador1 !== parseInt(cpf.charAt(9))) return false;
        
        // Validação do segundo dígito verificador
        soma = 0;
        for (let i = 0; i < 10; i++) {
            soma += parseInt(cpf.charAt(i)) * (11 - i);
        }
        resto = 11 - (soma % 11);
        let digitoVerificador2 = resto === 10 || resto === 11 ? 0 : resto;
        
        return digitoVerificador2 === parseInt(cpf.charAt(10));
    }

    // Validação antes do envio
    document.getElementById('formResetSenha').addEventListener('submit', function(e) {
        const email = document.getElementById('email').value.trim();
        const cpf = document.getElementById('cpf').value.trim();

        // Validação de email
        if (email.length < 5 || !email.includes('@')) {
            e.preventDefault();
            alert('Por favor, digite um e-mail válido');
            document.getElementById('email').focus();
            return false;
        }

        // Validação de CPF - verifica se tem menos de 11
        if (cpf.length < 11) {
            e.preventDefault();
            alert('O CPF deve conter 11 dígitos. Você digitou apenas ' + cpf.length + '.');
            document.getElementById('cpf').focus();
            return false;
        }

        // Validação de CPF - verifica se é válido
        if (!validarCPF(cpf)) {
            e.preventDefault();
            alert('CPF inválido. Por favor, verifique os números digitados.');
            document.getElementById('cpf').focus();
            return false;
        }

        // Confirmação antes de resetar
        if (!confirm('Confirmar reset de senha?\n\nE-mail: ' + email + '\nCPF: ' + cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4') + '\n\nSua senha será alterada para 123@smo')) {
            e.preventDefault();
            return false;
        }
    });
</script>

<?php
include_once 'footer.php';
?>
