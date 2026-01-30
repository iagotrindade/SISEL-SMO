<?php 

ob_start();

if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Obrigatorio.php';
include_once '../models/Oficio.php';
include_once '../models/Om.php';
include_once '../dao/ObrigatorioDAO.php';
include_once '../dao/LogDAO.php';
include_once '../dao/AuxiliarDAO.php';
include("mpdf60/mpdf.php");

if($_SESSION['perfil_smo'] != "admin")
{    
    erro($BASE_URL, 2, 998854564, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");
    exit();
}

$data_sel_geral = filtra_campo_post('data_sel_geral');
$data_tratada = trata_data($data_sel_geral);

$logDAO = new LogDAO($conexao);
$conexao = new AuxiliarDAO($conexao);

$cidade = null;
$sessao = null;
$assinante_1 = null;
$assinante_2 = null;
$assinante_3 = null;

$get_exames_medico = $conexao->findExameMedico($data_tratada);  


if($get_exames_medico)
{
    $sessao = $get_exames_medico[0]['secao'];
    $cidade = $get_exames_medico[0]['cidade'];
    $assinante_1 = $get_exames_medico[0]['presidente'];
    $assinante_2 = $get_exames_medico[0]['membro_1'];
    $assinante_3 = $get_exames_medico[0]['membro_2'];
}


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

$html = $html. "
 <table border='0' style='width:100%'>
    <tr>
    <th align='center'><strong>Relatório dos Inspecionados</strong></th>
    </tr>
    <tr>
    <th align='center'><strong>Em $data_sel_geral</strong></th>
</tr>
</table> 
 <table border='0' style='width:100%'>
  <tr>
    <th align='center'><strong> <p style='font-size: 12px; font-family: Times New Roman;'>JISE:  Sessão Nº $sessao - CSE - Médicos Obrigatórios ".$_POST['data_inspecao']." 
    na cidade de  $cidade. </p></strong></th>
  </tr>
</table> 

" ;

$lista_obrigatorios = $conexao->findObrigatoriosPorDataCompSelGeral($data_tratada); 

    $html = $html . " <br>
        <table border='0' style='font-size: 10px; font-family: Times New Roman; width:100%' >
            <tr>
                <td style='background-color: #D8D8D8'>
                    <b>Nº</b>
                </td>
                <td style='background-color: #D8D8D8'>
                    <b>CPF</b>
                </td>
                <td style='background-color: #D8D8D8'>
                    <b>NOME COMPLETO</b>
                </td>
              
                <td style='background-color: #D8D8D8'>
                    <b>NASCIMENTO</b>
                </td>
                <td style='background-color: #D8D8D8'>
                    <b>NOME DA MÃE</b>
                </td>
                <td style='background-color: #D8D8D8'>
                    <b>PARECER</b>
                </td>
                
                <td style='background-color: #D8D8D8'>
                    <b>CID</b>
                </td>
            </tr>";
    
$contador = 1;

$lista_cpf_obrigatorios = [];
if ($lista_obrigatorios)
foreach ($lista_obrigatorios as $candidato) 
{ 
    array_push($lista_cpf_obrigatorios, $candidato['cpf']);
    $parecer = $candidato['jise'];
        if($candidato['jisr'] != NULL)  $parecer = $candidato['jisr'];
    $cid = $candidato['cid_jise'];
        if($candidato['cid_jisr'] != NULL)  $cid = $candidato['cid_jisr'];
    
    $nascimento = "";
    if($candidato['data_nascimento'] != null) $nascimento = trata_data($candidato['data_nascimento']);
    
    $html = $html . "
        <tr>
            <td>
                ".$contador."
            </td>
            <td>
                ".$candidato['cpf']."
            </td>
            <td>
                ".mb_strtoupper($candidato['nome_completo'],"UTF-8")."
            </td>
        
            <td>
                ".$nascimento."
            </td>
            <td>
                ".$candidato['nome_mae']."
            </td>
            <td>
                ".$parecer."
            </td>
            
            <td>
                ".$cid."
            </td>
        </tr>";
    
    $contador++;
}
$html = $html . "</table>";


$html = $html . " <br><br><br>
 <table border='0' style='font-size: 10px; font-family: Times New Roman; width:100%'>
  <tr>
    <th>_______________________________________________</th>
    <th>_______________________________________________</th>
    <th>_______________________________________________</th>
  </tr>
  <tr>
    <th width='33%'>$assinante_1</th>
    <th width='33%'>$assinante_2</th>
    <th width='33%'>$assinante_3</th>
  </tr>
</table> ";

$mpdf = new mPDF('C', 'A4-L'); 
$mpdf->WriteHTML($html);

$timestamp = time();
$nome_arquivo = 'relatorio_inspecao_saude_' . $timestamp . '.pdf';
$arquivo = $destino . $nome_arquivo;


$alteracao = "Gerou um Relatório de comparecimento JISE/JISER na data de $data_sel_geral";
$alteracao_detalahada = "CPF dos Obrigatórios ". print_r($lista_cpf_obrigatorios, true);
$insere_log = $logDAO->insertLog(4005, "PDF", null, $alteracao, $alteracao_detalahada, null);

ob_get_clean();
$mpdf->Output();
//$mpdf->Output($arquivo, 'F');

?>
