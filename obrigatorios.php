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
                <h1><b><i class="fas fa-user-md"></i> Obrigatórios</b></h1>
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
                <!-- Linha 1: Situação Militar + Distribuição (6+6=12) -->
                <div class="col-lg-6">
                    <label class="form-label fw-semibold">Situação Militar</label>
                    <select id="situacao_militar" name="situacao_militar[]" style="width: 100%" class="chosen-select" multiple>
                        <option value="">Situação Militar</option>
                        <option value="Em Débito - REFRATÁRIO">Em Débito - REFRATÁRIO</option>
                        <option value="Em Débito - INSUBMISSO">Em Débito - INSUBMISSO</option>
                        <option value="Em Dia - JUDICIAL">Em Dia - JUDICIAL</option>
                        <option value="Em Dia - JUDICIAL LIMINAR">Em Dia - JUDICIAL LIMINAR</option>
                        <option value="Em Dia - TRANSFERÊNCIA FISEMI">Em Dia - TRANSFERÊNCIA FISEMI</option>
                        <option value="Em Dia - ALISTADO MFDV (FISEMI)">Em Dia - ALISTADO MFDV (FISEMI)</option>
                        <option value="Em Dia - ADIADO CURSANDO RESIDÊNCIA">Em Dia - ADIADO CURSANDO RESIDÊNCIA</option>
                        <option value="Em Dia - B1 - INSP SAU - RETORNAR PRÓXIMA SELEÇÃO">Em Dia - B1 - INSP SAU - RETORNAR PRÓXIMA SELEÇÃO</option>
                        <option value="Quite SMO - EXCESSO - CONTINGENTE">Quite SMO - EXCESSO - CONTINGENTE</option>
                        <option value="Quite SMO - EXCESSO - INCAPAZ SAÚDE">Quite SMO - EXCESSO - INCAPAZ SAÚDE</option>
                        <option value="Quite SMO - DESOBRIGADO - MAIOR 38 ANOS">Quite SMO - DESOBRIGADO - MAIOR 38 ANOS</option>
                        <option value="Quite SMO - DESOBRIGADO - JÁ RESERVISTA">Quite SMO - DESOBRIGADO - JÁ RESERVISTA</option>
                        <option value="Quite SMO - DESOBRIGADO - NATURALIZADO">Quite SMO - DESOBRIGADO - NATURALIZADO</option>
                        <option value="Quite SMO - CONVOCADO">Quite SMO - CONVOCADO</option>
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

                <!-- Linha 2: Datas de seleção (4+4+4=12) -->
                <div class="col-lg-4">
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
                <div class="col-lg-4">
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

                <!-- Linha 3: Especialidade + IE Graduação (8+4=12) -->
                <div class="col-lg-8">
                    <label class="form-label fw-semibold">Especialidade</label>
                    <select id="especialidade_filtro" name="especialidade_filtro[]" style="width: 100%" class="chosen-select" multiple>
                        <option value="">Especialidade</option>
                        <option value="todas_espec">Todas as Especialidades</option>
                        <?php
                        foreach ($todas_espec as $value) {
                            echo "<option value='" . htmlspecialchars($value['nome']) . "'>" . htmlspecialchars($value['nome']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-lg-4">
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

                <!-- Linha 4: OMs + Dt Comp Designação (4+4+4=12) -->
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

                <!-- Linha 5: Filtros pequenos (6 × col-2 = 12) -->
                <div class="col-lg-2">
                    <label class="form-label fw-semibold">Voluntário</label>
                    <select id="voluntario_filtro" name="voluntario_filtro[]" style="width: 100%" class="chosen-select" multiple>
                        <option value="SIM">Sim</option>
                        <option value="NÃO">Não</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="form-label fw-semibold">Já foi refratário</label>
                    <select id="ja_foi_refratorio_filtro" name="ja_foi_refratorio_filtro" style="width: 100%" class="chosen-select">
                        <option value="">Todos</option>
                        <option value="SIM">Sim</option>
                        <option value="NÃO">Não</option>
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

                <!-- Linha 6: Prioridade + Revisão + ISGRev + Incorporação (3+3+3+3=12) -->
                <div class="col-lg-3">
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
                <div class="col-lg-3">
                    <label class="form-label fw-semibold">Resultado Revisão Médica</label>
                    <select id="resultado_revisao_filtro" name="resultado_revisao_filtro[]" style="width: 100%" class="chosen-select" multiple>
                        <option value="">Resultado Revisão Médica</option>
                        <option value="APTO">APTO</option>
                        <option value="INAPTO">INAPTO</option>
                        <option value="NÃO COMPARECEU">NÃO COMPARECEU</option>
                    </select>
                </div>
                <div class="col-lg-3">
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
                <div class="col-lg-3">
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
                    <button type="button" id="btn-limpar" class="btn btn-outline-secondary border border-secondary">
                        <i class="fas fa-eraser me-2"></i>Limpar Filtros
                    </button>
                    <button type="button" id="btn-exportar-excel" class="btn btn-primary ms-2">
                        <i class="fas fa-file-excel me-2"></i>Exportar Excel
                    </button>
                    <small class="text-muted ms-3">
                        <i class="fas fa-info-circle me-1"></i>Dica: Clique nas colunas para adicionar ordenação. Clique novamente para inverter (ASC/DESC) ou remover
                    </small>
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
    // Array global para ordenações ativas (inicia vazio - usuário define ordenação clicando nas colunas)
    var activeOrders = [];

    $(document).ready(function() {
        // Inicializa DataTables com Server-Side Processing
        tabela = $('#tabela_dinamica').DataTable({
            processing: true,
            serverSide: true,
            ordering: false, // DESABILITA ordenação do DataTables completamente
            order: [], // Remove qualquer ordenação inicial
            orderFixed: false, // Remove ordenação fixa
            orderClasses: false, // Remove classes de ordenação
            ajax: {
                url: 'controller/obrigatorios_ajax.php',
                type: 'GET',
                cache: false, // Desabilita cache do navegador
                data: function(d) {
                    // Cache busting - força requisição nova a cada chamada
                    d._cache_bust = new Date().getTime();

                    // Adiciona filtros customizados à requisição
                    d.voluntario_filtro = $('#voluntario_filtro').val();
                    d.ja_foi_refratorio_filtro = $('#ja_foi_refratorio_filtro').val();
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

                    // ORDENAÇÃO CUMULATIVA: Envia as ordenações acumuladas
                    d.order = activeOrders.map(function(ord) {
                        return {column: ord[0], dir: ord[1]};
                    });
                    console.log('✓ Enviando ordenações:', activeOrders);
                    console.log('✓ Dados enviados (order):', d.order);
                },
                dataSrc: function(json) {
                    console.log('✓ Dados recebidos do servidor:', json);
                    console.log('✓ Primeiros 3 registros:', json.data.slice(0, 3));

                    // Log dos nomes para verificar ordem
                    const nomesRecebidos = json.data.slice(0, 5).map(function(row) {
                        return $(row.nome).text();
                    });
                    console.log('✓ Primeiros 5 nomes recebidos:', nomesRecebidos);

                    return json.data;
                }
            },
            drawCallback: function(settings) {
                // Log dos nomes renderizados na tabela
                const nomesRenderizados = [];
                $('#tabela_dinamica tbody tr').slice(0, 5).each(function() {
                    const nome = $(this).find('td:eq(0)').text().trim();
                    nomesRenderizados.push(nome);
                });
                console.log('✓ Primeiros 5 nomes RENDERIZADOS na tabela:', nomesRenderizados);
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
                    data: 'ano_residencia'
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
                    data: 'transferencia'
                }
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

        // ==================== ORDENAÇÃO CUMULATIVA MANUAL ====================
        // Intercepta cliques nos headers das colunas
        $('#tabela_dinamica thead th').on('click', function() {
            var columnIndex = $(this).index();
            var columnName = $(this).text().trim().split('(')[0].trim();

            console.log('=================================');
            console.log('🖱️ Clique detectado na coluna:', columnName, '(índice:', columnIndex + ')');
            console.log('📊 activeOrders ANTES:', JSON.parse(JSON.stringify(activeOrders)));

            // Verifica se a coluna já está nas ordenações ativas
            var existingIndex = -1;
            for (var i = 0; i < activeOrders.length; i++) {
                if (activeOrders[i][0] === columnIndex) {
                    existingIndex = i;
                    break;
                }
            }

            if (existingIndex !== -1) {
                // Se já existe, verifica a direção atual
                var currentDir = activeOrders[existingIndex][1];

                if (currentDir === 'asc') {
                    // ASC → DESC
                    activeOrders[existingIndex][1] = 'desc';
                    console.log('🔄 Alternando: ASC → DESC');
                } else {
                    // DESC → REMOVE
                    activeOrders.splice(existingIndex, 1);
                    console.log('🗑️ Removendo coluna da ordenação (DESC → REMOVE)');
                }
            } else {
                // Se não existe, adiciona à lista com direção 'asc'
                activeOrders.push([columnIndex, 'asc']);
                console.log('➕ Nova coluna adicionada à ordenação (prioridade ' + activeOrders.length + ')');
            }

            console.log('📊 activeOrders DEPOIS:', JSON.parse(JSON.stringify(activeOrders)));

            // Atualiza indicadores visuais
            updateOrderIndicators();

            // Recarrega a tabela com as novas ordenações
            console.log('🔃 Recarregando tabela...');
            tabela.ajax.reload(function() {
                console.log('✅ Tabela recarregada com sucesso');
            });
            console.log('=================================');
        });

        // Função para atualizar os indicadores visuais de ordenação
        function updateOrderIndicators() {
            console.log('🔄 Atualizando indicadores visuais. activeOrders:', activeOrders);

            // Remove todos os indicadores existentes
            $('#tabela_dinamica thead th').removeClass('sorting_asc sorting_desc').addClass('sorting');
            $('#tabela_dinamica thead th .order-number').remove();

            // Se não há ordenações, não mostra indicadores
            if (activeOrders.length === 0) {
                console.log('⚠️ Nenhuma ordenação ativa - sem indicadores visuais');
                return;
            }

            // Adiciona indicadores para cada ordenação ativa
            activeOrders.forEach(function(order, index) {
                var columnIndex = order[0];
                var direction = order[1];
                var $th = $('#tabela_dinamica thead th').eq(columnIndex);
                var columnName = $th.text().trim().split('(')[0].trim(); // Remove números antigos

                console.log('➕ Adicionando indicador:', {
                    coluna: columnName,
                    index: columnIndex,
                    ordem: index + 1,
                    direcao: direction
                });

                $th.removeClass('sorting').addClass('sorting_' + direction);

                // SEMPRE adiciona número da ordem (mesmo se for apenas 1 ordenação)
                var orderBadge = '<span class="order-number" style="display: inline-block; background: #007bff; color: white; border-radius: 50%; width: 20px; height: 20px; text-align: center; line-height: 20px; font-size: 0.75em; font-weight: bold; margin-left: 5px;">(' + (index + 1) + ')</span>';
                $th.append(orderBadge);
            });

            console.log('✅ Indicadores atualizados com sucesso');
        }

        // Botão para limpar todas as ordenações
        $('<button class="btn btn-sm btn-secondary ms-2" id="btn-limpar-ordenacao" title="Limpar todas as ordenações"><i class="fas fa-sort-amount-up-alt"></i> Limpar Ordenação</button>')
            .insertAfter('#btn-limpar')
            .on('click', function() {
                activeOrders = []; // Limpa todas as ordenações
                updateOrderIndicators();
                tabela.ajax.reload();
            });

        // Inicializa os indicadores visuais
        updateOrderIndicators();

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

        // Botão Exportar Excel
        $('#btn-exportar-excel').click(function() {
            // Monta a URL com os filtros atuais
            var params = new URLSearchParams();

            var filtros = {
                'voluntario_filtro[]': $('#voluntario_filtro').val(),
                'dependentes_filtro': $('#dependentes_filtro').val(),
                'faculdade_filtro[]': $('#faculdade_filtro').val(),
                'jise_filtro[]': $('#jise_filtro').val(),
                'jisr_filtro[]': $('#jisr_filtro').val(),
                'distribuicao_filtro[]': $('#distribuicao_filtro').val(),
                'om_1_fase_filtro[]': $('#om_1_fase_filtro').val(),
                'resultado_revisao_filtro[]': $('#resultado_revisao_filtro').val(),
                'isgr_filtro[]': $('#isgr_filtro').val(),
                'sel_geral_filtro[]': $('#sel_geral_filtro').val(),
                'comp_designacao_filtro[]': $('#comp_designacao_filtro').val(),
                'incorporacao_filtro[]': $('#incorporacao_filtro').val(),
                'sel_complementar_filtro[]': $('#sel_complementar_filtro').val(),
                'situacao_militar[]': $('#situacao_militar').val(),
                'rm_destino[]': $('#rm_destino').val(),
                'especialidade_filtro[]': $('#especialidade_filtro').val(),
                'prioridade_forca_filtro[]': $('#prioridade_forca_filtro').val(),
                'data_selecao_geral_semestre_filtro': $('#data_selecao_geral_semestre_filtro').val()
            };

            // Adiciona apenas filtros com valor
            for (var key in filtros) {
                var val = filtros[key];
                if (val && val.length > 0) {
                    if (Array.isArray(val)) {
                        val.forEach(function(v) {
                            if (v) params.append(key, v);
                        });
                    } else {
                        params.append(key, val);
                    }
                }
            }

            // Abre a URL de exportação
            window.location.href = 'controller/obrigatorios_export_excel.php?' + params.toString();
        });
    });
</script>

<?php
include_once 'footer.php';
?>