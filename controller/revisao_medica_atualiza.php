<?php
  if(!isset($_SESSION)) session_start();

  include_once '../funcoes.php';
  include_once '../dao/conecta_banco.php';
  include_once '../models/Obrigatorio.php';
  include_once '../models/Om.php';
  include_once '../dao/ObrigatorioDAO.php';
  include_once '../dao/LogDAO.php';
  
  if($_SESSION['perfil_smo'] != "admin" && $_SESSION['perfil_smo'] != "operador")
  {
      erro($BASE_URL, 2, 23575883, $pagina_atual, "obrigatorio!admin", "Não foi possível acessar a página!");
      exit();
  }
  
  $obrigatorioDAO = new obrigatorioDAO($conexao);
  $logDAO = new LogDAO($conexao);
  
  $crip = filtra_campo_post('crip');
  $resultado_revisao_medica_complementar = filtra_campo_post('resultado_revisao_medica_complementar');
  $resultado_isgr = filtra_campo_post('resultado_isgr');
  $cid_revisao_medica = filtra_campo_post('cid_revisao_medica');

 // if($data_apresentacao_atualiza != null && valida_data($data_apresentacao_atualiza)) $data_apresentacao_atualiza = trata_data($data_apresentacao_atualiza);

  $idsSelecionados = null;
  if(isset($_POST['ids'])) $idsSelecionados = $_POST['ids'];
  

  if($crip != hash('sha256', $_SESSION['chave']. "atualiza")) 
        erro($BASE_URL, 2, 2357645, $pagina_atual, "criptografia_invalida", "Não foi possível fazer a atualização!");

    
  if($idsSelecionados)
  { 
    $om_1_fase = new OM();
    $om_1_fase->setId($id_om_1_fase);
    foreach($idsSelecionados as $id_selecionado)
    {
        $id_selecionado = (int)$id_selecionado;
        $obrigatorio_atualiza = $obrigatorioDAO->findById((int)$id_selecionado);
        $obrigatorio_atualiza->setResultadoRevisaoMedicaComplementar($resultado_revisao_medica_complementar);
        $obrigatorio_atualiza->setResultadoIsgr($resultado_isgr);
        $obrigatorio_atualiza->setCid_revisao_medica($cid_revisao_medica);

        $data = $obrigatorioDAO->update($obrigatorio_atualiza);

        if($data)
        {
            $alteracao = "Atualizou o obrigatório na Revisão Médica " . $obrigatorio_atualiza;
            $alteracao_detalahada = print_r($data, true);
            $logDAO->insertLog(3006, "obrigatorio", $id_selecionado, $alteracao, $alteracao_detalahada);
            $_SESSION['mensagem'] = count($idsSelecionados) . " Obrigatório(s) atualizado(s)";
            header ("Location: ../revisao_medica.php");
        }
        else 
            erro($BASE_URL, 3, 264757, $pagina_atual, "obrigatorio_nao_atualizado", "Não foi possível atualizar o obrigatório!");
    }
  }
  else
      erro($BASE_URL, 1, 254576475, $pagina_atual, "nenhum_obr_selecionado", "Você deve selecionar pelo menos um obrigatório!");

exit();

  
  $obrigatorio_apaga = $obrigatorioDAO->findById($id_obrigatorio);
  
  if(!$obrigatorio_apaga)
  {
      erro($BASE_URL, 3, 568468, $pagina_atual, "obrigatorio_nao_encontrado", "obrigatório não pode ser apagado!");
      exit();
  }
  
  if($obrigatorio_apaga->getApagado())
  {
      erro($BASE_URL, 3, 346756756, $pagina_atual, "obrigatorio_ja_apagado", "obrigatório não pode ser apagado!");
      exit();
  }
  
  $retorno = $obrigatorioDAO->deleteId($obrigatorio_apaga->getId());
  
  if($retorno)
  {
      $alteracao = "Apagou o obrigatório ".$obrigatorio_apaga;
      $alteracao_detalahada = $obrigatorio_apaga;
      $logDAO->insertLog(2003, "obrigatorio", $obrigatorio_apaga->getId(), $alteracao, $alteracao_detalahada);
      $_SESSION['mensagem'] = "Obrigatório APAGADO com Sucesso";
      header ("Location: ../revisao_medica.php");
  }
  else 
  {
     erro($BASE_URL, 3, 2353656, $pagina_atual, "obrigatorio_nao_apagado", "obrigatório não pode ser apagado!");
     exit();
  }
  
  ?>