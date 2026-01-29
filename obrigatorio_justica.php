<section>
    <div class="row justify-content-center">
        <form action="controller/obrigatorio_edita_justica.php" method="post" role="form">
            <input hidden type="text" value="<?php echo $obrigatorio->getId() ?>" name="id_obrigatorio">
            <input hidden type="text" value="<?php echo $crip_url ?>" name="crip_url">
            <input hidden type="text" value="fisemi" name="aba">
            <div class="card">
                <div class="row">
                    <div class="text-center text-success">
                        <h3 color="green">SITUAÇÃO MILITAR</h3>
                    </div>

                    <div class="col-md-12 form-group">
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
                        <h3 color="green">JUSTIÇA</h3>
                    </div>
                    <div class="col-md-3 form-group">
                        <b>Número da Ação</b>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->getNumeroAcao() ?>" name="numero_acao" placeholder="Número da Ação">
                    </div>

                    <div class="col-md-3 form-group">
                        <b>Transitou Julgado?</b>
                        <select name="transitou_julgado" class="form-control" value="<?php echo $obrigatorio->getTransitouJulgado() ?>">
                            <option value="">Transitou Julgado?</option>
                            <option <?php if ($obrigatorio->getTransitouJulgado() == "SIM") echo " selected " ?> value="SIM">SIM</option>
                            <option <?php if ($obrigatorio->getTransitouJulgado() == "NÃO") echo " selected " ?> value="NÃO">NÃO</option>

                        </select>
                    </div>

                    <div class="col-md-2 form-group">
                        <b>Data Liminar</b>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->imprimeDataLiminar() ?>" name="data_liminar" placeholder="Data da Liminar">
                    </div>

                    <div class="col-md-2 form-group">
                        <b>Favorável</b>
                        <select name="favoravel" class="form-control" value="<?php echo $obrigatorio->getFavoravel() ?>">
                            <option value="">Selecione a Opção</option>
                            <option <?php if ($obrigatorio->getFavoravel() == "SIM") echo " selected " ?> value="SIM">SIM</option>
                            <option <?php if ($obrigatorio->getFavoravel() == "NÃO") echo " selected " ?> value="NÃO">NÃO</option>

                        </select>
                    </div>

                    <div class="col-md-2 form-group" id="distribuicao">
                        <b>Convocado</b>
                        <select name="convocado" class="form-control" value="<?php echo $obrigatorio->getConvocado() ?>">
                            <option value="">Selecione a Opção</option>
                            <option <?php if ($obrigatorio->getConvocado() == "SIM") echo " selected " ?> value="SIM">SIM</option>
                            <option <?php if ($obrigatorio->getConvocado() == "NÃO") echo " selected " ?> value="NÃO">NÃO</option>
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