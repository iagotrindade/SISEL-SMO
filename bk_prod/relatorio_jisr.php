<?php 

ob_start();

?>

<?php

    if(!isset($_SESSION)) session_start();

    include_once '../funcoes.php';
    include_once '../dao/conecta_banco.php';
    include_once '../models/Obrigatorio.php';
    include_once '../models/Om.php';
    include_once '../dao/ObrigatorioDAO.php';
    include_once '../dao/LogDAO.php';
    include_once '../dao/JuntaDAO.php';
    include("mpdf60/mpdf.php");

    $obrigatorioDAO = new obrigatorioDAO($conexao);
    $logDAO = new LogDAO($conexao);

    $id_obrigatorio = (int)filtra_campo_get('id_obrigatorio');
    $crip = filtra_campo_get('crip');

    if($crip != hash('sha256', $id_obrigatorio. "criptografia")) erro($BASE_URL, 2, 4845623352, $pagina_atual, "criptografia_invalida", "Não foi possível gerar o relatório!");
    if($id_obrigatorio < 1) erro($BASE_URL, 2, 235367, $pagina_atual, "id_obrigatorio", "Não foi possível gerar o relatório!");

    $obrigatorio = $obrigatorioDAO->findById($id_obrigatorio);
    
    $juntaDAO = new JuntaDAO($conexao);
    $junta = $juntaDAO->findByData($obrigatorio->getDataComparecimentoSelecaoGeral());
 
    $data_hoje = date('d/m/Y');
    $data_nascimento = trata_data($obrigatorio->getDataNascimento());
    $data_expedicao = trata_data($obrigatorio->getDataExpedicao());
    if($data_hoje) $data_hoje = dataExtenso($data_hoje);

    $junta_secao = null;
    if($junta) $junta_secao = $junta[0]['secao'];
    $junta_cidade= null;
    if($junta) $junta_cidade = $junta[0]['cidade'];
   
$html = "

<center>
<p class='center' style='font-size: 10px; text-align: center'>

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
    <th align='center'><strong>Sessão ". $junta_secao . " - SMO</strong>
  </tr>
  <tr>
    <th align='center'><strong>JISR</strong>
  </tr>
</table> 
<br>
<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   &nbsp;&nbsp;&nbsp;
   A Junta de Inspeção de Saúde Especial inspecionou na presente sessão, o abaixo declarado, para fins de incorporação  sobre seu estado de saúde, proferiu o parecer abaixo:
</p>
<br>
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
    <td><b>Naturalidade: </b>".$obrigatorio->getNaturalidade()." </td>
    <td><b>Data de Nascimento: </b>".$data_nascimento." </td>
</tr>

<tr style='background-color: #D8D8D8'>
<td colspan=\"2\"> <center><b>INSTITUIÇÃO DE ENSINO</b></center></td>
</tr>

<tr>
    <td><b>Nome do Instituto de Ensino: </b>".$obrigatorio->getNomeInstitutoEnsino()."</td>
    <td><b>Ano de Formação: </b>".$obrigatorio->getAnoFormacao()."</td>
</tr>

<tr>
    <td><b>Formação: </b>".$obrigatorio->getFormacao()."</td>
</tr>

<tr style='background-color: #D8D8D8'>
    <td colspan=\"2\"> <center><b>CIVIL/MILTAR</b></center></td>
</tr>

<tr>
    <td><b>Situação Militar: </b>".$obrigatorio->getSituacaoMilitar()."</td>
    <td><b>Documento Militar: </b>".$obrigatorio->getDocumentoMilitar()." </td>
</tr>

<tr>
    <td><b>Nº do Documento: </b>".$obrigatorio->getNumeroDocumentoMilitar()." </td>
    <td><b>Data da Expedição: </b>".$data_expedicao."</td>
</tr>

<tr style='background-color: #D8D8D8'>
    <td colspan=\"2\"> <center><b>EXAME DE SAÚDE</b></center></td>
</tr>

<tr>
    <td><b>Grupo do Ex Med: </b>".$obrigatorio->getJise()."</td>
</tr>

<tr>
    <td><b>CID: </b>".$obrigatorio->getCidJise()." </td>
    <td><b>Data Inspeção de Saúde: </b>".$obrigatorio->imprimeDataComparecimentoSelecaoGeral()."</td>
</tr>

<tr>
    <td colspan=\"2\"><b>Observação: </b>".$obrigatorio->getObservacaoJise()."</td>
</tr>

<tr style='background-color: #D8D8D8'>
    <td colspan=\"2\"> <center><b>EXAME DE SAÚDE EM GRAU DE RECURSO</b></center></td>
</tr>

<tr>
    <td><b>JISR: </b>".$obrigatorio->getJisr()."</td>
</tr>

<tr>
    <td><b>CID JISR: </b>".$obrigatorio->getCidJisR()." </td>
    <td><b>Data do JISR: </b>".$obrigatorio->imprimeDataJisr()."</td>
</tr>


<tr>
    <td colspan=\"2\"><b>Observação JISR: </b>".$obrigatorio->getObsJisr()."</td>
</tr>


</table> ";


$html = $html. "  <br><br>

<table border='0' style='width:100%'>
  <tr>
    <th align='left'><strong> <p style='font-size: 12px; font-family: Times New Roman;'>  ".$data_hoje." - " . $junta_cidade . "  </p></strong></th>
  </tr>
  
</table> ";


$html = $html . " <br><br>
 <table border='0' style='font-size: 10px; font-family: Times New Roman; width:100%'>
  <tr>
    <th width='33%'>______________________________________</th>
    <th width='33%'>______________________________________</th>
    <th width='33%'>______________________________________</th>
  </tr>";

if ($junta)
$html = $html . "

  <tr>
    <th>" . $junta[0]['presidente'] . "</th>
    <th>" . $junta[0]['membro_1'] . " </th>
    <th>" . $junta[0]['membro_2'] . "</th>
  </tr>
";

$html = $html . " </table> <br>

<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   &nbsp;&nbsp;&nbsp;
   Eu, ". mb_strtoupper($obrigatorio->getNomeCompleto(), "UTF-8").", em _____/_____/20_____, tomei ciência do resultado deste parecer, e caso não  concorde, tenho até 15 dias para requerer Inspeção em grau de recurso.   
</p>

<br><br>

<center>
<p align='center' style='font-size: 12px; font-family: Times New Roman;'>
_________________________________________<br>
". mb_strtoupper($obrigatorio->getNomeCompleto(), "UTF-8")."
<br>
CPF: ".$obrigatorio->getCpf()." 
</p>
</center>
";

$mpdf = new mPDF('C', 'A4'); 
$mpdf->WriteHTML($html);

$alteracao_detalahada = "CPF do Obrigatório ". $obrigatorio->getCpf();
$insere_log = $logDAO->insertLog(4002, "PDF", $id_obrigatorio, "Gerou o JISR Individual do Obrigatório " . $obrigatorio->getCpf(), $alteracao_detalahada);


ob_get_clean();
$mpdf->Output();
//$mpdf->Output($arquivo, 'F');

?>
