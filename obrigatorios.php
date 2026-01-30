<?php

include_once 'header.php';
include_once 'dao/conecta_banco.php';
include_once 'models/Obrigatorio.php';
include_once 'models/Om.php';
include_once 'dao/ObrigatorioDAO.php';
include_once 'dao/AuxiliarDAO.php';

$ObrigatorioDAO = new ObrigatorioDAO($conexao);

$AuxiliarDAO = new AuxiliarDAO($conexao);
$todas_gu = $AuxiliarDAO->findAllGuarnicao();
$todas_oms_1_fase = $AuxiliarDAO->findAllOM1Fase();
$todas_oms_2_fase = $AuxiliarDAO->findAllOM2Fase();
$todas_dt_sel_geral = $AuxiliarDAO->findAllDtSelecaoComp();
$todas_dt_sel_geral_semestre = $AuxiliarDAO->findAllDtSelecaoComp();
$todas_dt_comp_desigancao = $AuxiliarDAO->findAllDtCompDesignacao();
$todas_dt_prox_apresentacao = $AuxiliarDAO->findAllDtProxApresentacao();
$todas_dt_incorporacao = $AuxiliarDAO->findAllDtIncorporacao();
$todas_dt_sel_complementar = $AuxiliarDAO->findAllDtSelComplementar();
$todas_espec = $AuxiliarDAO->findAllEspec();

if ($_SESSION['perfil_smo'] != "admin") {
    erro($BASE_URL, 2, 9961356, $pagina_atual, "usuario_sem_permissao", "Usuário sem permissão!");
}
if (!isset($_SESSION['id_usuario_smo'])) {
    erro($BASE_URL, 2, 63216754, $pagina_atual, "Obrigatorio_nao_logado", "Página não encontrada!");
    exit();
}
if (!isset($_SESSION['mensagem'])) $_SESSION['mensagem'] = null;

?>

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h1><b><i class="fas fa-stethoscope"></i> Obrigatórios</b></h1>
            </div>
        </div>
    </section>
</main>

<?php if (isset($_SESSION['mensagem']) && $_SESSION['mensagem']): ?>
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['mensagem'];
                                                    $_SESSION['mensagem'] = null; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    </div>
<?php endif; ?>

<section id="contact" class="contact">
    <!-- Card de Filtros -->
    <div class="card shadow-sm mb-4" id="filtros-container">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filtros</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-lg-6">
                    <label class="form-label fw-semibold">Data Seleção Geral</label>
                    <select id="sel_geral_filtro" name="sel_geral_filtro[]" style="width: 100%" class="chosen-select" multiple>
                        <option value=""></option>
                        <?php
                        if ($todas_dt_sel_geral != null)
                            foreach ($todas_dt_sel_geral as $value) {
                                echo "<option value='" . htmlspecialchars($value['data']) . "'>" . trata_data($value['data']) . "</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="col-lg-6">
                    <label class="form-label fw-semibold">Data Seleção Geral SEMESTRE</label>
                    <select id="data_selecao_geral_semestre_filtro" name="data_selecao_geral_semestre_filtro" style="width: 100%" class="chosen-select">
                        <option value="">Selecione o Semestre</option>
                        <option value="um_semestre_vintetres">1º Semestre de 2023</option>
                        <option value="dois_semestre_vintetres">2º Semestre de 2023</option>
                        <option value="um_semestre_vintequatro">1º Semestre de 2024</option>
                        <option value="dois_semestre_vintequatro">2º Semestre de 2024</option>
                        <option value="um_semestre_vintecinco">1º Semestre de 2025</option>
                        <option value="dois_semestre_vintecinco">2º Semestre de 2025</option>
                        <option value="um_semestre_vinteseis">1º Semestre de 2026</option>
                        <option value="dois_semestre_vinteseis">2º Semestre de 2026</option>
                    </select>
                </div>
                <div class="col-lg-6">
                    <label class="form-label fw-semibold">Especialidade</label>
                    <select id="especialidade_filtro" name="especialidade_filtro[]" style="width: 100%" class="chosen-select" multiple>
                        <option value="">Especialidade</option>
                        <?php
                        foreach ($todas_espec as $value) {
                            echo "<option value='" . htmlspecialchars($value['nome']) . "'>" . htmlspecialchars($value['nome']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-lg-6">
                    <label class="form-label fw-semibold">IE Graduação</label>
                    <select id="faculdade_filtro" name="faculdade_filtro[]" style="width: 100%" class="chosen-select" multiple>
                        <option value="">IE Graduação</option>
                        <option value="FURG - RIO GRANDE">FURG - RIO GRANDE</option>
                        <option value="UFPEL - PELOTAS">UFPEL - PELOTAS</option>
                        <option value="UCPEL - PELOTAS">UCPEL - PELOTAS</option>
                        <option value="UPF - PASSO FUNDO">UPF - PASSO FUNDO</option>
                        <option value="UFFS - PASSO FUNDO">UFFS - PASSO FUNDO</option>
                        <option value="ATITUS - PASSO FUNDO (IMED)">ATITUS - PASSO FUNDO (IMED)</option>
                        <option value="UCS - CAXIAS DO SUL">UCS - CAXIAS DO SUL</option>
                        <option value="UNIVATES - LAJEADO">UNIVATES - LAJEADO</option>
                        <option value="UFCSPA - PORTO ALEGRE">UFCSPA - PORTO ALEGRE</option>
                        <option value="ULBRA - CANOAS">ULBRA - CANOAS</option>
                        <option value="PUCRS - PORTO ALEGRE">PUCRS - PORTO ALEGRE</option>
                        <option value="UFRGS - PORTO ALEGRE">UFRGS - PORTO ALEGRE</option>
                        <option value="UNISINOS - SÃO LEOPOLDO">UNISINOS - SÃO LEOPOLDO</option>
                        <option value="UNIPAMPA - URUGUAIANA">UNIPAMPA - URUGUAIANA</option>
                        <option value="UFSM - SANTA MARIA">UFSM - SANTA MARIA</option>
                        <option value="UFN - SANTA MARIA">UFN - SANTA MARIA</option>
                        <option value="UNISC - SANTA CRUZ DO SUL">UNISC - SANTA CRUZ DO SUL</option>
                        <option value="URI - ERECHIM">URI - ERECHIM</option>
                        <option value="FEEVALE - NOVO HAMBURGO">FEEVALE - NOVO HAMBURGO</option>
                        <option value="UNIJUÍ - IJUÍ">UNIJUÍ - IJUÍ</option>
                        <option value="GRADUADO EM FACULDADE FORA RS">GRADUADO EM FACULDADE FORA RS</option>
                    </select>
                </div>
                <div class="col-lg-6">
                    <label class="form-label fw-semibold">Situação Militar</label>
                    <select id="situacao_militar" name="situacao_militar[]" style="width: 100%" class="chosen-select" multiple>
                        <option value="">Situação Militar</option>
                        <option value="Em Débito - REFRATÁRIO">Em Débito - REFRATÁRIO</option>
                        <option value="Em Débito - INSUBMISSO">Em Débito - INSUBMISSO</option>
                        <option value="Em Dia - JUDICIAL">Em Dia - JUDICIAL</option>
                        <option value="Em Dia - TRANSFERÊNCIA FISEMI">Em Dia - TRANSFERÊNCIA FISEMI</option>
                        <option value="Em Dia - ALISTADO MFDV (FISEMI)">Em Dia - ALISTADO MFDV (FISEMI)</option>
                        <option value="Em Dia - ADIADO CURSANDO RESIDÊNCIA">Em Dia - ADIADO CURSANDO RESIDÊNCIA</option>
                        <option value="Em Dia - B1 - INSP SAU - RETORNAR PRÓXIMA SELEÇÃO">Em Dia - B1 - INSP SAU - RETORNAR PRÓXIMA SELEÇÃO</option>
                        <option value="Em Dia - LIMINAR JUDICIAL">Em Dia - LIMINAR JUDICIAL</option>
                        <option value="Quite SMO - EXCESSO - CONTINGENTE">Quite SMO - EXCESSO - CONTINGENTE</option>
                        <option value="Quite SMO - EXCESSO - INCAPAZ SAÚDE">Quite SMO - EXCESSO - INCAPAZ SAÚDE</option>
                        <option value="Quite SMO - DESOBRIGADO - MAIOR 38 ANOS">Quite SMO - DESOBRIGADO - MAIOR 38 ANOS</option>
                        <option value="Quite SMO - DESOBRIGADO - JÁ RESERVISTA">Quite SMO - DESOBRIGADO - JÁ RESERVISTA</option>
                        <option value="Quite SMO - DESOBRIGADO - NATURALIZADO">Quite SMO - DESOBRIGADO - NATURALIZADO</option>
                        <option value="Quite SMO - CONVOCADO">Quite SMO - CONVOCADO</option>
                    </select>
                </div>
                <div class="col-lg-4">
                    <label class="form-label fw-semibold">Dt Comp Designação</label>
                    <select id="comp_designacao_filtro" name="comp_designacao_filtro[]" style="width: 100%" class="chosen-select" multiple>
                        <option value="">Dt Comp Designação</option>
                        <?php
                        if ($todas_dt_comp_desigancao != null)
                            foreach ($todas_dt_comp_desigancao as $value) {
                                echo "<option value='" . htmlspecialchars($value['data']) . "'>" . trata_data($value['data']) . "</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="form-label fw-semibold">Voluntário</label>
                    <select id="voluntario_filtro" name="voluntario_filtro[]" style="width: 100%" class="chosen-select" multiple>
                        <option value="SIM">Sim</option>
                        <option value="NÃO">Não</option>
                    </select>
                </div>
                <div class="col-lg-6">
                    <label class="form-label fw-semibold">Distribuição</label>
                    <select id="distribuicao_filtro" name="distribuicao_filtro[]" style="width: 100%" class="chosen-select" multiple>
                        <option value="">Distribuição</option>
                        <option value="DESIGNADO - 1ª Distribuição">DESIGNADO - 1ª Distribuição</option>
                        <option value="DESIGNADO - 2ª Distribuição">DESIGNADO - 2ª Distribuição</option>
                        <option value="MAJORADO - 1ª Distribuição">MAJORADO - 1ª Distribuição</option>
                        <option value="MAJORADO - 2ª Distribuição">MAJORADO - 2ª Distribuição</option>
                        <option value="EXCESSO CONTINGENTE">EXCESSO CONTINGENTE</option>
                        <option value="MARINHA">MARINHA</option>
                        <option value="FORÇA AÉREA">FORÇA AÉREA</option>
                    </select>
                </div>
                <div class="col-lg-4">
                    <label class="form-label fw-semibold">OM 1ª Fase</label>
                    <select id="om_1_fase_filtro" name="om_1_fase_filtro[]" style="width: 100%" class="chosen-select" multiple>
                        <option value="">OM - 1ª Fase</option>
                        <?php
                        foreach ($todas_oms_1_fase as $value) {
                            echo "<option value='" . htmlspecialchars($value['abreviatura']) . "'>" . htmlspecialchars($value['abreviatura']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="form-label fw-semibold">Dependentes</label>
                    <select id="dependentes_filtro" name="dependentes_filtro" style="width: 100%" class="chosen-select">
                        <option value="">Dependentes</option>
                        <option value="nenhum">Nenhum Dependente</option>
                        <option value="possui_dependente">Com dependentes</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="form-label fw-semibold">RM Destino</label>
                    <select id="rm_destino" name="rm_destino[]" style="width: 100%" class="chosen-select" multiple>
                        <option value="">FISEMI - RM Destino</option>
                        <option value="1">1ª RM</option>
                        <option value="2">2ª RM</option>
                        <option value="3">3ª RM</option>
                        <option value="4">4ª RM</option>
                        <option value="5">5ª RM</option>
                        <option value="6">6ª RM</option>
                        <option value="7">7ª RM</option>
                        <option value="8">8ª RM</option>
                        <option value="9">9ª RM</option>
                        <option value="10">10ª RM</option>
                        <option value="11">11ª RM</option>
                        <option value="12">12ª RM</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="form-label fw-semibold">JISE</label>
                    <select id="jise_filtro" name="jise_filtro[]" style="width: 100%" class="chosen-select" multiple>
                        <option value="">JISE</option>
                        <option value="A">A</option>
                        <option value="B1">B1</option>
                        <option value="B2">B2</option>
                        <option value="C">C</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="form-label fw-semibold">JISR</label>
                    <select id="jisr_filtro" name="jisr_filtro[]" style="width: 100%" class="chosen-select" multiple>
                        <option value="">JISR</option>
                        <option value="A">A</option>
                        <option value="B1">B1</option>
                        <option value="B2">B2</option>
                        <option value="C">C</option>
                    </select>
                </div>
                <div class="col-lg-4">
                    <label class="form-label fw-semibold">OM 2ª Fase</label>
                    <select id="om_2_fase_filtro" name="om_2_fase_filtro[]" style="width: 100%" class="chosen-select" multiple>
                        <option value="">OM 2ª Fase</option>
                        <?php
                        foreach ($todas_oms_2_fase as $value) {
                            echo "<option value='" . htmlspecialchars($value['abreviatura']) . "'>" . htmlspecialchars($value['abreviatura']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="form-label fw-semibold">Prioridade Força</label>
                    <select id="prioridade_forca_filtro" name="prioridade_forca_filtro[]" style="width: 100%" class="chosen-select" multiple>
                        <option value="">Prioridade da Força</option>
                        <option value="EMA">EMA</option>
                        <option value="EAM">EAM</option>
                        <option value="MAE">MAE</option>
                        <option value="MEA">MEA</option>
                        <option value="AEM">AEM</option>
                        <option value="AME">AME</option>
                    </select>
                </div>
                <div class="col-lg-4">
                    <label class="form-label fw-semibold">Dt Seleção Complementar</label>
                    <select id="sel_complementar_filtro" name="sel_complementar_filtro[]" style="width: 100%" class="chosen-select" multiple>
                        <option value="">Dt Seleção Complementar</option>
                        <?php
                        foreach ($todas_dt_sel_complementar as $value) {
                            echo "<option value='" . htmlspecialchars($value['data']) . "'>" . trata_data($value['data']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="form-label fw-semibold">Resultado Revisão Médica</label>
                    <select id="resultado_revisao_filtro" name="resultado_revisao_filtro[]" style="width: 100%" class="chosen-select" multiple>
                        <option value="">Resultado Revisão Médica</option>
                        <option value="APTO">APTO</option>
                        <option value="INAPTO">INAPTO</option>
                        <option value="NÃO COMPARECEU">NÃO COMPARECEU</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="form-label fw-semibold">ISGRev</label>
                    <select id="isgr_filtro" name="isgr_filtro[]" style="width: 100%" class="chosen-select" multiple>
                        <option value="">Resultado ISGRev</option>
                        <option value="Não é o caso">Não é o caso</option>
                        <option value="A">A</option>
                        <option value="B1">B1</option>
                        <option value="B2">B2</option>
                        <option value="C">C</option>
                    </select>
                </div>
                <div class="col-lg-4">
                    <label class="form-label fw-semibold">Dt Incorporação</label>
                    <select id="incorporacao_filtro" name="incorporacao_filtro[]" style="width: 100%" class="chosen-select" multiple>
                        <option value="">Dt Incorporação</option>
                        <?php
                        foreach ($todas_dt_incorporacao as $value) {
                            echo "<option value='" . htmlspecialchars($value['data']) . "'>" . trata_data($value['data']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-12 mt-3">
                    <button type="button" id="btn-limpar" class="btn btn-outline-secondary">
                        <i class="fas fa-eraser me-2"></i>Limpar Filtros
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Dados -->
    <table id="tabela_dinamica" class="table table-hover" style="width:100%; font-size: 11px;">
        <thead>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Formação / Ano</th>
                <th>Grad Facul</th>
                <th>Ano Conc Resid</th>
                <th>Especialidade</th>
                <th>Nascimento</th>
                <th>Sit Militar</th>
                <th>Trans Fisemi</th>
            </tr>
        </thead>
        <tbody>
            <!-- Dados carregados via AJAX -->
        </tbody>
    </table>
</section>

<script type="text/javascript">
    var tabela;

    $(document).ready(function() {
        // Inicializa DataTables com Server-Side Processing
        tabela = $('#tabela_dinamica').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: 'controller/obrigatorios_ajax.php',
                type: 'GET',
                data: function(d) {
                    // Adiciona filtros customizados à requisição
                    d.voluntario_filtro = $('#voluntario_filtro').val();
                    d.dependentes_filtro = $('#dependentes_filtro').val();
                    d.faculdade_filtro = $('#faculdade_filtro').val();
                    d.jise_filtro = $('#jise_filtro').val();
                    d.jisr_filtro = $('#jisr_filtro').val();
                    d.distribuicao_filtro = $('#distribuicao_filtro').val();
                    d.om_1_fase_filtro = $('#om_1_fase_filtro').val();
                    d.resultado_revisao_filtro = $('#resultado_revisao_filtro').val();
                    d.isgr_filtro = $('#isgr_filtro').val();
                    d.sel_geral_filtro = $('#sel_geral_filtro').val();
                    d.comp_designacao_filtro = $('#comp_designacao_filtro').val();
                    d.incorporacao_filtro = $('#incorporacao_filtro').val();
                    d.sel_complementar_filtro = $('#sel_complementar_filtro').val();
                    d.situacao_militar = $('#situacao_militar').val();
                    d.rm_destino = $('#rm_destino').val();
                    d.especialidade_filtro = $('#especialidade_filtro').val();
                    d.prioridade_forca_filtro = $('#prioridade_forca_filtro').val();
                    d.data_selecao_geral_semestre_filtro = $('#data_selecao_geral_semestre_filtro').val();
                }
            },
            columns: [{
                    data: 'nome'
                },
                {
                    data: 'cpf'
                },
                {
                    data: 'formacao'
                },
                {
                    data: 'instituicao'
                },
                {
                    data: 'ano_residencia',
                    orderable: false
                },
                {
                    data: 'especialidade'
                },
                {
                    data: 'nascimento'
                },
                {
                    data: 'situacao_militar'
                },
                {
                    data: 'transferencia',
                    orderable: false
                }
            ],
            order: [
                [0, 'asc']
            ],
            pageLength: 50,
            lengthMenu: [50, 100, 200, 500],
            language: {
                processing: "Carregando...",
                search: "Buscar:",
                lengthMenu: "Mostrar _MENU_ registros",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "Mostrando 0 a 0 de 0 registros",
                infoFiltered: "(filtrado de _MAX_ registros)",
                loadingRecords: "Carregando...",
                zeroRecords: "Nenhum registro encontrado",
                emptyTable: "Nenhum dado disponível",
                paginate: {
                    first: "Primeiro",
                    previous: "Anterior",
                    next: "Próximo",
                    last: "Último"
                }
            }
        });

        // Aplica filtros automaticamente ao alterar qualquer select
        $('#filtros-container select').on('change', function() {
            tabela.ajax.reload();
        });

        // Botão Limpar Filtros
        $('#btn-limpar').click(function() {
            // Limpa todos os selects
            $('#filtros-container select').each(function() {
                $(this).val('');
                if ($(this).hasClass('chosen-select')) {
                    $(this).trigger('chosen:updated');
                }
            });
            // Recarrega a tabela sem filtros
            tabela.ajax.reload();
        });
    });
</script>

<?php
include_once 'footer.php';
?>