<?php 

ob_start();

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
    <th align='center'><strong>". $obrigatorio->getNomeCompleto() . "   </strong>
  </tr>
  <tr>
    <th align='center'><strong>Ficha Completa</strong>
  </tr>
</table> 

<br>
<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
   <b>Situação Militar</b>: ".$obrigatorio->getSituacaoMilitar()."
</p>

";

 $html = $html. "
     
<table border='0'  style='font-size: 10px; font-family: Times New Roman; width:100%' >

<tr style='background-color: #D8D8D8'>
    <td colspan=\"3\"> <center><b>IDENTIFICAÇÃO</b></center></td>
</tr>

<tr>
    <td><b>Nome Completo: </b> ".$obrigatorio->getNomeCompleto()."</td>
    <td><b>CPF: </b> ".$obrigatorio->getCpf()."</td>
    <td><b>Estado Civil: </b> ".$obrigatorio->getEstadoCivil()."</td>
</tr>
<tr>
    <td><b>Data de Nascimento: </b>".$data_nascimento." </td>
    <td><b>Identidade: </b>".$obrigatorio->getId()." </td>
    <td><b>E-mail: </b>".$obrigatorio->getMail()." </td>
</tr>

<tr>
    <td><b>Telefone: </b>".$obrigatorio->getTelefone()." </td>
    <td><b>Nome Pai: </b>".$obrigatorio->getNomePai()." </td>
    <td><b>Nome Mãe: </b>".$obrigatorio->getNomeMae()." </td>
</tr>

<tr>
    <td><b>Voluntário: </b>".$obrigatorio->getVoluntario()." </td>
    <td><b>Nacionalidade: </b>".$obrigatorio->getNacionalidade()." </td>
    <td><b>Naturalidade: </b>".$obrigatorio->getNaturalidade()." </td>
</tr>

<tr>
    <td><b>Endereço: </b>".$obrigatorio->getEndereco()." </td>
    <td><b>Dependentes: </b>".$obrigatorio->getDependentes()." </td>
    <td><b>Força: </b>".$obrigatorio->getForca()." </td>
</tr>


<tr style='background-color: #D8D8D8'>
<td colspan=\"3\"> <center><b>FORMAÇÃO</b></center></td>
</tr>

<tr>
    <td><b>IE Graduação: </b>".$obrigatorio->getNomeInstitutoEnsino()."</td>
    <td><b>Ano de Formação: </b>".$obrigatorio->getAnoFormacao()."</td>
    <td><b>Formação: </b>".$obrigatorio->getFormacao()."</td>
</tr>

<tr>
    <td><b>Especialidade 1: </b>".$obrigatorio->getEspecialidade()."</td>
    <td><b>Especialidade 2: </b>".$obrigatorio->getEspecialidade2()."</td>
    <td><b>Especialidade 3: </b>".$obrigatorio->getEspecialidade3()."</td>
</tr>

<tr>
    <td><b>Ano Especialidade 1: </b>".$obrigatorio->getAnoResEspe1()."</td>
    <td><b>Ano Especialidade 2: </b>".$obrigatorio->getAnoResEspe2()."</td>
    <td><b>Ano Especialidade 3: </b>".$obrigatorio->getAnoResEspe3()."</td>
</tr>

<tr style='background-color: #D8D8D8'>
    <td colspan=\"3\"> <center><b>ADIAMENTO SMO</b></center></td>
</tr>

<tr>
    <td><b>Solicitou Adiamento?: </b>".$obrigatorio->getSolicitouAdiamento()."</td>
    <td><b>Início Adiamento: </b>".$obrigatorio->getInicioAdiamento()." </td>
    <td><b>fIM Adiamento: </b>".$obrigatorio->getFimAdiamento()." </td>
</tr>

<tr>
    <td><b>Especialidade Adiamento?: </b>".$obrigatorio->getEspecialidadeAdiamento()."</td>
</tr>

<tr style='background-color: #D8D8D8'>
    <td colspan=\"3\"> <center><b>SELEÇÃO GERAL</b></center></td>
</tr>

<tr>
    <td><b>Data Comparecimento Sel Geral: </b>".$obrigatorio->getDataComparecimentoSelecaoGeral()."</td>
    <td><b>JISE: </b>".$obrigatorio->getJise()." </td>
    <td><b>CID JISE: </b>".$obrigatorio->getCidJise()." </td>
</tr>

<tr>
    <td><b>Obs JISE: </b>".$obrigatorio->getObservacaoJise()."</td>
    <td><b>JISR: </b>".$obrigatorio->getJisr()." </td>
    <td><b>CID JISR: </b>".$obrigatorio->getCidJisr()." </td>
</tr>

<tr>
    <td><b>Data JISR: </b>".$obrigatorio->getDataJisr()."</td>
    <td><b>Obs JISR: </b>".$obrigatorio->getObsJisr()." </td>
    <td><b>JISE A-1: </b>".$obrigatorio->getCidJisea1()." </td>
</tr>

<tr>
    <td><b>CID JISE A-1: </b>".$obrigatorio->getCidJisea1()."</td>
    <td><b>Data JISE A-1: </b>".$obrigatorio->getDataJisea1()." </td>
</tr>

<tr style='background-color: #D8D8D8'>
    <td colspan=\"3\"> <center><b>REVISÃO MÉDICA - OM 1ª FASE </b></center></td>
</tr>

<tr>
    <td><b>Data Revisão Médica: </b>".$obrigatorio->getData_revisao_medica()."</td>
    <td><b>Resultado Revisão Médica: </b>".$obrigatorio->getResultadoRevisaoMedicaComplementar()." </td>
    <td><b>CID Revisão Médica: </b>".$obrigatorio->getCid_revisao_medica()." </td>
</tr>

<tr>
    <td><b>Obs Revisão Médica: </b>".$obrigatorio->getObs_revisao_medica()."</td>
</tr>

<tr style='background-color: #D8D8D8'>
    <td colspan=\"3\"> <center><b>FISEMI</b></center></td>
</tr>
<tr>
    <td><b>Transferência do FISEMI: </b>".$obrigatorio->getTransferenciaFisemi()."</td>
    <td><b>Região Origem: </b>".$obrigatorio->getRmOrigemFisemi()." </td>
    <td><b>Região Destino: </b>".$obrigatorio->getRmDestinoFisemi()." </td>
</tr>

<tr style='background-color: #D8D8D8'>
    <td colspan=\"3\"> <center><b>ISGRev</b></center></td>
</tr>
<tr>
    <td><b>ISGRev: </b>".$obrigatorio->getCid_isgr()."</td>
    <td><b>Data ISGRev: </b>".$obrigatorio->getData_isgr()." </td>
    <td><b>Região Destino: </b>".$obrigatorio->getRmDestinoFisemi()." </td>
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
$insere_log = $logDAO->insertLog(4001, "PDF", $id_obrigatorio, "Gerou o JISE Individual do Obrigatório " . $obrigatorio->getCpf(), $alteracao_detalahada);

ob_get_clean();
$mpdf->Output();
//$mpdf->Output($arquivo, 'F');

 
?>
