<?php

if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Obrigatorio.php';
include_once '../models/Om.php';
include_once '../dao/ObrigatorioDAO.php';
include_once '../dao/LogDAO.php';

if($_SESSION['perfil_smo'] != "admin")
{    
    erro($BASE_URL, 2, 2536466, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");
    exit();
}

$logDAO = new logDAO($conexao);
$obrigatorioDAO = new ObrigatorioDAO($conexao);

$id_obrigatorio_edita = (int)filtra_campo_post('id_obrigatorio');
$crip_url = filtra_campo_post('crip_url');
$nome_completo = filtra_campo_post('nome_completo');
$cpf = filtra_campo_post('cpf');
$telefone = filtra_campo_post('telefone');
$mail = filtra_campo_post('mail');
$estado_civil = filtra_campo_post('estado_civil');
$data_nascimento = filtra_campo_post('data_nascimento');
$data_expedicao = filtra_campo_post('data_expedicao');
$nome_pai = filtra_campo_post('nome_pai');
$nome_mae = filtra_campo_post('nome_mae');
$nacionalidade = filtra_campo_post('nacionalidade');
$naturalidade = filtra_campo_post('naturalidade');
$identidade = filtra_campo_post('identidade');
$dependentes = filtra_campo_post('dependentes');
$endereco = filtra_campo_post('endereco');
$prioridade_forca = filtra_campo_post('prioridade_forca');
$crip = filtra_campo_post('crip');
$voluntario = filtra_campo_post('voluntario');
$documento_militar = filtra_campo_post('documento_militar');
$numero_documento_militar = filtra_campo_post('numero_documento_militar');
$data_expedicao = filtra_campo_post('data_expedicao');
$forca = filtra_campo_post('forca');
$nome_instituicao_ensino = filtra_campo_post('nome_instituicao_ensino');
$ano_formacao = filtra_campo_post('ano_formacao');
$formacao = filtra_campo_post('formacao');
$cidade_instituicao_ensino = filtra_campo_post('cidade_instituicao_ensino');
$apto_exame_medico = filtra_campo_post('apto_exame_medico');
$jise = filtra_campo_post('jise');
$cid_jise = filtra_campo_post('cid_jise');
$data_exame_medico = filtra_campo_post('data_exame_medico');
$observacao_jise = filtra_campo_post('observacao_jise');
$apto_exame_medico_recurso = filtra_campo_post('apto_exame_medico_recurso');
$jisr = filtra_campo_post('jisr');
$cid_jisr = filtra_campo_post('cid_jisr');
$data_jisr = filtra_campo_post('data_jisr');
$obs_jisr = filtra_campo_post('obs_jisr');
$jise_a_1 = filtra_campo_post('jise_a_1');
$cid_jise_a_1 = filtra_campo_post('cid_jise_a_1');
$data_jise_a_1 = filtra_campo_post('data_jise_a_1');
$observacao_jise_a_1 = filtra_campo_post('observacao_jise_a_1');
$ano_conclusao_residencia = filtra_campo_post('ano_conclusao_residencia');
$data_comparecimento_selecao_geral = filtra_campo_post('data_comparecimento_selecao_geral');
$data_proxima_apresentacao = filtra_campo_post('data_proxima_apresentacao');
$situacao_militar = filtra_campo_post('situacao_militar');
$solicitou_adiamento = filtra_campo_post('solicitou_adiamento');
$inicio_adiamento = filtra_campo_post('data_inicio_adiamento');
$fim_adiamento = filtra_campo_post('data_fim_adiamento');
$especialidade_adiamento = filtra_campo_post('especialidade_adiamento');
$transferencia_fisemi = filtra_campo_post('transferencia_fisemi');
$rm_origem_fisemi = filtra_campo_post('rm_origem_fisemi');
$rm_destino_fisemi = filtra_campo_post('rm_destino_fisemi');
$numero_acao = filtra_campo_post('numero_acao');
$transitou_julgado = filtra_campo_post('transitou_julgado');
$data_liminar = filtra_campo_post('data_liminar');
$favoravel = filtra_campo_post('favoravel');
$convocado = filtra_campo_post('convocado');
$distribuicao = filtra_campo_post('distribuicao');
$data_selecao_complementar = filtra_campo_post('data_selecao_complementar');
$resultado_revisao_medica_complementar = filtra_campo_post('resultado_revisao_medica_complementar');
$resultado_isgr = filtra_campo_post('resultado_isgr');
$data_incorporacao = filtra_campo_post('data_incorporacao');
$om_2_fase = filtra_campo_post('om_2_fase');
$observacao = filtra_campo_post('observacao');
$especialidade_1 = filtra_campo_post('especialidade_1');
$especialidade_2 = filtra_campo_post('especialidade_2');
$especialidade_3 = filtra_campo_post('especialidade_3');
$ano_residencia_espe_1 = filtra_campo_post('ano_residencia_espe_1');
$ano_residencia_espe_2 = filtra_campo_post('ano_residencia_espe_2');
$ano_residencia_espe_3 = filtra_campo_post('ano_residencia_espe_3');
$aba = filtra_campo_post('aba');

// Tratamento de dadas
$data_nascimento = trata_data($data_nascimento);
$data_expedicao = trata_data($data_expedicao);
$data_exame_medico = trata_data($data_exame_medico);
$data_jisr = trata_data($data_jisr);
$data_comparecimento_selecao_geral = trata_data($data_comparecimento_selecao_geral);
//$data_comparecimento_designacao = trata_data($data_comparecimento_designacao);
$data_proxima_apresentacao = trata_data($data_proxima_apresentacao);
$inicio_adiamento = trata_data($inicio_adiamento);
$fim_adiamento = trata_data($fim_adiamento);
$data_liminar = trata_data($data_liminar);
$data_selecao_complementar = trata_data($data_selecao_complementar);
$data_incorporacao = trata_data($data_incorporacao);
$data_jise_a_1 = trata_data($data_jise_a_1);



if($crip != hash('sha256', $_SESSION['chave']. "obrigatorio"))
    erro($BASE_URL, 2, 3463463464, $pagina_atual, "criptografia_invalida", "Não foi possível editar o usuário!");

if(empty($nome_completo) && $aba == 'identificação')
    erro($BASE_URL, 1, 253253446, $pagina_atual, "empty(nome_completo)", "O Campo NOME COMPLETO é obrigatório!");

if(!empty($cpf))
{
    $cpf = $cpf = str_replace('.','',$cpf);
    $cpf = $cpf = str_replace('-','',$cpf);

    if(!valida_cpf($cpf))
        erro($BASE_URL, 1, 47345734578, $pagina_atual, "cpf_invalido", "O Campo CPF é inválido!");
}

$obrigatorio_editar = $obrigatorioDAO->findById($id_obrigatorio_edita);

try
{
    $obrigatorio_editar->setDataProximaApresentacao($data_proxima_apresentacao);
    $obrigatorio_editar->setNomeCompleto($nome_completo);
    $obrigatorio_editar->setTelefone($telefone);
    $obrigatorio_editar->setMail($mail);
    $obrigatorio_editar->setEstadoCivil($estado_civil);
    $obrigatorio_editar->setDataNascimento($data_nascimento);
    $obrigatorio_editar->setNomePai($nome_pai);
    $obrigatorio_editar->setNomeMae($nome_mae);
    $obrigatorio_editar->setNacionalidade($nacionalidade);
    $obrigatorio_editar->setNaturalidade($naturalidade);
    $obrigatorio_editar->setIdentidade($identidade);
    $obrigatorio_editar->setDependentes($dependentes);
    $obrigatorio_editar->setEndereco($endereco);
    $obrigatorio_editar->setPrioridadeForca($prioridade_forca);
    $obrigatorio_editar->setVoluntario($voluntario);
    $obrigatorio_editar->setDocumentoMilitar($documento_militar);
    $obrigatorio_editar->setNumeroDocumentoMilitar($numero_documento_militar);
    $obrigatorio_editar->setDataExpedicao($data_expedicao);
    $obrigatorio_editar->setForca($forca);
    $obrigatorio_editar->setNomeInstitutoEnsino($nome_instituicao_ensino);
    $obrigatorio_editar->setAnoFormacao($ano_formacao);
    $obrigatorio_editar->setFormacao($formacao);
    $obrigatorio_editar->setCidadeInstituicaoEnsino($cidade_instituicao_ensino);
    $obrigatorio_editar->setJise($jise);
    $obrigatorio_editar->setCidJise($cid_jise);
    $obrigatorio_editar->setObservacaoJise($observacao_jise);
    $obrigatorio_editar->setJisr($jisr);
    $obrigatorio_editar->setCidJisr($cid_jisr);
    $obrigatorio_editar->setDataJisr($data_jisr);
    $obrigatorio_editar->setObsJisr($obs_jisr);
    $obrigatorio_editar->setJisea1($jise_a_1);
    $obrigatorio_editar->setCidJisea1($cid_jise_a_1);
    $obrigatorio_editar->setDataJisea1($data_jise_a_1);
    $obrigatorio_editar->setObservacaoJisea1($observacao_jise_a_1);
    $obrigatorio_editar->setDataComparecimentoSelecaoGeral($data_comparecimento_selecao_geral);
    $obrigatorio_editar->setSituacaoMilitar($situacao_militar);
    $obrigatorio_editar->setEspecialidade($especialidade_1);
    $obrigatorio_editar->setEspecialidade2($especialidade_2);
    $obrigatorio_editar->setEspecialidade3($especialidade_3);
    $obrigatorio_editar->setAnoResEspe1($ano_residencia_espe_1);
    $obrigatorio_editar->setAnoResEspe2($ano_residencia_espe_2);
    $obrigatorio_editar->setAnoResEspe3($ano_residencia_espe_3);
    $obrigatorio_editar->setObservacao($observacao);

    
}
catch(Exception $e) 
{
    erro($BASE_URL, 3, 23675689, $pagina_atual, "catch", $e->getMessage());
}


$data = $obrigatorioDAO->update($obrigatorio_editar);

if($data)
{
    $alteracao = "Atualizou a aba $aba do obrigatório " . $obrigatorio_editar;
    $alteracao_detalahada = print_r($data, true);
    $logDAO->insertLog(3003, "obrigatorio", $id_obrigatorio_edita, $alteracao, $alteracao_detalahada);
}
else 
{   
    erro($BASE_URL, 3, 987654, $pagina_atual, "obrigatorio_nao_atualizado", "Não foi possível atualizar o obrigatório!");
}

$_SESSION['mensagem'] = "Obrigatório ATUALIZADO com sucesso";

header ("Location: ../obrigatorio.php?crip=$crip_url&id_obrigatorio=$id_obrigatorio_edita&aba=1");



?>