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

$mpdf = new mPDF('C', 'A4-P');

if ($_SESSION['perfil_smo'] != "admin") erro($BASE_URL, 2, 6549874951, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");


$ObrigatorioDAO = new ObrigatorioDAO($conexao);
$todos_obrigatorios = $ObrigatorioDAO->findAllAtivos();

$paragrafo_um = $_POST['paragrafo_um'];
$paragrafo_dois = $_POST['paragrafo_dois'];

$data_inicial = $_POST['date_inicial'];
$data_final = $_POST['date_final'];

$data = $_POST['documento_dia'];

$logDAO = new LogDAO($conexao);
$conexao = new AuxiliarDAO($conexao);

// Dados vindos do formulário
$titulo        = $_POST['titulo'] ?? '';
$subtitulo     = $_POST['subtitulo'] ?? '';
$paragrafo_um  = $_POST['paragrafo_um'] ?? '';
$paragrafo_dois = $_POST['paragrafo_dois'] ?? '';
$data_inicial  = $_POST['date_inicial'] ?? '';
$data_final    = $_POST['date_final'] ?? '';
$data          = $_POST['documento_dia'] ?? '';

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

$mpdf->WriteHTML($html);

// Inicialização das listas
$aptos = [];
$inaptos = [];

// Montagem das listas
foreach ($todos_obrigatorios as $candidato) {
    $dataSelecao = $candidato->getDataSelecaoGeral();
    if (empty($dataSelecao)) continue;

    if (!empty($data_inicial) && !empty($data_final)) {
        $dataInspecao = new DateTime($dataSelecao);
        if ($dataInspecao < new DateTime($data_inicial) || $dataInspecao > new DateTime($data_final)) continue;
    }

    $cpf = strlen($candidato->getCpf()) > 5
        ? substr($candidato->getCpf(), 0, -5) . "******"
        : "******";
    $nome = mb_strtoupper($candidato->getNomeCompleto());
    $jise = $candidato->getJise();

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
    $mpdf->WriteHTML($html_aptos);
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
    $mpdf->WriteHTML($html_inaptos);
}

// Registro de log
$timestamp = time();
$nome_arquivo = 'relatorio_inspeção_saúde_' . $timestamp . '.pdf';
$alteracao = "Gerou um Relatório de lista de presença na data de $data.";
$logDAO->insertLog(4006, "PDF", null, $alteracao, "Relatório gerado com sucesso.", null);

ob_end_clean();
$mpdf->Output($nome_arquivo, 'I'); // 'I' = abre no navegador