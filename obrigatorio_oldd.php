<?php
    include_once 'header.php';
    include_once 'dao/conecta_banco.php';
    include_once 'models/Obrigatorio.php';
    include_once 'models/Arquivo.php';
    include_once 'models/Oficio.php';
    include_once 'dao/ObrigatorioDAO.php';
    include_once 'dao/ArquivoDAO.php';
    include_once 'dao/AuxiliarDAO.php';
    
    if(!isset($_SESSION['id_usuario_smo'])){ erro($BASE_URL, 2, 26347634, $pagina_atual, "usuario_nao_logado", "Página não encontrada!"); exit();}
    if(!isset($_SESSION['mensagem'])) $_SESSION['mensagem'] = null;

    $crip_url = filtra_campo_get('crip');
    $id_obrigatorio = 0;
    
    if(isset($_GET['id_obrigatorio']))
        $id_obrigatorio = (int)filtra_campo_get('id_obrigatorio');

    if($id_obrigatorio == 0)
    { erro($BASE_URL, 2, 26346457, $pagina_atual, "id_obrigatorio==0", "Página não encontrada!"); exit();}

    if(hash('sha256', $id_obrigatorio. "criptografia") != $crip_url)
    { erro($BASE_URL, 2, 2363677, $pagina_atual, "crip_invalida", "Página não encontrada!"); exit();}
    
    $ObrigatorioDAO = new ObrigatorioDAO($conexao);
    $obrigatorio = $ObrigatorioDAO->findById($id_obrigatorio);
   
    $arquivosDAO = new ArquivoDAO($conexao);
    $arquivos = $arquivosDAO->findByIdObrigatorio($id_obrigatorio);

    $AuxiliarDAO = new AuxiliarDAO($conexao);
    $todas_oms = $AuxiliarDAO->findAllOM();
    $todas_espec = $AuxiliarDAO->findAllEspec();
    $todas_cid = $AuxiliarDAO->findAllCidades();
    $todas_gu = $AuxiliarDAO->findAllGuarnicao();
    $todas_cid_inst = $AuxiliarDAO->findAllCidInst();

    $ano_atual = date("Y");

?>

<main id="main">
    <section class="breadcrumbs" >
        <div class="container">
            <div class="section-title" data-aos="fade-up">
            
                <h1><b><?php echo $obrigatorio->getNomecompleto() ?></b></h1> 
                <br>
                
                <a href="mpdf/ficha_obrigatorio.php?crip=<?php echo $crip_url ?>&id_obrigatorio=<?php echo $id_obrigatorio?>">
                <label for="ficha">Ficha</label>
                <img src="imagens/pdf.png" height="50px"></a>

                <?php 
                    if($obrigatorio->getDataComparecimentoSelecaoGeral() != null || $obrigatorio->getJise() != null || $obrigatorio->getCidJise() != null) 
                    { 
                ?>
                        <a href="mpdf/relatorio_jise.php?crip=<?php echo $crip_url ?>&id_obrigatorio=<?php echo $id_obrigatorio?>">
                        <label for="relatorio">JISE</label> 
                        <img src="imagens/pdf.png" height="50px"></a>
                <?php 
                    } 
                        if($obrigatorio->getDataJisr() != null || $obrigatorio->getJisr() != null || $obrigatorio->getCidJisr() != null) 
                    {
                ?>
                        <a href="mpdf/relatorio_jisr.php?crip=<?php echo $crip_url ?>&id_obrigatorio=<?php echo $id_obrigatorio?>">
                        <label for="relatorio">JISR</label> 
                        <img src="imagens/pdf.png" height="50px"></a>
                <?php } ?>
                
            </div>
        </div>
    </section>
</main>



<center><font size="4" color="green" size="4px"><?php echo $_SESSION['mensagem']; $_SESSION['mensagem'] = null; ?></font></center>

<section id="contact" class="contact">
    <div class="container card">
        <div class="row  justify-content-center" >

        


        <!-- ABAS -->



        <ul class="nav nav-tabs" id="myTabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#aba1">Identificação</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#aba2">FISEMI</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#aba3">Distribuição</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#aba4">Ofício</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#aba5">Ficha</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#aba6">Arquivos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#aba7">Apagar</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="aba1">
                <h4>Conteúdo da Aba 1</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
            <div class="tab-pane fade" id="aba2">
                <h4>Conteúdo da Aba 2</h4>
                <p>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
            </div>
            <div class="tab-pane fade" id="aba3">
                <h4>Conteúdo da Aba 3</h4>
                <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            </div>
            <div class="tab-pane fade" id="aba4">
                <?php include_once 'obrigatorio_codigo_oficio.php' ?>
            </div>
            <div class="tab-pane fade" id="aba5">
                <?php include_once 'obrigatorio_codigo_ficha.php' ?>
            </div>
            <div class="tab-pane fade" id="aba6">
                <?php include_once 'obrigatorio_codigo_arquivos.php' ?>
            </div>
            <div class="tab-pane fade" id="aba7">
            <br><br>
            <center>  
                <a href ="controller/obrigatorio_apaga.php?crip=<?php echo $crip_url ?>&id_obrigatorio=<?php echo $id_obrigatorio ?>"><img data-toggle='tooltip' title='Apagar Obrigatório' src='imagens/apagar.png' width='30px'></a>
                <br>Apagar
            </center>
            </div>
            
        </div>
        






































        <!-- PRIMEIRA TABELA -->

            

                   

                    </div>
                </div>

            </div>
        </div>
    </div>


</section>

<!-- SEGUNDA TABELA -->

<section id="contact" class="contact" >
      <div class="container ">
        <div class="row  justify-content-center" >
            <form action="controller/obrigatorio_edita.php" method="post" role="form" class="card">
                <input hidden type="text" value="<?php echo $obrigatorio->getId()?>" name="id_obrigatorio" >
                <input hidden type="text" value="<?php echo $crip_url?>" name="crip_url" >
                <div class="row">
                <div class="col-md-12 form-group" id="identificacao">
                            <b>Data Próxima Apresentação</b>
                          <input type="text" class="form-control" value="<?php echo $obrigatorio->imprimeDataProximaApresentacao()?>" name="data_proxima_apresentacao" >
                        </div>
                </div>
                
                <center> <b> <font size="5" color="green" >IDENTIFICAÇÃO</font> </b></center>
                
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <b>Nome Completo</b>
                          <input type="text" class="form-control" value="<?php echo $obrigatorio->getNomeCompleto()?>" name="nome_completo" placeholder="Nome Completo">
                        </div>

                        <div class="col-md-4 form-group">
                            <b>CPF</b>
                            <input type="text" class="form-control" value="<?php echo $obrigatorio->getCPF() ?>" disabled onclick="selectText()" id="cpf" name="cpf" placeholder="CPF">
                        </div>

                        <div class="col-md-4 form-group">
                        <b>Estado Civil</b>
                            <select name="estado_civil" class="form-control">
                                <option value="">Estado Civil</option>
                                <option <?php if($obrigatorio->getEstadoCivil() == "SOLTEIRO") echo " selected" ?> value="SOLTEIRO">SOLTEIRO</option>
                                <option <?php if($obrigatorio->getEstadoCivil() == "CASADO") echo " selected" ?> value="CASADO">CASADO</option>
                                <option <?php if($obrigatorio->getEstadoCivil() == "UNIÃO ESTÁVEL") echo " selected" ?> value="UNIÃO ESTÁVEL">UNIÃO ESTÁVEL</option>
                                <option <?php if($obrigatorio->getEstadoCivil() == "DIVORCIADO") echo " selected" ?> value="DIVORCIADO">DIVORCIADO</option>
                                <option <?php if($obrigatorio->getEstadoCivil() == "VIÚVO") echo " selected" ?> value="VIÚVO">VIÚVO</option>
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                        <b>Data de Nascimento</b>
                            <input type="text" class="form-control" value="<?= $obrigatorio->imprimeDataNascimento() ?>" name="data_nascimento" placeholder="Data de Nascimento">
                        </div>

                        <div class="col-md-4 form-group">
                        <b>Identidade</b>
                            <input type="text" class="form-control" value="<?php echo $obrigatorio->getIdentidade() ?>" id="identidade" name="identidade" placeholder="Identidade">
                        </div>

                        <div class="col-md-4 form-group">
                        <b>E-Mail</b>
                            <input type="text" class="form-control" value="<?php echo $obrigatorio->getMail() ?>" id="mail" name="mail" placeholder="E-mail">
                        </div>

                        <div class="col-md-4 form-group">
                        <b>Telefone</b>
                            <input type="text" class="form-control" value="<?php echo $obrigatorio->getTelefone() ?>" id="telefone" name="telefone" placeholder="Telefone">
                        </div>

                        <div class="col-md-4 form-group">
                        <b>Nome do Pai</b>
                            <input type="text" class="form-control" value="<?php echo $obrigatorio->getNomePai() ?>" id="nome_pai" name="nome_pai" placeholder="Nome do Pai">
                        </div>
                        <div class="col-md-4 form-group">
                        <b>Nome da Mãe</b>
                            <input type="text" class="form-control" value="<?php echo $obrigatorio->getNomeMae() ?>" id="nome_mae" name="nome_mae" placeholder="Nome da Mãe">
                        </div>
                        
                        <div class="col-md-4 form-group">
                        <b>Voluntário?</b>
                            <select name="voluntario" class="form-control">
                                <option value="">Selecione a Opção</option>
                                <option <?php if($obrigatorio->getVoluntario() == "SIM") echo " selected " ?> value="SIM">SIM</option>
                                <option <?php if($obrigatorio->getVoluntario() == "NÃO") echo " selected " ?> value="NÃO">NÃO</option>
                              
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                        <b>Nacionalidade</b>

                            <select name="nacionalidade" class="form-control">
                                <option value="">Selecione a Opção</option>
                                <option <?php if($obrigatorio->getNacionalidade() === "BRASILEIRA") echo " selected " ?> value="BRASILEIRA">BRASILEIRA</option>
                                <option <?php if($obrigatorio->getNacionalidade() === "ESTRANGEIRA") echo " selected " ?> value="ESTRANGEIRA">ESTRANGEIRA</option>
                            </select>

                        </div>
                        <div class="col-md-4 form-group">
                        <b>Naturalidade</b>
                        <select name="naturalidade" class="form-control">
                        <option value="">Selecione a Opção</option>
                            <?php 
                            foreach ($todas_cid as $value) 
                            {
                                if ($value['nome'] == $obrigatorio->getNaturalidade()) 
                                {
                                    echo "<option value='" . $value['nome'] . "' selected>" . $value['nome'] . "</option>";
                                } 
                                else
                                {
                                    echo "<option value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
                                }
                            
                            }


                              
                        ?>
                        </select>
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Endereço</b>
                            <input type="text" class="form-control" value="<?php echo $obrigatorio->getEndereco() ?>" id="endereco" name="endereco" placeholder="Endereço">
                        </div>
						
                        <div class="col-md-4 form-group">
                        <b>Dependentes</b>
                            <select name="dependentes" class="form-control">
                                <option value="">Dependentes</option>
                                <?php 
                                     for ($i = 0; $i <= 10; $i++) 
                                    {
                                        if($obrigatorio->getDependentes() == $i)
                                            echo '<option selected value="' . $i . '">' . $i . '</option>';
                                        else
                                            echo '<option value="' . $i . '">' . $i . '</option>';
                                    }

                                    
                                ?>
                                                                
                            </select>
                        </div>

                        <div class="col-md-4 form-group" id="formacao">
                        <b>Prioridade das Forças</b>
                            <select name="prioridade_forca" class="form-control" value="<?php echo $obrigatorio->getPrioridadeForca() ?>">
                                <option value="">Selecione a Opção</option>
                                <option <?php if($obrigatorio->getPrioridadeForca() == "EMF") echo " selected "?> value="EMF">EMF</option>
                                <option <?php if($obrigatorio->getPrioridadeForca() == "EFM") echo " selected "?>value="EFM">EFM</option>
                                <option <?php if($obrigatorio->getPrioridadeForca() == "MFE") echo " selected "?>value="MFE">MFE</option>
                                <option <?php if($obrigatorio->getPrioridadeForca() == "MEF") echo " selected "?>value="MEF">MEF</option>
                                <option <?php if($obrigatorio->getPrioridadeForca() == "FEM") echo " selected "?>value="FEM">FEM</option>
                                <option <?php if($obrigatorio->getPrioridadeForca() == "FME") echo " selected "?>value="FME">FME</option>
                            </select>
                        </div>
                    </div>

                <center> <b><font size="5" color="green">FORMAÇÃO</font> </b></center>
                    <div class="row">      

                        <div class="col-md-4 form-group">
                        <b>IE Graduação</b>
                            <select name="nome_instituicao_ensino" class="form-control">
                            <option value="">Nome da Instituição de Ensino</option>
                                <?php 
                                   foreach ($todas_cid_inst as $value) 
                                   {
                                       if ($value['nome'] == $obrigatorio->getNomeInstitutoEnsino()) 
                                       {
                                           echo "<option value='" . $value['nome'] . "' selected>" . $value['nome'] . "</option>";
                                       } 
                                       else 
                                       {
                                       echo "<option value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
                                       }
                                   }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                        <b>Ano de Formação</b>
                            <select name="ano_formacao" class="form-control">
                            <option value="">Selecione a Opção </option>
                                <?php 
                                
                                    for ($i=$ano_atual; $i >= 2010; $i--) 
                                        { 
                                        if ($i == $obrigatorio->getAnoFormacao()) 
                                        {
                                            echo '<option value="' . $i . '" selected>' . $i . '</option>';
                                        } 
                                        else 
                                        {
                                        echo '<option value="' . $i . '" >' . $i . '</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                        <b></b>
                        </div>
                        
                        <div class="col-md-4 form-group">
                        <b> Especialidade 1 </b>
                            <select name="especialidade_1" class="form-control">
                                <option value="">Selecione a Especialidade</option>
                                <?php 
                                    foreach ($todas_espec as $value) 
                                    {
                                        if ($value['nome'] == $obrigatorio->getEspecialidade()) 
                                        {
                                            echo "<option value='" . $value['nome'] . "' selected>" . $value['nome'] . "</option>";
                                        } 
                                        else 
                                        {
                                        echo "<option value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                        <b> Especialidade 2 </b>
                            <select name="especialidade_2" class="form-control">
                                <option value="">Selecione a Especialidade</option>
                                <?php 
                                    foreach ($todas_espec as $value) 
                                    {
                                        if ($value['nome'] == $obrigatorio->getEspecialidade2()) {
                                            echo "<option value='" . $value['nome'] . "' selected>" . $value['nome'] . "</option>";
                                        } 
                                        else 
                                        {
                                        echo "<option value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                        <b> Especialidade 3 </b>
                            <select name="especialidade_3" class="form-control">
                                <option value="">Selecione a Especialidade</option>
                                <?php 
                                    foreach ($todas_espec as $value) 
                                    {
                                        if ($value['nome'] == $obrigatorio->getEspecialidade3()) 
                                        {
                                            echo "<option value='" . $value['nome'] . "' selected>" . $value['nome'] . "</option>";
                                        } 
                                        else 
                                        {
                                        echo "<option value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                        <b>Ano Especialização 1</b>
                            <select name="ano_residencia_espe_1" class="form-control">
                            <option value="">Ano Especialização 1</option>
                                <?php 
                                    
                                    for ($i=2013; $i <= $ano_atual; $i++) 
                                    { 
                                        if ($i == $obrigatorio->getAnoResEspe1()) 
                                        {
                                            echo '<option value="' . $i . '" selected>' . $i . '</option>';
                                        } 
                                        else 
                                        {
                                            echo '<option value="' . $i . '" >' . $i . '</option>';
                                        }
                                        
                                    }

                                ?>
                            </select>
                        </div>  
                        <div class="col-md-4 form-group">
                        <b>Ano Especialização 2</b>
                            <select name="ano_residencia_espe_2" class="form-control">
                            <option value="">Ano Especialização 2</option>
                                <?php 
                                    for ($i=$ano_atual; $i >= 2010; $i--) 
                                    { 
                                        if ($i == $obrigatorio->getAnoResEspe2()) {
                                            echo '<option value="' . $i . '" selected>' . $i . '</option>';
                                        } 
                                        else
                                        {
                                            echo '<option value="' . $i . '" >' . $i . '</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 form-group" id="adiamento">
                        <b>Ano Especialização 3</b>
                            <select name="ano_residencia_espe_3" class="form-control">
                            <option value="">Ano Especialização 3</option>
                                <?php 
                                    for ($i = $ano_atual; $i >= 2010; $i--) 
                                    { 
                                        if ($i == $obrigatorio->getAnoResEspe3()) 
                                            echo '<option value="' . $i . '" selected>' . $i . '</option>';
                                        else 
                                            echo '<option value="' . $i . '" >' . $i . '</option>';
                                     }
                                ?>
                            </select>
                        </div>
                    </div>
                    <center> <b><font size="5" color="green">ADIAMENTO - SMO</font> </b></center>
                    <div class="row">
                    <div class="col-md-4 form-group">
                        <b>Solicitou Adiamento?</b>
                            <select name="solicitou_adiamento" class="form-control">
                                <option value="">Selecione a Opção</option>
                                <option <?php if($obrigatorio->getSolicitouAdiamento() == "SIM") echo " selected "?> value="SIM">SIM</option>
                                <option <?php if($obrigatorio->getSolicitouAdiamento() == "NÃO") echo " selected "?> value="NÃO">NÃO</option>
                            </select>
                        </div>
						
						<div class="col-md-4 form-group">
                        <b>Início Adiamento</b>
                          <input type="text" class="form-control"  value="<?php echo $obrigatorio->imprimeInicioAdiamento()?>" name="data_inicio_adiamento" placeholder="Início do Adiamento">
                        </div>
						
						<div class="col-md-4 form-group">
                        <b>Fim Adiamento</b>
                          <input type="text" class="form-control"  value="<?php echo $obrigatorio->imprimeFimAdiamento()?>" name="data_fim_adiamento" placeholder="Fim do Adiamento">
                        </div>
						
						<div class="col-md-4 form-group" id="situacao_militar">
                        <b>Especialidade Adiamento</b>
                            <select id="opcao" name="especialidade_adiamento" class="form-control" value="<?php echo $obrigatorio->getEspecialidadeAdiamento() ?>">
                                <?php 
                                    foreach ($todas_espec as $value) {
                                       
                                        if ($value['nome'] == $obrigatorio->getEspecialidade()) {
                                            echo "<option value='" . $value['nome'] . "' selected>" . $value['nome'] . "</option>";
                                        } echo "<option value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
                                    }
                                ?>
                                <option value="OUTRA">OUTRA</option> 
                            </select>

                        </div>

                    <div id="outra" style="display: none;" class="col-md-4 form-group">
                        <b>Outra Especialidade Adiamento</b>
                          <input type="text" class="form-control" value="<?php echo $obrigatorio->getEspecialidadeAdiamento()?>" name="outra" placeholder="Outra Especialidade">
                    </div>

                        <script>
                                    $(document).ready(function() {
                                        $('#opcao').change(function() {
                                            if ($(this).val() === 'OUTRA') {
                                                $('#outra').show();
                                            } else {
                                                $('#outra').hide();
                                            }
                                        });
                                    });
                        </script>

                    </div>      

                <center> <b><font size="5" color="green" >SITUAÇÃO MILITAR</font> </b></center>
                    <div class="row">  
                        <div class="col-md-4 form-group">
                        <b>Situação Militar</b>


                        <select name="situacao_militar" class="form-control">
                                <option value="">Selecione a Opção</option>
                                <option <?php if($obrigatorio->getSituacaoMilitar() == "Em Débito - REFRATÁRIO") echo " selected "?> value="Em Débito - REFRATÁRIO">Em Débito - REFRATÁRIO</option>
                                <option <?php if($obrigatorio->getSituacaoMilitar() == "Em Débito - INSUBMISSO") echo " selected "?> value="Em Débito - INSUBMISSO">Em Débito - INSUBMISSO</option>
                                <option <?php if($obrigatorio->getSituacaoMilitar() == "Em Dia - JUDICIAL") echo " selected "?> value="Em Dia - JUDICIAL">Em Dia - JUDICIAL</option>
                                <option <?php if($obrigatorio->getSituacaoMilitar() == "Em Dia - ALISTADO MFDV (FISEMI)") echo " selected "?> value="Em Dia - ALISTADO MFDV (FISEMI)">Em Dia - ALISTADO MFDV (FISEMI)</option>
                                <option <?php if($obrigatorio->getSituacaoMilitar() == "Em Dia - ADIADO CURSANDO RESIDÊNCIA") echo " selected "?> value="Em Dia - ADIADO CURSANDO RESIDÊNCIA">Em Dia - ADIADO CURSANDO RESIDÊNCIA</option>
                                <option <?php if($obrigatorio->getSituacaoMilitar() == "Em Dia - B1- INSP SAU -Retornar próxima Seleção") echo " selected "?> value="Em Dia - B1- INSP SAU -Retornar próxima Seleção">Em Dia - B1- INSP SAU -Retornar próxima Seleção</option>
                                <option <?php if($obrigatorio->getSituacaoMilitar() == "Quite SMO - EXCESSO - CONTIGENTE") echo " selected "?> value="Quite SMO - EXCESSO - CONTIGENTE">Quite SMO - EXCESSO - CONTIGENTE</option>
                                <option <?php if($obrigatorio->getSituacaoMilitar() == "Quite SMO - EXCESSO - INCAPAZ SAÚDE") echo " selected "?> value="Quite SMO - EXCESSO - INCAPAZ SAÚDE">Quite SMO - EXCESSO - INCAPAZ SAÚDE</option>
                                <option <?php if($obrigatorio->getSituacaoMilitar() == "Quite SMO - DESOBRIGADO - MAIOR 38 ANOS") echo " selected "?> value="Quite SMO - DESOBRIGADO - MAIOR 38 ANOS">Quite SMO - DESOBRIGADO - MAIOR 38 ANOS</option>
                                <option <?php if($obrigatorio->getSituacaoMilitar() == "Quite SMO  - DESOBRIGADO - JÁ RESERVISTA") echo " selected "?> value="Quite SMO  - DESOBRIGADO - JÁ RESERVISTA">Quite SMO  - DESOBRIGADO - JÁ RESERVISTA</option>
                                <option <?php if($obrigatorio->getSituacaoMilitar() == "Quite SMO  - DESOBRIGADO - NATURALIZADO") echo " selected "?> value="Quite SMO  - DESOBRIGADO - NATURALIZADO">Quite SMO  - DESOBRIGADO - NATURALIZADO</option>
                                <option <?php if($obrigatorio->getSituacaoMilitar() == "Quite SMO  - CONVOCADO") echo " selected "?> value="Quite SMO  - CONVOCADO">Quite SMO  - CONVOCADO</option>
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                        <b>Documento Militar</b>
                            <select name="documento_militar" class="form-control" value="<?php echo $obrigatorio->getDocumentoMilitar() ?>">
                                <option <?php if($obrigatorio->getDocumentoMilitar() == "Documento Militar") echo " selected "?> value="">Documento Militar</option>
                                <option <?php if($obrigatorio->getDocumentoMilitar() == "Certificado de Isenção (CI)") echo " selected "?>value="Certificado de Isenção (CI)">Certificado de Isenção (CI)</option>
                                <option <?php if($obrigatorio->getDocumentoMilitar() == "Certificado de Dispensa de Incorporação (CDI)") echo " selected "?>value="Certificado de Dispensa de Incorporação (CDI)">Certificado de Dispensa de Incorporação (CDI)</option>
                                <option <?php if($obrigatorio->getDocumentoMilitar() == "Certidão de Situação Militar (CSM)") echo " selected "?>value="Certidão de Situação Militar (CSM)">Certidão de Situação Militar (CSM)</option>
                                <option <?php if($obrigatorio->getDocumentoMilitar() == "Certificado de Reservista Militar 1ª Categoria (CRM)") echo " selected "?>value="Certificado de Reservista Militar 1ª Categoria (CRM)">Certificado de Reservista Militar 1ª Categoria (CRM)</option>
                                <option <?php if($obrigatorio->getDocumentoMilitar() == "Certificado de Reservista Militar 2ª Categoria (CRM)") echo " selected "?>value="Certificado de Reservista Militar 2ª Categoria (CRM)">Certificado de Reservista Militar 2ª Categoria (CRM)</option>
                                
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                        <b>Nº Doc Militar</b>
                            <input type="text" class="form-control" value="<?php echo $obrigatorio->getNumeroDocumentoMilitar() ?>" name="numero_documento_militar" placeholder="Número do Documento Militar">
                        </div>
						
                        <div class="col-md-4 form-group">
                        <b>Data de Expedição</b>
                            <input type="text" class="form-control" value="<?php echo $obrigatorio->imprimeDataExpedicao() ?>"  name="data_expedicao" placeholder="Data de Expedição">
                        </div>
                        <div class="col-md-4 form-group" id="selecao_geral">
                        <b>Força</b>
                            <select name="forca" class="form-control">
                                <option value="">Força</option>
                                <option <?php if($obrigatorio->getForca() == "EXÉRCITO") echo " selected "?> value="EXÉRCITO">EXÉRCITO</option>
                                <option <?php if($obrigatorio->getForca() == "MARINHA") echo " selected "?> value="MARINHA">MARINHA</option>
                                <option <?php if($obrigatorio->getForca() == "AERONÁUTICA") echo " selected "?> value="AERONÁUTICA">AERONÁUTICA</option>
                            </select>
                        </div>

                    </div>

                <center> <b><font size="5" color="green">SELEÇÃO GERAL</font></b></center>
                
                    <div class="row">  

                    <div class="col-md-4 form-group">
                            <b>Data Comparecimento Seleção Geral</b>
                          <input type="text" class="form-control" value="<?php echo $obrigatorio->imprimeDataComparecimentoSelecaoGeral()?>" name="data_comparecimento_selecao_geral" placeholder="Data do Comparecimento da Seleção Geral">
                        </div>
                        

                        <div class="col-md-4 form-group">
                            <b>JISE</b>
                            <select name="jise" class="form-control">
                                <option value="">Selecione a Opção</option>
                                <option <?php if($obrigatorio->getJise() == "A") echo " selected "?> value="A">A</option>
                                <option <?php if($obrigatorio->getJise() == "B1") echo " selected "?> value="B1">B1</option>
                                <option <?php if($obrigatorio->getJise() == "B2") echo " selected "?> value="B2">B2</option>
                                <option <?php if($obrigatorio->getJise() == "C") echo " selected "?> value="C">C</option>
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                        <b>CID JISE</b>
                            <input type="text" class="form-control" value="<?php echo $obrigatorio->getCidJise() ?>" name="cid_jise" placeholder="CID">
                        </div>
                        
                        <div class="col-md-12 form-group">
                            <b>Observação JISE</b>
                            <textarea placeholder="Observação JISE" name="observacao_jise" class="form-control"><?php echo $obrigatorio->getObservacaoJise() ?></textarea>
                        </div>
                        
                        <div class="col-md-4 form-group">
                        <b>JISR</b>
                        <select name="jisr" class="form-control">
                                <option value="">Selecione a Opção</option>
                                <option <?php if($obrigatorio->getJisr() == "A") echo " selected "?> value="A">A</option>
                                <option <?php if($obrigatorio->getJisr() == "B1") echo " selected "?> value="B1">B1</option>
                                <option <?php if($obrigatorio->getJisr() == "B2") echo " selected "?> value="B2">B2</option>
                                <option <?php if($obrigatorio->getJisr() == "C") echo " selected "?> value="C">C</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                        <b>CID JISR</b>
                            <input type="text" class="form-control" value="<?php echo $obrigatorio->getCidJisr() ?>" name="cid_jisr" placeholder="CID - JISR">
                        </div>
                        <div class="col-md-4 form-group">
                        <b>Data JISR</b>
                            <input type="text" class="form-control" value="<?php echo $obrigatorio->imprimeDataJisr() ?>" name="data_jisr" placeholder="Data do JISR">
                        </div>
                        <div class="col-md-12 form-group" id="fisemi">
                        <b>Observação JISR</b>
                            <textarea placeholder="Observação JISR" name="obs_jisr" class="form-control"><?php echo $obrigatorio->getObsJisr() ?></textarea>
                        </div>

                    </div>


                <center> <b><font size="5" color="green">FISEMI</font></b></center>
                
                    <div class="row">   
                    <div class="col-md-4 form-group">
                    <b>Tranferência de FISEMI?</b>
                        <select name="transferencia_fisemi" class="form-control">
                                <option value="">Selecione a Opção</option>
                                <option <?php if($obrigatorio->getTransferenciaFisemi() == "SIM") echo " selected "?> value="SIM">SIM</option>
                                <option <?php if($obrigatorio->getTransferenciaFisemi() == "NÃO") echo " selected "?> value="NÃO">NÃO</option>
                            </select>
                        </div>
						
						<div class="col-md-4 form-group">
                        <b>Região Origem</b>
                            <select name="rm_origem_fisemi" class="form-control">
                                <option value="">Selecione a Região</option>
                                <option <?php if($obrigatorio->getRmOrigemFisemi() == "1ª RM") echo " selected "?> value="1ª RM">1ª RM</option>
                                <option <?php if($obrigatorio->getRmOrigemFisemi() == "2ª RM") echo " selected "?> value="2ª RM">2ª RM</option>
                                <option <?php if($obrigatorio->getRmOrigemFisemi() == "3ª RM") echo " selected "?> value="3ª RM">3ª RM</option>
                                <option <?php if($obrigatorio->getRmOrigemFisemi() == "4ª RM") echo " selected "?> value="4ª RM">4ª RM</option>
                                <option <?php if($obrigatorio->getRmOrigemFisemi() == "5ª RM") echo " selected "?> value="5ª RM">5ª RM</option>
                                <option <?php if($obrigatorio->getRmOrigemFisemi() == "6ª RM") echo " selected "?> value="6ª RM">6ª RM</option>
                                <option <?php if($obrigatorio->getRmOrigemFisemi() == "7ª RM") echo " selected "?> value="7ª RM">7ª RM</option>
                                <option <?php if($obrigatorio->getRmOrigemFisemi() == "8ª RM") echo " selected "?> value="8ª RM">8ª RM</option>
                                <option <?php if($obrigatorio->getRmOrigemFisemi() == "9ª RM") echo " selected "?> value="9ª RM">9ª RM</option>
                                <option <?php if($obrigatorio->getRmOrigemFisemi() == "10ª RM") echo " selected "?> value="10ª RM">10ª RM</option>
                                <option <?php if($obrigatorio->getRmOrigemFisemi() == "11ª RM") echo " selected "?> value="11ª RM">11ª RM</option>
                                <option <?php if($obrigatorio->getRmOrigemFisemi() == "12ª RM") echo " selected "?> value="12ª RM">12ª RM</option>
                            </select>

						</div>
						
						<div class="col-md-4 form-group" id="justica">
                        <b>Região Destino</b>
                        <select name="rm_destino_fisemi" class="form-control">
                                <option value="">Selecione a Região</option>
                                <option <?php if($obrigatorio->getRmDestinoFisemi() == "1ª RM") echo " selected "?> value="1ª RM">1ª RM</option>
                                <option <?php if($obrigatorio->getRmDestinoFisemi() == "2ª RM") echo " selected "?> value="2ª RM">2ª RM</option>
                                <option <?php if($obrigatorio->getRmDestinoFisemi() == "3ª RM") echo " selected "?> value="3ª RM">3ª RM</option>
                                <option <?php if($obrigatorio->getRmDestinoFisemi() == "4ª RM") echo " selected "?> value="4ª RM">4ª RM</option>
                                <option <?php if($obrigatorio->getRmDestinoFisemi() == "5ª RM") echo " selected "?> value="5ª RM">5ª RM</option>
                                <option <?php if($obrigatorio->getRmDestinoFisemi() == "6ª RM") echo " selected "?> value="6ª RM">6ª RM</option>
                                <option <?php if($obrigatorio->getRmDestinoFisemi() == "7ª RM") echo " selected "?> value="7ª RM">7ª RM</option>
                                <option <?php if($obrigatorio->getRmDestinoFisemi() == "8ª RM") echo " selected "?> value=">8ª RM">8ª RM</option>
                                <option <?php if($obrigatorio->getRmDestinoFisemi() == "9ª RM") echo " selected "?> value="9ª RM">9ª RM</option>
                                <option <?php if($obrigatorio->getRmDestinoFisemi() == "10ª RM") echo " selected "?> value="10ª RM">10ª RM</option>
                                <option <?php if($obrigatorio->getRmDestinoFisemi() == "11ª RM") echo " selected "?> value="11ª RM">11ª RM</option>
                                <option <?php if($obrigatorio->getRmDestinoFisemi() == "12ª RM") echo " selected "?> value="12ª RM">12ª RM</option>
                            </select>
						</div>
                    </div>

                <center> <b><font size="5" color="green">JUSTIÇA</font></b></center>
                
                    <div class="row">   
                            
						<div class="col-md-4 form-group">
                        <b>Número da Ação</b>
						  <input type="text" class="form-control" value="<?php echo $obrigatorio->getNumeroAcao()?>" name="numero_acao" placeholder="Número da Ação">
						</div>

                        <div class="col-md-4 form-group">
                        <b>Transitou Julgado?</b>
                            <select name="transitou_julgado" class="form-control" value="<?php echo $obrigatorio->getTransitouJulgado() ?>">
                                <option value="">Transitou Julgado?</option>
                                <option <?php if($obrigatorio->getTransitouJulgado() == "SIM") echo " selected "?> value="SIM">SIM</option>
                                <option <?php if($obrigatorio->getTransitouJulgado() == "NÃO") echo " selected "?> value="NÃO">NÃO</option>
                              
                            </select>
                        </div>

						<div class="col-md-4 form-group">
                        <b>Data Liminar</b>
						  <input type="text" class="form-control" value="<?php echo $obrigatorio->imprimeDataLiminar()?>" name="data_liminar" placeholder="Data da Liminar">
						</div>

                        <div class="col-md-4 form-group">
                        <b>Favoravel</b>
                            <select name="favoravel" class="form-control" value="<?php echo $obrigatorio->getFavoravel() ?>">
                                <option value="">Selecione a Opção</option>
                                <option <?php if($obrigatorio->getFavoravel() == "SIM") echo " selected "?> value="SIM">SIM</option>
                                <option <?php if($obrigatorio->getFavoravel() == "NÃO") echo " selected "?> value="NÃO">NÃO</option>
                              
                            </select>
                        </div>
                        
                        <div class="col-md-4 form-group" id="distribuicao">
                        <b>Convocado</b>
                            <select name="convocado" class="form-control" value="<?php echo $obrigatorio->getConvocado() ?>">
                            <option value="">Selecione a Opção</option>
                                <option <?php if($obrigatorio->getConvocado() == "SIM") echo " selected "?> value="SIM">SIM</option>
                                <option <?php if($obrigatorio->getConvocado() == "NÃO") echo " selected "?> value="NÃO">NÃO</option>
                            </select>
                        </div>

                    </div>


               

                <center> <b><font size="5" color="green">DISTRIBUIÇÃO</font></b></center>
                
                    <div class="row">  

                    <div class="col-md-4 form-group">
                        <b>Data Comparec Designação</b>
                          <input type="text" class="form-control" value="<?php echo $obrigatorio->imprimeDataComparecimentoDesignacao()?>" name="data_comparecimento_designacao" placeholder="Data do Comparecimento da Designação">
                        </div>

                    <div class="col-md-4 form-group">

                    
                        <b>Distribuição</b>

                        <select name="distribuicao" class="form-control">
                            <option value="">Selecione a Opção</option>
                            <option <?php if($obrigatorio->getDistribuicao() == "DESIGNADO - 1ª Distribuição") echo " selected "?> value="DESIGNADO - 1ª Distribuição">DESIGNADO - 1ª Distribuição</option>
                            <option <?php if($obrigatorio->getDistribuicao() == "DESIGNADO - 2ª Distribuição") echo " selected "?> value="DESIGNADO - 2ª Distribuição">DESIGNADO - 2ª Distribuição</option>
                            <option <?php if($obrigatorio->getDistribuicao() == "MAJORADO - 1ª Distribuição") echo " selected "?> value="MAJORADO - 1ª Distribuição">MAJORADO - 1ª Distribuição</option>
                            <option <?php if($obrigatorio->getDistribuicao() == "MAJORADO - 2ª Distribuição") echo " selected "?> value="MAJORADO - 2ª Distribuição">MAJORADO - 2ª Distribuição</option>
                            <option <?php if($obrigatorio->getDistribuicao() == "EXCESSO CONTIGENTE") echo " selected "?> value="EXCESSO CONTIGENTE">EXCESSO CONTIGENTE</option>
                            <option <?php if($obrigatorio->getDistribuicao() == "MARINHA") echo " selected "?> value="MARINHA">MARINHA</option>
                            <option <?php if($obrigatorio->getDistribuicao() == "FORÇA AÉREA") echo " selected "?> value="FORÇA AÉREA">FORÇA AÉREA</option>

                            </select>
						</div>

						<div class="col-md-4 form-group">
                        <b>Gu 1ª Fase</b>
                        <select name="gu_1_fase" class="form-control">
                            <option value="">Selecione a Opção</option>
                            <?php 
                                    foreach ($todas_gu as $value) 
                                    {
                                        if ($value['nome'] == $obrigatorio->getGu1Fase()) 
                                            echo "<option value='" . $value['nome'] . "' selected>" . $value['nome'] . "</option>";
                                        else 
                                            echo "<option value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
                                    }
                                ?>
                        </select>

						</div>
                        
                        
						<div class="col-md-4 form-group">
                        <b>Data Seleção Complementar</b>
						  <input type="text" class="form-control" value="<?php echo $obrigatorio->imprimeDataSelecaoComplementar()?>" name="data_selecao_complementar" placeholder="Data Seleção Complementar">
						</div>

						<div class="col-md-4 form-group">
                        <b>Resultado Revisão Médica </b>
                        <select name="resultado_revisao_medica_complementar" class="form-control">
                          <option value="">Selecione a Opção</option>
                            <option <?php if($obrigatorio->getResultadoRevisaoMedicaComplementar() == "APTO") echo " selected "?> value="APTO">APTO</option>
                            <option <?php if($obrigatorio->getResultadoRevisaoMedicaComplementar() == "INAPTO") echo " selected "?> value="INAPTO">INAPTO</option>
                            <option <?php if($obrigatorio->getResultadoRevisaoMedicaComplementar() == "NÃO COMPARECEU") echo " selected "?> value="NÃO COMPARECEU">NÃO COMPARECEU</option>
                        </select>
                        </div>

						<div class="col-md-4 form-group">
                        <b>ISGR - MPGu</b>
                          <select name="resultado_isgr" class="form-control">
                                <option value="">Selecione a Opção</option>
                                <option <?php if($obrigatorio->getResultadoIsgr() == "NÃO é o caso") echo " selected "?> value="NÃO é o caso">NÃO é o caso</option>
                                <option <?php if($obrigatorio->getResultadoIsgr() == "A") echo " selected "?> value="A">A</option>
                                <option <?php if($obrigatorio->getResultadoIsgr() == "B1") echo " selected "?> value="B1">B1</option>
                                <option <?php if($obrigatorio->getResultadoIsgr() == "B2") echo " selected "?> value="B2">B2</option>
                                <option <?php if($obrigatorio->getResultadoIsgr() == "C") echo " selected "?> value="C">C</option>
                            </select>
						</div>

						<div class="col-md-4 form-group">
                        <b>Data Incorporação</b>
						  <input type="text" class="form-control" value="<?php echo $obrigatorio->imprimeDataIncorporacao()?>" name="data_incorporacao" placeholder="Data de Incorporação">
						</div>

                        <div class="col-md-4 form-group" id="observacoes">
                        <b>OM 2ª Fase</b>
                            <select name="om_2_fase" class="form-control">
                                <option value="">OM 2ª Fase</option>
                                <?php 
                                    foreach ($todas_oms as $value) 
                                    {
                                        if ($value['nome'] == $obrigatorio->getOm2Fase()) 
                                            echo "<option value='" . $value['nome'] . "' selected>" . $value['abreviatura'] . "</option>";
                                        else 
                                            echo "<option value='" . $value['nome'] . "' >" . $value['abreviatura'] . "</option>";
                                    }
                                ?>
                            </select>
						</div>

                    </div>

                    <center> <b><font size="5" color="green">OBSERVAÇÕES</font></b></center>
                
                <div class="row">   
					<div class="col-md-12 form-group">
                    <textarea placeholder="Observação" name="observacao" rows="5" class="form-control"><?php echo $obrigatorio->getObservacao() ?></textarea>
					</div>	  
                </div>

                <div class="text-center">
                    <br>
                    <button type="submit" style="width: 100%;" class="btn btn-primary btn-block">ATUALIZAR</button>
                </div>
                <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave']."obrigatorio"); ?>">
            </form>

                <br>
                <br>    
                                    
             <!-- TABELA OFICIO MÉDICO -->

        
        </div>
      </div>
    </section>

   

    




  
<?php 
  include_once 'footer.php';
?>