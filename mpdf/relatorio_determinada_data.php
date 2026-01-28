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
<style>
    body { font-family: 'Times New Roman', Times, serif; }
    .cabecalho { font-size: 10px; text-align: center; line-height: 1.4; margin-bottom: 8px; }
    .titulo-principal { font-size: 12px; font-weight: bold; text-align: center; margin: 15px 0; }
    .titulo-secundario { font-size: 12px; font-weight: bold; text-align: center; margin: 10px 0; }
    .subtitulo { font-size: 12px; font-weight: bold; text-align: center; margin: 10px 0 20px 0; }
    .tabela-lista { width: 100%; border-collapse: collapse; font-size: 12px; margin: 15px 0; }
    .tabela-lista th { padding: 2px 4px; border: 1px solid #CCCCCC; background-color: #E8E8E8; font-weight: bold; text-align: center; }
    .tabela-lista td { padding: 2px 4px; border: 1px solid #DDDDDD; vertical-align: top; }
    .tabela-assinatura { width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 40px; }
    .tabela-assinatura td { text-align: center; padding: 5px; border: none; }
    .linha-assinatura { border-top: 1px solid #000000; display: inline-block; width: 320px; margin-top: 50px; }
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

<div class='titulo-principal'>Relatório dos Inspecionados</div>
<div class='titulo-secundario'>Em $data_sel_geral</div>
<div class='subtitulo'>JISE: Sessão Nº $sessao - CSE - Médicos Obrigatórios ".$_POST['data_inspecao']." na cidade de $cidade.</div>
" ;

$lista_obrigatorios = $conexao->findObrigatoriosPorDataCompSelGeral($data_tratada); 

    $html = $html . "
        <table class='tabela-lista'>
            <tr>
                <th width='5%'>Nº</th>
                <th width='12%'>CPF</th>
                <th width='30%'>NOME COMPLETO</th>
                <th width='12%'>NASCIMENTO</th>
                <th width='23%'>NOME DA MÃE</th>
                <th width='10%'>PARECER</th>
                <th width='8%'>CID</th>
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
            <td style='text-align: center;'>".$contador."</td>
            <td>".$candidato['cpf']."</td>
            <td>".mb_strtoupper($candidato['nome_completo'],"UTF-8")."</td>
            <td style='text-align: center;'>".$nascimento."</td>
            <td>".$candidato['nome_mae']."</td>
            <td style='text-align: center;'>".$parecer."</td>
            <td style='text-align: center;'>".$cid."</td>
        </tr>";
    
    $contador++;
}
$html = $html . "</table>";


$html = $html . "
<table class='tabela-assinatura'>
  <tr>
    <td width='33%'><span class='linha-assinatura'></span></td>
    <td width='33%'><span class='linha-assinatura'></span></td>
    <td width='33%'><span class='linha-assinatura'></span></td>
  </tr>
  <tr>
    <td>$assinante_1</td>
    <td>$assinante_2</td>
    <td>$assinante_3</td>
  </tr>
</table>";

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
