<section id="contact" class="contact">
    <div class="row  justify-content-center">
        <form action="controller/obrigatorio_edita_distribuicao.php" method="post" role="form">
            <input hidden type="text" value="<?php echo $obrigatorio->getId() ?>" name="id_obrigatorio">
            <input hidden type="text" value="<?php echo $crip_url ?>" name="crip_url">
            <input hidden type="text" value="distribuição" name="aba">
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
                        <h3 color="green">DISTRIBUIÇÃO</h3>
                    </div>
                    <div class="col-md-4 form-group">
                        <b>Compareceu Designação?</b>
                        <select name="compareceu_designacao" class="form-control">
                            <option value="">Selecione a Opção</option>
                            <option <?php if ($obrigatorio->getCompareceuDesignacao() == "sim") echo " selected " ?> value="sim">Sim</option>
                            <option <?php if ($obrigatorio->getCompareceuDesignacao() == "nao") echo " selected " ?> value="nao">Não</option>
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <b>Local Comparec Designação</b>
                        <select name="local_compareceu_designacao" class="form-control">
                            <option value="">Selecione a Opção</option>
                            <?php
                            foreach ($todas_oms_1_fase as $value) {
                                if ($value['nome'] == $obrigatorio->getLocalCompareceuDesignacao())
                                    echo "<option value='" . $value['nome'] . "' selected>" . $value['abreviatura'] . "</option>";
                                else
                                    echo "<option value='" . $value['nome'] . "' >" . $value['abreviatura'] . "</option>";
                            }
                            ?>

                            <?php foreach ($todas_cid_inst as $value) {
                                if ($value['nome'] == $obrigatorio->getLocalCompareceuDesignacao())
                                    echo "<option value='" . $value['nome'] . "' selected>" . $value['nome'] . "</option>";
                                else
                                    echo "<option value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <b>Data Comparec Designação</b>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->imprimeDataComparecimentoDesignacao() ?>" name="data_comparecimento_designacao" placeholder="Data do Comparecimento da Designação">
                    </div>

                    <div class="col-md-4 form-group">
                        <b>Distribuição</b>
                        <select name="distribuicao" class="form-control">
                            <option value="">Selecione a Opção</option>
                            <option <?php if ($obrigatorio->getDistribuicao() == "DESIGNADO - 1ª Distribuição") echo " selected " ?> value="DESIGNADO - 1ª Distribuição">DESIGNADO - 1ª Distribuição</option>
                            <option <?php if ($obrigatorio->getDistribuicao() == "DESIGNADO - 2ª Distribuição") echo " selected " ?> value="DESIGNADO - 2ª Distribuição">DESIGNADO - 2ª Distribuição</option>
                            <option <?php if ($obrigatorio->getDistribuicao() == "MAJORADO - 1ª Distribuição") echo " selected " ?> value="MAJORADO - 1ª Distribuição">MAJORADO - 1ª Distribuição</option>
                            <option <?php if ($obrigatorio->getDistribuicao() == "MAJORADO - 2ª Distribuição") echo " selected " ?> value="MAJORADO - 2ª Distribuição">MAJORADO - 2ª Distribuição</option>
                            <option <?php if ($obrigatorio->getDistribuicao() == "EXCESSO CONTINGENTE") echo " selected " ?> value="EXCESSO CONTINGENTE">EXCESSO CONTINGENTE</option>
                            <option <?php if ($obrigatorio->getDistribuicao() == "MARINHA") echo " selected " ?> value="MARINHA">MARINHA</option>
                            <option <?php if ($obrigatorio->getDistribuicao() == "FORÇA AÉREA") echo " selected " ?> value="FORÇA AÉREA">FORÇA AÉREA</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <b>OM 1ª Fase</b>
                        <select name="id_om_1_fase" class="form-control">
                            <option value="">Selecione a Opção</option>
                            <?php
                            foreach ($todas_oms_1_fase as $value) {
                                if ($value['nome'] == $obrigatorio->getOm1Fase()->getNome())
                                    echo "<option value='" . $value['id'] . "' selected>" . $value['abreviatura'] . "</option>";
                                else
                                    echo "<option value='" . $value['id'] . "' >" . $value['abreviatura'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <b>Data Seleção Complementar</b>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->imprimeDataSelecaoComplementar() ?>" name="data_selecao_complementar" placeholder="Data Seleção Complementar">
                    </div>
                </div>
            </div>

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