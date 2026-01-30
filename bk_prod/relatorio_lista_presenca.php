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

ini_set('memory_limit', '256M');

if ($_SESSION['perfil_smo'] != "admin") erro($BASE_URL, 2, 6549874951, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");


$ObrigatorioDAO = new ObrigatorioDAO($conexao);
$todos_obrigatorios = $ObrigatorioDAO->findAllAtivos();

$data = filtra_campo_post('data_lista_presenca');
if (!valida_data($data))  erro($BASE_URL, 1, 34755866, $pagina_atual, "data_invalida", "Data Inválida!");
$situacao_militar1 = filtra_campo_post('situacao_militar1');
$situacao_militar2 = filtra_campo_post('situacao_militar2');
$situacao_militar3 = filtra_campo_post('situacao_militar3');
$situacao_militar4 = filtra_campo_post('situacao_militar4');
$situacao_militar5 = filtra_campo_post('situacao_militar5');
$situacao_militar6 = filtra_campo_post('situacao_militar6');
$nome_instituicao_ensino = filtra_campo_post('nome_instituicao_ensino');

if (empty($nome_instituicao_ensino)) erro($BASE_URL, 1, 325346478, $pagina_atual, "IE_Graduacao_NULL", "O campo IE Graduação é obrigatório!");

$logDAO = new LogDAO($conexao);
$conexao = new AuxiliarDAO($conexao);

$lista_presenca_obrigatorios = $conexao->findObrigatoriosParaListaPresenca();

//$html = ob_get_clean();

$html = "
<center>
<p class='center' style='font-size: 10px; text-align: center;'>
<img src='../imagens/brasao.png' width='70px'><br>
        MINISTÉRIO DA DEFESA<br>
        EXÉRCITO BRASILEIRO<br>
        COMANDO MILITAR DO SUL<br>
        COMANDO DA 3ª REGIÃO MILITAR<br>
        (Gov das Armas Prov do RS/1821)<br>
        REGIÃO DOM DIOGO DE SOUZA<br> 
</p>
</center>";

$html = $html . "
 <table border='0' style='width:100%'>
    <tr>
    <th align='center'><strong>$nome_instituicao_ensino </strong></th>
    </tr>
    <tr>
    <th align='center'><strong>Lista de Presença em: " . $data . " </strong></th>
    </tr>
</table> 
 

";



$html = $html . " <br>
        <table border='0' style='font-size: 11px; font-family: Times New Roman; width:100%' >
            <tr>
              
                <td style='background-color: #D8D8D8'>
                    <b>CPF</b>
                </td>
                <td style='background-color: #D8D8D8'>
                    <b>NOME COMPLETO</b>
                </td>

                <td style='background-color: #D8D8D8'>
                    <b>SITUAÇÃO MILITAR</b>
                </td>

                 <td style='background-color: #D8D8D8'>
                    <b>ASSINATURA</b>
                </td>
              
                
            </tr>";

//if ($todos_obrigatorios)
foreach ($todos_obrigatorios as $obrigatorio) {
    if ($obrigatorio->getSituacaoMilitar() == null) continue;
    if ($obrigatorio->getNomeInstitutoEnsino() != $nome_instituicao_ensino) continue;

    if (
        $obrigatorio->getSituacaoMilitar() == $situacao_militar1 ||
        $obrigatorio->getSituacaoMilitar() == $situacao_militar2 ||
        $obrigatorio->getSituacaoMilitar() == $situacao_militar3 ||
        $obrigatorio->getSituacaoMilitar() == $situacao_militar4 ||
        $obrigatorio->getSituacaoMilitar() == $situacao_militar5 ||
        $obrigatorio->getSituacaoMilitar() == $situacao_militar6
    )


        $html = $html . "
        <tr>


            <td width='10%'>
                    " . $obrigatorio->getCPF() . "
            </td>

            <td width='35%'>
                    " . $obrigatorio->getNomeCompleto() . "
            </td>

            <td width='30%'>
                    " . $obrigatorio->getSituacaoMilitar() . "
            </td>

            <td width='25%'>
            </td>
            
        </tr>
        
        <tr>
            <td colspan='4'>
            <br>
                <hr>
            </td>
        </tr>
        ";
}

$html = $html . "</table>";


$mpdf = new mPDF('C', 'A4-L');
$mpdf->WriteHTML($html);

$timestamp = time();
$nome_arquivo = 'relatorio_lista_presenca' . $timestamp . '.pdf';
$arquivo = $destino . $nome_arquivo;


$alteracao = "Gerou um Relatório de lista de presença na data de $data para a IE $nome_instituicao_ensino";
$alteracao_detalahada = "Todas as situações militares do relatório " . $situacao_militar1 . " - " . $situacao_militar2 . " - " .
    $situacao_militar3 . " - " . $situacao_militar4 . " - " . $situacao_militar5 . " - " . $situacao_militar6;
$insere_log = $logDAO->insertLog(4006, "PDF", null, $alteracao, $alteracao_detalahada, null);

ob_get_clean();
$mpdf->Output();
//$mpdf->Output($arquivo, 'F');
