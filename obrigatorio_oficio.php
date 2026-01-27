<br>

<form action="mpdf/oficio_medico_obrigatorio.php" method="post" target="_blank" role="form" class="card">
    <input name="id_obrigatorio " hidden value="<?php echo $obrigatorio->getId(); ?>">

    <br>

    <div class="row">
        <div class="text-center text-success">
            <h3 color="green">OFÍCIO</h3>
        </div>

        <div class="col-md-4 form-group">
            <b>Data Prevista para Incorporação</b>
            <input type="text" class="form-control" value="<?php echo $oficio->imprimeDataCabecalho() ?>" name="data_cabecalho">
        </div>

        <div class="col-md-4 form-group">
            <b>Endereço e Bairro da OM de 1ª Fase</b>
            <input type="text" class="form-control" value="<?php echo $obrigatorio->getOm1Fase()->getEndereco() ?>" name="bairro_om_1_fase">
        </div>

        <div class="col-md-4 form-group">
            <b>Presidente da Comissão da Deignação</b>
            <input type="text" class="form-control" value="ARTHUR MÁRCIO RIGOTTI - CEL PTTC" name="presidente_comissao">
        </div>

        <div class="col-md-3 form-group">
            <b>CEP e Cidade da OM DE 1ª Fase</b>
            <input type="text" class="form-control" value="<?php echo $obrigatorio->getOm1Fase()->getCep() ?>" name="cep_cidade_om_1_fase">
        </div>
        <div class="col-md-3 form-group">
            <b>Data da próxima Apresentação</b>
            <input type="text" class="form-control" value="<?php echo $obrigatorio->imprimeDataProximaApresentacao() ?>" name="data_apresentacao">
        </div>

        <div class="col-md-3 form-group">
            <b>Hora Apresentação</b>
            <input type="text" class="form-control" value="09:00" name="hora_apresentacao">
        </div>
        
        <div class="col-md-3 form-group">
            <b>Telefone da OM de 1ª Fase</b>
            <input type="text" class="form-control" value="<?php echo $obrigatorio->getOm1Fase()->getTelefone() ?>" name="tel_om_1_fase">
        </div>


    </div>
    <div class="text-center">
        <br>
        <button type="submit" style="width: 100%;" class="btn btn-primary btn-block">GERAR OFÍCIO</button>
    </div>

    <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave'] . "criptografia"); ?>">

</form>