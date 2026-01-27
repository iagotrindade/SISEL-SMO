<div class="col-md-4 form-group">
    <b>Data da Próxima Apresentação: </b> <?php echo $obrigatorio->imprimeDataProximaApresentacao() ?>
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
            <b>Voluntário: </b> <?php echo $obrigatorio->getVoluntario() ?>
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
            <b>Transferencia do Fisemi: </b> <?php echo $obrigatorio->getTransferenciaFisemi() ?>
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
            <b>Transitou Julgado: </b> <?php echo $obrigatorio->getTransitouJulgado() ?>
        </div>
        
        <div class="col-md-4 form-group">
            <b>Data da Liminar: </b> <?php echo $obrigatorio->imprimeDataLiminar() ?>
        </div>
        
        <div class="col-md-4 form-group">
            <b>Favorável: </b> <?php echo $obrigatorio->getFavoravel() ?>
        </div>
        
        <div class="col-md-4 form-group">
            <b>Convocado: </b> <?php echo $obrigatorio->getConvocado() ?>
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
            <b>ISGRev: </b> <?php echo $obrigatorio->getResultadoIsgr() ?>
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