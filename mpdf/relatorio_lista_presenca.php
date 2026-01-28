<?php 
    ob_start();

if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Obrigatorio.php';
include_once '../models/Om.php';
include_once '../models/Oficio.php';
include_once '../dao/ObrigatorioDAO.php';
include_once '../dao/LogDAO.php';
include_once '../dao/AuxiliarDAO.php';
include("mpdf60/mpdf.php");

if($_SESSION['perfil_smo'] != "admin") erro($BASE_URL, 2, 6549874951, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");
    

$ObrigatorioDAO = new ObrigatorioDAO($conexao);
$todos_obrigatorios = $ObrigatorioDAO->findAllAtivos();

$data = filtra_campo_post('data_lista_presenca');
if(!valida_data($data))  erro($BASE_URL, 1, 34755866, $pagina_atual, "data_invalida", "Data Inválida!");
$situacao_militar1 = filtra_campo_post('situacao_militar1');
$situacao_militar2 = filtra_campo_post('situacao_militar2');
$situacao_militar3 = filtra_campo_post('situacao_militar3');
$situacao_militar4 = filtra_campo_post('situacao_militar4');
$situacao_militar5 = filtra_campo_post('situacao_militar5');
$situacao_militar6 = filtra_campo_post('situacao_militar6');
$nome_instituicao_ensino = filtra_campo_post('nome_instituicao_ensino');

if(empty($nome_instituicao_ensino)) erro($BASE_URL, 1, 325346478, $pagina_atual, "IE_Graduacao_NULL", "O campo IE Graduação é obrigatório!");

$logDAO = new LogDAO($conexao);
$conexao = new AuxiliarDAO($conexao);

$lista_presenca_obrigatorios = $conexao->findObrigatoriosParaListaPresenca(); 

//$html = ob_get_clean();

$html = "
<style>
    body { font-family: 'Times New Roman', Times, serif; }
    .cabecalho { font-size: 9px; text-align: center; line-height: 1.4; margin-bottom: 20px; }
    .titulo-ie { font-size: 14px; font-weight: bold; text-align: center; margin: 15px 0 5px 0; }
    .titulo-lista { font-size: 13px; font-weight: bold; text-align: center; margin: 5px 0 20px 0; }
    .tabela-presenca { width: 100%; border-collapse: collapse; font-size: 10px; margin: 15px 0; }
    .tabela-presenca th { padding: 10px 8px; border: 1px solid #CCCCCC; background-color: #E8E8E8; font-weight: bold; text-align: center; }
    .tabela-presenca td { padding: 12px 8px; border: 1px solid #DDDDDD; vertical-align: middle; }
    .linha-separadora { border-top: 1px solid #CCCCCC; margin: 8px 0; }
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

<div class='titulo-ie'>$nome_instituicao_ensino</div>
<div class='titulo-lista'>Lista de Presença em: ".$data."</div>
" ;



    $html = $html . "
        <table class='tabela-presenca'>
            <tr>
                <th width='14%'>CPF</th>
                <th width='35%'>NOME COMPLETO</th>
                <th width='26%'>SITUAÇÃO MILITAR</th>
                <th width='25%'>ASSINATURA</th>
            </tr>";

//if ($todos_obrigatorios)
foreach ($todos_obrigatorios as $obrigatorio) 
{ 
    if($obrigatorio->getSituacaoMilitar() == null) continue;
    if($obrigatorio->getNomeInstitutoEnsino() != $nome_instituicao_ensino) continue;

    if ($obrigatorio->getSituacaoMilitar() == $situacao_militar1 ||
    $obrigatorio->getSituacaoMilitar() == $situacao_militar2 ||
    $obrigatorio->getSituacaoMilitar() == $situacao_militar3 ||
    $obrigatorio->getSituacaoMilitar() == $situacao_militar4 ||
    $obrigatorio->getSituacaoMilitar() == $situacao_militar5 ||
    $obrigatorio->getSituacaoMilitar() == $situacao_militar6)
    

    $html = $html . "
        <tr>
            <td style='vertical-align: middle;'>".$obrigatorio->getCPF()."</td>
            <td style='vertical-align: middle;'>".$obrigatorio->getNomeCompleto()."</td>
            <td style='vertical-align: middle;'>".$obrigatorio->getSituacaoMilitar()."</td>
            <td style='height: 35px;'></td>
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
$alteracao_detalahada = "Todas as situações militares do relatório ". $situacao_militar1 . " - " . $situacao_militar2 . " - " . 
$situacao_militar3 . " - " . $situacao_militar4 . " - " . $situacao_militar5 . " - " . $situacao_militar6;
$insere_log = $logDAO->insertLog(4006, "PDF", null, $alteracao, $alteracao_detalahada, null);

ob_get_clean();
$mpdf->Output();
//$mpdf->Output($arquivo, 'F');

?>
