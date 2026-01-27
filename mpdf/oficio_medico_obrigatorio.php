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
include_once '../dao/OficioDAO.php';
include_once '../dao/AuxiliarDAO.php';
include("mpdf60/mpdf.php");

if($_SESSION['perfil_smo'] != "admin")
{    
    erro($BASE_URL, 2, 113551322, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");
    exit();
}

$logDAO = new logDAO($conexao);
$oficioDAO = new OficioDAO($conexao);
$obrigatorioDAO = new obrigatorioDAO($conexao);
$auxiliar = new AuxiliarDAO($conexao);

$oficio_atualiza = new Oficio();

$oficio_atualiza->setIdObrigatorio(filtra_campo_post('id_obrigatorio')); 
$oficio_atualiza->setBairroOm1Fase(filtra_campo_post('bairro_om_1_fase')); 
$oficio_atualiza->setPresidenteComissao(filtra_campo_post('presidente_comissao')); 
$oficio_atualiza->setCepCidadeom1Fase(filtra_campo_post('cep_cidade_om_1_fase')); 
$oficio_atualiza->setCidadeOm1Fase(filtra_campo_post('cidade_om_1_fase')); 
$oficio_atualiza->setDataApresentacao(trata_data(filtra_campo_post('data_apresentacao')));   
$oficio_atualiza->setHoraApresentacao(filtra_campo_post('hora_apresentacao'));  
$oficio_atualiza->setTelOm1Fase(filtra_campo_post('tel_om_1_fase')); 
$oficio_atualiza->setDataCabecalho(trata_data(filtra_campo_post('data_cabecalho')));

$oficio_existente = $oficioDAO->findByIdObrigatorio($oficio_atualiza->getIdObrigatorio());

if ($oficio_existente == false) 
{
    $data = $oficioDAO->insert($oficio_atualiza);
    if($data)
    {
        $alteracao = "Cadastrou o Ofício ID: " . $data['id_obrigatorio'] ;
        $alteracao_detalahada = print_r($data, true);
        $logDAO->insertLog(1005, "oficio", $data['id_obrigatorio'], $alteracao, $alteracao_detalahada);
    }

    else 
        erro($BASE_URL, 3, 14786366, $pagina_atual, "oficio_nao_cadastrado", "Não foi possível cadastrar o ofício!");
}
 
else 
{
    $data = $oficioDAO->update($oficio_atualiza);
      if($data)
      {
          $alteracao = "Atualizou o Ofício ID: " . $data['id_obrigatorio'] ;
          $alteracao_detalahada = print_r($data, true);
          $logDAO->insertLog(3004, "oficio", $data['id_obrigatorio'], $alteracao, $alteracao_detalahada);
      }
      
      else 
          erro($BASE_URL, 3, 3486251, $pagina_atual, "oficio_nao_atualizado", "Não foi possível atualizar o ofício!");
}

$oficio = $oficioDAO->findByIdObrigatorio($oficio_atualiza->getIdObrigatorio());
$obrigatorio = $obrigatorioDAO->findById($oficio->getIdObrigatorio());
$oficio_data_cabecalho = trata_data($oficio->getDataCabecalho());
$oficio_data_apresentacao = trata_data($oficio->getDataApresentacao());
$oficio_data_incorporacao = trata_data($obrigatorio->getDataIncorporacao());
$data_comparecimento_sel_geral = trata_data($obrigatorio->getDataComparecimentoSelecaoGeral());
$data_selecao_complementar = trata_data($obrigatorio->getDataSelecaoComplementar());
$data_comparecimento_designacao = trata_data($obrigatorio->getDataComparecimentoDesignacao());

$data_hoje = date('d/m/Y');
$ano = date("Y");
if($data_comparecimento_sel_geral) $data_comparecimento_sel_geral = dataExtenso($data_comparecimento_sel_geral);
//$eb = strtoupper(md5($obrigatorio->getId()));

$eb =               "10/" . $obrigatorio->getId() ."-"
                    .strtoupper(substr(md5($obrigatorio->getId()) ,0,18))
                    ."10";
                    
                    
set_time_limit(300);

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
<br>";

$html = $html. "
        <strong>          
        <p align='right'>Porto Alegre-RS, " . $data_comparecimento_designacao . "</p>
        </strong>

<p> Ofício nº 10". $oficio->getIdObrigatorio() ." SSSMT/SSMR/Esc Pes </p>
<p> EB: ".$eb. "</p>


<table border='0' style='width:100%; font-size: 12px; font-family: Times New Roman; text-align: justify;'>
  <tr>
    <th style='width:30%'></th>
    <th align='left' style='width:10%; font-weight:normal'><b>Do:</b> Presidente da Comissão de Designação MFDV</th>
  </tr>
  <tr>
    <th style='width:30%'></th>
    <th align='left' style='width:50%; font-weight:normal'><b>Ao:</b> Sr Cmt /Ch / Dir do (a) ".$obrigatorio->getOm1Fase()->getAbreviatura()."</th>
  </tr>
  <tr>
    <th style='width:30%'></th>
    <th align='left' style='width:50; font-weight:normal'><b>Assunto:</b> Apresentação de MFDV para a Seleção Complementar para o Estágio de Adaptação e Serviço</th>
  </tr>
  <tr>
    <th style='width:30%'></th>
    <th align='left' style='width:50%; font-weight:normal'><b>Ref:</b> Decreto-Lei Nr 1.001, de 21 Out 69 - Código Penal Militar; <br>
        - R L MFDV; e <br>
        - Súmula 7 do Superior Tribunal Militar.<br>
        - Ordem de Serviço</th>
  </tr>
</table> 


        
<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
1. Apresento a esse Comando o Sr ".$obrigatorio->getNomeCompleto().", ".$obrigatorio->getFormacao().", que após Seleção Especial de Médicos, Farmacêuticos, Dentistas e Veterinários (MFDV), está convocado à Incorporação para a prestação do Serviço Militar Obrigatório sob a forma da 1ª Fase do Estágio de Adaptação e Serviço (1ª Fase/EAS)
</p>
<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	2. Comunico que as atividades de Seleção Complementar, Convocação à Incorporação, Incorporação e outras medidas administrativas estão reguladas em Ordem de Serviço específica.
</p>
<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	3. Informo, também, que o ".$obrigatorio->getFormacao()." convocado tem conhecimento de que, conforme previsto no Art 183 do Decreto-Lei Nr 1.001, de 21 de outubro de 1969 (Código Penal Militar), incorrerá no crime de “insubmissão”, caso deixe de apresentar-se à incorporação, dentro do prazo marcado, ou que, apresentando-se, ausente-se antes do ato oficial de incorporação. Adicionalmente, comunico que, conforme a Súmula 7 do Superior Tribunal Militar, incorrerá no mesmo crime citado (Insubmissão), caso não compareça à Seleção Complementar.
</p>


<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    4. A OM de 1ª Fase, após a realização da Seleção Complementar, deverá expedir Ofício convocando o ".$obrigatorio->getFormacao()." para a sua Incorporação, informando a data, horário e local de apresentação para a referida Incorporação.
    <br>
    <br>

OM DE APRESENTAÇÃO: ".$obrigatorio->getOm1Fase()->getAbreviatura()." <br>
ENDEREÇO: " . $obrigatorio->getOm1Fase()->getEndereco() . " <br>
CEP/CIDADE: ".$obrigatorio->getOm1Fase()->getCep(). " / ". $obrigatorio->getOm1Fase()->getCidade() . " <br>
TELEFONE: ".$obrigatorio->getOm1Fase()->getTelefone()." <br>
<br>
<p style='font-size: 12px;'>
</p>
<br>


<p class='center' style='font-size: 12px; text-align: center'><b> ".$oficio->getPresidenteComissao()." </b>
<br>
 Presidente da Comissão de Designação     
</p>
<br>
<p style='font-size: 12px;'>
Eu ".$obrigatorio->getNomeCompleto()." Recebi o ORIGINAL e declaro ter tomado ciência nesta data:______/______/______ <br><br>
CPF ________._________._________-_________
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Ass: _______________________________________________
</p>
" ;


$html = $html. "
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
<br>";

$html = $html. "
        <strong>          
        <p align='right'>Porto Alegre-RS, ". $data_comparecimento_designacao . "</p>
        </strong>

<p> Ofício de Convocação nº 10". $oficio->getIdObrigatorio() . " SSSMT/SSMR/Esc Pes  <br></p>
<p> EB: ".$eb. " <br></p>
    

<table border='0' style='width:100%; font-size: 12px; font-family: Times New Roman; text-align: justify;'>
  <tr>
    <th style='width:30%'></th>
    <th align='left' style='width:70%; font-weight:normal'><b>Do:</b> Presidente da Comissão de Designação MFDV</th>
  </tr>
  <tr>
    <th style='width:30%'></th>
    <th align='left' style='width:70%; font-weight:normal'><b>Ao:</b> Sr " . $obrigatorio->getNomeCompleto() . " </th>
  </tr>
  <tr>
    <th style='width:30%'></th>
    <th align='left' style='width:70%; font-weight:normal'><b>Assunto</b> Convocação à Incorporação para o Estágio de Adaptação e Serviço</th>
  </tr>
  <tr>
    <th style='width:30%'></th>
    <th align='left' style='width:70%; font-weight:normal'><b>Ref:</b> - Decreto-Lei Nr 1.001, de 21 Out 69 - Código Penal Militar; <br>
        - R L MFDV; e <br>
        - Súmula 7 do Superior Tribunal Militar.<br>
        </th>
  </tr>
</table> 

        
<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
1. Comunico que V Sa está convocado à Incorporação para a prestação do Serviço Militar Obrigatório sob a forma de Estágio de Adaptação e Serviço – EAS.
</p>
<p style='font-size: 12px; font-family: Times New Roman;'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	2. Dessa forma, informo que V Sa deverá comparecer para a Seleção Complementar, às ".$oficio->getHoraApresentacao()."h, do dia ". $data_selecao_complementar .", portando os seguintes documentos:
    <br><br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a. Original e cópia do diploma de graduação;<br></span>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b. Original e cópia do documento militar, RG, CPF e comprovante de residência;<br></span>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c. Declaração de tempo de serviço público anterior;<br></span>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;d. 6 (seis) fotos 3 X 4; e<br></span>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;e. Cópia do cabeçalho do extrato bancário de conta-corrente, para fins de recebimentos dos proventos.</span>

<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	3. Informo o seguinte local para a apresentação e realização da Seleção Complementar e Incorporação:
  <br>
  <br>
	
  OM DE APRESENTAÇÃO: ".$obrigatorio->getOm1Fase()->getAbreviatura()." <br>
  ENDEREÇO: " . $obrigatorio->getOm1Fase()->getEndereco() . " <br>
  CEP/CIDADE: ".$obrigatorio->getOm1Fase()->getCep(). " / ". $obrigatorio->getOm1Fase()->getCidade() . " <br>
  TELEFONE: ".$obrigatorio->getOm1Fase()->getTelefone()." <br>
</p>
<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	4. Informo, também, que incorrerá no crime de “insubmissão”, conforme previsto no Art 183 do Decreto-Lei Nr 1.001, de 21 de outubro de 1969 (Código Penal Militar), caso deixe de apresentar-se à incorporação, dentro do prazo marcado, ou que, apresentando-se, ausente-se antes do ato oficial de incorporação. Adicionalmente, comunico que, conforme a Súmula 7 do Superior Tribunal Militar, incorrerá no mesmo crime citado (Insubmissão), caso não compareça à Seleção Complementar.
</p>



<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    5. A sua Incorporação ocorrerá em ". $oficio_data_cabecalho . ".


<br><br>
<p class='center' style='font-size: 12px; text-align: center'><b> ".$oficio->getPresidenteComissao()." </b>
<br>
 Presidente da Comissão de Designação     
</p>

<br>

<p style='font-size: 12px;'>
Eu ".$obrigatorio->getNomeCompleto()." Recebi o ORIGINAL e declaro ter tomado ciência nesta data:______/______/______ <br><br>
CPF ________._________._________-_________
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Ass: _______________________________________________
</p>
" ;

$mpdf = new mPDF('C', 'A4'); 
$mpdf->WriteHTML($html);

$cpf = $obrigatorio->getCpf();

$alteracao_detalahada = "CPF do Obrigatório ". $obrigatorio->getCpf();
$insere_log = $logDAO->insertLog(4004, "PDF", $obrigatorio->getId(), "Gerou o Ofício do Obrigatório " . $obrigatorio->getCpf(), $alteracao_detalahada);

ob_get_clean();
$mpdf->Output();
//$mpdf->Output($arquivo, 'F');
 

?>