<?php
    include_once 'header.php';
    include_once 'dao/conecta_banco.php';
    include_once 'models/Obrigatorio.php';
    include_once 'models/Arquivo.php';
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

<center>  
<a href ="controller/obrigatorio_apaga.php?crip=<?php echo $crip_url ?>&id_obrigatorio=<?php echo $id_obrigatorio ?>"><img data-toggle='tooltip' title='Apagar Obrigatório' src='imagens/apagar.png' width='30px'></a>
<br>Apagar
</center>

<center><font size="4" color="green" size="4px"><?php echo $_SESSION['mensagem']; $_SESSION['mensagem'] = null; ?></font></center>



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
                            <input type="text" class="form-control" value="<?php echo $obrigatorio->getCPF() ?>" disabled id="cpf" name="cpf" placeholder="CPF">
                        </div>

                        <div class="col-md-4 form-group">
                        <b>Estado Civil</b>
                            <select name="estado_civil" class="form-control">
                                <option value="">Estado Civil</option>
                                <option <?php if($obrigatorio->getEstadoCivil() == "Solteiro") echo " selected" ?> value="Solteiro">Solteiro</option>
                                <option <?php if($obrigatorio->getEstadoCivil() == "Casado") echo " selected" ?> value="Casado">Casado</option>
                                <option <?php if($obrigatorio->getEstadoCivil() == "União Estável") echo " selected" ?> value="União Estável">União Estável</option>
                                <option <?php if($obrigatorio->getEstadoCivil() == "Divorciado") echo " selected" ?> value="Divorciado">Divorciado</option>
                                <option <?php if($obrigatorio->getEstadoCivil() == "Viúvo") echo " selected" ?> value="Viúvo">Viúvo</option>
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                        <b>Data de Nascimento</b>
                            <input type="text" class="form-control" value="<?php echo $obrigatorio->imprimeDataNascimento() ?>" id="data_nascimento" onclick="cursorDataNascimento()" name="data_nascimento" placeholder="Data de Nascimento">
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
                        <b>Telefone</b><font color='red'> Digite o Prefixo (99)</font>
                            <input type="text" class="form-control" value="<?php echo $obrigatorio->getTelefone() ?>" name="telefone" placeholder="(99) 99999-9999">
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
                                <option <?php if($obrigatorio->getVoluntario() == "1") echo " selected " ?> value="1">Sim</option>
                                <option <?php if($obrigatorio->getVoluntario() == "0") echo " selected " ?> value="0">Não</option>
                              
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
                            <select name="nome_instituicao_ensino" class="form-control" value="<?php echo $obrigatorio->getNomeInstitutoEnsino() ?>">
                                <option value="">Nome da Instituição de Ensino</option>
                                    <option <?php if($obrigatorio->getNomeInstitutoEnsino() == "FURG - RIO GRANDE") echo " selected " ?> value="FURG - RIO GRANDE">FURG - RIO GRANDE</option>
                                    <option <?php if($obrigatorio->getNomeInstitutoEnsino() == "UFPEL - PELOTAS") echo " selected " ?> value="UFPEL - PELOTAS">UFPEL - PELOTAS</option>
                                    <option <?php if($obrigatorio->getNomeInstitutoEnsino() == "UCPEL - PELOTAS") echo " selected " ?> value="UCPEL - PELOTAS">UCPEL - PELOTAS</option>
                                    <option <?php if($obrigatorio->getNomeInstitutoEnsino() == "UPF - PASSO FUNDO") echo " selected " ?> value="UPF - PASSO FUNDO">UPF - PASSO FUNDO</option>
                                    <option <?php if($obrigatorio->getNomeInstitutoEnsino() == "UFFS - PASSO FUNDO") echo " selected " ?> value="UFFS - PASSO FUNDO">UFFS - PASSO FUNDO</option>
                                    <option <?php if($obrigatorio->getNomeInstitutoEnsino() == "ATITUS - PASSO FUNDO (IMED)") echo " selected " ?> value="ATITUS - PASSO FUNDO (IMED)">ATITUS - PASSO FUNDO (IMED)</option>
                                    <option <?php if($obrigatorio->getNomeInstitutoEnsino() == "UCS - CAXIAS DO SUL") echo " selected " ?> value="UCS - CAXIAS DO SUL">UCS - CAXIAS DO SUL</option>
                                    <option <?php if($obrigatorio->getNomeInstitutoEnsino() == "UNIVATES - LAJEADO") echo " selected " ?> value="UNIVATES - LAJEADO">UNIVATES - LAJEADO</option>
                                    <option <?php if($obrigatorio->getNomeInstitutoEnsino() == "UFCSPA - PORTO ALEGRE") echo " selected " ?> value="UFCSPA - PORTO ALEGRE">UFCSPA - PORTO ALEGRE</option>
                                    <option <?php if($obrigatorio->getNomeInstitutoEnsino() == "ULBRA - CANOAS") echo " selected " ?> value="ULBRA - CANOAS">ULBRA - CANOAS</option>
                                    <option <?php if($obrigatorio->getNomeInstitutoEnsino() == "PUCRS - PORTO ALEGRE") echo " selected " ?> value="PUCRS - PORTO ALEGRE">PUCRS - PORTO ALEGRE</option>
                                    <option <?php if($obrigatorio->getNomeInstitutoEnsino() == "UFRGS - PORTO ALEGRE") echo " selected " ?> value="UFRGS - PORTO ALEGRE">UFRGS - PORTO ALEGRE</option>
                                    <option <?php if($obrigatorio->getNomeInstitutoEnsino() == "UNISINOS - SÃO LEOPOLDO") echo " selected " ?> value="UNISINOS - SÃO LEOPOLDO">UNISINOS - SÃO LEOPOLDO</option>
                                    <option <?php if($obrigatorio->getNomeInstitutoEnsino() == "UNIPAMPA - URUGUAIANA") echo " selected " ?> value="UNIPAMPA - URUGUAIANA">UNIPAMPA - URUGUAIANA</option>
                                    <option <?php if($obrigatorio->getNomeInstitutoEnsino() == "UFSM - SANTA MARIA") echo " selected " ?> value="UFSM - SANTA MARIA">UFSM - SANTA MARIA</option>
                                    <option <?php if($obrigatorio->getNomeInstitutoEnsino() == "UFN - SANTA MARIA") echo " selected " ?> value="UFN - SANTA MARIA">UFN - SANTA MARIA</option>
                                    <option <?php if($obrigatorio->getNomeInstitutoEnsino() == "UNISC - SANTA CRUZ DO SUL") echo "selected " ?> value="UNISC - SANTA CRUZ DO SUL">UNISC - SANTA CRUZ DO SUL</option>
                                    <option <?php if($obrigatorio->getNomeInstitutoEnsino() == "URI - ERECHIM") echo " selected " ?> value="URI - ERECHIM">URI - ERECHIM</option>
                                    <option <?php if($obrigatorio->getNomeInstitutoEnsino() == "FEEVALE - NOVO HAMBURGO") echo " selected " ?> value="FEEVALE - NOVO HAMBURGO">FEEVALE - NOVO HAMBURGO</option>
                                    <option <?php if($obrigatorio->getNomeInstitutoEnsino() == "UNIJUÍ - IJUÍ") echo " selected " ?> value="UNIJUÍ - IJUÍ">UNIJUÍ - IJUÍ</option>
                                    <option <?php if($obrigatorio->getNomeInstitutoEnsino() == "GRADUADO EM FACULDADE FORA RS") echo " selected " ?> value="GRADUADO EM FACULDADE FORA RS">GRADUADO EM FACULDADE FORA RS</option>
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
                                <option <?php if($obrigatorio->getSolicitouAdiamento() == "1") echo " selected "?> value="1">Sim</option>
                                <option <?php if($obrigatorio->getSolicitouAdiamento() == "0") echo " selected "?> value="0">Não</option>
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
                            <select id="opcao" name="especialidade_adiamento" class="form-control">">
                            <option value="">Selecione a especialidade</option>
                                <?php 
                                    foreach ($todas_espec as $value) 
                                    {
                                        if ($value['nome'] == $obrigatorio->getEspecialidadeAdiamento()) 
                                            echo "<option selected value='" . $value['nome'] . "'>" . $value['nome'] . "</option>";
                                        else 
                                            echo "<option value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
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
                                <option <?php if($obrigatorio->getSituacaoMilitar() == "Em Débito - JUDICIAL") echo " selected "?> value="Em Débito - JUDICIAL">Em Débito - JUDICIAL</option>
                                <option <?php if($obrigatorio->getSituacaoMilitar() == "Em Dia - ALISTADO MFDV (FISEMI)") echo " selected "?> value="Em Dia - ALISTADO MFDV (FISEMI)">Em Dia - ALISTADO MFDV (FISEMI)</option>
                                <option <?php if($obrigatorio->getSituacaoMilitar() == "Em Dia - ADIADO CURSANDO RESIDÊNCIA") echo " selected "?> value="Em Dia - ADIADO CURSANDO RESIDÊNCIA">Em Dia - ADIADO CURSANDO RESIDÊNCIA</option>
                                <option <?php if($obrigatorio->getSituacaoMilitar() == "Em Dia - B1- INSP SAU -Retornar próxima Seleção") echo " selected "?> value="Em Dia - B1- INSP SAU -Retornar próxima Seleção">Em Dia - B1- INSP SAU -Retornar próxima Seleção</option>
                                <option <?php if($obrigatorio->getSituacaoMilitar() == "Quite SMO - EXCESSO - CONTIGENTE") echo " selected "?> value="Quite SMO - EXCESSO - CONTIGENTE">Quite SMO - EXCESSO - CONTIGENTE</option>
                                <option <?php if($obrigatorio->getSituacaoMilitar() == "Quite SMO - EXCESSO - INCAPAZ SAÚDE") echo " selected "?> value="Quite SMO - EXCESSO - INCAPAZ SAÚDE">Quite SMO - EXCESSO - INCAPAZ SAÚDE</option>
                                <option <?php if($obrigatorio->getSituacaoMilitar() == "Quite SMO - DESOBRIGADO - MAIOR 38 ANOS") echo " selected "?> value="Quite SMO - DESOBRIGADO - MAIOR 38 ANOS">Quite SMO - DESOBRIGADO - MAIOR 38 ANOS</option>
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
                                <option <?php if($obrigatorio->getDocumentoMilitar() == "CI") echo " selected "?>value="CI">Certificado de Isenção (CI)</option>
                                <option <?php if($obrigatorio->getDocumentoMilitar() == "CDI") echo " selected "?>value="CDI">Certificado de Dispensa de Incorporação (CDI)</option>
                                <option <?php if($obrigatorio->getDocumentoMilitar() == "CAM") echo " selected "?>value="CAM">Certificado de Alistmaneto Militar (CAM)</option>
                                <option <?php if($obrigatorio->getDocumentoMilitar() == "CSM") echo " selected "?>value="CSM">Certidão de Situação Militar (CSM)</option>
                                <option <?php if($obrigatorio->getDocumentoMilitar() == "CRM") echo " selected "?>value="CRM">Certificado de Reservista Militar 1ª Categoria (CRM)</option>
                                <option <?php if($obrigatorio->getDocumentoMilitar() == "CRM2") echo " selected "?>value="CRM2">Certificado de Reservista Militar 2ª Categoria (CRM)</option>
                                
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
                                <option <?php if($obrigatorio->getForca() == "Exército") echo " selected "?> value="Exército">Exército</option>
                                <option <?php if($obrigatorio->getForca() == "Marinha") echo " selected "?> value="Marinha">Marinha</option>
                                <option <?php if($obrigatorio->getForca() == "Aeronáutica") echo " selected "?> value="Aeronáutica">Aeronáutica</option>
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
                                <option <?php if($obrigatorio->getTransferenciaFisemi() == "1") echo " selected "?> value="1">Sim</option>
                                <option <?php if($obrigatorio->getTransferenciaFisemi() == "0") echo " selected "?> value="0">Não</option>
                            </select>
                        </div>
						
						<div class="col-md-4 form-group">
                        <b>Região Origem</b>
                            <select name="rm_origem_fisemi" class="form-control">
                                <option value="">Selecione a Região</option>
                                <option <?php if($obrigatorio->getRmOrigemFisemi() == "1") echo " selected "?> value="1">1ª RM</option>
                                <option <?php if($obrigatorio->getRmOrigemFisemi() == "2") echo " selected "?> value="2">2ª RM</option>
                                <option <?php if($obrigatorio->getRmOrigemFisemi() == "3") echo " selected "?> value="3">3ª RM</option>
                                <option <?php if($obrigatorio->getRmOrigemFisemi() == "4") echo " selected "?> value="4">4ª RM</option>
                                <option <?php if($obrigatorio->getRmOrigemFisemi() == "5") echo " selected "?> value="5">5ª RM</option>
                                <option <?php if($obrigatorio->getRmOrigemFisemi() == "6") echo " selected "?> value="6">6ª RM</option>
                                <option <?php if($obrigatorio->getRmOrigemFisemi() == "7") echo " selected "?> value="7">7ª RM</option>
                                <option <?php if($obrigatorio->getRmOrigemFisemi() == "8") echo " selected "?> value="8">8ª RM</option>
                                <option <?php if($obrigatorio->getRmOrigemFisemi() == "9") echo " selected "?> value="9">9ª RM</option>
                                <option <?php if($obrigatorio->getRmOrigemFisemi() == "10") echo " selected "?> value="10">10ª RM</option>
                                <option <?php if($obrigatorio->getRmOrigemFisemi() == "11") echo " selected "?> value="11">11ª RM</option>
                                <option <?php if($obrigatorio->getRmOrigemFisemi() == "12") echo " selected "?> value="12">12ª RM</option>
                            </select>

						</div>
						
						<div class="col-md-4 form-group" id="justica">
                        <b>Região Destino</b>
                        <select name="rm_destino_fisemi" class="form-control">
                                <option value="">Selecione a Região</option>
                                <option <?php if($obrigatorio->getRmDestinoFisemi() == "1") echo " selected "?> value="1">1ª RM</option>
                                <option <?php if($obrigatorio->getRmDestinoFisemi() == "2") echo " selected "?> value="2">2ª RM</option>
                                <option <?php if($obrigatorio->getRmDestinoFisemi() == "3") echo " selected "?> value="3">3ª RM</option>
                                <option <?php if($obrigatorio->getRmDestinoFisemi() == "4") echo " selected "?> value="4">4ª RM</option>
                                <option <?php if($obrigatorio->getRmDestinoFisemi() == "5") echo " selected "?> value="5">5ª RM</option>
                                <option <?php if($obrigatorio->getRmDestinoFisemi() == "6") echo " selected "?> value="6">6ª RM</option>
                                <option <?php if($obrigatorio->getRmDestinoFisemi() == "7") echo " selected "?> value="7">7ª RM</option>
                                <option <?php if($obrigatorio->getRmDestinoFisemi() == "8") echo " selected "?> value="8">8ª RM</option>
                                <option <?php if($obrigatorio->getRmDestinoFisemi() == "9") echo " selected "?> value="9">9ª RM</option>
                                <option <?php if($obrigatorio->getRmDestinoFisemi() == "10") echo " selected "?> value="10">10ª RM</option>
                                <option <?php if($obrigatorio->getRmDestinoFisemi() == "11") echo " selected "?> value="11">11ª RM</option>
                                <option <?php if($obrigatorio->getRmDestinoFisemi() == "12") echo " selected "?> value="12">12ª RM</option>
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
                                <option <?php if($obrigatorio->getTransitouJulgado() == "1") echo " selected "?> value="1">Sim</option>
                                <option <?php if($obrigatorio->getTransitouJulgado() == "0") echo " selected "?> value="0">Não</option>
                              
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
                                <option <?php if($obrigatorio->getFavoravel() == "1") echo " selected "?> value="1">Sim</option>
                                <option <?php if($obrigatorio->getFavoravel() == "0") echo " selected "?> value="0">Não</option>
                              
                            </select>
                        </div>
                        
                        <div class="col-md-4 form-group" id="distribuicao">
                        <b>Convocado</b>
                            <select name="convocado" class="form-control" value="<?php echo $obrigatorio->getConvocado() ?>">
                            <option value="">Selecione a Opção</option>
                                <option <?php if($obrigatorio->getConvocado() == "1") echo " selected "?> value="1">Sim</option>
                                <option <?php if($obrigatorio->getConvocado() == "0") echo " selected "?> value="0">Não</option>
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
                                <option <?php if($obrigatorio->getResultadoIsgr() == "Não é o caso") echo " selected "?> value="Não é o caso">Não é o caso</option>
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
                                        {
                                            echo "<option value='" . $value['nome'] . "' selected>" . $value['abreviatura'] . "</option>";
                                        }
                                        else 
                                        {
                                            echo "<option value='" . $value['nome'] . "' >" . $value['abreviatura'] . "</option>";
                                        }
                                            
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

                <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave']."obrigatorio"); ?>">
                <div class="text-center">
                    <br>
                    <button type="submit" style="width: 100%;" class="btn btn-primary btn-block">ATUALIZAR</button>
                </div>
                
            </form>
        </div>
      </div>
    </section>

    <section id="contact" class="contact">
    <div class="container card">
        <div class="row  justify-content-center" >

        <!-- PRIMEIRA TABELA -->

            <div class="col-md-4 form-group">
                    <center><b>Data da Próxima Apresentação: </b> <?php echo $obrigatorio->imprimeDataProximaApresentacao() ?></center>
            </div>

                <center> <b><font size="5" color="green"><a href="#identificacao">IDENTIFICAÇÃO</a></font></b></center>
                <br>
                <br>
                <div class='card'>
                
                    <div class="row">

                        <div class="col-md-4 form-group">
                            <b>Nome Completo: </b><?php echo $obrigatorio->getNomeCompleto() ?> 
                        </div>

                        <div class="col-md-4 form-group">
                            <b>CPF: </b><?php echo $obrigatorio->getCPF() ?> 
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Estado Civil: </b> <?php echo $obrigatorio->getEstadoCivil() ?>
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Data de Nascimento: </b> <?php echo $obrigatorio->imprimeDataNascimento() ?>
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Identidade: </b><?php echo $obrigatorio->getIdentidade() ?> 
                        </div>

                        <div class="col-md-4 form-group">
                            <b>E-Mail: </b> <?php echo $obrigatorio->getMail() ?>
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Telefone: </b> <?php echo $obrigatorio->getTelefone() ?> 
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Nome do Pai: </b><?php echo $obrigatorio->getNomePai() ?> 
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Nome da Mãe: </b><?php echo $obrigatorio->getNomeMae() ?> 
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Voluntário </b> <?php echo $obrigatorio->getVoluntario() ?>
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Nacionalidade: </b> <?php echo $obrigatorio->getNacionalidade() ?> 
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Naturalidade: </b> <?php echo $obrigatorio->getNaturalidade() ?> 
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Endereço completo: </b> <?php echo $obrigatorio->getEndereco() ?>
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Dependentes: </b> <?php echo $obrigatorio->getDependentes() ?>
                        </div>


                        <div class="col-md-4 form-group">
                            <b>Prioridade Força: </b> <?php echo $obrigatorio->getPrioridadeForca() ?>
                        </div>

                    </div>
                </div>


                <center> <b><font size="5" color="green"><a href="#formacao">FORMAÇÃO<a></font> </b></center>
                <br>
                <br>
                <div class='card'>
                
                    <div class="row">

                        <div class="col-md-4 form-group">
                            <b>IE Graduação: </b> <?php echo $obrigatorio->getNomeInstitutoEnsino()?>
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Ano de Formação: </b> <?php echo $obrigatorio->getAnoFormacao() ?>
                            
                        </div>

                        <div class="col-md-4 form-group">
                        <b></b> 
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Especialidade 1: </b> <?php echo $obrigatorio->getEspecialidade() ?>
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Especialidade 2: </b> <?php echo $obrigatorio->getEspecialidade2() ?>
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Especialidade 3: </b> <?php echo $obrigatorio->getEspecialidade3() ?>
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Ano Especialidade 1: </b> <?php echo $obrigatorio->getAnoResEspe1() ?>
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Ano Especialidade 2: </b> <?php echo $obrigatorio->getAnoResEspe2() ?>
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Ano Especialidade 3: </b> <?php echo $obrigatorio->getAnoResEspe3() ?>
                        </div>

                    </div>
                </div>

                <center> <b><font size="5" color="green"><a href="#adiamento">ADIAMENTO - SMO</a></font> </b></center>
                <br>
                <br>
                <div class='card'>
                
                    <div class="row">

                        <div class="col-md-4 form-group">
                            <b>Solicitou Adiamento? </b> <?php echo $obrigatorio->getSolicitouAdiamento() ?>
                        </div>
    
                        <div class="col-md-4 form-group">
                            <b>Início Adiamento: </b> <?php echo $obrigatorio->imprimeInicioAdiamento() ?>
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Fim Adiamento: </b> <?php echo $obrigatorio->imprimeFimAdiamento() ?>
                        </div>
                        
                        <div class="col-md-4 form-group">
                            <b>Especialidade Adiamento: </b> <?php echo $obrigatorio->getEspecialidadeAdiamento() ?>
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Outra Especialidade Adiamento: </b> <?php echo $obrigatorio->getEspecialidadeAdiamento() ?>
                        </div>
  
                    </div>
                </div>

                <center> <b><font size="5" color="green"><a href="#situacao_militar">SITUAÇÃO MILITAR</a></font> </b></center>
                <br>
                <br>
                <div class='card'>
                
                    <div class="row">

                        <div class="col-md-4 form-group">
                            <b>Situação Militar: </b> <?php echo $obrigatorio->getSituacaoMilitar() ?>
                        </div>
    
                        <div class="col-md-4 form-group">
                            <b>Documento Militar: </b> <?php echo $obrigatorio->getDocumentoMilitar() ?>
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Numero do Doc Militar: </b> <?php echo $obrigatorio->getNumeroDocumentoMilitar() ?>
                        </div>
                        
                        <div class="col-md-4 form-group">
                            <b>Data de Expedição: </b> <?php echo $obrigatorio->imprimeDataExpedicao() ?>
                        </div>
  
                        <div class="col-md-4 form-group">
                            <b>Força: </b> <?php echo $obrigatorio->getForca() ?>
                        </div>

                    </div>
                </div>

                <center> <b><font size="5" color="green"><a href="#selecao_geral">SELEÇÃO GERAL</a></font></b></center>
                <div class='card'>
                
                    <div class="row">

                        <div class="col-md-4 form-group">
                            <b>Data Comparecimento Seleção Geral: </b> <?php echo $obrigatorio->imprimeDataComparecimentoSelecaoGeral() ?>
                        </div>

                        <div class="col-md-4 form-group">
                            <b>JISE: </b> <?php echo $obrigatorio->getJise() ?>
                        </div>

                        <div class="col-md-4 form-group">
                            <b>CID JISE: </b> <?php echo $obrigatorio->getCidJise() ?>
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Observação JISE: </b> <?php echo $obrigatorio->getObservacaoJise() ?>
                        </div>

                        <div class="col-md-4 form-group">
                            <b>JISR:</b> <?php echo $obrigatorio->getJisr() ?>
                        </div>

                        <div class="col-md-4 form-group">
                            <b>CID JISR: </b> <?php echo $obrigatorio->getCidJisr() ?>
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Data JISR: </b> <?php echo $obrigatorio->imprimeDataJisr() ?>
                        </div>

                        <div class="col-md-4 form-group">
                            <b>Observação JISR: </b> <?php echo $obrigatorio->getObsJisr() ?>
                        </div>

                </div>    
                </div> 

                

                <center> <b><font size="5" color="green"><a href="#fisemi">FISEMI</a></font></b></center>
                <div class='card'>
                
                    <div class="row">
                    <div class="col-md-4 form-group">
                            <b>Transferencia do Fisemi: </b> <?php echo $obrigatorio->getTransferenciaFisemiExibe() ?>
                        </div>
                        
                        <div class="col-md-4 form-group">
                            <b>Região Origem: </b> <?php echo $obrigatorio->getRmOrigemFisemi() ?>
                        </div>
                        
                        <div class="col-md-4 form-group">
                            <b>Região Destino: </b> <?php echo $obrigatorio->getRmDestinoFisemi() ?>
                        </div>

                    </div>
                </div>
                

                <center> <b><font size="5" color="green"><a href="#justica">JUSTIÇA</a></font></b></center>
                <div class='card'>
                
                    <div class="row">
                           
                    <div class="col-md-4 form-group">
                            <b>Numero da Ação: </b> <?php echo $obrigatorio->getNumeroAcao() ?>
                        </div>
                        
                        <div class="col-md-4 form-group">
                            <b>Transitou Julgado: </b> <?php echo $obrigatorio->getImprimeTransitouJulgado() ?>
                        </div>
                        
                        <div class="col-md-4 form-group">
                            <b>Data da Liminar: </b> <?php echo $obrigatorio->imprimeDataLiminar() ?>
                        </div>
                        
                        <div class="col-md-4 form-group">
                            <b>Favorável: </b> <?php echo $obrigatorio->getImprimeFavoravel() ?>
                        </div>
                        
                        <div class="col-md-4 form-group">
                            <b>Convocado: </b> <?php echo $obrigatorio->getImprimeConvocado() ?>
                        </div>

                    </div>
                </div>

                <center> <b><font size="5" color="green"><a href="#distribuicao">DISTRIBUIÇÃO</a></font> </b></center>
                <div class='card'>

                    <div class="row">
                    <div class="col-md-4 form-group">
                            <b>Data Comparec Designação: </b> <?php echo $obrigatorio->imprimeDataComparecimentoDesignacao() ?>
                        </div>

                    <div class="col-md-4 form-group">
                            <b>Distribuição: </b> <?php echo $obrigatorio->getDistribuicao() ?>
                        </div>
                        
                        <div class="col-md-4 form-group">
                            <b>Gu 1ª Fase: </b> <?php echo $obrigatorio->getGu1Fase() ?>
                        </div>
                        
                        <div class="col-md-4 form-group">
                            <b>Data da Seleção Complementar: </b> <?php echo $obrigatorio->imprimeDataSelecaoComplementar() ?>
                        </div>
                        
                        <div class="col-md-4 form-group">
                            <b>Resultado da Revisão Médica: </b> <?php echo $obrigatorio->getResultadoRevisaoMedicaComplementar() ?>
                        </div>
                        
                        <div class="col-md-4 form-group">
                            <b>ISGR - MPGu: </b> <?php echo $obrigatorio->getResultadoIsgr() ?>
                        </div>
                        
                        <div class="col-md-4 form-group">
                            <b>Data da Incorporação: </b> <?php echo $obrigatorio->imprimeDataIncorporacao() ?>
                        </div>
                        
                        <div class="col-md-4 form-group">
                            <b>OM 2ª Fase: </b> <?php echo $obrigatorio->getOm2Fase() ?>
                        </div>
                    </div>
                </div>

                       
                     
                    <center> <b><font size="5" color="green"><a href="#observacoes">OBSERVAÇÕES </a></font> </b></center>         
                        <div class="col-md-12 form-group">
                        <b>Observações: </b> <?php echo $obrigatorio->getObservacao() ?>
                    </div>

                    </div>
                </div>

            </div>
        </div>
    </div>


</section>

    <a name="arquivos">




    <section id="contact" class="contact">
      <div class="container card">

      <center><h4><b> Arquivos </b></h4></center><hr>


          <div class="row">
                <div class="col-md-12">

               

                <form action="controller/arquivo_adiciona.php" method="POST" enctype="multipart/form-data" style="width: 100%">
                  <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave']."arquivo_obrigatorio"); ?>">
                  <input name="id_obrigatorio" hidden value="<?php echo  $obrigatorio->getId() ?>">
                  <div class="row">
                      <div class="col-md-4">
                          <div class="form-group">
                              <input type="text" class="form-control" name="nome_arquivo" placeholder="Nome do arquivo">
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="form-group">
                              <input type="file" style="width: 100%;" class="btn btn-primary btn-block" name="arquivo">
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="form-group">
                              <button type="submit" style="width: 100%;" class="btn btn-primary btn-block">ENVIAR ARQUIVO</button>
                          </div>
                      </div>
                  </div>
                  </form>
                </div>

                <div class="col-md-12">

                <br>
                <hr>
                <br>
                    <?php
                          if(!empty($arquivos))
                          {
                              foreach ($arquivos as $arquivo)
                              {
							  
                                  $crip = hash('sha256', $arquivo->getNome()."crip_download_arquivo");
                    ?>
                                    <div class="col-md-12">
                                        <form action="controller/arquivo_apaga.php" method="POST" style="width: 100%">
                                            <input name="crip" hidden value="<?php echo  hash('sha256', $arquivo->getId()."criptografia_arquivo"); ?>">
                                            <input name="id_arquivo" hidden value="<?php echo $arquivo->getId() ?>">
                                            <input name="id_obrigatorio" hidden value="<?php echo $id_obrigatorio ?>">
                                            <input name="crip_url" hidden value="<?php echo $crip_url ?>">
                                            <input name="chave" hidden value="<?php echo $_SESSION['chave'] ?>">
                                            
                                                <a target='_blank' href='controller/arquivo_download.php?crip=<?php echo $crip?>&id_arquivo=<?php echo $arquivo->getId() ?>'>
                                                
                                                <img src='imagens/pdf.png' height='60px'>  
                                                <?php echo $arquivo->getLabel() ?>
                                                    &nbsp;&nbsp;&nbsp;
                                                </a>
                                                <button type="submit" >Apagar</button>
                                                <br>
                                                <br>
                                                <br>
                                        </form>
                                    </div>
                    <?php
                              }
                          }
                    ?>

                </div>
          </div>


      </div>
    </section>




  
<?php 
  include_once 'footer.php';
?>