<section>
    <div class="justify-content-center">
        <form action="controller/obrigatorio_edita_identificacao.php" method="post" role="form">
            <input hidden type="text" value="<?php echo $obrigatorio->getId() ?>" name="id_obrigatorio">
            <input hidden type="text" value="<?php echo $crip_url ?>" name="crip_url">
            <input hidden type="text" value="identificacao" name="aba">

            <div class="card">
                <div class="row">
                    <div class="text-center text-success">
                        <h3 color="green">SITUAÇÃO MILITAR</h3>
                    </div>

                    <div class="col-md-12 form-group" id="identificacao">
                        <b>Selecione a Situação</b>
                        <select name="situacao_militar" class="form-control">
                            <option value="">Selecione a Opção</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Em Débito - REFRATÁRIO") echo " selected " ?> value="Em Débito - REFRATÁRIO">Em Débito - REFRATÁRIO</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Em Débito - INSUBMISSO") echo " selected " ?> value="Em Débito - INSUBMISSO">Em Débito - INSUBMISSO</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Em Dia - JUDICIAL") echo " selected " ?> value="Em Dia - JUDICIAL">Em Dia - JUDICIAL</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Em Dia - ALISTADO MFDV (FISEMI)") echo " selected " ?> value="Em Dia - ALISTADO MFDV (FISEMI)">Em Dia - ALISTADO MFDV (FISEMI)</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Em Dia - TRANSFERÊNCIA FISEMI") echo " selected " ?> value="Em Dia - TRANSFERÊNCIA FISEMI">Em Dia - TRANSFERÊNCIA FISEMI</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Em Dia - ADIADO CURSANDO RESIDÊNCIA") echo " selected " ?> value="Em Dia - ADIADO CURSANDO RESIDÊNCIA">Em Dia - ADIADO CURSANDO RESIDÊNCIA</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Em Dia - B1 - INSP SAU - RETORNAR PRÓXIMA SELEÇÃO") echo " selected " ?> value="Em Dia - B1 - INSP SAU - RETORNAR PRÓXIMA SELEÇÃO">Em Dia - B1 - INSP SAU - RETORNAR PRÓXIMA SELEÇÃO</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Em Dia - JUDICIAL LIMINAR") echo " selected " ?> value="Em Dia - JUDICIAL LIMINAR">Em Dia - JUDICIAL LIMINAR</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Quite SMO - EXCESSO - CONTINGENTE") echo " selected " ?> value="Quite SMO - EXCESSO - CONTINGENTE">Quite SMO - EXCESSO - CONTINGENTE</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Quite SMO - EXCESSO - INCAPAZ SAÚDE") echo " selected " ?> value="Quite SMO - EXCESSO - INCAPAZ SAÚDE">Quite SMO - EXCESSO - INCAPAZ SAÚDE</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Quite SMO - DESOBRIGADO - MAIOR 38 ANOS") echo " selected " ?> value="Quite SMO - DESOBRIGADO - MAIOR 38 ANOS">Quite SMO - DESOBRIGADO - MAIOR 38 ANOS</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Quite SMO - DESOBRIGADO - JÁ RESERVISTA") echo " selected " ?> value="Quite SMO - DESOBRIGADO - JÁ RESERVISTA">Quite SMO - DESOBRIGADO - JÁ RESERVISTA</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Quite SMO - DESOBRIGADO - NATURALIZADO") echo " selected " ?> value="Quite SMO - DESOBRIGADO - NATURALIZADO">Quite SMO - DESOBRIGADO - NATURALIZADO</option>
                            <option <?php if ($obrigatorio->getSituacaoMilitar() == "Quite SMO - CONVOCADO") echo " selected " ?> value="Quite SMO - CONVOCADO">Quite SMO - CONVOCADO</option>
                        </select>


                        <b hidden>Data Próxima Apresentação</b>
                        <input hidden type="text" class="form-control" value="<?php echo $obrigatorio->imprimeDataProximaApresentacao() ?>" name="data_proxima_apresentacao">
                    </div>
                </div>
            </div>

            <br>

            <div class="card">
                <div class="row">
                    <div class="text-center text-success">
                        <h3 color="green">IDENTIFICAÇÃO</h3>
                    </div>

                    <div class="col-md-4 form-group">
                        <b>Nome Completo</b>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->getNomeCompleto() ?>" name="nome_completo" placeholder="Nome Completo">
                    </div>

                    <div class="col-md-4 form-group">
                        <b>CPF</b>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->getCPF() ?>" disabled onclick="selectText()" id="cpf" name="" placeholder="CPF">
                    </div>

                    <div class="col-md-4 form-group">
                        <b>Estado Civil</b>
                        <select name="estado_civil" class="form-control">
                            <option value="">Estado Civil</option>
                            <option <?php if ($obrigatorio->getEstadoCivil() == "SOLTEIRO") echo " selected" ?> value="SOLTEIRO">SOLTEIRO</option>
                            <option <?php if ($obrigatorio->getEstadoCivil() == "CASADO") echo " selected" ?> value="CASADO">CASADO</option>
                            <option <?php if ($obrigatorio->getEstadoCivil() == "UNIÃO ESTÁVEL") echo " selected" ?> value="UNIÃO ESTÁVEL">UNIÃO ESTÁVEL</option>
                            <option <?php if ($obrigatorio->getEstadoCivil() == "DIVORCIADO") echo " selected" ?> value="DIVORCIADO">DIVORCIADO</option>
                            <option <?php if ($obrigatorio->getEstadoCivil() == "VIÚVO") echo " selected" ?> value="VIÚVO">VIÚVO</option>
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <b>Data de Nascimento</b>
                        <input type="text" class="form-control" value="<?= $obrigatorio->imprimeDataNascimento() ?>" name="data_nascimento" placeholder="Data de Nascimento">
                    </div>

                    <div class="col-md-4 form-group">
                        <b>Identidade</b>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->getIdentidade() ?>" name="identidade" placeholder="Identidade">
                    </div>

                    <div class="col-md-4 form-group">
                        <b>E-Mail</b>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->getMail() ?>" name="mail" placeholder="E-mail">
                    </div>

                    <div class="col-md-4 form-group">
                        <b>Telefone</b>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->getTelefone() ?>" name="telefone" placeholder="Telefone">
                    </div>

                    <div class="col-md-4 form-group">
                        <b>Nome do Pai</b>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->getNomePai() ?>" name="nome_pai" placeholder="Nome do Pai">
                    </div>
                    <div class="col-md-4 form-group">
                        <b>Nome da Mãe</b>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->getNomeMae() ?>" name="nome_mae" placeholder="Nome da Mãe">
                    </div>

                    <div class="col-md-4 form-group">
                        <b>Voluntário?</b>
                        <select name="voluntario" class="form-control">
                            <option value="">Selecione a Opção</option>
                            <option <?php if ($obrigatorio->getVoluntario() == "SIM") echo " selected " ?> value="SIM">SIM</option>
                            <option <?php if ($obrigatorio->getVoluntario() == "NÃO") echo " selected " ?> value="NÃO">NÃO</option>
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <b>Nacionalidade</b>
                        <select name="nacionalidade" class="form-control">
                            <option value="">Selecione a Opção</option>
                            <option <?php if ($obrigatorio->getNacionalidade() === "BRASILEIRA") echo " selected " ?> value="BRASILEIRA">BRASILEIRA</option>
                            <option <?php if ($obrigatorio->getNacionalidade() === "ESTRANGEIRA") echo " selected " ?> value="ESTRANGEIRA">ESTRANGEIRA</option>
                        </select>
                    </div>

                    <div class="col-md-4 form-group">

                        <b>Naturalidade</b>
                        <select name="naturalidade" class="chosen-select" style="width: 100%" id="emailField">
                            <option value="">Selecione a Opção</option>
                            <?php
                            foreach ($todas_cid as $value) {
                                if ($value['nome'] == $obrigatorio->getNaturalidade())
                                    echo "<option value='" . $value['nome'] . "' selected>" . $value['nome'] . " - " . $value['uf'] . "</option>";
                                else
                                    echo "<option value='" . $value['nome'] . "' >" . $value['nome'] . " - " . $value['uf'] . "</option>";
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
                            for ($i = 0; $i <= 10; $i++) {
                                if ($obrigatorio->getDependentes() == $i)
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
                            <option <?php if ($obrigatorio->getPrioridadeForca() == "EMA") echo " selected " ?>value="EMA">EMA</option>
                            <option <?php if ($obrigatorio->getPrioridadeForca() == "EAM") echo " selected " ?>value="EAM">EAM</option>
                            <option <?php if ($obrigatorio->getPrioridadeForca() == "MAE") echo " selected " ?>value="MAE">MAE</option>
                            <option <?php if ($obrigatorio->getPrioridadeForca() == "MEA") echo " selected " ?>value="MEA">MEA</option>
                            <option <?php if ($obrigatorio->getPrioridadeForca() == "AEM") echo " selected " ?>value="AEM">AEM</option>
                            <option <?php if ($obrigatorio->getPrioridadeForca() == "AME") echo " selected " ?>value="AME">AME</option>
                        </select>
                    </div>
                </div>
            </div>

            <br>

            <div class="card">
                <div class="row">
                    <div class="text-center text-success">
                        <h3 color="green">FORMAÇÃO</h3>
                    </div>
                    <div class="col-md-4 form-group">
                        <b>IE Graduação</b>
                        <select name="nome_instituicao_ensino" class="form-control">
                            <option value="">Nome da Instituição de Ensino</option>
                            <?php
                            foreach ($todas_cid_inst as $value) {
                                if ($value['nome'] == $obrigatorio->getNomeInstitutoEnsino())
                                    echo "<option value='" . $value['nome'] . "' selected>" . $value['nome'] . "</option>";
                                else
                                    echo "<option value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <b>Ano de Formação</b>
                        <select name="ano_formacao" class="form-control">
                            <option value="">Selecione a Opção </option>
                            <?php
                            for ($i = $ano_sete_anos_frente; $i >= 1990; $i--) {
                                if ($i == $obrigatorio->getAnoFormacao())
                                    echo '<option value="' . $i . '" selected>' . $i . '</option>';
                                else
                                    echo '<option value="' . $i . '" >' . $i . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <b>Formação</b>
                        <select name="formacao" class="form-control">
                            <option value="">Selecione a Especialidade</option>
                            <option <?php if ($obrigatorio->getFormacao() == "DENTISTA - Cirurgião Dentista") echo " selected " ?>value="DENTISTA - Cirurgião Dentista">DENTISTA - Cirurgião Dentista</option>
                            <option <?php if ($obrigatorio->getFormacao() == "MÉDICO - Generalista") echo " selected " ?>value="MÉDICO - Generalista">MÉDICO - Generalista</option>
                            <option <?php if ($obrigatorio->getFormacao() == "MÉDICO - Especialista") echo " selected " ?>value="MÉDICO - Especialista">MÉDICO - Especialista</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <b> Especialidade 1 </b>
                        <select name="especialidade_1" class="form-control">
                            <option value="">Selecione a Especialidade</option>
                            <?php
                            foreach ($todas_espec as $value) {
                                if ($value['nome'] == $obrigatorio->getEspecialidade())
                                    echo "<option value='" . $value['nome'] . "' selected>" . $value['nome'] . "</option>";
                                else
                                    echo "<option value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <b> Especialidade 2 </b>
                        <select name="especialidade_2" class="form-control">
                            <option value="">Selecione a Especialidade</option>
                            <?php
                            foreach ($todas_espec as $value) {
                                if ($value['nome'] == $obrigatorio->getEspecialidade2())
                                    echo "<option value='" . $value['nome'] . "' selected>" . $value['nome'] . "</option>";
                                else
                                    echo "<option value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <b> Especialidade 3 </b>
                        <select name="especialidade_3" class="form-control">
                            <option value="">Selecione a Especialidade</option>
                            <?php
                            foreach ($todas_espec as $value) {
                                if ($value['nome'] == $obrigatorio->getEspecialidade3())
                                    echo "<option value='" . $value['nome'] . "' selected>" . $value['nome'] . "</option>";
                                else
                                    echo "<option value='" . $value['nome'] . "' >" . $value['nome'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <b>Ano Especialização 1</b>
                        <select name="ano_residencia_espe_1" class="form-control">
                            <option value="">Ano Especialização 1</option>
                            <?php

                            for ($i = $ano_sete_anos_frente; $i >= 2000; $i--) {
                                if ($i == $obrigatorio->getAnoResEspe1())
                                    echo '<option value="' . $i . '" selected>' . $i . '</option>';
                                else
                                    echo '<option value="' . $i . '" >' . $i . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <b>Ano Especialização 2</b>
                        <select name="ano_residencia_espe_2" class="form-control">
                            <option value="">Ano Especialização 2</option>
                            <?php
                            for ($i = $ano_sete_anos_frente; $i >= 2000; $i--) {
                                if ($i == $obrigatorio->getAnoResEspe2())
                                    echo '<option value="' . $i . '" selected>' . $i . '</option>';
                                else
                                    echo '<option value="' . $i . '" >' . $i . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4 form-group" id="adiamento">
                        <b>Ano Especialização 3</b>
                        <select name="ano_residencia_espe_3" class="form-control">
                            <option value="">Ano Especialização 3</option>
                            <?php
                            for ($i = $ano_sete_anos_frente; $i >= 2000; $i--) {
                                if ($i == $obrigatorio->getAnoResEspe3())
                                    echo '<option value="' . $i . '" selected>' . $i . '</option>';
                                else
                                    echo '<option value="' . $i . '" >' . $i . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <br>

            <div class="card">
                <div class="row">
                    <div class="text-center text-success">
                        <h3 color="green">SELEÇÃO GERAL</h3>
                    </div>

                    <div class="col-md-3 form-group">
                        <b>Data Seleção Geral</b>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->imprimeDataSelecaoGeral() ?>" name="data_selecao_geral" placeholder="Data Seleção Geral">
                    </div>

                    <div class="col-md-3 form-group">
                        <b>Data Inspeção de Saúde</b>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->imprimeDataComparecimentoSelecaoGeral() ?>" name="data_comparecimento_selecao_geral" placeholder="Data Inspeção de Saúde">
                    </div>


                    <div class="col-md-3 form-group">
                        <b>JISE</b>
                        <select name="jise" class="form-control">
                            <option value="">Selecione a Opção</option>
                            <option <?php if ($obrigatorio->getJise() == "A") echo " selected " ?> value="A">A</option>
                            <option <?php if ($obrigatorio->getJise() == "B1") echo " selected " ?> value="B1">B1</option>
                            <option <?php if ($obrigatorio->getJise() == "B2") echo " selected " ?> value="B2">B2</option>
                            <option <?php if ($obrigatorio->getJise() == "C") echo " selected " ?> value="C">C</option>
                        </select>
                    </div>

                    <div class="col-md-3 form-group">
                        <b>CID JISE</b>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->getCidJise() ?>" name="cid_jise" placeholder="CID">
                    </div>

                    <div class="col-md-12 form-group">
                        <b>Observação JISE</b>
                        <textarea placeholder="Observação JISE" name="observacao_jise" class="form-control"><?php echo $obrigatorio->getObservacaoJise() ?></textarea>
                    </div>

                    <div class="col-md-4 form-group">
                        <b>Data JISR</b>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->imprimeDataJisr() ?>" name="data_jisr" placeholder="Data do JISR">
                    </div>

                    <div class="col-md-4 form-group">
                        <b>JISR</b>
                        <select name="jisr" class="form-control">
                            <option value="">Selecione a Opção</option>
                            <option <?php if ($obrigatorio->getJisr() == "A") echo " selected " ?> value="A">A</option>
                            <option <?php if ($obrigatorio->getJisr() == "B1") echo " selected " ?> value="B1">B1</option>
                            <option <?php if ($obrigatorio->getJisr() == "B2") echo " selected " ?> value="B2">B2</option>
                            <option <?php if ($obrigatorio->getJisr() == "C") echo " selected " ?> value="C">C</option>
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <b>CID JISR</b>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->getCidJisr() ?>" name="cid_jisr" placeholder="CID - JISR">
                    </div>

                    <div class="col-md-12 form-group" id="fisemi">
                        <b>Observação JISR</b>
                        <textarea placeholder="Observação JISR" name="obs_jisr" class="form-control"><?php echo $obrigatorio->getObsJisr() ?></textarea>
                    </div>

                    <div class="col-md-4 form-group">
                        <b>JISE A-1</b>
                        <select name="jise_a_1" class="form-control">
                            <option value="">Selecione a Opção</option>
                            <option <?php if ($obrigatorio->getJisea1() == "A") echo " selected " ?> value="A">A</option>
                            <option <?php if ($obrigatorio->getJisea1() == "B1") echo " selected " ?> value="B1">B1</option>
                            <option <?php if ($obrigatorio->getJisea1() == "B2") echo " selected " ?> value="B2">B2</option>
                            <option <?php if ($obrigatorio->getJisea1() == "C") echo " selected " ?> value="C">C</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <b>CID JISE A-1</b>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->getCidJisea1() ?>" name="cid_jise_a_1" placeholder="CID - JISE A-1">
                    </div>
                    <div class="col-md-4 form-group">
                        <b>Data JISE A-1</b>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->imprimeDataJisea1() ?>" name="data_jise_a_1" placeholder="Data do JISE A-1">
                    </div>
                    <div class="col-md-12 form-group" id="fisemi">
                        <b>Observação JISE A-1</b>
                        <textarea placeholder="Observação JISE A-1" name="observacao_jise_a_1" class="form-control"><?php echo $obrigatorio->getObservacaoJisea1() ?></textarea>
                    </div>

                </div>
            </div>

            <br>

            <div class="card">
                <div class="row">
                    <div class="text-center text-success">
                        <h3 color="green">REVISÃO MÉDICA - OM 1ª FASE</h3>
                    </div>
                    <div class="col-md-4 form-group">
                        <b>*Data Revisão Médica (novo)</b>
                        <input type="text" class="form-control" name="data_revisao_medica" value="<?php echo $obrigatorio->imprimeData_revisao_medica() ?>" placeholder="Data Revisão Médica">
                    </div>

                    <div class="col-md-4 form-group">
                        <b>Resultado Revisão Médica </b>
                        <select name="resultado_revisao_medica_complementar" class="form-control">
                            <option value="">Selecione a Opção</option>
                            <option <?php if ($obrigatorio->getResultadoRevisaoMedicaComplementar() == "APTO") echo " selected " ?> value="APTO">APTO</option>
                            <option <?php if ($obrigatorio->getResultadoRevisaoMedicaComplementar() == "INAPTO") echo " selected " ?> value="INAPTO">INAPTO</option>
                            <option <?php if ($obrigatorio->getResultadoRevisaoMedicaComplementar() == "NÃO COMPARECEU") echo " selected " ?> value="NÃO COMPARECEU">NÃO COMPARECEU</option>
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <b>*CID Revisão Médica (novo)</b>
                        <textarea placeholder="CID Revisão Médica" name="cid_revisao_medica" class="form-control"><?php echo $obrigatorio->getCid_revisao_medica() ?></textarea>
                    </div>

                    <div class="col-md-12 form-group">
                        <b>*Observação Revisão Médica (novo)</b>
                        <textarea placeholder="OBS Revisão Médica" name="observacao_revisao_medica" class="form-control"><?php echo $obrigatorio->getObs_revisao_medica() ?></textarea>
                    </div>
                </div>
            </div>

            <br>

            <div class="card">
                <div class="row  justify-content-center">
                    <div class="row form">
                        <div class="text-center text-success">
                            <h3 color="green">IS GRAU REVISIONAL</h3>
                        </div>

                        <div class="col-md-4 form-group">
                            <b>ISGRev</b>
                            <select name="resultado_isgr" class="form-control">
                                <option value="">Selecione a Opção</option>
                                <option <?php if ($obrigatorio->getResultadoIsgr() == "NÃO é o caso") echo " selected " ?> value="NÃO é o caso">NÃO é o caso</option>
                                <option <?php if ($obrigatorio->getResultadoIsgr() == "A") echo " selected " ?> value="A">A</option>
                                <option <?php if ($obrigatorio->getResultadoIsgr() == "B1") echo " selected " ?> value="B1">B1</option>
                                <option <?php if ($obrigatorio->getResultadoIsgr() == "B2") echo " selected " ?> value="B2">B2</option>
                                <option <?php if ($obrigatorio->getResultadoIsgr() == "C") echo " selected " ?> value="C">C</option>
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                            <b>*Data ISGRev </b>
                            <input type="text" class="form-control" name="data_isgr" value="<?php echo $obrigatorio->imprimeData_isgr() ?>" placeholder="Data ISGRev">
                        </div>

                        <div class="col-md-4 form-group">
                            <b>*CID ISGRev </b>
                            <textarea placeholder="CID ISGRev" name="cid_isgr" class="form-control"><?php echo $obrigatorio->getCid_isgr() ?></textarea>
                        </div>

                        <div class="col-md-12 form-group">
                            <b>*Observação ISGRev </b>
                            <textarea placeholder="OBS ISGRev" name="observacao_isgr" class="form-control"><?php echo $obrigatorio->getObservacao_isgr() ?></textarea>
                        </div>
                        <input hidden type="text" value="<?= $id_obrigatorio ?>" name="id_obrigatorio">
                        <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave'] . "obrigatorio"); ?>">
                    </div>

                    <div class="text-center">
                        <br>
                    </div>
                </div>
            </div>

            <div hidden class="card">
                <div class="row">
                    <div class="text-center text-success">
                        <h3 color="green">SITUAÇÃO MILITAR</h3>
                    </div>
                    <div class="col-md-4 form-group">
                        <b>Documento Militar</b>
                        <select name="documento_militar" class="form-control" value="<?php echo $obrigatorio->getDocumentoMilitar() ?>">
                            <option <?php if ($obrigatorio->getDocumentoMilitar() == "Documento Militar") echo " selected " ?> value="">Documento Militar</option>
                            <option <?php if ($obrigatorio->getDocumentoMilitar() == "Certificado de Isenção (CI)") echo " selected " ?>value="Certificado de Isenção (CI)">Certificado de Isenção (CI)</option>
                            <option <?php if ($obrigatorio->getDocumentoMilitar() == "Certificado de Dispensa de Incorporação (CDI)") echo " selected " ?>value="Certificado de Dispensa de Incorporação (CDI)">Certificado de Dispensa de Incorporação (CDI)</option>
                            <option <?php if ($obrigatorio->getDocumentoMilitar() == "Certificado de Alistamento Militar (CAM)") echo " selected " ?>value="Certificado de Alistamento Militar (CAM)">Certificado de Alistamento Militar (CAM)</option>
                            <option <?php if ($obrigatorio->getDocumentoMilitar() == "Certidão de Situação Militar (CSM)") echo " selected " ?>value="Certidão de Situação Militar (CSM)">Certidão de Situação Militar (CSM)</option>
                            <option <?php if ($obrigatorio->getDocumentoMilitar() == "Certificado de Reservista Militar 1ª Categoria (CRM)") echo " selected " ?>value="Certificado de Reservista Militar 1ª Categoria (CRM)">Certificado de Reservista Militar 1ª Categoria (CRM)</option>
                            <option <?php if ($obrigatorio->getDocumentoMilitar() == "Certificado de Reservista Militar 2ª Categoria (CRM)") echo " selected " ?>value="Certificado de Reservista Militar 2ª Categoria (CRM)">Certificado de Reservista Militar 2ª Categoria (CRM)</option>
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <b>Nº Doc Militar</b>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->getNumeroDocumentoMilitar() ?>" name="numero_documento_militar" placeholder="Número do Documento Militar">
                    </div>

                    <div class="col-md-4 form-group">
                        <b>Data de Expedição</b>
                        <input type="text" class="form-control" value="<?php echo $obrigatorio->imprimeDataExpedicao() ?>" name="data_expedicao" placeholder="Data de Expedição">
                    </div>
                    <div class="col-md-4 form-group" id="selecao_geral">
                        <b>Força</b>
                        <select name="forca" class="form-control">
                            <option value="">Força</option>
                            <option <?php if ($obrigatorio->getForca() == "EXÉRCITO") echo " selected " ?> value="EXÉRCITO">EXÉRCITO</option>
                            <option <?php if ($obrigatorio->getForca() == "MARINHA") echo " selected " ?> value="MARINHA">MARINHA</option>
                            <option <?php if ($obrigatorio->getForca() == "AERONÁUTICA") echo " selected " ?> value="AERONÁUTICA">AERONÁUTICA</option>
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

        <br>

        <a name='prioridades'></a>

        <div class="card">
            <form action="controller/prioridade_guarnicao_cadastra.php" method="post" role="form">
                <div class="text-center text-success">
                    <h3 color="green">PRIORIDADE DAS CIDADES</h3>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group p-0">
                        <select id="combobox" name="id_guarnicao" class="form-control">
                            <option value="nomegu">Selecione a Guarnição</option>
                            <?php
                            foreach ($todas_gu as $guarnicao) {
                                $imprimegu = true;
                                if ($prioridade_guarnicao)
                                    foreach ($prioridade_guarnicao as $value) {
                                        if ($value['id_guarnicao'] == $guarnicao['id']) {
                                            $imprimegu = false;
                                            break;
                                        }
                                    }
                                if ($imprimegu)
                                    echo "<option value='" . $guarnicao['id'] . "' >" . $guarnicao['nome'] . "</option>";
                            }
                            ?>
                        </select>
                        <input hidden id="nomegu" name="nomegu">
                        <script>
                            // Captura o evento de alteração da combobox
                            document.getElementById('combobox').addEventListener('change', function() {
                                // Obtém o valor e o texto da opção selecionada
                                var selectedOption = this.options[this.selectedIndex];
                                var optionValue = selectedOption.value;
                                var optionText = selectedOption.text;
                                // Exibe o valor selecionado na div
                                document.getElementById('nomegu').value = optionText;
                            });
                        </script>
                    </div>

                    <input hidden type="text" value="<?= $id_obrigatorio ?>" name="id_obrigatorio">
                    <input name="crip" hidden value="<?php echo  hash('sha256', $_SESSION['chave'] . "obrigatorio"); ?>">

                    <button type="submit" style="width: 100%;" class="btn btn-primary btn-block">DEFINIR</button>
                </div>
            </form>

            <form action="controller/prioridade_guarnicao_apaga.php" method="post" role="form" name="nomegu">
                <input hidden type="text" value="<?= $id_obrigatorio ?>" name="id_obrigatorio">
               
                <div class="row">
                    <div class="col-md-12 form-group">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <b>
                                    <?php
                                    if ($prioridade_guarnicao) {
                                        foreach ($prioridade_guarnicao as $value) {
                                            echo $value['prioridade'] . "ª ";
                                            echo $value['guarnicao'];
                                            echo "<br>";
                                            $nomegu = $value['guarnicao'];
                                        }
                                    } else echo "Nenhuma Prioridade cadastrada";
                                    ?>
                                </b>
                            </div>
                            <div class="col-md-6 form-group">
                                <div class="text-center"><button class="bg-danger" type="submit" <?php if ($usuario->getPerfil() == 'operador') echo 'hidden' ?> onclick="return confirm('Tem certeza que deseja apagar a prioridade de cidades?')">Apagar</button></div>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</section>