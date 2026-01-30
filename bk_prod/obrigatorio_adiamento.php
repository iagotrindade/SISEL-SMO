<section id="contact" class="contact">
    <div class="row  justify-content-center">
        <form action="controller/obrigatorio_edita_adiamento.php" method="post" role="form">
            <input hidden type="text" value="<?php echo $obrigatorio->getId() ?>" name="id_obrigatorio">
            <input hidden type="text" value="<?php echo $crip_url ?>" name="crip_url">
            <input hidden type="text" value="fisemi" name="aba">
            <div class="row card">
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
            </div>

            <br>

            <div class="card">
                <div class="row">
                    <div class="text-center text-success">
                        <h3 color="green">ADIAMENTO - SMO</h3>
                    </div>
                    <div class="col-md-3 form-group">
                        <b>Solicitou Adiamento?</b>
                        <select name="solicitou_adiamento" class="form-control">
                            <option value="">Selecione a Opção</option>
                            <option <?php if ($obrigatorio->getSolicitouAdiamento() == "SIM") echo " selected " ?> value="SIM">SIM</option>
                            <option <?php if ($obrigatorio->getSolicitouAdiamento() == "NÃO") echo " selected " ?> value="NÃO">NÃO</option>
                        </select>
                    </div>

                    <div class="col-md-3 form-group">
                        <b>Início Adiamento</b>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->imprimeInicioAdiamento() ?>" name="inicio_adiamento" placeholder="Início do Adiamento">
                    </div>

                    <div class="col-md-3 form-group">
                        <b>Fim Adiamento</b>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->imprimeFimAdiamento() ?>" name="fim_adiamento" placeholder="Fim do Adiamento">
                    </div>

                    <div class="col-md-3 form-group" id="situacao_militar">
                        <b>Especialidade Adiamento</b>
                        <select id="opcao" name="especialidade_adiamento" class="form-control" value="<?php echo $obrigatorio->getEspecialidadeAdiamento() ?>">
                            <option value="">Selecione a Especialidade</option>
                            <?php
                            foreach ($todas_espec as $value) {

                                if ($value['nome'] == $obrigatorio->getEspecialidadeAdiamento()) {
                                    echo "<option value='" . $value['nome'] . "' selected>" . $value['nome'] . "</option>";
                                }
                                echo "<option value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
                            }
                            ?>
                            <option value="OUTRA">OUTRA</option>
                        </select>
                    </div>


                    <div id="outra" style="display: none;" class="col-md-4 form-group">
                        <b>Outra Especialidade Adiamento</b>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->getEspecialidadeAdiamento() ?>" name="outra" placeholder="Outra Especialidade">
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