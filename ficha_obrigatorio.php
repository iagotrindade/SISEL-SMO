<?php 

ob_start();

?>

<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);

    if(!isset($_SESSION)) session_start();

    include_once '../funcoes.php';
    include_once '../dao/conecta_banco.php';
    include_once '../models/Obrigatorio.php';
    include_once '../models/Om.php';
    include_once '../dao/ObrigatorioDAO.php';
    include_once '../dao/LogDAO.php';
    include("mpdf60/mpdf.php");

    $obrigatorioDAO = new obrigatorioDAO($conexao);
    $logDAO = new LogDAO($conexao);

    $id_obrigatorio = (int)filtra_campo_get('id_obrigatorio');
    $crip = filtra_campo_get('crip');

    if($crip != hash('sha256', $id_obrigatorio. "criptografia")) erro($BASE_URL, 2, 4845623352, $pagina_atual, "criptografia_invalida", "Não foi possível gerar o relatório!");
    if($id_obrigatorio < 1) erro($BASE_URL, 2, 235367, $pagina_atual, "id_obrigatorio<1", "Não foi possível gerar o relatório!");

    $obrigatorio = $obrigatorioDAO->findById($id_obrigatorio);
    $prioridade_guarnicao = $obrigatorioDAO->findAllGuarnicaoPrioridade($id_obrigatorio);
    
    $listapg = "";
    foreach ($prioridade_guarnicao as $value) 
    { 
        $listapg = $listapg . "<b>" . $value['prioridade'] . "ª</b> " . $value['guarnicao'] . " - ";
    }
 
    $dataexpedicao = $obrigatorio->getDataExpedicao();
    $dataexpedicao = trata_data($dataexpedicao);

    $datetime = date('d/m/Y H:i:s');
    $data_hoje = date('d/m/Y');

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
</center>
<br>
<table border='0' style='width:100%'>
  <tr>
    <th align='center'><strong> FICHA CADASTRAL </strong>
  </tr>
  <tr>
    <th align='center'><strong>" . $obrigatorio->getFormacao() . "</strong>
  </tr>
</table> 

<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
 
</p>

";

 $html = $html. "

<table border='0'  style='font-size: 10px; font-family: Times New Roman; width:100%' >

<tr style='background-color: #D8D8D8'>
    <td colspan=\"2\"> <center><b>IDENTIFICAÇÃO</b></center></td>
</tr>

<tr>
    <td><b>Nome Completo: </b> ".$obrigatorio->getNomeCompleto()."</td>
    <td><b>CPF: </b> ".$obrigatorio->getCpf()."</td>
</tr>
<tr>
    <td><b>Estado Civil: </b> ".$obrigatorio->getEstadoCivil()." </td>
    <td><b>Data de Nascimento: </b> ".$obrigatorio->getDataNascimento()." </td>
</tr>

<tr>
    <td><b>Identidade: </b> ".$obrigatorio->getIdentidade()." </td>
    <td><b>Email: </b> ".$obrigatorio->getMail()." </td>
</tr>

<tr>
    <td><b>Telefone: </b> ".$obrigatorio->getTelefone()." </td>
    <td><b>Nome do Pai: </b> ".$obrigatorio->getNomePai()." </td>
</tr>

<tr>
    <td><b>Nome da Mão: </b> ".$obrigatorio->getNomeMae()." </td>
    <td><b>Voluntário: </b> ".$obrigatorio->getVoluntario()." </td>
</tr>

<tr>
    <td><b>Nacionalidade: </b> ".$obrigatorio->getNacionalidade()." </td>
    <td><b>Naturalidade: </b> ".$obrigatorio->getNaturalidade()." </td>
</tr>

<tr>
    <td><b>Endereço Completo: </b> ".$obrigatorio->getEndereco()." </td>
    <td><b>Dependentes: </b> ".$obrigatorio->getDependentes()." </td>
</tr>

<tr>
    <td><b>Prioridade Força: </b> ".$obrigatorio->getPrioridadeForca()." </td>
</tr>

<tr style='background-color: #D8D8D8'>
<td colspan=\"2\"> <center><b>ENSINO</b></center></td>
</tr>

<tr>
    <td><b>Formação: </b>".$obrigatorio->getFormacao()."</td>
    <td><b>Ano de Formação: </b>".$obrigatorio->getAnoFormacao()."</td>
</tr>


<tr>
    <td><b>Instituição de Ensino: </b>".$obrigatorio->getNomeInstitutoEnsino()."</td>          
</tr>

<tr style='background-color: #D8D8D8'>
<td colspan=\"2\"> <center><b>SITUAÇÃO MILITAR</b></center></td>
</tr>

<tr>
    <td><b>Situação Militar: </b>".$obrigatorio->getSituacaoMilitar()."</td>
    <td><b>Força: </b>".$obrigatorio->getForca()."</td>
</tr>

<tr>
    <td><b>Número do Doc Militar: </b>".$obrigatorio->getNumeroDocumentoMilitar()."</td>
    <td><b>Data de Expedição: </b>". $dataexpedicao ."</td>
</tr>

<tr>
    <td colspan=\"2\"><b>Documento Militar: </b>".$obrigatorio->getDocumentoMilitar()."</td>
</tr>


<tr style='background-color: #D8D8D8'>
<td colspan=\"2\"> <center><b>SAÚDE</b></center></td>
</tr>
<tr>
    <td><b>Grupo do Exame Médico: </b>".$obrigatorio->getJise()."</td>
    <td><b>CID JISE: </b>".$obrigatorio->getCidJise()."</td>
</tr>

<tr>
   
    <td><b>Data de Comparecimento Seleção Geral: </b>".$obrigatorio->imprimeDataComparecimentoSelecaoGeral()."</td>          
</tr>


<tr>
    <td><b>Observação: </b>".$obrigatorio->getObservacaoJise()."</td>          
</tr>";

if ($obrigatorio->getJisr() != null || $obrigatorio->getCidJisr() != null || $obrigatorio->getObsJisr() != null || $obrigatorio->getDataJisr() != null) 
$html = $html."
<tr>
<td></td>          
</tr>
<tr>
    <td><b>Grupo do Exame Médico JISR: </b>".$obrigatorio->getCidJisr()."</td>
    <td><b>CID JISR: </b>".$obrigatorio->getCidJisr()."</td>
</tr>

<tr>
    
    <td><b>Data de Realização do Exame Médico JISR: </b>".$obrigatorio->imprimeDataJisr()."</td>          
</tr>

<tr>
    <td><b>Observação JISR: </b>".$obrigatorio->getObsJisr()."</td>          
</tr>";

if ($obrigatorio->getJisea1() != null || $obrigatorio->getCidJisea1() != null || $obrigatorio->getObservacaoJisea1() != null || $obrigatorio->getDataJisea1() != null) 
$html = $html." 
        <tr>
        <td></td>          
        </tr>
        <tr>
            <td><b>Grupo do Exame Médico JISE A-1: </b>".$obrigatorio->getJisea1()."</td>
            <td><b>CID JISE A-1: </b>".$obrigatorio->getCidJisea1()."</td>
        </tr>
        
        
        <tr>
            <td><b>Data de Realização do Exame Médico JISE A-1: </b>".$obrigatorio->imprimeDataJisea1()."</td>          
        </tr>
        
        <tr>
            <td><b>Observação JISE A-1: </b>".$obrigatorio->getObservacaoJisea1()."</td>          
        </tr>";

$html = $html."    

<tr style='background-color: #D8D8D8'>
<td colspan=\"2\"> <center><b>ESPECIALIDADES</b></center></td>
</tr>

<tr>
    <td><b>Especialidade: </b>".$obrigatorio->getEspecialidade(). " (" . $obrigatorio->getAnoResEspe1() . ")</td>";

    if ($obrigatorio->getEspecialidade2() != null) echo $html = $html. "
    <td><b>2ª Especialidade: </b>". $obrigatorio->getEspecialidade2() . " (" . $obrigatorio->getAnoResEspe2() . ") </td>
    </tr>";

$html = $html."

    <tr>";

        if ($obrigatorio->getEspecialidade3() != null) echo $html = $html. "
        <td><b>3ª Especialidade: </b>". $obrigatorio->getEspecialidade3() . " (" . $obrigatorio->getAnoResEspe3() . ")</td>
        </tr>"; 
        
$html = $html."

        </tr>

<tr style='background-color: #D8D8D8'>
<td colspan=\"2\"> <center><b>PRIORIDADE DE CIDADES</b></center></td>
</tr>

<tr>
    <td colspan=\"2\"><b></b> $listapg </td>
</tr>



";

$html = $html."</table>";


$html = $html. "  <br>

<table border='0' style='width:100%'>
  <tr>
    <th align='right'><strong> <p style='font-size: 12px; font-family: Times New Roman;'>  </p></strong></th>
  </tr>
</table> ";

$html = $html . " <br>

<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'> 
   _____/_____/__________
</p>

<br><br>


<center>
<table border='0' style='font-size: 12px; font-family: Times New Roman; width:100%'>
<tr>
  <th width='33%'>______________________________________</th> 
  <th width='33%'>______________________________________</th>
  <th width='33%'>______________________________________</th>
</tr>

<tr>
  <th>Presidente da CSE</th>
  <th>" . mb_strtoupper($obrigatorio->getNomeCompleto(), "UTF-8"). " </th>
  <th>Avaliador / Entrevistador</th>
</tr>
";

$html = $html . " </table>

</center>

";

 $mpdf = new mPDF('C', 'A4'); 
 $mpdf->WriteHTML($html);

 $cpf = $obrigatorio->getCpf();
 $insere_log = $logDAO->insertLog(4003, "PDF", $id_obrigatorio, "Gerou a Ficha do Obrigatório de CPF " . $obrigatorio->getCpf(), $obrigatorio);
 
 ob_get_clean();
 $mpdf->Output();
 //$mpdf->Output($arquivo, 'F');
 
 
 ?>

