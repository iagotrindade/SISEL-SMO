<?php
ob_start();

if (!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Obrigatorio.php';
include_once '../models/Om.php';
include_once '../models/Oficio.php';
include_once '../dao/ObrigatorioDAO.php';
include_once '../dao/LogDAO.php';
include_once '../dao/AuxiliarDAO.php';
include("mpdf60/mpdf.php");

if ($_SESSION['perfil_smo'] != "admin") erro($BASE_URL, 2, 6549874951, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");

// Dados vindos do formulário
$titulo        = $_POST['titulo'] ?? '';
$subtitulo     = $_POST['subtitulo'] ?? '';
$paragrafo_um  = $_POST['paragrafo_um'] ?? '';
$paragrafo_dois = $_POST['paragrafo_dois'] ?? '';
$data          = $_POST['documento_dia'] ?? '';

// Converte strings vazias em null para evitar erro SQL
$data_inicial  = !empty(trim($_POST['date_inicial'] ?? '')) ? trim($_POST['date_inicial']) : null;
$data_final    = !empty(trim($_POST['date_final'] ?? '')) ? trim($_POST['date_final']) : null;

// Inicializa DAOs
$logDAO = new LogDAO($conexao);
$auxiliarDAO = new AuxiliarDAO($conexao);

// Busca apenas os obrigatórios necessários com filtro SQL
$ObrigatorioDAO = new ObrigatorioDAO($conexao);
$todos_obrigatorios = $ObrigatorioDAO->findParaInspecaoSaude($data_inicial, $data_final);

// Cabeçalho
$html = "
<style>
    body { font-family: 'Times New Roman', Times, serif; }
    .cabecalho { font-size: 10px; text-align: center; line-height: 1.4; margin-bottom: 8px; }
    .titulo-principal { font-size: 12px; font-weight: bold; text-align: center; margin: 10px 0; }
    .titulo-secundario { font-size: 12px; font-weight: bold; text-align: center; margin: 5px 0 15px 0; }
    .texto-corpo { font-size: 12px; text-align: justify; line-height: 1.6; margin: 10px 0; text-indent: 60px; }
    .texto-data { font-size: 12px; text-align: right; margin: 10px 0; }
    .tabela-lista { width: 100%; border-collapse: collapse; font-size: 12px; margin: 15px 0; }
    .tabela-lista th { padding: 2px 4px; border: 1px solid #CCCCCC; font-weight: bold; text-align: center; }
    .tabela-lista td { padding: 2px 4px; border: 1px solid #DDDDDD; text-align: center; }
</style>

<div style='text-align: center;'>
    <div class='cabecalho'>
        <img src='../imagens/brasao.png' width='85'><br>
        MINISTÉRIO DA DEFESA<br>
        EXÉRCITO BRASILEIRO<br>
        COMANDO MILITAR DO SUL<br>
        COMANDO DA 3ª REGIÃO MILITAR<br>
        (Gov das Armas Prov do RS/1821)<br>
        REGIÃO DOM DIOGO DE SOUZA
    </div>
</div>

<div class='titulo-principal'>$titulo</div>
<div class='titulo-secundario'>$subtitulo</div>

<p class='texto-corpo'>$paragrafo_um</p>
<p class='texto-corpo'>$paragrafo_dois</p>
<p class='texto-data'>$data</p>";

// Inicialização das listas
$aptos = [];
$inaptos = [];

// Montagem das listas (dados já filtrados pelo SQL)
foreach ($todos_obrigatorios as $candidato) {
    $jise = $candidato->getJise();
    $dataSelecao = $candidato->getDataSelecaoGeral();

    // Ignora registros inválidos
    if (empty($jise) || !in_array($jise, ['A', 'B1', 'B2', 'C'])) {
        continue;
    }

    // Valida se a data está no período (proteção adicional)
    if ($data_inicial && $data_final) {
        if (empty($dataSelecao) || $dataSelecao < $data_inicial || $dataSelecao > $data_final) {
            continue;
        }
    }

    $cpf = strlen($candidato->getCPF()) > 5
        ? substr($candidato->getCPF(), 0, -5) . "******"
        : "******";
    $nome = mb_strtoupper($candidato->getNomeCompleto());

    $statusMap = [
        'A' => 'APTO',
        'B1' => 'INCAPAZ B1',
        'B2' => 'INCAPAZ B2',
        'C' => 'INCAPAZ C'
    ];

    $statusDescricao = $statusMap[$jise] ?? 'INDEFINIDO';
    $status = ($statusDescricao === 'APTO') ? 'APTO' : 'INAPTO';

    $linha = [
        'cpf' => $cpf,
        'nome' => $nome,
        'status' => $status
    ];

    if ($jise == 'A') {
        $aptos[] = $linha;
    } else {
        $inaptos[] = $linha;
    }
}

// Verifica se há dados para exibir
if (empty($aptos) && empty($inaptos)) {
    $html_sem_dados = "
    <div style='text-align: center; padding: 40px; border: 2px solid #CCCCCC; border-radius: 8px; margin: 20px 0;'>
        <p style='font-size: 14px; color: #666; margin: 0;'>
            <strong>Nenhum registro encontrado</strong><br><br>
            Não há candidatos com resultado de inspeção de saúde (JISE) no período selecionado.<br>
            Período: " . ($data_inicial && $data_final ? date('d/m/Y', strtotime($data_inicial)) . " a " . date('d/m/Y', strtotime($data_final)) : "Todos os registros") . "
        </p>
    </div>";
    $html .= $html_sem_dados;
}

// Geração da tabela de APTOS
if (!empty($aptos)) {
    $html_aptos = "
    <table class='tabela-lista'>
        <tr>
            <th colspan='3' style='background-color: #D8D8D8; font-size: 14px;'>APTOS</th>
        </tr>
        <tr>
            <th style='width: 5%;'>Nº</th>
            <th style='width: 15%;'>CPF</th>
            <th style='width: 80%;'>NOME</th>
        </tr>";

    $contador_aptos = 1;
    foreach ($aptos as $apto) {
        $html_aptos .= "
        <tr>
            <td>$contador_aptos</td>
            <td>{$apto['cpf']}</td>
            <td>{$apto['nome']}</td>
        </tr>";
        $contador_aptos++;
    }

    $html_aptos .= "</table>";
    $html .= $html_aptos;
}

// Geração da tabela de INAPTOS
if (!empty($inaptos)) {
    $html_inaptos = "
    <table class='tabela-lista'>
        <tr>
            <th colspan='3' style='background-color: #D8D8D8; font-size: 14px;'>INAPTOS</th>
        </tr>
        <tr>
            <th style='width: 5%;'>Nº</th>
            <th style='width: 15%;'>CPF</th>
            <th style='width: 80%;'>NOME</th>
        </tr>";

    $contador_inaptos = 1;
    foreach ($inaptos as $inapto) {
        $html_inaptos .= "
        <tr>
            <td>$contador_inaptos</td>
            <td>{$inapto['cpf']}</td>
            <td>{$inapto['nome']}</td>
        </tr>";
        $contador_inaptos++;
    }

    $html_inaptos .= "</table>";
    $html .= $html_inaptos;
}

$formato_saida = filtra_campo_post('formato_saida') ?: 'pdf';

// Registro de log
$alteracao = "Gerou um Relatório de inspeção de saúde na data de $data.";
$logDAO->insertLog(4006, strtoupper($formato_saida), null, $alteracao, "Relatório gerado com sucesso.", null);

if ($formato_saida !== 'pdf') {
    ob_end_clean();
    outputComoDocumento($html, $formato_saida, 'inspecao_saude', [
        'orientacao' => 'portrait',
        'margin_top' => '15mm',
        'margin_bottom' => '15mm',
        'margin_left' => '15mm',
        'margin_right' => '15mm',
    ]);
    exit();
}

$mpdf = new mPDF('C', 'A4-P');
$mpdf->WriteHTML($html);

$timestamp = time();
$nome_arquivo = 'relatorio_inspecao_saude_' . $timestamp . '.pdf';

ob_end_clean();
$mpdf->Output($nome_arquivo, 'I');