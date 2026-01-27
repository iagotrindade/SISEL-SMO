<form action="mpdf/oficio_medico_obrigatorio.php" method="post" role="form" class="card">
<input name="id_obrigatorio" hidden value="<?php echo $obrigatorio->getId(); ?>"> 
    <center> <b><font size="5" color="green">OFÍCIO</font></b></center>
    <br>
    <div class="row">  
        <div class="col-md-4 form-group">
            <b>Endereço e Bairro da OM de 1ª Fase</b> 
            <input type="text" class="form-control" value="<?php echo $oficio->getBairroOm1Fase()?>" name="bairro_om_1_fase">
        </div>
        <div class="col-md-4 form-group">
            <b>Presidente da Comissão da Deignação</b>
            <input type="text" class="form-control" value="<?php echo $oficio->getPresidenteComissao()?>" name="presidente_comissao">
        </div>
    </div>
    <div class="row">  
        <div class="col-md-4 form-group">
            <b>CEP e Cidade da OM DE 1ª Fase</b>
            <input type="text" class="form-control" value="<?php echo $oficio->getCepCidadeOm1Fase()?>" name="cep_cidade_om_1_fase">
        </div>
        <div class="col-md-4 form-group">
            <b>Data de Apresentação</b>
            <input type="text" class="form-control" value="<?php echo $oficio->imprimeDataApresentacao()?>" name="data_apresentacao">
        </div>
        <div class="col-md-4 form-group">
            <b>Hora Apresentação</b>
            <input type="text" class="form-control" value="<?php echo $oficio->getHoraApresentacao()?>" name="hora_apresentacao">
        </div>
        <div class="col-md-4 form-group">
            <b>Telefone da OM de 1ª Fase</b>
            <input type="text" class="form-control" value="<?php echo $oficio->getTelOm1Fase()?>" name="tel_om_1_fase">
        </div>
        <div class="col-md-4 form-group">
            <b>EB</b>
            <input type="text" class="form-control" value="<?php echo $oficio->getEb()?>" name="eb">
        </div>
        <div class="col-md-4 form-group">
            <b>Data do Cabeçalho</b>
            <input type="text" class="form-control" value="<?php echo $oficio->imprimeDataCabecalho()?>" name="data_cabecalho">
        </div>
    </div>
    <div class="text-center">
        <br>
        <button type="submit" style="width: 100%;" class="btn btn-primary btn-block">GERAR OFÍCIO</button>
    </div>                      
    
    <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave']."criptografia"); ?>">
    
</form>