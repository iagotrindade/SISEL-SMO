<section>
    <div class="row justify-content-center">
        <form action="controller/obrigatorio_edita_fisemi.php" method="post" role="form">
            <input hidden type="text" value="<?php echo $obrigatorio->getId() ?>" name="id_obrigatorio">
            <input hidden type="text" value="<?php echo $crip_url ?>" name="crip_url">
            <input hidden type="text" value="fisemi" name="aba">
            <div class="card">
                <div class="row">
                    <div class="text-center text-success">
                        <h3 color="green">SITUAÇÃO MILITAR</h3>
                    </div>


                    <div class="col-md-12 form-group p-0">
                        <b>Situação Militar</b>
                        <select name="situacao_militar" class="form-control">
                            <option value="">Selecione a Opção</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Em Débito - REFRATÁRIO") echo " selected " ?> value="Em Débito - REFRATÁRIO">Em Débito - REFRATÁRIO</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Em Débito - INSUBMISSO") echo " selected " ?> value="Em Débito - INSUBMISSO">Em Débito - INSUBMISSO</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Em Dia - JUDICIAL") echo " selected " ?> value="Em Dia - JUDICIAL">Em Dia - JUDICIAL</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Em Dia - JUDICIAL LIMINAR") echo " selected " ?> value="Em Dia - JUDICIAL LIMINAR">Em Dia - JUDICIAL LIMINAR</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Em Dia - ALISTADO MFDV (FISEMI)") echo " selected " ?> value="Em Dia - ALISTADO MFDV (FISEMI)">Em Dia - ALISTADO MFDV (FISEMI)</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Em Dia - TRANSFERÊNCIA FISEMI") echo " selected " ?> value="Em Dia - TRANSFERÊNCIA FISEMI">Em Dia - TRANSFERÊNCIA FISEMI</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Em Dia - ADIADO CURSANDO RESIDÊNCIA") echo " selected " ?> value="Em Dia - ADIADO CURSANDO RESIDÊNCIA">Em Dia - ADIADO CURSANDO RESIDÊNCIA</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Em Dia - B1 - INSP SAU - RETORNAR PRÓXIMA SELEÇÃO") echo " selected " ?> value="Em Dia - B1 - INSP SAU - RETORNAR PRÓXIMA SELEÇÃO">Em Dia - B1 - INSP SAU - RETORNAR PRÓXIMA SELEÇÃO</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Quite SMO - EXCESSO - CONTINGENTE") echo " selected " ?> value="Quite SMO - EXCESSO - CONTINGENTE">Quite SMO - EXCESSO - CONTINGENTE</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Quite SMO - EXCESSO - INCAPAZ SAÚDE") echo " selected " ?> value="Quite SMO - EXCESSO - INCAPAZ SAÚDE">Quite SMO - EXCESSO - INCAPAZ SAÚDE</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Quite SMO - DESOBRIGADO - MAIOR 38 ANOS") echo " selected " ?> value="Quite SMO - DESOBRIGADO - MAIOR 38 ANOS">Quite SMO - DESOBRIGADO - MAIOR 38 ANOS</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Quite SMO - DESOBRIGADO - JÁ RESERVISTA") echo " selected " ?> value="Quite SMO - DESOBRIGADO - JÁ RESERVISTA">Quite SMO - DESOBRIGADO - JÁ RESERVISTA</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Quite SMO - DESOBRIGADO - NATURALIZADO") echo " selected " ?> value="Quite SMO - DESOBRIGADO - NATURALIZADO">Quite SMO - DESOBRIGADO - NATURALIZADO</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Quite SMO - CONVOCADO") echo " selected " ?> value="Quite SMO - CONVOCADO">Quite SMO - CONVOCADO</option>
                        </select>
                    </div>
                    <div hidden class="col-md-6 form-group" id="identificacao">
                        <b>Data Próxima Apresentação</b>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->imprimeDataProximaApresentacao() ?>" name="data_proxima_apresentacao">
                    </div>
                </div>
            </div>
            <br>

            <div class="card">
                <div class="row">
                    <div class="text-center text-success">
                        <h3 color="green">FISEMI</h3>
                    </div>
                    <div class="col-md-4 form-group">
                        <b>Tranferência de FISEMI?</b>
                        <select name="transferencia_fisemi" class="form-control">
                            <option value="">Selecione a Opção</option>
                            <option <?php if ($obrigatorio->getTransferenciaFisemi() == "SIM") echo " selected " ?> value="SIM">SIM</option>
                            <option <?php if ($obrigatorio->getTransferenciaFisemi() == "NÃO") echo " selected " ?> value="NÃO">NÃO</option>
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <b>Região Origem</b>
                        <select name="rm_origem_fisemi" class="form-control">
                            <option value="">Selecione a Região</option>
                            <option <?php if ($obrigatorio->getRmOrigemFisemi() == "1") echo " selected " ?> value="1">1ª RM</option>
                            <option <?php if ($obrigatorio->getRmOrigemFisemi() == "2") echo " selected " ?> value="2">2ª RM</option>
                            <option <?php if ($obrigatorio->getRmOrigemFisemi() == "3") echo " selected " ?> value="3">3ª RM</option>
                            <option <?php if ($obrigatorio->getRmOrigemFisemi() == "4") echo " selected " ?> value="4">4ª RM</option>
                            <option <?php if ($obrigatorio->getRmOrigemFisemi() == "5") echo " selected " ?> value="5">5ª RM</option>
                            <option <?php if ($obrigatorio->getRmOrigemFisemi() == "6") echo " selected " ?> value="6">6ª RM</option>
                            <option <?php if ($obrigatorio->getRmOrigemFisemi() == "7") echo " selected " ?> value="7">7ª RM</option>
                            <option <?php if ($obrigatorio->getRmOrigemFisemi() == "8") echo " selected " ?> value="8">8ª RM</option>
                            <option <?php if ($obrigatorio->getRmOrigemFisemi() == "9") echo " selected " ?> value="9">9ª RM</option>
                            <option <?php if ($obrigatorio->getRmOrigemFisemi() == "10") echo " selected " ?> value="10">10ª RM</option>
                            <option <?php if ($obrigatorio->getRmOrigemFisemi() == "11") echo " selected " ?> value="11">11ª RM</option>
                            <option <?php if ($obrigatorio->getRmOrigemFisemi() == "12") echo " selected " ?> value="12">12ª RM</option>
                        </select>

                    </div>

                    <div class="col-md-4 form-group" id="justica">
                        <b>Região Destino</b>
                        <select name="rm_destino_fisemi" class="form-control">
                            <option value="">Selecione a Região</option>
                            <option <?php if ($obrigatorio->getRmDestinoFisemi() == "1") echo " selected " ?> value="1">1ª RM</option>
                            <option <?php if ($obrigatorio->getRmDestinoFisemi() == "2") echo " selected " ?> value="2">2ª RM</option>
                            <option <?php if ($obrigatorio->getRmDestinoFisemi() == "3") echo " selected " ?> value="3">3ª RM</option>
                            <option <?php if ($obrigatorio->getRmDestinoFisemi() == "4") echo " selected " ?> value="4">4ª RM</option>
                            <option <?php if ($obrigatorio->getRmDestinoFisemi() == "5") echo " selected " ?> value="5">5ª RM</option>
                            <option <?php if ($obrigatorio->getRmDestinoFisemi() == "6") echo " selected " ?> value="6">6ª RM</option>
                            <option <?php if ($obrigatorio->getRmDestinoFisemi() == "7") echo " selected " ?> value="7">7ª RM</option>
                            <option <?php if ($obrigatorio->getRmDestinoFisemi() == "8") echo " selected " ?> value="8">8ª RM</option>
                            <option <?php if ($obrigatorio->getRmDestinoFisemi() == "9") echo " selected " ?> value="9">9ª RM</option>
                            <option <?php if ($obrigatorio->getRmDestinoFisemi() == "10") echo " selected " ?> value="10">10ª RM</option>
                            <option <?php if ($obrigatorio->getRmDestinoFisemi() == "11") echo " selected " ?> value="11">11ª RM</option>
                            <option <?php if ($obrigatorio->getRmDestinoFisemi() == "12") echo " selected " ?> value="12">12ª RM</option>
                        </select>
                    </div>
                </div>
            </div>

            <br>

            <div class="card">
                <div class="row">
                    <div class="text-center text-success">
                        <h3 color="green">OBSERVAÇÕES</h3>
                    </div>
                    <div class="col-md-12 form-group">
                        <textarea placeholder="Observação" name="observacao" rows="5" class="form-control"><?php echo $obrigatorio->getObservacao() ?></textarea>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <br>
                <button type="submit" style="width: 100%;" class="btn btn-primary btn-block">ATUALIZAR</button>
            </div>
            <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave'] . "obrigatorio"); ?>">
        </form>
    </div>
</section>