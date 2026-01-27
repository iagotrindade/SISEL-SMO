<?php
include_once 'header.php';
include_once 'dao/conecta_banco.php';
include_once 'models/Usuario.php';
include_once 'dao/UsuarioDAO.php';
include_once 'models/Obrigatorio.php';
include_once 'dao/ObrigatorioDAO.php';

if (!isset($_SESSION['id_usuario_smo'])) {
    erro($BASE_URL, 2, 236325634, $pagina_atual, "usuario_nao_logado", "Página não encontrada!");
    exit();
}
if (!isset($_SESSION['mensagem'])) $_SESSION['mensagem'] = null;

// Busca dados dinâmicos do banco
$ObrigatorioDAO = new ObrigatorioDAO($conexao);
$total_obrigatorios = $ObrigatorioDAO->countObrigatoriosAnoAtual();
$total_selecao_concluida = $ObrigatorioDAO->countObrigatoriosSelecaoConcluida();
$total_distribuidos = $ObrigatorioDAO->countObrigatoriosIncorporadosAnoAtual();

?>

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h1><i class="fas fa-stethoscope me-3"></i><b>Bem-vindo ao SISEL - SMO</b></h1>
                <p style="color: var(--text-secondary); margin-top: 1rem; font-size: 1.1rem;">
                    <?php echo saudacoes() . ", " . $_SESSION['posto_grad_smo'] . " " . $_SESSION['nome_guerra_smo'] ?>
                </p>
            </div>
        </div>
    </section>

    <!-- Mensagem de Sucesso/Erro -->
    <?php if (!empty($_SESSION['mensagem'])): ?>
        <div class="container mt-4">
            <div class="alert alert-success" role="alert" data-aos="fade-down">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?php echo $_SESSION['mensagem'];
                $_SESSION['mensagem'] = null; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Cards de Estatísticas -->
    <section class="section-bg" style="padding: 3rem 0;">
        <div class="container">

            <!-- Estatísticas Principais -->
            <div class="row mb-4">
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon-box" style="background: linear-gradient(135deg, rgba(0, 100, 0, 0.15), rgba(34, 139, 34, 0.08)); border-color: #228b22;">
                        <div class="icon" style="background: linear-gradient(135deg, #006400, #228b22);">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h4 class="title">
                            <a href="obrigatorios.php" style="color: var(--text-primary);">Obrigatórios <?= date('Y') ?></a>
                        </h4>
                        <p class="description" style="color: var(--accent-green); font-size: 2.5rem; font-weight: 800;">
                            <?php echo $total_obrigatorios; ?>
                        </p>
                        <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.5rem;">
                            <i class="fas fa-stethoscope me-1"></i> Obrigatórios inspecionados este ano
                        </p>
                    </div>
                </div>

                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="icon-box" style="background: linear-gradient(135deg, rgba(0, 100, 0, 0.15), rgba(34, 139, 34, 0.08)); border-color: #228b22;">
                        <div class="icon" style="background: linear-gradient(135deg, #006400, #228b22);">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <h4 class="title">
                            <a href="relatorios.php" style="color: var(--text-primary);">Incorporados <?= date('Y') ?></a>
                        </h4>
                        <p class="description" style="color: var(--accent-green); font-size: 2.5rem; font-weight: 800;">
                            <?php echo $total_distribuidos; ?>
                        </p>
                        <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.5rem;">
                            <i class="bi bi-people me-1"></i> Incorporados este ano
                        </p>
                    </div>
                </div>

                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon-box" style="background: linear-gradient(135deg, rgba(0, 100, 0, 0.15), rgba(34, 139, 34, 0.08)); border-color: #228b22;">
                        <div class="icon" style="background: linear-gradient(135deg, #006400, #228b22);">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <h4 class="title">
                            <a href="#" style="color: var(--text-primary);">Histórico de Processos</a>
                        </h4>
                        <p class="description" style="color: var(--accent-green); font-size: 2.5rem; font-weight: 800;">
                            <?php echo $total_selecao_concluida; ?>
                        </p>
                        <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.5rem;">
                            <i class="bi bi-graph-up me-1"></i> Obrigatórios com Seleção concluída ou em andamento
                        </p>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="row mt-5">
                <div class="col-12" data-aos="fade-up">
                    <div class="card" style="padding: 2rem;">
                        <h3 style="margin-bottom: 2rem; color: var(--text-primary); display: flex; align-items: center;">
                            <i class="bi bi-lightning-charge-fill me-2" style="color: var(--accent-green);"></i>
                            Ações Rápidas
                        </h3>

                        <div class="row">
                            <?php if ($_SESSION['perfil_smo'] == 'admin'): ?>

                                <div class="col-md-3 mb-3" data-aos="fade-up" data-aos-delay="100">
                                    <a href="obrigatorio_cadastra.php" class="btn btn-primary w-100" style="padding: 1.5rem; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                                        <i class="bi bi-person-plus-fill" style="font-size: 2rem;"></i>
                                        <span>Cadastrar Obrigatório</span>
                                    </a>
                                </div>

                                <div class="col-md-3 mb-3" data-aos="fade-up" data-aos-delay="150">
                                    <a href="obrigatorios.php" class="btn btn-primary w-100" style="padding: 1.5rem; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                                        <i class="bi bi-list-ul" style="font-size: 2rem;"></i>
                                        <span>Listar Obrigatórios</span>
                                    </a>
                                </div>

                                <div class="col-md-3 mb-3" data-aos="fade-up" data-aos-delay="200">
                                    <a href="pre_distribuicao.php" class="btn btn-primary w-100" style="padding: 1.5rem; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                                        <i class="bi bi-diagram-3-fill" style="font-size: 2rem;"></i>
                                        <span>Pré Distribuição</span>
                                    </a>
                                </div>

                                <div class="col-md-3 mb-3" data-aos="fade-up" data-aos-delay="250">
                                    <a href="relatorios.php" class="btn btn-primary w-100" style="padding: 1.5rem; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                                        <i class="bi bi-file-earmark-text-fill" style="font-size: 2rem;"></i>
                                        <span>Relatórios</span>
                                    </a>
                                </div>

                                <div class="col-md-3 mb-3" data-aos="fade-up" data-aos-delay="300">
                                    <a href="junta_saude_cadastra.php" class="btn btn-primary w-100" style="padding: 1.5rem; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                                        <i class="bi bi-journal-medical" style="font-size: 2rem;"></i>
                                        <span>Inspeções de Saúde</span>
                                    </a>
                                </div>

                                <div class="col-md-3 mb-3" data-aos="fade-up" data-aos-delay="350">
                                    <a href="usuarios.php" class="btn btn-primary w-100" style="padding: 1.5rem; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                                        <i class="bi bi-people-fill" style="font-size: 2rem;"></i>
                                        <span>Gerenciar Usuários</span>
                                    </a>
                                </div>

                                <div class="col-md-3 mb-3" data-aos="fade-up" data-aos-delay="400">
                                    <a href="auditoria.php" class="btn btn-primary w-100" style="padding: 1.5rem; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                                        <i class="bi bi-shield-check" style="font-size: 2rem;"></i>
                                        <span>Auditoria</span>
                                    </a>
                                </div>

                            <?php else: ?>

                                <div class="col-md-4 mb-3" data-aos="fade-up" data-aos-delay="100">
                                    <a href="distribuidos_om_1_fase.php" class="btn btn-primary w-100" style="padding: 1.5rem; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                                        <i class="bi bi-diagram-3-fill" style="font-size: 2rem;"></i>
                                        <span>Distribuídos OM 1ª Fase</span>
                                    </a>
                                </div>

                                <div class="col-md-4 mb-3" data-aos="fade-up" data-aos-delay="150">
                                    <a href="revisao_medica.php" class="btn btn-primary w-100" style="padding: 1.5rem; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                                        <i class="bi bi-heart-pulse-fill" style="font-size: 2rem;"></i>
                                        <span>Revisão Médica</span>
                                    </a>
                                </div>

                            <?php endif; ?>

                            <div class="col-md-3 mb-3" data-aos="fade-up" data-aos-delay="450">
                                <a href="controller/logout.php" class="btn btn-primary w-100" style="padding: 1.5rem; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                                    <i class="bi bi-box-arrow-left" style="font-size: 2rem;"></i>
                                    <span>Sair</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações do Sistema -->
            <div class="row mt-4">
                <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card" style="height: 100%;">
                        <h4 style="margin-bottom: 1.5rem; color: var(--text-primary); display: flex; align-items: center;">
                            <i class="bi bi-info-circle-fill me-2" style="color: var(--info);"></i>
                            Informações do Sistema
                        </h4>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--gray-dark); border-radius: var(--border-radius);">
                                <span style="color: var(--text-secondary);">Versão do Sistema:</span>
                                <strong style="color: var(--accent-green);">1.0.0</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--gray-dark); border-radius: var(--border-radius);">
                                <span style="color: var(--text-secondary);">Última Atualização:</span>
                                <strong style="color: var(--text-primary);">Janeiro/2026</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--gray-dark); border-radius: var(--border-radius);">
                                <span style="color: var(--text-secondary);">Perfil de Acesso:</span>
                                <strong style="color: var(--accent-blue); text-transform: uppercase;"><?php echo $_SESSION['perfil_smo']; ?></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card" style="height: 100%;">
                        <h4 style="margin-bottom: 1.5rem; color: var(--text-primary); display: flex; align-items: center;">
                            <i class="bi bi-calendar-event me-2" style="color: var(--accent-gold);"></i>
                            Atividades Recentes
                        </h4>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div style="padding: 0.75rem; background: var(--gray-dark); border-radius: var(--border-radius); border-left: 3px solid var(--success);">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="color: var(--text-secondary); font-size: 0.9rem;">
                                        <i class="bi bi-check-circle-fill me-2" style="color: var(--success);"></i>
                                        Login realizado com sucesso
                                    </span>
                                    <small style="color: var(--text-muted);">Agora</small>
                                </div>
                            </div>
                            <div style="padding: 0.75rem; background: var(--gray-dark); border-radius: var(--border-radius); border-left: 3px solid var(--info);">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="color: var(--text-secondary); font-size: 0.9rem;">
                                        <i class="bi bi-file-text me-2" style="color: var(--info);"></i>
                                        Sistema atualizado
                                    </span>
                                    <small style="color: var(--text-muted);">Hoje</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</main>

<script type="text/javascript">
    $(document).ready(function() {
        // Inicializa DataTables se existir
        if ($.fn.DataTable) {
            $('#tabela_dinamica').DataTable();
        }

        // Auto-hide mensagens de sucesso após 5 segundos
        setTimeout(function() {
            $('.alert-success').fadeOut('slow');
        }, 5000);
    });
</script>

<?php
include_once 'footer.php';
?>