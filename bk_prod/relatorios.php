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

<!-- LISTA DE PRESENÇA -->
<section id="contact" class="contact">
    <div class="container">
        <div class="row  justify-content-center">
            <div class="col-lg-12">

                <form action="mpdf/relatorio_lista_presenca.php" method="POST" target="_blank" role="form" class="card">
                    <h2>LISTA DE PRESENÇA</h2>

                    <div class="row">

                        <div class="col-md-6 form-group">
                            <b>IE Graduação</b>
                            <select name="nome_instituicao_ensino" class="form-control">
                                <option value="">Nome da Instituição de Ensino</option>
                                <?php
                                foreach ($lista_ie_graduacao as $value) {
                                    echo "<option value='" . $value['nome'] . " ' >" . $value['nome'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <b>Data</b>
                            <input type="text" class="form-control" name="data_lista_presenca">
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Situação Militar</b>
                            <select name="situacao_militar1" class="form-control">
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
                        <div class="col-md-4 form-group">
                            <b>Situação Militar #2</b>
                            <select name="situacao_militar2" class="form-control">
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
                        <div class="col-md-4 form-group">
                            <b>Situação Militar #3</b>
                            <select name="situacao_militar3" class="form-control">
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
                        <div class="col-md-4 form-group">
                            <b>Situação Militar #4</b>
                            <select name="situacao_militar4" class="form-control">
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
                        <div class="col-md-4 form-group">
                            <b>Situação Militar #5</b>
                            <select name="situacao_militar5" class="form-control">
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
                        <div class="col-md-4 form-group">
                            <b>Situação Militar #6</b>
                            <select name="situacao_militar6" class="form-control">
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

                        <div class="text-center">
                            <br>
                            <button type="submit" style="width: 100%;" class="btn btn-primary btn-block">GERAR RELATÓRIO</button>
                        </div>
                        <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave'] . "criptografia"); ?>">
                </form>
            </div>
        </div>
    </div>
</section>

<!-- RESULTADO SELEÇÃO INICIAL -->
<section id="contact" class="contact">
    <div class="container">
        <div class="row  justify-content-center">
            <div class="col-lg-12">

                <form action="mpdf/relatorio_inspecao_de_saude.php" method="POST" target="_blank" role="form" class="card">
                    <h2>RESULTADO DA INSPEÇÃO DE SAÚDE</h2>
                    <br>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <b>Título</b>
                            <input type="text" class="form-control" name="titulo" value="PROCESSO SELETIVO PARA MFDV 20XX/20XX - OBRIGATÓRIO">
                        </div>

                        <div class="col-md-6 form-group">
                            <b>Subtítulo</b>
                            <input type="text" class="form-control" name="subtitulo" value="RESULTADO INSPEÇÃO DE SAÚDE">
                        </div>

                        <div class="col-md-12 form-group">
                            <b>Data do Documento</b>
                            <input type="text" class="form-control" name="documento_dia" value="Porto Alegre, 23 de outubro de 2025.">
                        </div>

                        <div class="col-md-12 form-group">
                            <b>1º Parágrafo</b>
                            <textarea type="text" class="form-control" name="paragrafo_um">O Comandante da 3ª Região Militar divulga o resultado referente à Inspeção de Saúde, conforme anexo "A" (Calendário Geral de Atividades) do Aviso de Convocação Nr XX-SSMR/3, de XX de junho de 20XX.</textarea>
                        </div>

                        <div class="col-md-12 form-group">
                            <b>2º Parágrafo</b>
                            <textarea type="text" class="form-control" name="paragrafo_dois">O período para interposição de Recursos da Inspeção de Saúde será no dia XX de outubro de 20XX das 0830h às 1130h e no dia XX de outubro de 20XX das 0930h às 1130h e das 1300h às 1630h, na Comissão de Seleção Especial - Rua dos Andradas 551, Centro Histórico, Porto Alegre.</textarea>
                        </div>

                        <div class="col-md-6 form-group">
                            <b>Data Inicial da Inspeção</b>
                            <input type="date" class="form-control" name="date_inicial">
                        </div>

                        <div class="col-md-6 form-group">
                            <b>Data Final da Inspeção</b>
                            <input type="date" class="form-control" name="date_final">
                        </div>

                        <div class="text-center">
                            <br>
                            <button type="submit" style="width: 100%;" class="btn btn-primary btn-block">GERAR RELATÓRIO</button>
                        </div>
                        <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave'] . "criptografia"); ?>">
                </form>
            </div>
        </div>
    </div>
</section>

<section id="contact" class="contact">
    <div class="container">
        <div class="row  justify-content-center">
            <div class="col-lg-12">
                <form action="mpdf/relatorio_determinada_data.php" method="POST" target="_blank" role="form" class="card">
                    <h2>RELATÓRIO JISE/JISR</h2>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <b>Data</b>
                            <input type="text" class="form-control" name="data_sel_geral">
                        </div>
                    </div>
                    <div class="text-center">
                        <br>
                        <button type="submit" style="width: 100%;" class="btn btn-primary btn-block">GERAR RELATÓRIO JISE/JISR</button>
                    </div>
                    <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave'] . "criptografia"); ?>">
                </form>
            </div>
        </div>
    </div>
</section>

<?php
include_once 'footer.php';
?>