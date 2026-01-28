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
<style>
    body { font-family: 'Times New Roman', Times, serif; }
    .cabecalho { font-size: 9px; text-align: center; line-height: 1.4; margin-bottom: 20px; }
    .titulo-sessao { font-size: 14px; font-weight: bold; text-align: center; margin: 15px 0; }
    .titulo-documento { font-size: 16px; font-weight: bold; text-align: center; margin: 10px 0 20px 0; }
    .texto-corpo { font-size: 11px; text-align: justify; line-height: 1.6; margin: 15px 0; text-indent: 60px; }
    .tabela-dados { width: 100%; border-collapse: collapse; font-size: 10px; margin: 15px 0; }
    .tabela-dados td { padding: 8px 10px; border: 1px solid #CCCCCC; }
    .tabela-dados .secao-header { background-color: #E8E8E8; font-weight: bold; text-align: center; padding: 10px; }
    .tabela-dados .campo-label { font-weight: bold; }
    .tabela-assinatura { width: 100%; border-collapse: collapse; font-size: 10px; margin-top: 30px; }
    .tabela-assinatura td { text-align: center; padding: 5px; border: none; }
    .linha-assinatura { border-top: 1px solid #000000; display: inline-block; width: 280px; margin-top: 40px; }
    .texto-assinatura { font-size: 11px; text-align: center; margin-top: 25px; }
    .campo-data { border-bottom: 1px solid #000000; display: inline-block; min-width: 120px; }
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

<div class='titulo-sessao'>Sessão ". $junta_secao . " - SMO</div>
<div class='titulo-documento'>JISR</div>

<p class='texto-corpo'>
   A Junta de Inspeção de Saúde Especial inspecionou na presente sessão, o abaixo declarado, para fins de incorporação sobre seu estado de saúde, proferiu o parecer abaixo:
</p>
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
    <td><span class='campo-label'>Naturalidade:</span> ".$obrigatorio->getNaturalidade()."</td>
    <td><span class='campo-label'>Data de Nascimento:</span> ".$data_nascimento."</td>
</tr>

<tr>
    <td colspan=\"2\" class='secao-header'>INSTITUIÇÃO DE ENSINO</td>
</tr>

<tr>
    <td><span class='campo-label'>Nome do Instituto de Ensino:</span> ".$obrigatorio->getNomeInstitutoEnsino()."</td>
    <td><span class='campo-label'>Ano de Formação:</span> ".$obrigatorio->getAnoFormacao()."</td>
</tr>

<tr>
    <td colspan=\"2\"><span class='campo-label'>Formação:</span> ".$obrigatorio->getFormacao()."</td>
</tr>

<tr>
    <td colspan=\"2\" class='secao-header'>CIVIL/MILITAR</td>
</tr>

<tr>
    <td><span class='campo-label'>Situação Militar:</span> ".$obrigatorio->getSituacaoMilitar()."</td>
    <td><span class='campo-label'>Documento Militar:</span> ".$obrigatorio->getDocumentoMilitar()."</td>
</tr>

<tr>
    <td><span class='campo-label'>Nº do Documento:</span> ".$obrigatorio->getNumeroDocumentoMilitar()."</td>
    <td><span class='campo-label'>Data da Expedição:</span> ".$data_expedicao."</td>
</tr>

<tr>
    <td colspan=\"2\" class='secao-header'>EXAME DE SAÚDE</td>
</tr>

<tr>
    <td colspan=\"2\"><span class='campo-label'>Grupo do Ex Med:</span> ".$obrigatorio->getJise()."</td>
</tr>

<tr>
    <td><span class='campo-label'>CID:</span> ".$obrigatorio->getCidJise()."</td>
    <td><span class='campo-label'>Data Inspeção de Saúde:</span> ".$obrigatorio->imprimeDataComparecimentoSelecaoGeral()."</td>
</tr>

<tr>
    <td colspan=\"2\"><span class='campo-label'>Observação:</span> ".$obrigatorio->getObservacaoJise()."</td>
</tr>

<tr>
    <td colspan=\"2\" class='secao-header'>EXAME DE SAÚDE EM GRAU DE RECURSO</td>
</tr>

<tr>
    <td colspan=\"2\"><span class='campo-label'>JISR:</span> ".$obrigatorio->getJisr()."</td>
</tr>

<tr>
    <td><span class='campo-label'>CID JISR:</span> ".$obrigatorio->getCidJisR()."</td>
    <td><span class='campo-label'>Data do JISR:</span> ".$obrigatorio->imprimeDataJisr()."</td>
</tr>

<tr>
    <td colspan=\"2\"><span class='campo-label'>Observação JISR:</span> ".$obrigatorio->getObsJisr()."</td>
</tr>

</table> ";


$html = $html. "

<div style='margin-top: 30px;'>
    <p style='font-size: 11px; font-weight: bold;'>".$data_hoje." - " . $junta_cidade . "</p>
</div>

<table class='tabela-assinatura'>
  <tr>
    <td width='33%'><span class='linha-assinatura'></span></td>
    <td width='33%'><span class='linha-assinatura'></span></td>
    <td width='33%'><span class='linha-assinatura'></span></td>
  </tr>";

if ($junta)
$html = $html . "
  <tr>
    <td>" . $junta[0]['presidente'] . "</td>
    <td>" . $junta[0]['membro_1'] . "</td>
    <td>" . $junta[0]['membro_2'] . "</td>
  </tr>
";

$html = $html . "</table>

<p class='texto-corpo' style='margin-top: 25px;'>
   Eu, ". mb_strtoupper($obrigatorio->getNomeCompleto(), "UTF-8").", em <span class='campo-data'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>/<span class='campo-data'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>/<span class='campo-data'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>, tomei ciência do resultado deste parecer, e caso não concorde, tenho até 15 dias para requerer Inspeção em grau de recurso.
</p>

<div class='texto-assinatura'>
    <span class='linha-assinatura'></span><br>
    <strong>". mb_strtoupper($obrigatorio->getNomeCompleto(), "UTF-8")."</strong><br>
    CPF: ".$obrigatorio->getCpf()."
</div>
";

$mpdf = new mPDF('C', 'A4'); 
$mpdf->WriteHTML($html);

$alteracao_detalahada = "CPF do Obrigatório ". $obrigatorio->getCpf();
$insere_log = $logDAO->insertLog(4002, "PDF", $id_obrigatorio, "Gerou o JISR Individual do Obrigatório " . $obrigatorio->getCpf(), $alteracao_detalahada);


ob_get_clean();
$mpdf->Output();
//$mpdf->Output($arquivo, 'F');

?>
