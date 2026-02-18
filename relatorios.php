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

                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Formato de Saída</label>
                            <select name="formato_saida" class="form-select formato-saida-select">
                                <option value="pdf">PDF</option>
                                <option value="doc">Word (.doc)</option>
                            </select>
                        </div>
                    </div>

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
                            <label class="form-label fw-semibold">Data Inicial da Inspeção <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="date_inicial" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Data Final da Inspeção <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="date_final" required>
                        </div>
                    </div>

                    <input name="crip" type="hidden" value="<?php echo hash('sha256', $_SESSION['chave'] . "criptografia"); ?>">

                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Formato de Saída</label>
                            <select name="formato_saida" class="form-select formato-saida-select">
                                <option value="pdf">PDF</option>
                                <option value="doc">Word (.doc)</option>
                            </select>
                        </div>
                    </div>

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

                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Formato de Saída</label>
                            <select name="formato_saida" class="form-select formato-saida-select">
                                <option value="pdf">PDF</option>
                                <option value="doc">Word (.doc)</option>
                            </select>
                        </div>
                    </div>

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

<section id="contact" class="contact">
    <div class="container">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-file-signature me-2"></i>Aditamento ao BAR - Convocacao EAS</h5>
            </div>
            <div class="card-body">
                <form action="mpdf/relatorio_aditamento_bar.php" method="POST" target="_blank" role="form">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Cabecalho do Documento</label>
                            <input type="text" class="form-control" name="texto_cabecalho" value="Quartel em Porto Alegre - RS, XX de XXXX de 20XX - XXXX-feira." required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Nr do Aditamento</label>
                            <input type="text" class="form-control" name="nr_aditamento" placeholder="Ex: 2" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Nr do BAR</label>
                            <input type="text" class="form-control" name="nr_bar" placeholder="Ex: 4" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Data do BAR</label>
                            <input type="text" class="form-control" name="quando_bar" placeholder="Ex: 22 JAN 25" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Tempo de Servico (Incorporacao)</label>
                            <input type="text" class="form-control" name="tempo_servico" value="Por 12 meses, a contar de 1º FEV 25" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tipo de Distribuicao</label>
                            <select name="tipo_distribuicao" class="form-select" required>
                                <option value="DESIGNADO - 1ª Distribuição">DESIGNADO - 1ª Distribuição</option>
                                <option value="DESIGNADO - 2ª Distribuição">DESIGNADO - 2ª Distribuição</option>
                                <option value="MAJORADO - 1ª Distribuição">MAJORADO - 1ª Distribuição</option>
                                <option value="MAJORADO - 2ª Distribuição">MAJORADO - 2ª Distribuição</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ano Filtro (data_comparecimento_designacao)</label>
                            <input type="text" class="form-control" name="ano_filtro" value="<?php echo date('Y'); ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nome do General</label>
                            <input type="text" class="form-control" name="nome_general" value="Gen Div RODRIGO FERRAZ SILVA" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nome do Coronel</label>
                            <input type="text" class="form-control" name="nome_coronel" value="MARCIO RODRIGO RIBAS - CEL" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Titulo Item 1 (3a Parte)</label>
                            <input type="text" class="form-control" name="titulo_item1" value="1. CONVOCAÇÃO DE MÉDICOS DO SERVIÇO MILITAR OBRIGATÓRIO (SMO) PARA O ESTÁGIO DE ADAPTAÇÃO E SERVIÇO (EAS)" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Paragrafo Principal</label>
                            <textarea class="form-control" name="paragrafo_principal" rows="4" required>O Comandante da 3ª Região Militar, de acordo com prescrito nos Art 27 e 28 do RLMFDV, por atenderem as condições estabelecidas no parágrafo 2 do Art 14 do mesmo Regulamento e as prescrições do Plano Geral de Convocação para o Serviço Militar Inicial nas Forças Armadas em 2024-2025 (PGC 2024-2025) para a prestação do Serviço Militar Inicial sob a forma de Estágio de Adaptação e Serviço (EAS). AUTORIZOU as Organizações Militares (OM) responsáveis pela 1ª Fase do Estágio (EAS) a INCORPORAR no serviço militar ativo, os médicos e dentistas a seguir nominados, nas Organizações Militares abaixo discriminadas:</textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Providencia a.</label>
                            <textarea class="form-control" name="providencia_a" rows="2" required>As OM deverão Cadastrar as Fichas Cadastro (F Cdtr) do SiCAPEx dos Oficiais Temporários, conforme a Portaria 147-DGP, de 23 DEZ 11 (IR 30-87):</textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Providencia b.</label>
                            <textarea class="form-control" name="providencia_b" rows="2" required>Os interessados tomem conhecimento e providências: e</textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Providencia c.</label>
                            <textarea class="form-control" name="providencia_c" rows="2" required>Em consequência, as OM de 1ª Fase do EAS e os demais órgãos envolvidos devem adotar as seguintes medidas administrativas:</textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Providencia c.1)</label>
                            <textarea class="form-control" name="providencia_c1" rows="2" required>observar o prescrito no Art 143 da Portaria-DGP/C Ex nº 407, de 25 JUL 22;</textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Providencia c.2)</label>
                            <textarea class="form-control" name="providencia_c2" rows="3" required>publicar em BI a incorporação dos Oficiais MFDV Temporários convocados e cadastrar na BDCP (Base de Dados Corporativa de Pessoal) via SiCAPEx, conforme os incisos I e II do Art 5º da Port 147-DGP, de 23 SET 11 (IR 30-87) combinado com as letras c) e f) do inciso I do Art 208 da Portaria- DGP/C Ex nº 407, de 25 JUL 22. Especial atenção deve ser conferida aos dados individuais, que devem ser extraídos à luz da Certidão de Nascimento/Casamento (nome, filiação, data e local de nascimento), às especialidades e aos cursos e estágios que possuem; e</textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Providencia c.3)</label>
                            <textarea class="form-control" name="providencia_c3" rows="2" required>As OM de 1ª Fase, na data da incorporação, deverão providenciar a identificação do MFDV (Art 205 e 206 da Port 251-DGP, de 11 NOV 09 - NT 13-DSM).</textarea>
                        </div>
                    </div>

                    <input name="crip" type="hidden" value="<?php echo hash('sha256', $_SESSION['chave'] . "criptografia"); ?>">

                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Formato de Saída</label>
                            <select name="formato_saida" class="form-select formato-saida-select">
                                <option value="pdf">PDF</option>
                                <option value="doc">Word (.doc)</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-file-pdf me-2"></i>Gerar Aditamento BAR
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
document.querySelectorAll('.formato-saida-select').forEach(function(select) {
    select.addEventListener('change', function() {
        var btn = this.closest('form').querySelector('button[type="submit"] i');
        var icones = { pdf: 'fa-file-pdf', doc: 'fa-file-word' };
        btn.className = 'fas ' + (icones[this.value] || 'fa-file-pdf') + ' me-2';
    });
});
</script>

<?php
include_once 'footer.php';
?>