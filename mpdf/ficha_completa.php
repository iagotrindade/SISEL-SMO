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
<style>
    body { font-family: 'Times New Roman', Times, serif; }
    .cabecalho { font-size: 9px; text-align: center; line-height: 1.4; margin-bottom: 20px; }
    .titulo-nome { font-size: 14px; font-weight: bold; text-align: center; margin: 15px 0 5px 0; }
    .titulo-documento { font-size: 15px; font-weight: bold; text-align: center; margin: 5px 0 15px 0; }
    .info-situacao { font-size: 11px; font-weight: bold; margin: 15px 0; }
    .tabela-dados { width: 100%; border-collapse: collapse; font-size: 9px; margin: 15px 0; }
    .tabela-dados td { padding: 6px 8px; border: 1px solid #CCCCCC; }
    .tabela-dados .secao-header { background-color: #E8E8E8; font-weight: bold; text-align: center; padding: 8px; }
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

<div class='titulo-nome'>". $obrigatorio->getNomeCompleto() . "</div>
<div class='titulo-documento'>Ficha Completa</div>

<div class='info-situacao'>
   <span class='campo-label'>Situação Militar:</span> ".$obrigatorio->getSituacaoMilitar()."
</div>
";

 $html = $html. "

<table class='tabela-dados'>

<tr>
    <td colspan=\"3\" class='secao-header'>IDENTIFICAÇÃO</td>
</tr>

<tr>
    <td width='33%'><span class='campo-label'>Nome Completo:</span> ".$obrigatorio->getNomeCompleto()."</td>
    <td width='33%'><span class='campo-label'>CPF:</span> ".$obrigatorio->getCpf()."</td>
    <td width='33%'><span class='campo-label'>Estado Civil:</span> ".$obrigatorio->getEstadoCivil()."</td>
</tr>
<tr>
    <td><span class='campo-label'>Data de Nascimento:</span> ".$data_nascimento."</td>
    <td><span class='campo-label'>Identidade:</span> ".$obrigatorio->getId()."</td>
    <td><span class='campo-label'>E-mail:</span> ".$obrigatorio->getMail()."</td>
</tr>

<tr>
    <td><span class='campo-label'>Telefone:</span> ".$obrigatorio->getTelefone()."</td>
    <td><span class='campo-label'>Nome Pai:</span> ".$obrigatorio->getNomePai()."</td>
    <td><span class='campo-label'>Nome Mãe:</span> ".$obrigatorio->getNomeMae()."</td>
</tr>

<tr>
    <td><span class='campo-label'>Voluntário:</span> ".$obrigatorio->getVoluntario()."</td>
    <td><span class='campo-label'>Nacionalidade:</span> ".$obrigatorio->getNacionalidade()."</td>
    <td><span class='campo-label'>Naturalidade:</span> ".$obrigatorio->getNaturalidade()."</td>
</tr>

<tr>
    <td><span class='campo-label'>Endereço:</span> ".$obrigatorio->getEndereco()."</td>
    <td><span class='campo-label'>Dependentes:</span> ".$obrigatorio->getDependentes()."</td>
    <td><span class='campo-label'>Força:</span> ".$obrigatorio->getForca()."</td>
</tr>

<tr>
    <td colspan=\"3\" class='secao-header'>FORMAÇÃO</td>
</tr>

<tr>
    <td><span class='campo-label'>IE Graduação:</span> ".$obrigatorio->getNomeInstitutoEnsino()."</td>
    <td><span class='campo-label'>Ano de Formação:</span> ".$obrigatorio->getAnoFormacao()."</td>
    <td><span class='campo-label'>Formação:</span> ".$obrigatorio->getFormacao()."</td>
</tr>

<tr>
    <td><span class='campo-label'>Especialidade 1:</span> ".$obrigatorio->getEspecialidade()."</td>
    <td><span class='campo-label'>Especialidade 2:</span> ".$obrigatorio->getEspecialidade2()."</td>
    <td><span class='campo-label'>Especialidade 3:</span> ".$obrigatorio->getEspecialidade3()."</td>
</tr>

<tr>
    <td><span class='campo-label'>Ano Especialidade 1:</span> ".$obrigatorio->getAnoResEspe1()."</td>
    <td><span class='campo-label'>Ano Especialidade 2:</span> ".$obrigatorio->getAnoResEspe2()."</td>
    <td><span class='campo-label'>Ano Especialidade 3:</span> ".$obrigatorio->getAnoResEspe3()."</td>
</tr>

<tr>
    <td colspan=\"3\" class='secao-header'>ADIAMENTO SMO</td>
</tr>

<tr>
    <td><span class='campo-label'>Solicitou Adiamento?:</span> ".$obrigatorio->getSolicitouAdiamento()."</td>
    <td><span class='campo-label'>Início Adiamento:</span> ".$obrigatorio->getInicioAdiamento()."</td>
    <td><span class='campo-label'>Fim Adiamento:</span> ".$obrigatorio->getFimAdiamento()."</td>
</tr>

<tr>
    <td colspan=\"3\"><span class='campo-label'>Especialidade Adiamento?:</span> ".$obrigatorio->getEspecialidadeAdiamento()."</td>
</tr>

<tr>
    <td colspan=\"3\" class='secao-header'>SELEÇÃO GERAL</td>
</tr>

<tr>
    <td><span class='campo-label'>Data Comparecimento Sel Geral:</span> ".$obrigatorio->getDataComparecimentoSelecaoGeral()."</td>
    <td><span class='campo-label'>JISE:</span> ".$obrigatorio->getJise()."</td>
    <td><span class='campo-label'>CID JISE:</span> ".$obrigatorio->getCidJise()."</td>
</tr>

<tr>
    <td><span class='campo-label'>Obs JISE:</span> ".$obrigatorio->getObservacaoJise()."</td>
    <td><span class='campo-label'>JISR:</span> ".$obrigatorio->getJisr()."</td>
    <td><span class='campo-label'>CID JISR:</span> ".$obrigatorio->getCidJisr()."</td>
</tr>

<tr>
    <td><span class='campo-label'>Data JISR:</span> ".$obrigatorio->getDataJisr()."</td>
    <td><span class='campo-label'>Obs JISR:</span> ".$obrigatorio->getObsJisr()."</td>
    <td><span class='campo-label'>JISE A-1:</span> ".$obrigatorio->getCidJisea1()."</td>
</tr>

<tr>
    <td><span class='campo-label'>CID JISE A-1:</span> ".$obrigatorio->getCidJisea1()."</td>
    <td colspan=\"2\"><span class='campo-label'>Data JISE A-1:</span> ".$obrigatorio->getDataJisea1()."</td>
</tr>

<tr>
    <td colspan=\"3\" class='secao-header'>REVISÃO MÉDICA - OM 1ª FASE</td>
</tr>

<tr>
    <td><span class='campo-label'>Data Revisão Médica:</span> ".$obrigatorio->getData_revisao_medica()."</td>
    <td><span class='campo-label'>Resultado Revisão Médica:</span> ".$obrigatorio->getResultadoRevisaoMedicaComplementar()."</td>
    <td><span class='campo-label'>CID Revisão Médica:</span> ".$obrigatorio->getCid_revisao_medica()."</td>
</tr>

<tr>
    <td colspan=\"3\"><span class='campo-label'>Obs Revisão Médica:</span> ".$obrigatorio->getObs_revisao_medica()."</td>
</tr>

<tr>
    <td colspan=\"3\" class='secao-header'>FISEMI</td>
</tr>
<tr>
    <td><span class='campo-label'>Transferência do FISEMI:</span> ".$obrigatorio->getTransferenciaFisemi()."</td>
    <td><span class='campo-label'>Região Origem:</span> ".$obrigatorio->getRmOrigemFisemi()."</td>
    <td><span class='campo-label'>Região Destino:</span> ".$obrigatorio->getRmDestinoFisemi()."</td>
</tr>

<tr>
    <td colspan=\"3\" class='secao-header'>ISGRev</td>
</tr>
<tr>
    <td><span class='campo-label'>ISGRev:</span> ".$obrigatorio->getCid_isgr()."</td>
    <td><span class='campo-label'>Data ISGRev:</span> ".$obrigatorio->getData_isgr()."</td>
    <td><span class='campo-label'>Região Destino:</span> ".$obrigatorio->getRmDestinoFisemi()."</td>
</tr>

</table>";

                      
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

<p class='texto-corpo' style='margin-top: 25px; text-align: justify; text-indent: 60px; font-size: 11px;'>
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
$insere_log = $logDAO->insertLog(4001, "PDF", $id_obrigatorio, "Gerou o JISE Individual do Obrigatório " . $obrigatorio->getCpf(), $alteracao_detalahada);

ob_get_clean();
$mpdf->Output();
//$mpdf->Output($arquivo, 'F');

 
?>
