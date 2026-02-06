<?php
// Usar output buffering para permitir redirect após output do header
ob_start();

include_once 'header.php';
include_once 'dao/conecta_banco.php';
include_once 'dao/ConfiguracaoDAO.php';
include_once 'dao/LogDAO.php';

$configuracaoDAO = new ConfiguracaoDAO($conexao);
$logDAO = new LogDAO($conexao);

if (!isset($_SESSION['id_usuario_smo'])) {
    erro($BASE_URL, 2, 27899757, $pagina_atual, "usuario_nao_logado", "Página não encontrada!");
    exit();
}
if ($_SESSION['perfil_smo'] != "admin") {
    erro($BASE_URL, 2, 643197827194, $pagina_atual, "perfil!=admin", "Sem permissão!");
    exit();
}
if (!isset($_SESSION['mensagem'])) $_SESSION['mensagem'] = null;

// Processar toggle de configuração
if (isset($_POST['toggle_config']) && isset($_POST['chave'])) {
    $chave = filtra_campo_post('chave');
    $resultado = $configuracaoDAO->toggleValor($chave, $_SESSION['id_usuario_smo']);

    if ($resultado) {
        $config = $configuracaoDAO->findByChave($chave);
        if ($config && isset($config['valor'])) {
            $status = $config['valor'] === '1' ? 'LIBERADO' : 'BLOQUEADO';
            $_SESSION['mensagem'] = "Configuração atualizada com sucesso! Status: $status";

            // Registrar log
            $logDAO->insertLog(5001, "Configuração", null, "Alterou configuração: $chave", "Novo valor: {$config['valor']}");
        } else {
            $_SESSION['mensagem'] = "Configuração atualizada!";
        }
    } else {
        $_SESSION['mensagem'] = "Erro ao atualizar configuração.";
    }

    // Limpar buffer e redirecionar
    ob_end_clean();
    header("Location: configuracoes.php");
    exit();
}

$todas_configuracoes = $configuracaoDAO->findAll();
?>

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h1><i class="fas fa-cog"></i> Configurações do Sistema</h1>
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

        <?php if (count($todas_configuracoes) > 0): ?>
            <div class="row">
                <?php foreach ($todas_configuracoes as $config): ?>
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm h-100 border-2 <?php echo $config['valor'] === '1' ? 'border-success' : 'border-danger'; ?>">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title mb-1">
                                            <?php if ($config['valor'] === '1'): ?>
                                                <i class="fas fa-toggle-on text-success me-2"></i>
                                            <?php else: ?>
                                                <i class="fas fa-toggle-off text-danger me-2"></i>
                                            <?php endif; ?>
                                            <?php echo htmlspecialchars($config['label'] ?? $config['chave']); ?>
                                        </h5>
                                    </div>
                                    <div>
                                        <?php if ($config['valor'] === '1'): ?>
                                            <span class="badge badge-success-custom">
                                                <i class="fas fa-check-circle me-1"></i> Liberado
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-danger-custom">
                                                <i class="fas fa-ban me-1"></i> Bloqueado
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <p class="card-text text-muted mb-3">
                                    <?php echo htmlspecialchars($config['descricao'] ?? 'Sem descrição'); ?>
                                </p>

                                <hr>

                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <?php if (!empty($config['atualizado_em'])): ?>
                                            <i class="fas fa-clock me-1"></i>
                                            <?php echo date('d/m/Y \à\s H:i', strtotime($config['atualizado_em'])); ?>
                                        <?php else: ?>
                                            <i class="fas fa-clock me-1"></i> Nunca alterado
                                        <?php endif; ?>
                                    </small>

                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="chave" value="<?php echo htmlspecialchars($config['chave']); ?>">
                                        <input type="hidden" name="toggle_config" value="1">
                                        <?php if ($config['valor'] === '1'): ?>
                                            <button type="submit" class="btn btn-outline-danger"
                                                    onclick="return confirm('Deseja BLOQUEAR esta configuração?');">
                                                <i class="fas fa-lock me-1"></i> Bloquear
                                            </button>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-success"
                                                    onclick="return confirm('Deseja LIBERAR esta configuração?');">
                                                <i class="fas fa-unlock me-1"></i> Liberar
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-cogs fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Nenhuma configuração encontrada</h4>
                    <p class="text-muted mb-0">Execute o script SQL para criar as configurações iniciais.</p>
                </div>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm mt-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-question-circle me-2"></i>
                    Como funciona?
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start mb-3">
                            <span class="badge badge-success-custom me-3">
                                <i class="fas fa-check"></i>
                            </span>
                            <div>
                                <strong>Liberado</strong>
                                <p class="text-muted mb-0 small">A funcionalidade está ativa e disponível para os usuários.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start mb-3">
                            <span class="badge badge-danger-custom me-3">
                                <i class="fas fa-ban"></i>
                            </span>
                            <div>
                                <strong>Bloqueado</strong>
                                <p class="text-muted mb-0 small">A funcionalidade está desativada. Usuários verão uma mensagem de indisponibilidade.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <h6 class="fw-semibold mb-3"><i class="fas fa-info-circle me-2"></i>Detalhes das Configurações</h6>

                <div class="alert alert-light border mb-3">
                    <h6 class="alert-heading"><i class="fas fa-eye me-2"></i>Visualização Distribuídos OM</h6>
                    <p class="mb-0 small">
                        Controla se os operadores de OM podem visualizar os candidatos distribuídos nas páginas
                        <strong>"Distribuídos OM 1ª Fase"</strong> e <strong>"Revisão Médica"</strong>.
                    </p>
                </div>

                <div class="alert alert-light border mb-0">
                    <h6 class="alert-heading"><i class="fas fa-sign-in-alt me-2"></i>Login Operadores de OM</h6>
                    <p class="mb-0 small">
                        Controla se os operadores de OM podem fazer login no sistema.
                        Quando <strong>bloqueado</strong>, operadores receberão uma mensagem de acesso negado ao tentar entrar.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
include_once 'footer.php';
ob_end_flush();
?>
