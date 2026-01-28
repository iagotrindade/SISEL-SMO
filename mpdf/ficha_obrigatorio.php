<?php 

ob_start();


//error_reporting(E_ALL);
//ini_set('display_errors', 1);

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
    if($prioridade_guarnicao)
    foreach ($prioridade_guarnicao as $value) 
    { 
        $listapg = $listapg . "<b>" . $value['prioridade'] . "ª</b> " . $value['guarnicao'] . " - ";
    }
 
    $dataexpedicao = $obrigatorio->getDataExpedicao();
    $dataexpedicao = trata_data($dataexpedicao);

    $datetime = date('d/m/Y H:i:s');
    $data_hoje = date('d/m/Y');

$html = "
<style>
    body { font-family: 'Times New Roman', Times, serif; }
    .cabecalho { font-size: 9px; text-align: center; line-height: 1.4; margin-bottom: 20px; }
    .titulo-principal { font-size: 15px; font-weight: bold; text-align: center; margin: 15px 0; }
    .titulo-secundario { font-size: 13px; font-weight: bold; text-align: center; margin: 10px 0 20px 0; }
    .tabela-dados { width: 100%; border-collapse: collapse; font-size: 10px; margin: 15px 0; }
    .tabela-dados td { padding: 8px 10px; border: 1px solid #CCCCCC; }
    .tabela-dados .secao-header { background-color: #E8E8E8; font-weight: bold; text-align: center; padding: 10px; }
    .tabela-dados .campo-label { font-weight: bold; }
    .tabela-assinatura { width: 100%; border-collapse: collapse; font-size: 10px; margin-top: 30px; }
    .tabela-assinatura td { text-align: center; padding: 5px; border: none; }
    .linha-assinatura { border-top: 1px solid #000000; display: inline-block; width: 280px; margin-top: 40px; }
    .campo-data { border-bottom: 1px solid #000000; display: inline-block; min-width: 150px; }
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

<div class='titulo-principal'>FICHA CADASTRAL</div>
<div class='titulo-secundario'>" . $obrigatorio->getFormacao() . "</div>
";

 $html = $html. "

<table class='tabela-dados'>

<tr>
    <td colspan=\"2\" class='secao-header'>IDENTIFICAÇÃO</td>
</tr>

<tr>
    <td width='50%'><span class='campo-label'>Nome Completo:</span> ".$obrigatorio->getNomeCompleto()."</td>
    <td width='50%'><span class='campo-label'>CPF:</span> ".$obrigatorio->getCpf()."</td>
</tr>
<tr>
    <td><span class='campo-label'>Estado Civil:</span> ".$obrigatorio->getEstadoCivil()."</td>
    <td><span class='campo-label'>Data de Nascimento:</span> ".$obrigatorio->getDataNascimento()."</td>
</tr>

<tr>
    <td><span class='campo-label'>Identidade:</span> ".$obrigatorio->getIdentidade()."</td>
    <td><span class='campo-label'>Email:</span> ".$obrigatorio->getMail()."</td>
</tr>

<tr>
    <td><span class='campo-label'>Telefone:</span> ".$obrigatorio->getTelefone()."</td>
    <td><span class='campo-label'>Nome do Pai:</span> ".$obrigatorio->getNomePai()."</td>
</tr>

<tr>
    <td><span class='campo-label'>Nome da Mãe:</span> ".$obrigatorio->getNomeMae()."</td>
    <td><span class='campo-label'>Voluntário:</span> ".$obrigatorio->getVoluntario()."</td>
</tr>

<tr>
    <td><span class='campo-label'>Nacionalidade:</span> ".$obrigatorio->getNacionalidade()."</td>
    <td><span class='campo-label'>Naturalidade:</span> ".$obrigatorio->getNaturalidade()."</td>
</tr>

<tr>
    <td><span class='campo-label'>Endereço Completo:</span> ".$obrigatorio->getEndereco()."</td>
    <td><span class='campo-label'>Dependentes:</span> ".$obrigatorio->getDependentes()."</td>
</tr>

<tr>
    <td colspan=\"2\"><span class='campo-label'>Prioridade Força:</span> ".$obrigatorio->getPrioridadeForca()."</td>
</tr>

<tr>
    <td colspan=\"2\" class='secao-header'>ENSINO</td>
</tr>

<tr>
    <td><span class='campo-label'>Formação:</span> ".$obrigatorio->getFormacao()."</td>
    <td><span class='campo-label'>Ano de Formação:</span> ".$obrigatorio->getAnoFormacao()."</td>
</tr>

<tr>
    <td colspan=\"2\"><span class='campo-label'>Instituição de Ensino:</span> ".$obrigatorio->getNomeInstitutoEnsino()."</td>
</tr>

<tr>
    <td colspan=\"2\" class='secao-header'>SITUAÇÃO MILITAR</td>
</tr>

<tr>
    <td><span class='campo-label'>Situação Militar:</span> ".$obrigatorio->getSituacaoMilitar()."</td>
    <td><span class='campo-label'>Força:</span> ".$obrigatorio->getForca()."</td>
</tr>

<tr>
    <td><span class='campo-label'>Número do Doc Militar:</span> ".$obrigatorio->getNumeroDocumentoMilitar()."</td>
    <td><span class='campo-label'>Data de Expedição:</span> ". $dataexpedicao ."</td>
</tr>

<tr>
    <td colspan=\"2\"><span class='campo-label'>Documento Militar:</span> ".$obrigatorio->getDocumentoMilitar()."</td>
</tr>

<tr>
    <td colspan=\"2\" class='secao-header'>SAÚDE</td>
</tr>

<tr>
    <td><span class='campo-label'>Grupo do Exame Médico:</span> ".$obrigatorio->getJise()."</td>
    <td><span class='campo-label'>CID JISE:</span> ".$obrigatorio->getCidJise()."</td>
</tr>

<tr>
    <td colspan=\"2\"><span class='campo-label'>Data de Comparecimento Seleção Geral:</span> ".$obrigatorio->imprimeDataComparecimentoSelecaoGeral()."</td>
</tr>

<tr>
    <td colspan=\"2\"><span class='campo-label'>Observação:</span> ".$obrigatorio->getObservacaoJise()."</td>
</tr>";

if ($obrigatorio->getJisr() != null || $obrigatorio->getCidJisr() != null || $obrigatorio->getObsJisr() != null || $obrigatorio->getDataJisr() != null)
$html = $html."
<tr>
    <td colspan=\"2\" style='height: 10px;'></td>
</tr>
<tr>
    <td><span class='campo-label'>Grupo do Exame Médico JISR:</span> ".$obrigatorio->getCidJisr()."</td>
    <td><span class='campo-label'>CID JISR:</span> ".$obrigatorio->getCidJisr()."</td>
</tr>

<tr>
    <td colspan=\"2\"><span class='campo-label'>Data de Realização do Exame Médico JISR:</span> ".$obrigatorio->imprimeDataJisr()."</td>
</tr>

<tr>
    <td colspan=\"2\"><span class='campo-label'>Observação JISR:</span> ".$obrigatorio->getObsJisr()."</td>
</tr>";

if ($obrigatorio->getJisea1() != null || $obrigatorio->getCidJisea1() != null || $obrigatorio->getObservacaoJisea1() != null || $obrigatorio->getDataJisea1() != null)
$html = $html."
        <tr>
            <td colspan=\"2\" style='height: 10px;'></td>
        </tr>
        <tr>
            <td><span class='campo-label'>Grupo do Exame Médico JISE A-1:</span> ".$obrigatorio->getJisea1()."</td>
            <td><span class='campo-label'>CID JISE A-1:</span> ".$obrigatorio->getCidJisea1()."</td>
        </tr>

        <tr>
            <td colspan=\"2\"><span class='campo-label'>Data de Realização do Exame Médico JISE A-1:</span> ".$obrigatorio->imprimeDataJisea1()."</td>
        </tr>

        <tr>
            <td colspan=\"2\"><span class='campo-label'>Observação JISE A-1:</span> ".$obrigatorio->getObservacaoJisea1()."</td>
        </tr>";

$html = $html."

<tr>
    <td colspan=\"2\" class='secao-header'>ESPECIALIDADES</td>
</tr>

<tr>
    <td><span class='campo-label'>Especialidade:</span> ".$obrigatorio->getEspecialidade(). " (" . $obrigatorio->getAnoResEspe1() . ")</td>
    <td><span class='campo-label'>2ª Especialidade:</span> ".$obrigatorio->getEspecialidade2(). " (" . $obrigatorio->getAnoResEspe2() . ")</td>
</tr>
<tr>
    <td colspan=\"2\"><span class='campo-label'>3ª Especialidade:</span> ".$obrigatorio->getEspecialidade3(). " (" . $obrigatorio->getAnoResEspe3() . ")</td>
</tr>

<tr>
    <td colspan=\"2\" class='secao-header'>PRIORIDADE DE CIDADES</td>
</tr>

<tr>
    <td colspan=\"2\">$listapg</td>
</tr>

</table>";


$html = $html. "

<div style='margin-top: 25px;'>
    <p style='font-size: 11px;'>Data: <span class='campo-data'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>/<span class='campo-data'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>/<span class='campo-data'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
</div>

<table class='tabela-assinatura'>
<tr>
  <td width='33%'><span class='linha-assinatura'></span></td>
  <td width='33%'><span class='linha-assinatura'></span></td>
  <td width='33%'><span class='linha-assinatura'></span></td>
</tr>

<tr>
  <td>Presidente da CSE</td>
  <td>" . mb_strtoupper($obrigatorio->getNomeCompleto(), "UTF-8"). "</td>
  <td>Avaliador / Entrevistador</td>
</tr>
</table>
";

$mpdf = new mPDF('C', 'A4');
$mpdf->WriteHTML($html);

$cpf = $obrigatorio->getCpf();
$insere_log = $logDAO->insertLog(4003, "PDF", $id_obrigatorio, "Gerou a Ficha do Obrigatório de CPF " . $obrigatorio->getCpf(), $obrigatorio);

ob_get_clean();
$mpdf->Output();
//$mpdf->Output($arquivo, 'F');
 
 
 ?>

