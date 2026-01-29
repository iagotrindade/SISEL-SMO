<div class='card'>
    <div class="row">
        <div class="text-center text-success">
            <h3 color="green">SITUAÇÃO MILITAR</h3>
        </div>

        <div class="col-md-12 form-group">
            <b>Situação Militar: </b> <?php echo $obrigatorio->getSituacaoMilitar() ?>
        </div>

        <div hidden class="col-md-4 form-group">
            <b>Data da Próxima Apresentação: </b> <?php echo $obrigatorio->imprimeDataProximaApresentacao() ?>
        </div>
    </div>
</div>

<br>

<div class='card'>
    <div class="row">
        <div class="text-center text-success">
            <h3 color="green">IDENTIFICAÇÃO</h3>
        </div>


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

<br>

<div class='card'>
    <div class="row">
        <div class="text-center text-success">
            <h3 color="green">FORMAÇÃO</h3>
        </div>

        <div class="col-md-4 form-group">
            <b>IE Graduação: </b> <?php echo $obrigatorio->getNomeInstitutoEnsino() ?>
        </div>

        <div class="col-md-4 form-group">
            <b>Ano de Formação: </b> <?php echo $obrigatorio->getAnoFormacao() ?>
        </div>
        <div class="col-md-4 form-group">
            <b>Formação: </b> <?php echo $obrigatorio->getFormacao() ?>
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

<br>

<div class='card'>
    <div class="row">
        <div class="text-center text-success">
            <h3 color="green">ADIAMENTO - SMO</h3>
        </div>

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

<br hidden>

<div hidden class='card'>
    <div class="row">
        <div class="text-center text-success">
            <h3 color="green">SITUAÇÃO MILITAR</h3>
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

<br>

<div class='card'>
    <div class="row">
        <div class="text-center text-success">
            <h3 color="green">SELEÇÃO GERAL</h3>
        </div>

        <div class="col-md-4 form-group">
            <b>Data Comparecimento Seleção Geral: </b> <?php echo $obrigatorio->imprimeDataComparecimentoSelecaoGeral() ?>
        </div>

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

        <div class="col-md-4 form-group">
            <b>JISE A-1: </b> <?php echo $obrigatorio->getJisea1() ?>
        </div>

        <div class="col-md-4 form-group">
            <b>CID JISE A-1: </b> <?php echo $obrigatorio->getCidJisea1() ?>
        </div>


        <div class="col-md-4 form-group">
            <b>Data JISE A-1: </b> <?php echo $obrigatorio->imprimeDataJisea1() ?>
        </div>

        <div class="col-md-4 form-group">
            <b>Observação JISR: </b> <?php echo $obrigatorio->getObsJisr() ?>
        </div>

    </div>
</div>

<br>

<div class='card'>
    <div class="row">
        <div class="text-center text-success">
            <h3 color="green">REVISÃO MÉDICA - OM 1ª FASE</h3>
        </div>
        <div class="col-md-4 form-group">
            <b>Data Revisão Médica: </b> <?php echo $obrigatorio->getData_revisao_medica() ?>
        </div>

        <div class="col-md-4 form-group">
            <b>Resultado Revisão Médica: </b> <?php echo $obrigatorio->getResultadoRevisaoMedicaComplementar() . "ªRM " ?>
        </div>

        <div class="col-md-4 form-group">
            <b>CID Revisão Médica: </b> <?php echo $obrigatorio->getCid_revisao_medica() . "ªRM " ?>
        </div>

        <div class="col-md-4 form-group">
            <b>Observação Revisão Médica: </b> <?php echo $obrigatorio->getObs_revisao_medica() . "ªRM " ?>
        </div>

    </div>
</div>

<br>

<div class='card'>
    <div class="row">
        <div class="text-center text-success">
            <h3 color="green">FISEMI</h3>
        </div>
        <div class="col-md-4 form-group">
            <b>Transferencia do Fisemi: </b> <?php echo $obrigatorio->getTransferenciaFisemi() ?>
        </div>

        <div class="col-md-4 form-group">
            <b>Região Origem: </b> <?php echo $obrigatorio->getRmOrigemFisemi() . " ªRM" ?>
        </div>

        <div class="col-md-4 form-group">
            <b>Região Destino: </b> <?php echo $obrigatorio->getRmDestinoFisemi() . " ªRM" ?>
        </div>

    </div>
</div>

<br>

<div class='card'>
    <div class="row">
        <div class="text-center text-success">
            <h3 color="green">ISGRev</h3>
        </div>
        <div class="col-md-4 form-group">
            <b>ISGRev: </b> <?php echo $obrigatorio->getResultadoIsgr() ?>
        </div>

        <div class="col-md-4 form-group">
            <b>Data ISGRev: </b> <?php echo $obrigatorio->getData_isgr() ?>
        </div>

        <div class="col-md-4 form-group">
            <b>CID ISGRev: </b> <?php echo $obrigatorio->getCid_isgr() ?>
        </div>

        <div class="col-md-4 form-group">
            <b>Observação ISGRev: </b> <?php echo $obrigatorio->getObservacao_isgr() ?>
        </div>

    </div>
</div>

<br>

<div class='card'>

    <div class="row">
        <div class="text-center text-success">
            <h3 color="green">INCORPORAÇÃO</h3>
        </div>

        <div class="col-md-4 form-group">
            <b>Incorporação - Compareceu? </b> <?php echo $obrigatorio->getIncorporacao() ?>
        </div>

        <div class="col-md-4 form-group">
            <b>Data Incorporação: </b> <?php echo $obrigatorio->getData_Incorporacao() ?>
        </div>

        <div class="col-md-4 form-group">
            <b>OM 1ª Fase: </b> <?php echo $obrigatorio->getOm1Fase()->getNome() ?>
        </div>

        <div class="col-md-4 form-group">
            <b>BAR Incorporação: </b> <?php echo $obrigatorio->getBar_om_1_fase() ?>
        </div>

        <div class="col-md-4 form-group">
            <b>OM 2ª Fase: </b> <?php echo $obrigatorio->getOm2Fase() ?>
        </div>

    </div>
</div>

<br>

<div class='card'>
    <div class="row">
        <div class="text-center text-success">
            <h3 color="green">JUSTIÇA</h3>
        </div>

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

<br>

<div class='card'>
    <div class="row">
        <div class="text-center text-success">
            <h3 color="green">DISTRIBUIÇÃO</h3>
        </div>

        <div class="col-md-4 form-group">
            <b>Data Comparec Designação: </b> <?php echo $obrigatorio->imprimeDataComparecimentoDesignacao() ?>
        </div>

        <div class="col-md-4 form-group">
            <b>Distribuição: </b> <?php echo $obrigatorio->getDistribuicao() ?>
        </div>

        <div class="col-md-4 form-group">
            <b>Data da Seleção Complementar: </b> <?php echo $obrigatorio->imprimeDataSelecaoComplementar() ?>
        </div>


        <div class="col-md-4 form-group">
            <b>ISGRev: </b> <?php echo $obrigatorio->getResultadoIsgr() ?>
        </div>


        <div class="col-md-4 form-group">
            <b>OM 2ª Fase: </b> <?php echo $obrigatorio->getOm2Fase() ?>
        </div>
    </div>
</div>

<br>

<div class="card">
    <div class='row'>
        <div class="text-center text-success">
            <h3 color="green">OBSERVAÇÕES</h3>
        </div>

        <b>Observações: </b> <?php echo $obrigatorio->getObservacao() ?>
    </div>
</div>

<br>

<center>
    <label>
        <a href="mpdf/ficha_completa.php?crip=<?php echo $crip_url ?>&id_obrigatorio=<?php echo $id_obrigatorio ?>" target="_blank">
            Imprimir
        </a>
    </label>
</center>