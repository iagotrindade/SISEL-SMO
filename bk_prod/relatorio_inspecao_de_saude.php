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

error_reporting(E_ALL);
ini_set('display_errors', 1);

ini_set('memory_limit', '256M');

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
$cabecalho = "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO MILITAR DO SUL<br>COMANDO DA 3ª REGIÃO MILITAR<br>(Gov das Armas Prov do RS/1821)<br>REGIÃO DOM DIOGO DE SOUZA<br>";

$html = "
<p style='text-align: center; font-size: 10px; margin-bottom: 5px;'>
    <img src='../imagens/brasao.png' width='70px'><br>
    $cabecalho
</p>

<h3 style='text-align: center;'>$titulo</h3>
<h4 style='text-align: center;'>$subtitulo</h4>

<p style='font-size: 12px; text-align: justify; text-indent: 2em;'>$paragrafo_um</p>
<p style='font-size: 12px; text-align: justify; text-indent: 2em;'>$paragrafo_dois</p>
<p style='font-size: 12px; text-align: right;'>$data</p>";

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
    <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
        <tr>
            <th colspan='4' style='text-align: center; background-color: #D8D8D8; font-size: 14px;'>APTOS</th>
        </tr>
        <tr>
            <th style='width: 5%; text-align:center;'>Nº</th>
            <th style='width: 15%; text-align:center;'>CPF</th>
            <th style='width: 80%; text-align:center;'>NOME</th>
        </tr>";

    $contador_aptos = 1;
    foreach ($aptos as $apto) {
        $html_aptos .= "
        <tr>
            <td style='text-align:center;'>$contador_aptos</td>
            <td style='text-align:center;'>{$apto['cpf']}</td>
            <td style='text-align:center;'>{$apto['nome']}</td>
        </tr>";
        $contador_aptos++;
    }

    $html_aptos .= "</table>";
    $mpdf->WriteHTML($html_aptos);
}

// Geração da tabela de INAPTOS
if (!empty($inaptos)) {
    $html_inaptos = "
    <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
        <tr>
            <th colspan='4' style='text-align: center; background-color: #D8D8D8; font-size: 14px;'>INAPTOS</th>
        </tr>
        <tr>
            <th style='width: 5%; text-align:center;'>Nº</th>
            <th style='width: 15%; text-align:center;'>CPF</th>
            <th style='width: 80%; text-align:center;'>NOME</th>
        </tr>";

    $contador_inaptos = 1;
    foreach ($inaptos as $inapto) {
        $html_inaptos .= "
        <tr>
            <td style='text-align:center;'>$contador_inaptos</td>
            <td style='text-align:center;'>{$inapto['cpf']}</td>
            <td style='text-align:center;'>{$inapto['nome']}</td>
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