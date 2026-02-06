<?php
ob_start();
error_reporting(0);

if (!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Obrigatorio.php';
include_once '../models/Om.php';
include_once '../dao/ObrigatorioDAO.php';
include_once '../dao/LogDAO.php';
include("mpdf60/mpdf.php");

if ($_SESSION['perfil_smo'] != "admin") erro($BASE_URL, 2, 6549874952, $pagina_atual, "usuario!admin", "Nao foi possivel acessar a pagina!");

// Receber dados do formulario
$texto_cabecalho = filtra_campo_post('texto_cabecalho');
$nr_aditamento = filtra_campo_post('nr_aditamento');
$nr_bar = filtra_campo_post('nr_bar');
$quando_bar = filtra_campo_post('quando_bar');
$tempo_servico = filtra_campo_post('tempo_servico');
$tipo_distribuicao = filtra_campo_post('tipo_distribuicao');
$ano_filtro = filtra_campo_post('ano_filtro');
$nome_general = filtra_campo_post('nome_general');
$nome_coronel = filtra_campo_post('nome_coronel');

// Novos campos de texto editaveis
$titulo_item1 = filtra_campo_post('titulo_item1');
$paragrafo_principal = filtra_campo_post('paragrafo_principal');
$providencia_a = filtra_campo_post('providencia_a');
$providencia_b = filtra_campo_post('providencia_b');
$providencia_c = filtra_campo_post('providencia_c');
$providencia_c1 = filtra_campo_post('providencia_c1');
$providencia_c2 = filtra_campo_post('providencia_c2');
$providencia_c3 = filtra_campo_post('providencia_c3');

// Validacoes basicas
if (empty($nr_aditamento)) erro($BASE_URL, 1, 34755867, $pagina_atual, "nr_aditamento_vazio", "Nr do Aditamento e obrigatorio!");
if (empty($nr_bar)) erro($BASE_URL, 1, 34755868, $pagina_atual, "nr_bar_vazio", "Nr do BAR e obrigatorio!");
if (empty($ano_filtro)) $ano_filtro = date('Y');

$logDAO = new LogDAO($conexao);
$obrigatorioDAO = new ObrigatorioDAO($conexao);

// Buscar obrigatorios com os filtros usando o DAO
$obrigatorios = $obrigatorioDAO->findParaAditamentoBAR($tipo_distribuicao, $ano_filtro);

$total_obrigatorios = count($obrigatorios);

// Se nao encontrou registros, exibe mensagem
if ($total_obrigatorios == 0) {
    echo "<h3>Nenhum obrigatorio encontrado com os filtros:</h3>";
    echo "<p><b>Distribuicao:</b> " . htmlspecialchars($tipo_distribuicao) . "</p>";
    echo "<p><b>Ano (data_comparecimento_designacao):</b> " . htmlspecialchars($ano_filtro) . "</p>";
    echo "<p>Verifique se existem registros com esses criterios no banco de dados.</p>";
    exit();
}

// Funcao para formatar CPF
function formatarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    if (strlen($cpf) == 11) {
        return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
    }
    return $cpf;
}

// Funcao para formatar data
function formatarDataBR($data) {
    if (empty($data)) return '';
    $timestamp = strtotime($data);
    return date('d/m/Y', $timestamp);
}

// Observacao fixa
$obs_tipo = "1ª Designacao - Servico Militar Obrigatorio.";

$html = "
<style>
    body { font-family: 'Times New Roman', Times, serif; font-size: 10pt; }
    .material-restrito { font-size: 8pt; text-align: center; border: 1px solid #CC0000; padding: 3px; margin-bottom: 10px; color: #CC0000; }
    .cabecalho { font-size: 10pt; text-align: center; line-height: 1.3; margin-bottom: 8px; }
    .data-documento { font-size: 10pt; text-align: center; margin: 10px 0; font-style: italic; }
    .titulo-aditamento { font-size: 10pt; text-align: center; font-weight: bold; margin: 10px 0; }
    .subtitulo { font-size: 10pt; text-align: center; margin: 5px 0; }
    .parte-titulo { font-size: 10pt; text-align: center; font-weight: bold; text-decoration: underline; margin: 15px 0 5px 0; }
    .parte-subtitulo { font-size: 10pt; text-align: center; text-decoration: underline; margin: 5px 0; }
    .sem-alteracao { font-size: 10pt; text-align: center; margin: 5px 0; }
    .item-titulo { font-size: 10pt; font-weight: bold; margin: 15px 0 10px 0; text-align: left; text-indent: 40px; }
    .paragrafo { font-size: 10pt; text-align: justify; text-indent: 40px; margin: 8px 0; line-height: 1.4; }
    .por-meses { font-size: 10pt; font-weight: bold; margin: 15px 0 10px 0; text-align: left; }
    .tabela-principal { width: 100%; border-collapse: collapse; font-size: 9pt; margin: 0; padding: 0; border-spacing: 0; }
    .tabela-principal th { padding: 3px 2px; border: 1px solid #000000; background-color: #EEEEEE; font-weight: bold; text-align: center; font-size: 8pt; }
    .tabela-principal td { padding: 2px; border: 1px solid #000000; vertical-align: middle; font-size: 8pt; }
    .tabela-dados { width: 100%; border-collapse: collapse; font-size: 8pt; margin: 0; padding: 0; border-spacing: 0; }
    .tabela-dados td { padding: 2px 5px; border: 1px solid #000000; border-top: none; }
    .rodape-bar { font-size: 8pt; font-style: italic; margin: 15px 0; }
    .assinatura { text-align: center; margin-top: 30px; }
    .assinatura-nome { font-weight: bold; font-size: 10pt; }
    .assinatura-cargo { font-size: 9pt; }
    .delegacao { margin-top: 20px; font-size: 9pt; }
    .providencias { margin-top: 20px; }
    .providencias-item { margin-left: 20px; text-align: justify; font-size: 10pt; line-height: 1.4; }
</style>

<div class='cabecalho'>
    <img src='../imagens/brasao.png' width='60'><br>
    MINISTERIO DA DEFESA<br>
    EXERCITO BRASILEIRO<br>
    COMANDO DA 3ª REGIAO MILITAR<br>
    (Gov das Armas Prov do RS/1821)<br>
    REGIAO DOM DIOGO DE SOUZA
</div>

<div class='data-documento'>$texto_cabecalho</div>

<div class='titulo-aditamento'>
    ADITAMENTO $nr_aditamento DA SECAO DE SERVICO MILITAR REGIONAL/SUBSECAO DE<br>
    SELECAO DE MILITARES TEMPORARIOS AO BOLETIM DE ACESSO RESTRITO<br>
    REGIONAL Nr $nr_bar
</div>

<div class='subtitulo'>
    PARA CONHECIMENTO DESTE COMANDO E DEVIDA EXECUCAO, PUBLICO O<br>
    SEGUINTE:
</div>

<div class='parte-titulo'>1ª PARTE</div>
<div class='parte-subtitulo'>SERVICOS DIARIOS</div>
<div class='sem-alteracao'>Sem alteração</div>

<div class='parte-titulo'>2ª PARTE</div>
<div class='parte-subtitulo'>INSTRUÇÃO</div>
<div class='sem-alteracao'>Sem alteração</div>

<div class='parte-titulo'>3ª PARTE</div>
<div class='parte-subtitulo'>ASSUNTOS GERAIS E ADMINISTRATIVOS</div>

<div class='item-titulo'>$titulo_item1</div>

<p class='paragrafo'>$paragrafo_principal</p>

<div class='por-meses'>$tempo_servico</div>
";

// Gerar tabelas para cada obrigatorio
$contador = 1;
foreach ($obrigatorios as $obrigatorio) {
    $nr = $contador;
    $posto = "Asp";

    // Determinar especialidade
    $formacao = $obrigatorio->getFormacao();
    $especialidade = $obrigatorio->imprime_ultima_especialidade();
    if (empty($especialidade)) {
        $especialidade = $obrigatorio->getEspecialidade();
    }

    $especialidade_completa = strtoupper($formacao) . ($especialidade ? " - " . $especialidade : "");

    $nome = strtoupper($obrigatorio->getNomeCompleto());
    $identidade = $obrigatorio->getIdentidade();
    $cpf = formatarCPF($obrigatorio->getCpf());
    $data_incorporacao = formatarDataBR($obrigatorio->getDataIncorporacao());
    $data_nascimento = formatarDataBR($obrigatorio->getDataNascimento());
    $local_curso = $obrigatorio->getCidadeInstituicaoEnsino();
    if (empty($local_curso)) {
        $local_curso = $obrigatorio->getNomeInstitutoEnsino();
    }
    $endereco = strtoupper($obrigatorio->getEndereco());

    // Localidade e OM 1ª Fase
    $om_1_fase = $obrigatorio->getOm1Fase();
    $cidade_om = "";
    $abreviatura_om = "";
    if ($om_1_fase) {
        $cidade_om = strtoupper($om_1_fase->getCidade());
        $abreviatura_om = $om_1_fase->getAbreviatura();
    }

    $local_compareceu = $obrigatorio->getLocalCompareceuDesignacao();
    $localidade_completa = $local_compareceu ? strtoupper($local_compareceu) : $cidade_om;
    if ($abreviatura_om) {
        $localidade_completa .= " - " . $abreviatura_om;
    }

    // Observacao fixa
    $observacao = $obs_tipo;

    $html .= "
    <table class='tabela-principal'>
        <tr>
            <th width='4%'>Nr</th>
            <th width='6%'>Posto</th>
            <th width='20%'>Especialidade</th>
            <th width='22%'>Nome</th>
            <th width='14%'>Identidade e<br>CPF</th>
            <th width='12%'>Data de<br>Incorporacao</th>
            <th width='22%'>Localidade<br>Convocacao e<br>OM 1ª Fase</th>
        </tr>
        <tr>
            <td style='text-align: center;'>$nr</td>
            <td style='text-align: center;'>$posto</td>
            <td style='font-size: 7pt; text-align: center;'>$especialidade_completa</td>
            <td style='font-weight: bold; text-align: center;'>$nome</td>
            <td style='text-align: center; font-size: 7pt;'>$identidade<br>$cpf</td>
            <td style='text-align: center;'>$data_incorporacao</td>
            <td style='text-align: center; font-size: 7pt;'>$localidade_completa</td>
        </tr>
    </table>
    <table class='tabela-dados'>
        <tr><td><b>Data de Nascimento:</b> $data_nascimento</td></tr>
        <tr><td><b>Local de conclusao de curso superior:</b> $local_curso</td></tr>
        <tr><td><b>Tempo de Servico Militar:</b> 0A 0M 0D</td></tr>
        <tr><td><b>Endereco:</b> $endereco</td></tr>
        <tr><td><b>Observacao:</b> $observacao</td></tr>
    </table>
    ";

    $contador++;
}

$html .= "
<div class='providencias'>
    <div class='item-titulo'>2. PROVIDENCIAS DECORRENTES:</div>

    <p class='providencias-item'><b>a.</b> $providencia_a</p>

    <p class='providencias-item'><b>b.</b> $providencia_b</p>

    <p class='providencias-item'><b>c.</b> $providencia_c</p>

    <p class='providencias-item' style='margin-left: 40px;'><b>1)</b> $providencia_c1</p>

    <p class='providencias-item' style='margin-left: 40px;'><b>2)</b> $providencia_c2</p>

    <p class='providencias-item' style='margin-left: 40px;'><b>3)</b> $providencia_c3</p>
</div>

<div class='parte-titulo'>4ª PARTE - JUSTICA E DISCIPLINA</div>
<div class='sem-alteracao'>Sem alteração</div>

<div class='assinatura'>
    <br><br>
    <span class='assinatura-nome'>Gen Div $nome_general</span><br>
    <span class='assinatura-cargo'>Comandante da 3ª Regiao Militar</span>
</div>

<div class='delegacao'>
    <i>Por delegacao:</i><br><br><br>
    <span class='assinatura-nome'>$nome_coronel - Cel</span><br>
    <span class='assinatura-cargo'>Chefe da Secao do Servico Militar da 3ª Regiao Militar</span>
</div>

<div class='rodape-bar'>
    <i>(Adt $nr_aditamento SSMR/SSMT ao Boletim de Acesso Restrito Regional Nr $nr_bar, de $quando_bar...............$total_obrigatorios de $total_obrigatorios)</i>
</div>
";

// mPDF com margens para cabecalho e rodape (mode, format, font-size, font, margin_left, margin_right, margin_top, margin_bottom, margin_header, margin_footer)
$mpdf = new mPDF('C', 'A4', 0, '', 10, 10, 18, 18, 5, 5);

// Definir cabecalho e rodape com aviso de acesso restrito
$header = "
<div style='font-size: 8pt; text-align: center; border: 1px solid #CC0000; padding: 3px; color: #CC0000;'>
    MATERIAL DE ACESSO RESTRITO<br>
    Art 44 e 45 do Dec 7.845, de 14 NOV 12
</div>
";

$footer = "
<div style='font-size: 8pt; text-align: center; border: 1px solid #CC0000; padding: 3px; color: #CC0000;'>
    MATERIAL DE ACESSO RESTRITO<br>
    Art 44 e 45 do Dec 7.845, de 14 NOV 12
</div>
";

$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLFooter($footer);

$mpdf->WriteHTML($html);

$timestamp = time();
$nome_arquivo = 'relatorio_aditamento_bar_' . $timestamp . '.pdf';

$alteracao = "Gerou um Aditamento ao BAR Nr $nr_bar para distribuicao $tipo_distribuicao";
$alteracao_detalhada = "Total de obrigatorios: $total_obrigatorios - Ano filtro: $ano_filtro";
$insere_log = $logDAO->insertLog(4007, "PDF", null, $alteracao, $alteracao_detalhada, null);

ob_end_clean();
$mpdf->Output();
