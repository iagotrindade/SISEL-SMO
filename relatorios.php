<?php
include_once 'header.php';
include_once 'dao/conecta_banco.php';
include_once 'models/Obrigatorio.php';
include_once 'models/Om.php';
include_once 'models/Arquivo.php';
include_once 'models/Oficio.php';
include_once 'dao/ObrigatorioDAO.php';
include_once 'dao/ArquivoDAO.php';
include_once 'dao/AuxiliarDAO.php';
include_once 'dao/OficioDAO.php';

if (!isset($_SESSION['id_usuario_smo'])) {
    erro($BASE_URL, 2, 63216754, $pagina_atual, "usuario_nao_logado", "Página não encontrada!");
    exit();
}

$auxiliar = new AuxiliarDAO($conexao);
$lista_ie_graduacao = $auxiliar->findAllCidInst();

?>

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h1><b><i class="fas fa-file-alt"></i> Geração de Publicações e Documentos</b></h1>
            </div>
        </div>
    </section>
</main>

<section id="contact" class="contact">
    <div class="container">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Lista de Presença</h5>
            </div>
            <div class="card-body">
                <form action="mpdf/relatorio_lista_presenca.php" method="POST" target="_blank" role="form">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">IE Graduação</label>
                            <select name="nome_instituicao_ensino" class="form-select">
                                <option value="">Nome da Instituição de Ensino</option>
                                <?php
                                foreach ($lista_ie_graduacao as $value) {
                                    echo "<option value='" . htmlspecialchars($value['nome']) . "'>" . htmlspecialchars($value['nome']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Data</label>
                            <input type="text" class="form-control" name="data_lista_presenca" placeholder="DD/MM/AAAA">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Situação Militar #1</label>
                            <select name="situacao_militar1" class="form-select">
                                <option value="">Selecione a Opção</option>
                                <option value="Em Débito - REFRATÁRIO">Em Débito - REFRATÁRIO</option>
                                <option value="Em Débito - INSUBMISSO">Em Débito - INSUBMISSO</option>
                                <option value="Em Dia - JUDICIAL">Em Dia - JUDICIAL</option>
                                <option value="Em Dia - ALISTADO MFDV (FISEMI)">Em Dia - ALISTADO MFDV (FISEMI)</option>
                                <option value="Em Dia - TRANSFERÊNCIA FISEMI">Em Dia - TRANSFERÊNCIA FISEMI</option>
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
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Situação Militar #2</label>
                            <select name="situacao_militar2" class="form-select">
                                <option value="">Selecione a Opção</option>
                                <option value="Em Débito - REFRATÁRIO">Em Débito - REFRATÁRIO</option>
                                <option value="Em Débito - INSUBMISSO">Em Débito - INSUBMISSO</option>
                                <option value="Em Dia - JUDICIAL">Em Dia - JUDICIAL</option>
                                <option value="Em Dia - ALISTADO MFDV (FISEMI)">Em Dia - ALISTADO MFDV (FISEMI)</option>
                                <option value="Em Dia - TRANSFERÊNCIA FISEMI">Em Dia - TRANSFERÊNCIA FISEMI</option>
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
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Situação Militar #3</label>
                            <select name="situacao_militar3" class="form-select">
                                <option value="">Selecione a Opção</option>
                                <option value="Em Débito - REFRATÁRIO">Em Débito - REFRATÁRIO</option>
                                <option value="Em Débito - INSUBMISSO">Em Débito - INSUBMISSO</option>
                                <option value="Em Dia - JUDICIAL">Em Dia - JUDICIAL</option>
                                <option value="Em Dia - ALISTADO MFDV (FISEMI)">Em Dia - ALISTADO MFDV (FISEMI)</option>
                                <option value="Em Dia - TRANSFERÊNCIA FISEMI">Em Dia - TRANSFERÊNCIA FISEMI</option>
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
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Situação Militar #4</label>
                            <select name="situacao_militar4" class="form-select">
                                <option value="">Selecione a Opção</option>
                                <option value="Em Débito - REFRATÁRIO">Em Débito - REFRATÁRIO</option>
                                <option value="Em Débito - INSUBMISSO">Em Débito - INSUBMISSO</option>
                                <option value="Em Dia - JUDICIAL">Em Dia - JUDICIAL</option>
                                <option value="Em Dia - ALISTADO MFDV (FISEMI)">Em Dia - ALISTADO MFDV (FISEMI)</option>
                                <option value="Em Dia - TRANSFERÊNCIA FISEMI">Em Dia - TRANSFERÊNCIA FISEMI</option>
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
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Situação Militar #5</label>
                            <select name="situacao_militar5" class="form-select">
                                <option value="">Selecione a Opção</option>
                                <option value="Em Débito - REFRATÁRIO">Em Débito - REFRATÁRIO</option>
                                <option value="Em Débito - INSUBMISSO">Em Débito - INSUBMISSO</option>
                                <option value="Em Dia - JUDICIAL">Em Dia - JUDICIAL</option>
                                <option value="Em Dia - ALISTADO MFDV (FISEMI)">Em Dia - ALISTADO MFDV (FISEMI)</option>
                                <option value="Em Dia - TRANSFERÊNCIA FISEMI">Em Dia - TRANSFERÊNCIA FISEMI</option>
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
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Situação Militar #6</label>
                            <select name="situacao_militar6" class="form-select">
                                <option value="">Selecione a Opção</option>
                                <option value="Em Débito - REFRATÁRIO">Em Débito - REFRATÁRIO</option>
                                <option value="Em Débito - INSUBMISSO">Em Débito - INSUBMISSO</option>
                                <option value="Em Dia - JUDICIAL">Em Dia - JUDICIAL</option>
                                <option value="Em Dia - ALISTADO MFDV (FISEMI)">Em Dia - ALISTADO MFDV (FISEMI)</option>
                                <option value="Em Dia - TRANSFERÊNCIA FISEMI">Em Dia - TRANSFERÊNCIA FISEMI</option>
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
                    </div>

                    <input name="crip" type="hidden" value="<?php echo hash('sha256', $_SESSION['chave'] . "criptografia"); ?>">

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-file-pdf me-2"></i>Gerar Relatório
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section id="contact" class="contact">
    <div class="container">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-heartbeat me-2"></i>Resultado da Inspeção de Saúde</h5>
            </div>
            <div class="card-body">
                <form action="mpdf/relatorio_inspecao_de_saude.php" method="POST" target="_blank" role="form">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Título</label>
                            <input type="text" class="form-control" name="titulo" value="PROCESSO SELETIVO PARA MFDV 20XX/20XX - OBRIGATÓRIO">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Subtítulo</label>
                            <input type="text" class="form-control" name="subtitulo" value="RESULTADO INSPEÇÃO DE SAÚDE">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Data do Documento</label>
                            <input type="text" class="form-control" name="documento_dia" value="Porto Alegre, 23 de outubro de 2025.">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">1º Parágrafo</label>
                            <textarea class="form-control" name="paragrafo_um" rows="3">O Comandante da 3ª Região Militar divulga o resultado referente à Inspeção de Saúde, conforme anexo "A" (Calendário Geral de Atividades) do Aviso de Convocação Nr XX-SSMR/3, de XX de junho de 20XX.</textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">2º Parágrafo</label>
                            <textarea class="form-control" name="paragrafo_dois" rows="3">O período para interposição de Recursos da Inspeção de Saúde será no dia XX de outubro de 20XX das 0830h às 1130h e no dia XX de outubro de 20XX das 0930h às 1130h e das 1300h às 1630h, na Comissão de Seleção Especial - Rua dos Andradas 551, Centro Histórico, Porto Alegre.</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Data Inicial da Inspeção</label>
                            <input type="date" class="form-control" name="date_inicial">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Data Final da Inspeção</label>
                            <input type="date" class="form-control" name="date_final">
                        </div>
                    </div>

                    <input name="crip" type="hidden" value="<?php echo hash('sha256', $_SESSION['chave'] . "criptografia"); ?>">

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-file-pdf me-2"></i>Gerar Relatório
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section id="contact" class="contact">
    <div class="container">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-notes-medical me-2"></i>Relatório JISE/JISR</h5>
            </div>
            <div class="card-body">
                <form action="mpdf/relatorio_determinada_data.php" method="POST" target="_blank" role="form">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Data</label>
                            <input type="text" class="form-control" name="data_sel_geral" placeholder="DD/MM/AAAA">
                        </div>
                    </div>

                    <input name="crip" type="hidden" value="<?php echo hash('sha256', $_SESSION['chave'] . "criptografia"); ?>">

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-file-pdf me-2"></i>Gerar Relatório JISE/JISR
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
include_once 'footer.php';
?>