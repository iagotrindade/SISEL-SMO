

<?php


class Obrigatorio
{
    private $id;
    private $id_om;
    private $nome_completo;
    private $cpf;
    private $telefone;
    private $mail;
    private $estado_civil;
    private $data_nascimento;
    private $nome_pai;
    private $nome_mae;
    private $nacionalidade;
    private $naturalidade;
    private $identidade;
    private $dependentes;
    private $endereco;
    private $prioridade_forca;
    private $voluntario;
    private $documento_militar;
    private $numero_documento_militar;
    private $data_expedicao;
    private $forca;
    private $nome_instituicao_ensino;
    private $ano_formacao;
    private $formacao;
    private $ano_residencia_espe_1;
    private $ano_residencia_espe_2;
    private $ano_residencia_espe_3;
    private $cidade_instituicao_ensino;
    private $jise;
    private $cid_jise;
    private $data_exame_medico;
    private $observacao_jise;
    private $jisr;
    private $cid_jisr;
    private $data_jisr;
    private $jise_a_1;
    private $cid_jise_a_1;
    private $data_jise_a_1;
    private $observacao_jise_a_1;
    private $data_selecao_geral;
    private $data_comparecimento_selecao_geral;
    private $data_comparecimento_designacao;
    private $data_proxima_apresentacao;
    private $situacao_militar;
    private $solicitou_adiamento;
    private $inicio_adiamento;
    private $fim_adiamento;
    private $especialidade_adiamento;
    private $transferencia_fisemi;
    private $rm_origem_fisemi;
    private $rm_destino_fisemi;
    private $numero_acao;
    private $transitou_julgado;
    private $data_liminar;
    private $favoravel;
    private $convocado;
    private $distribuicao;
    private $om_1_fase;
    private $compareceu_designacao;
    private $local_compareceu_designacao;
    private $data_selecao_complementar;
    private $resultado_revisao_medica_complementar;
    private $resultado_isgr;
    private $data_incorporacao;
    private $om_2_fase;
    private $observacao;
    private $especialidade_1;
    private $especialidade_2;
    private $especialidade_3;
    private $apagado;
    private $data_cadastro;
    private $usuario_ultima_atualizacao;
    private $data_ultima_atualizacao;
    private $prioridade_gu;
    private $data_revisao_medica;
    private $cid_revisao_medica;
    private $obs_revisao_medica;
    private $incorporacao;
    private $bar_om_1_fase;
    private $data_isgr;
    private $cid_isgr;
    private $observacao_isgr;


    public function __construct($cpf)
    {
        if (!empty($cpf)) {
            $this->setCpf($cpf);
            return $cpf;
        }
    }

    //////////////////////////////////////////
    // SETs
    //////////////////////////////////////////

    public function setId($id)
    {
        if ((int)$id > 0)
            $this->id = $id;
        else
            return null;
    }

    public function setIdOm($id_om)
    {
        if ((int)$id_om > 0)
            $this->id_om = (int)$id_om;
        else
            return 0;
    }

    public function setCpf($cpf)
    {
        if (!empty($cpf))
            $this->cpf = ($cpf);
        else
            return null;
    }

    public function setNomeCompleto($nome_completo)
    {
        if (!empty($nome_completo))
            $this->nome_completo = ucwords($nome_completo);
        else
            return null;
    }

    public function setIdentidade($identidade)
    {
        if (!empty($identidade))
            $this->identidade = ($identidade);
    }

    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    public function setMail($mail)
    {
        $this->mail = $mail;
    }

    public function setEstadoCivil($estado_civil)
    {
        if ($this->estado_civil == "" || $this->estado_civil  == "SOLTEIRO" || $this->estado_civil  == "CASADO" || $this->estado_civil  == "UNIÃO ESTÁVEL" || $this->estado_civil  == "DIVORCIADO" ||  $this->estado_civil  == "VÍUVO")
            $this->estado_civil = $estado_civil;
        else throw new Exception("O Estado Civil está Incorreto");
    }

    public function setDataNascimento($data_nascimento)
    {
        if ($data_nascimento == null || valida_data($data_nascimento))
            $this->data_nascimento = $data_nascimento;
        else
            throw new Exception("A Data Nascimento é inválida");
    }

    public function setNomePai($nome_pai)
    {
        $this->nome_pai = $nome_pai;
    }

    public function setNomeMae($nome_mae)
    {
        $this->nome_mae = $nome_mae;
    }

    public function setNacionalidade($nacionalidade)
    {
        if ($nacionalidade == "" || $nacionalidade == "BRASILEIRA" || $nacionalidade == "ESTRANGEIRA")
            $this->nacionalidade = $nacionalidade;
        else throw new Exception("A Nacionalidade está incorreta");
    }

    public function setNaturalidade($naturalidade)
    {
        $this->naturalidade = $naturalidade;
    }

    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;
    }

    public function setDependentes($dependentes)
    {
        if (
            $dependentes == "" || $dependentes == "0" || $dependentes == "1" || $dependentes == "2" || $dependentes == "3" || $dependentes == "4" || $dependentes == "5" ||
            $dependentes == "6" || $dependentes == "7" || $dependentes == "8" || $dependentes == "9" || $dependentes == "10"
        )
            $this->dependentes = $dependentes;
        else throw new Exception("O Campo Dependentes está incorreto");
    }

    public function setPrioridadeForca($prioridade_forca)
    {
        if (
            $prioridade_forca == "" || $prioridade_forca == "EMA" || $prioridade_forca == "EAM" || $prioridade_forca == "MAE" || $prioridade_forca == "MEA" ||
            $prioridade_forca == "AEM" || $prioridade_forca == "AME"
        )
            $this->prioridade_forca = $prioridade_forca;
        else throw new Exception("O Campo Prioridades Força está incorreto");
    }


    public function setVoluntario($voluntario)
    {
        if ($voluntario == "SIM" || $voluntario == "NÃO" || $voluntario == "")
            $this->voluntario = $voluntario;
        else throw new Exception("O campo voluntário está incorreto");
    }

    public function setDocumentoMilitar($documento_militar)
    {
        if (
            $documento_militar == "" ||
            $documento_militar == "Certificado de Isenção (CI)" ||
            $documento_militar == "Certificado de Dispensa de Incorporação (CDI)" ||
            $documento_militar == "Certificado de Alistamento Militar (CAM)" ||
            $documento_militar == "Certidão de Situação Militar (CSM)" ||
            $documento_militar == "Certificado de Reservista Militar 1ª Categoria (CRM)" ||
            $documento_militar == "Certificado de Reservista Militar 2ª Categoria (CRM)"
        )
            $this->documento_militar = $documento_militar;
        else throw new Exception("O tipo de Documento está incorreto");
    }

    public function setDataExpedicao($data_expedicao)
    {
        $this->data_expedicao = $data_expedicao;
    }

    public function setForca($forca)
    {
        if ($forca == "" || $forca == "MARINHA" || $forca == "EXÉRCITO" || $forca == "AERONÁUTICA")
            $this->forca = $forca;
        else throw new Exception("A Força está incorreta");
    }

    public function setNumeroDocumentoMilitar($numero_documento_militar)
    {
        $this->numero_documento_militar = $numero_documento_militar;
    }

    public function setNomeInstitutoEnsino($nome_instituicao_ensino)
    {
        $this->nome_instituicao_ensino = $nome_instituicao_ensino;
    }

    public function setAnoFormacao($ano_formacao)
    {
        $ano_atual = intval(date("Y")) + 7;
        if ($ano_formacao >= 2010 || $ano_formacao <= $ano_atual)
            $this->ano_formacao = $ano_formacao;
        else
            throw new Exception("O Ano de formação está incorreto");
    }

    public function setFormacao($formacao)
    {
        $this->formacao = $formacao;
    }


    public function setAnoResEspe1($ano_residencia_espe_1)
    {
        $ano_atual = intval(date("Y")) + "7";
        if (($ano_residencia_espe_1 >= 2010 && $ano_residencia_espe_1 <= $ano_atual) || $ano_residencia_espe_1 == null)
            $this->ano_residencia_espe_1 = $ano_residencia_espe_1;
        else
            throw new Exception("O Ano de Residência 1 está incorreto");
    }

    public function setAnoResEspe2($ano_residencia_espe_2)
    {
        $ano_atual = intval(date("Y")) + "7";
        if (($ano_residencia_espe_2 >= 2010 && $ano_residencia_espe_2 <= $ano_atual) || $ano_residencia_espe_2 == null)
            $this->ano_residencia_espe_2 = $ano_residencia_espe_2;
        else throw new Exception("O Ano de Res 2 está incorreto");
    }

    public function setAnoResEspe3($ano_residencia_espe_3)
    {
        $ano_atual = intval(date("Y")) + "7";
        if ($ano_residencia_espe_3 >= 2010 && $ano_residencia_espe_3 <= $ano_atual)
            $this->ano_residencia_espe_3 = $ano_residencia_espe_3;
        // else throw new Exception("O Ano de Res 3 está incorreto");
    }


    public function setObsJisr($obs_jisr)
    {
        $this->obs_jisr = $obs_jisr;
    }


    public function setCidadeInstituicaoEnsino($cidade_instituicao_ensino)
    {
        if (
            $cidade_instituicao_ensino == "" ||
            $cidade_instituicao_ensino == "FURG - RIO GRANDE" ||
            $cidade_instituicao_ensino == "UFPEL - PELOTAS" ||
            $cidade_instituicao_ensino == "UCPEL - PELOTAS" ||
            $cidade_instituicao_ensino == "UPF - PASSO FUNDO" ||
            $cidade_instituicao_ensino == "UFFS - PASSO FUNDO" ||
            $cidade_instituicao_ensino == "ATITUS - PASSO FUNDO (IMED)" ||
            $cidade_instituicao_ensino == "UCS - CAXIAS DO SUL" ||
            $cidade_instituicao_ensino == "UNIVATES - LAJEADO" ||
            $cidade_instituicao_ensino == "UFCSPA - PORTO ALEGRE" ||
            $cidade_instituicao_ensino == "ULBRA - CANOAS" ||
            $cidade_instituicao_ensino == "PUCRS - PORTO ALEGRE" ||
            $cidade_instituicao_ensino == "UFRGS - PORTO ALEGRE" ||
            $cidade_instituicao_ensino == "UNISINOS - SÃO LEOPOLDO" ||
            $cidade_instituicao_ensino == "UNIPAMPA - URUGUAIANA" ||
            $cidade_instituicao_ensino == "UFSM - SANTA MARIA" ||
            $cidade_instituicao_ensino == "UFN - SANTA MARIA" ||
            $cidade_instituicao_ensino == "UNISC - SANTA CRUZ DO SUL" ||
            $cidade_instituicao_ensino == "URI - ERECHIM" ||
            $cidade_instituicao_ensino == "FEEVALE - NOVO HAMBURGO" ||
            $cidade_instituicao_ensino == "UNIJUÍ - IJUÍ" ||
            $cidade_instituicao_ensino == "GRADUADO EM FACULDADE FORA RS"
        )
            $this->cidade_instituicao_ensino = $cidade_instituicao_ensino;
        else throw new Exception("A IE está incorreta");
    }


    public function setEspecialidade($especialidade_1)
    {
        $this->especialidade_1 = $especialidade_1;
    }

    public function setEspecialidade2($especialidade_2)
    {
        $this->especialidade_2 = $especialidade_2;
    }

    public function setEspecialidade3($especialidade_3)
    {
        $this->especialidade_3 = $especialidade_3;
    }

    public function setJise($jise)
    {
        if ($jise == "" || $jise == "A" || $jise == "B1" || $jise == "B2" || $jise == "C")
            $this->jise = $jise;
        else throw new Exception("O JISE está incorreto");
    }

    public function setCidJise($cid_jise)
    {
        $this->cid_jise = $cid_jise;
    }

    public function setDataJisr($data_jisr)
    {
        $this->data_jisr = $data_jisr;
    }


    public function setObservacaoJise($observacao_jise)
    {
        $this->observacao_jise = $observacao_jise;
    }

    public function setJisea1($jise_a_1)
    {
        if ($jise_a_1 == "" || $jise_a_1 == "A" || $jise_a_1 == "B1" || $jise_a_1 == "B2" || $jise_a_1 == "C")
            $this->jise_a_1 = $jise_a_1;
        else throw new Exception("O JISE A-1 está incorreto");
    }

    public function setCidJisea1($cid_jise_a_1)
    {
        $this->cid_jise_a_1 = $cid_jise_a_1;
    }

    public function setDataJisea1($data_jise_a_1)
    {
        $this->data_jise_a_1 = $data_jise_a_1;
    }

    public function setObservacaoJisea1($observacao_jise_a_1)
    {
        $this->observacao_jise_a_1 = $observacao_jise_a_1;
    }


    public function setJisr($jisr)
    {
        if ($jisr == "" || $jisr == "A" || $jisr == "B1" || $jisr == "B2" || $jisr == "C")
            $this->jisr = $jisr;
        else throw new Exception("O JISE está incorreto");
    }

    public function setCidJisr($cid_jisr)
    {
        $this->cid_jisr = $cid_jisr;
    }

    public function setDataSelecaoGeral($data_selecao_geral)
    {
        $this->data_selecao_geral = $data_selecao_geral;
    }

    public function setDataComparecimentoSelecaoGeral($data_comparecimento_selecao_geral)
    {
        $this->data_comparecimento_selecao_geral = $data_comparecimento_selecao_geral;
    }

    public function setDataComparecimentoDesignacao($data_comparecimento_designacao)
    {
        $this->data_comparecimento_designacao = $data_comparecimento_designacao;
    }

    public function setDataProximaApresentacao($data_proxima_apresentacao)
    {
        $this->data_proxima_apresentacao = $data_proxima_apresentacao;
    }

    public function setDataUltimaAtualizacao($data_ultima_atualizacao)
    {
        if (!empty($data_ultima_atualizacao))
            $this->data_ultima_atualizacao = $data_ultima_atualizacao;
        else
            return null;
    }

    public function setSituacaoMilitar($situacao_militar)
    {

        if (
            $situacao_militar == "" ||
            $situacao_militar == "Em Débito - REFRATÁRIO" ||
            $situacao_militar == "Em Débito - INSUBMISSO" ||
            $situacao_militar == "Em Dia - JUDICIAL" ||
            $situacao_militar == "Em Dia - ALISTADO MFDV (FISEMI)" ||
            $situacao_militar == "Em Dia - TRANSFERÊNCIA FISEMI" ||
            $situacao_militar == "Em Dia - ADIADO CURSANDO RESIDÊNCIA" ||
            $situacao_militar == "Em Dia - B1 - INSP SAU - RETORNAR PRÓXIMA SELEÇÃO" ||
            $situacao_militar == "Em Dia - LIMINAR JUDICIAL" ||
            $situacao_militar == "Quite SMO - EXCESSO - CONTINGENTE" ||
            $situacao_militar == "Quite SMO - EXCESSO - INCAPAZ SAÚDE" ||
            $situacao_militar == "Quite SMO - DESOBRIGADO - MAIOR 38 ANOS" ||
            $situacao_militar == "Quite SMO - DESOBRIGADO - JÁ RESERVISTA" ||
            $situacao_militar == "Quite SMO - DESOBRIGADO - NATURALIZADO" ||
            $situacao_militar == "Quite SMO - CONVOCADO"
        )
            $this->situacao_militar = $situacao_militar;
        else throw new Exception("O Campo Situação Militar está Incorreto");
    }

    public function setSolicitouAdiamento($solicitou_adiamento)
    {
        if ($solicitou_adiamento == "" || $solicitou_adiamento == "SIM" || $solicitou_adiamento == "NÃO")
            $this->solicitou_adiamento = $solicitou_adiamento;
        else throw new Exception("O Adiamento está Incorreto");
    }

    public function setInicioAdiamento($inicio_adiamento)
    {
        $this->inicio_adiamento = $inicio_adiamento;
    }

    public function setFimAdiamento($fim_adiamento)
    {
        $this->fim_adiamento = $fim_adiamento;
    }

    public function setEspecialidadeAdiamento($especialidade_adiamento)
    {
        $this->especialidade_adiamento = $especialidade_adiamento;
    }

    public function setTransferenciaFisemi($transferencia_fisemi)
    {

        if ($transferencia_fisemi == "SIM" || $transferencia_fisemi == "NÃO" || $transferencia_fisemi == "")
            $this->transferencia_fisemi = $transferencia_fisemi;
        else throw new Exception("Erro transf");
    }

    public function setRmOrigemFisemi($rm_origem_fisemi)
    {
        if (
            $rm_origem_fisemi == "" || $rm_origem_fisemi == "1" || $rm_origem_fisemi == "2" || $rm_origem_fisemi == "3" ||
            $rm_origem_fisemi == "4" || $rm_origem_fisemi == "5" || $rm_origem_fisemi == "6" || $rm_origem_fisemi == "7" ||
            $rm_origem_fisemi == "8" || $rm_origem_fisemi == "9" || $rm_origem_fisemi == "10" ||  $rm_origem_fisemi == "11" ||
            $rm_origem_fisemi == "12"
        )
            $this->rm_origem_fisemi = $rm_origem_fisemi;
        // else throw new Exception("Erro RM Origem Fisemi");
    }

    public function setRmDestinoFisemi($rm_destino_fisemi)
    {
        if (
            $rm_destino_fisemi == "" || $rm_destino_fisemi == "1" || $rm_destino_fisemi == "2" || $rm_destino_fisemi == "3" ||
            $rm_destino_fisemi == "4" || $rm_destino_fisemi == "5" || $rm_destino_fisemi == "6" || $rm_destino_fisemi == "7" ||
            $rm_destino_fisemi == "8" || $rm_destino_fisemi == "9" || $rm_destino_fisemi == "10" ||  $rm_destino_fisemi == "11" ||
            $rm_destino_fisemi == "12"
        )
            $this->rm_destino_fisemi = $rm_destino_fisemi;
        //   else throw new Exception("Erro RM Destino Fisemi");

    }

    public function setNumeroAcao($numero_acao)
    {
        $this->numero_acao = $numero_acao;
    }

    public function setTransitouJulgado($transitou_julgado)
    {
        if ($transitou_julgado == "" || $transitou_julgado == "SIM" || $transitou_julgado == "NÃO")
            $this->transitou_julgado = $transitou_julgado;
        else throw new Exception("Erro Transitou Julgado");
    }

    public function setDataLiminar($data_liminar)
    {
        $this->data_liminar = $data_liminar;
    }


    public function setFavoravel($favoravel)
    {
        if ($favoravel == "" || $favoravel == "SIM" || $favoravel == "NÃO")
            $this->favoravel = $favoravel;
        else throw new Exception("Erro Favorável");
    }

    public function setConvocado($convocado)
    {
        if ($convocado == "" || $convocado == "SIM" || $convocado == "NÃO")
            $this->convocado = $convocado;
        else throw new Exception("Erro Convocado");
    }

    public function setDistribuicao($distribuicao)
    {
        if (
            $distribuicao == "" || $distribuicao == "DESIGNADO - 1ª Distribuição" || $distribuicao == "DESIGNADO - 2ª Distribuição" || $distribuicao == "MAJORADO - 1ª Distribuição" ||
            $distribuicao == "MAJORADO - 2ª Distribuição" || $distribuicao == "EXCESSO CONTINGENTE" || $distribuicao == "MARINHA" ||
            $distribuicao == "FORÇA AÉREA"
        )
            $this->distribuicao = $distribuicao;
        else throw new Exception("Erro Distribuição");
    }

    public function setOm1Fase($om_1_fase)
    {
        $this->om_1_fase = $om_1_fase;
    }

    public function setCompareceuDesignacao($compareceu_designacao)
    {
        $this->compareceu_designacao = $compareceu_designacao;
    }

    public function setLocalCompareceuDesignacao($local_compareceu_designacao)
    {
        $this->local_compareceu_designacao = $local_compareceu_designacao;
    }

    public function setDataSelecaoComplementar($data_selecao_complementar)
    {
        $this->data_selecao_complementar = $data_selecao_complementar;
    }

    public function setResultadoRevisaoMedicaComplementar($resultado_revisao_medica_complementar)
    {
        if (
            $resultado_revisao_medica_complementar == "" || $resultado_revisao_medica_complementar == "APTO" || $resultado_revisao_medica_complementar == "INAPTO" ||
            $resultado_revisao_medica_complementar == "NÃO COMPARECEU"
        )
            $this->resultado_revisao_medica_complementar = $resultado_revisao_medica_complementar;
        else throw new Exception("Erro Resultado Rev Med Complementar");
    }

    public function setResultadoIsgr($resultado_isgr)
    {
        if ($resultado_isgr == "" || $resultado_isgr == "NÃO é o caso" || $resultado_isgr == "A" || $resultado_isgr == "B1" || $resultado_isgr == "B2" || $resultado_isgr == "C")
            $this->resultado_isgr = $resultado_isgr;
        else throw new Exception("Erro Resultado ISGR");
    }

    public function setDataIncorporacao($data_incorporacao)
    {
        $this->data_incorporacao = $data_incorporacao;
    }

    public function setOm2Fase($om_2_fase)
    {
        $this->om_2_fase = $om_2_fase;
    }

    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
    }

    public function setApagado($apagado)
    {
        if ($apagado == 1)
            $this->apagado = 1;
        else if ($apagado == 0)
            $this->apagado = 0;
        else
            return null;
    }

    public function setDataCadastro($data_cadastro)
    {
        if (!empty($data_cadastro))
            $this->data_cadastro = $data_cadastro;
        else
            return false;
    }

    public function setUsuarioUltimaAtualizacao($usuario_ultima_atualizacao)
    {
        if (!empty($usuario_ultima_atualizacao))
            $this->usuario_ultima_atualizacao = $usuario_ultima_atualizacao;
        else
            return false;
    }


    //////////////////////////////////////////
    // GETs
    //////////////////////////////////////////

    public function getId()
    {
        if ((int)$this->id > 0)
            return $this->id;
        else
            return 0;
    }

    public function getIdOm()
    {
        if (!empty($this->id_om))
            return $this->id_om;
        else
            return 0;
    }

    public function getNomeCompleto()
    {
        if (!empty($this->nome_completo))
            return $this->nome_completo;
        else
            return null;
    }


    public function getCpf()
    {
        if (!empty($this->cpf))
            return $this->cpf;
        else
            return null;
    }


    public function getIdentidade()
    {
        if (!empty($this->identidade))
            return $this->identidade;
        else
            return null;
    }


    public function getTelefone()
    {
        if (!empty($this->telefone))
            return $this->telefone;
        else
            return null;
    }


    public function getMail()
    {
        if (!empty($this->mail))
            return $this->mail;
        else
            return null;
    }

    public function getEstadoCivil()
    {
        if ($this->estado_civil)
            return $this->estado_civil;
        else return null;
    }

    public function imprimeDataNascimento()
    {
        if (!empty($this->data_nascimento))
            return trata_data($this->data_nascimento);
        else
            return null;
    }

    public function imprimeData_isgr()
    {
        if (!empty($this->data_isgr))
            return trata_data($this->data_isgr);
        else
            return null;
    }


    public function getDataNascimento()
    {

        if (!empty($this->data_nascimento))
            return $this->data_nascimento;
        else
            return null;
    }

    public function getDataIncorporacao()
    {
        if (!empty($this->data_incorporacao))
            return $this->data_incorporacao;
        else
            return null;
    }

    public function imprimeDataIncorporacao()
    {
        if (!empty($this->data_incorporacao))
            return trata_data($this->data_incorporacao);
        else
            return null;
    }

    public function getNomePai()
    {
        if (!empty($this->nome_pai))
            return $this->nome_pai;
        else
            return null;
    }

    public function getNomeMae()
    {
        if (!empty($this->nome_mae))
            return $this->nome_mae;
        else
            return null;
    }

    public function getNacionalidade()
    {
        if (!empty($this->nacionalidade))
            return $this->nacionalidade;
        else
            return null;
    }

    public function getNaturalidade()
    {
        if (!empty($this->naturalidade))
            return $this->naturalidade;
        else
            return null;
    }

    public function getEndereco()
    {
        if (!empty($this->endereco))
            return $this->endereco;
        else
            return null;
    }

    public function getDependentes()
    {
        if (
            $this->dependentes == "" || $this->dependentes == "0" || $this->dependentes == "1" || $this->dependentes == "2" || $this->dependentes == "3" ||
            $this->dependentes == "4" || $this->dependentes == "5" || $this->dependentes == "6" || $this->dependentes == "7" || $this->dependentes == "8" ||
            $this->dependentes == "9" || $this->dependentes == "10"
        )
            return $this->dependentes;
        else return null;
    }

    public function getPrioridadeForca()
    {
        if (!empty($this->prioridade_forca))
            return $this->prioridade_forca;
        else
            return null;
    }

    public function getVoluntario()
    {
        if ($this->voluntario == "SIM" || $this->voluntario == "NÃO" || $this->voluntario == "")

            return $this->voluntario;
    }


    public function getImprimeTransitouJulgado()
    {
        if ($this->transitou_julgado == "0" || $this->transitou_julgado == "1") {
            if ($this->transitou_julgado == 1)
                return 'Sim';
            else

                return 'Não';
        }


        return null;
    }

    public function getImprimeFavoravel()
    {
        if ($this->favoravel == "0" || $this->favoravel == "1") {
            if ($this->favoravel == 1)
                return 'Sim';
            else

                return 'Não';
        }
        return null;
    }

    public function getImprimeConvocado()
    {
        if ($this->convocado == "0" || $this->convocado == "1") {
            if ($this->convocado == 1)
                return 'Sim';
            else
                return 'Não';
        }
        return null;
    }

    public function getDocumentoMilitar()
    {
        return $this->documento_militar;
    }

    public function getDataExpedicao()
    {
        if (!empty($this->data_expedicao))
            return $this->data_expedicao;
        else
            return null;
    }

    public function imprimeDataExpedicao()
    {
        if (!empty($this->data_expedicao))
            return trata_data($this->data_expedicao);
        else
            return null;
    }


    public function getForca()
    {
        if ($this->forca == "" || $this->forca == "MARINHA" || $this->forca == "EXÉRCITO" || $this->forca == "AERONÁUTICA")
            return $this->forca;
    }


    public function getNumeroDocumentoMilitar()
    {
        if (!empty($this->numero_documento_militar))
            return $this->numero_documento_militar;
        else return null;
    }

    public function getNomeInstitutoEnsino()
    {
        if (!empty($this->nome_instituicao_ensino))
            return $this->nome_instituicao_ensino;
        else return null;
    }

    public function getAnoFormacao()
    {
        return $this->ano_formacao;
    }

    public function getFormacao()
    {
        return $this->formacao;
    }

    public function getCidadeInstituicaoEnsino()
    {
        if (
            $this->cidade_instituicao_ensino == "" ||
            $this->cidade_instituicao_ensino == "FURG - RIO GRANDE" ||
            $this->cidade_instituicao_ensino == "UFPEL - PELOTAS" ||
            $this->cidade_instituicao_ensino == "UCPEL - PELOTAS" ||
            $this->cidade_instituicao_ensino == "UPF - PASSO FUNDO" ||
            $this->cidade_instituicao_ensino == "UFFS - PASSO FUNDO" ||
            $this->cidade_instituicao_ensino == "ATITUS - PASSO FUNDO (IMED)" ||
            $this->cidade_instituicao_ensino == "UCS - CAXIAS DO SUL" ||
            $this->cidade_instituicao_ensino == "UNIVATES - LAJEADO" ||
            $this->cidade_instituicao_ensino == "UFCSPA - PORTO ALEGRE" ||
            $this->cidade_instituicao_ensino == "ULBRA - CANOAS" ||
            $this->cidade_instituicao_ensino == "PUCRS - PORTO ALEGRE" ||
            $this->cidade_instituicao_ensino == "UFRGS - PORTO ALEGRE" ||
            $this->cidade_instituicao_ensino == "UNISINOS - SÃO LEOPOLDO" ||
            $this->cidade_instituicao_ensino == "UNIPAMPA - URUGUAIANA" ||
            $this->cidade_instituicao_ensino == "UFSM - SANTA MARIA" ||
            $this->cidade_instituicao_ensino == "UFN - SANTA MARIA" ||
            $this->cidade_instituicao_ensino == "UNISC - SANTA CRUZ DO SUL" ||
            $this->cidade_instituicao_ensino == "URI - ERECHIM" ||
            $this->cidade_instituicao_ensino == "FEEVALE - NOVO HAMBURGO" ||
            $this->cidade_instituicao_ensino == "UNIJUÍ - IJUÍ" ||
            $this->cidade_instituicao_ensino == "GRADUADO EM FACULDADE FORA RS"
        )
            return $this->cidade_instituicao_ensino;
        else return null;
    }

    public function getCidJisr()
    {
        if (!empty($this->cid_jisr))
            return $this->cid_jisr;
        else return null;
    }

    public function imprime_ultima_especialidade()
    {
        // Cria um array associando especialidades com seus respectivos anos
        $especialidades = [
            ['ano' => $this->ano_residencia_espe_1, 'especialidade' => $this->especialidade_1],
            ['ano' => $this->ano_residencia_espe_2, 'especialidade' => $this->especialidade_2],
            ['ano' => $this->ano_residencia_espe_3, 'especialidade' => $this->especialidade_3],
        ];

        // Filtra os itens válidos (com ano e especialidade não vazios)
        $especialidades = array_filter($especialidades, function ($item) {
            return !empty($item['ano']) && !empty($item['especialidade']);
        });

        // Se não houver nenhuma especialidade válida, retorna null
        if (empty($especialidades)) {
            return null;
        }

        // Ordena pelo ano de forma decrescente (mais recente primeiro)
        usort($especialidades, function ($a, $b) {
            return $b['ano'] <=> $a['ano'];
        });

        // Retorna a especialidade com o ano mais recente
        return $especialidades[0]['especialidade'];
    }

    public function getEspecialidade()
    {
        if (!empty($this->especialidade_1))
            return $this->especialidade_1;
        else return null;
    }

    public function imprime_ano_res_mais_recente()
    {
        // Cria uma lista com os anos existentes
        $anos = [
            $this->ano_residencia_espe_1,
            $this->ano_residencia_espe_2,
            $this->ano_residencia_espe_3,
        ];

        // Filtra valores vazios ou nulos
        $anos = array_filter($anos, function ($ano) {
            return !empty($ano);
        });

        // Se não houver anos válidos, retorna null
        if (empty($anos)) {
            return null;
        }

        // Retorna o maior ano (mais recente)
        return max($anos);
    }

    public function getAnoResEspe1()
    {
        $ano_atual = intval(date("Y")) + "7";
        if ($this->ano_residencia_espe_1 >= 2010 && $this->ano_residencia_espe_1 <= $ano_atual)
            return $this->ano_residencia_espe_1;
        else return null;
    }


    public function getAnoResEspe2()
    {
        $ano_atual = intval(date("Y")) + "7";
        if ($this->ano_residencia_espe_2 >= 2010 && $this->ano_residencia_espe_2 <= $ano_atual)
            return $this->ano_residencia_espe_2;
        else return null;
    }

    public function getAnoResEspe3()
    {
        $ano_atual = intval(date("Y")) + "7";
        if ($this->ano_residencia_espe_3 >= 2010 && $this->ano_residencia_espe_3 <= $ano_atual)
            return $this->ano_residencia_espe_3;
        else return null;
    }


    public function getJise()
    {
        if ($this->jise == "" || $this->jise == "A" || $this->jise == "B1" || $this->jise == "B2" || $this->jise == "C")
            return $this->jise;
        else return null;
    }

    public function getCidJise()
    {
        if (!empty($this->cid_jise))
            return $this->cid_jise;
        else return null;
    }


    public function imprimeDataExameMedico()
    {
        if (!empty($this->data_exame_medico))
            return trata_data($this->data_exame_medico);
        else return null;
    }

    public function getObservacaoJise()
    {
        if (!empty($this->observacao_jise))
            return $this->observacao_jise;
        else return null;
    }

    public function getJisea1()
    {
        if ($this->jise_a_1 == "" || $this->jise_a_1 == "A" || $this->jise_a_1 == "B1" || $this->jise_a_1 == "B2" || $this->jise_a_1 == "C")
            return $this->jise_a_1;
        else return null;
    }

    public function getCidJisea1()
    {
        if (!empty($this->cid_jise_a_1))
            return $this->cid_jise_a_1;
        else return null;
    }

    public function getDataJisea1()
    {
        if (!empty($this->data_jise_a_1))
            return ($this->data_jise_a_1);
        else return null;
    }

    public function imprimeDataJisea1()
    {
        if (!empty($this->data_jise_a_1))
            return trata_Data($this->data_jise_a_1);
        else return null;
    }

    public function getObservacaoJisea1()
    { {
            if (!empty($this->observacao_jise_a_1))
                return $this->observacao_jise_a_1;
            else return null;
        }
    }


    public function getDataJisr()
    {
        if (!empty($this->data_jisr))
            return $this->data_jisr;
        else return null;
    }

    public function imprimeDataJisr()
    {
        if (!empty($this->data_jisr))
            return trata_data($this->data_jisr);
        else return null;
    }

    public function getJisr()
    {
        if ($this->jisr == "" || $this->jisr == "A" || $this->jisr == "B1" || $this->jisr == "B2" || $this->jisr == "C")
            return $this->jisr;
        else return null;
    }

    public function getDataSelecaoGeral()
    {
        if (!empty($this->data_selecao_geral))
            return $this->data_selecao_geral;
        else return null;
    }

    public function imprimeDataSelecaoGeral()
    {
        if (!empty($this->data_selecao_geral))
            return trata_data($this->data_selecao_geral);
        else return null;
    }



    public function getDataComparecimentoSelecaoGeral()
    {
        if (!empty($this->data_comparecimento_selecao_geral))
            return $this->data_comparecimento_selecao_geral;
        else return null;
    }

    public function imprimeDataComparecimentoSelecaoGeral()
    {
        if (!empty($this->data_comparecimento_selecao_geral))
            return trata_data($this->data_comparecimento_selecao_geral);
        else return null;
    }

    public function getDataComparecimentoDesignacao()
    {
        if (!empty($this->data_comparecimento_designacao))
            return $this->data_comparecimento_designacao;
        else return null;
    }

    public function imprimeDataComparecimentoDesignacao()
    {
        if (!empty($this->data_comparecimento_designacao))
            return trata_data($this->data_comparecimento_designacao);
        else return null;
    }


    public function getDataProximaApresentacao()
    {
        if (!empty($this->data_proxima_apresentacao))
            return $this->data_proxima_apresentacao;
        else return null;
    }

    public function imprimeDataProximaApresentacao()
    {
        if (!empty($this->data_proxima_apresentacao))
            return trata_data($this->data_proxima_apresentacao);
        else return null;
    }


    public function getSituacaoMilitar()
    {
        return $this->situacao_militar;
    }

    public function getSolicitouAdiamento()
    {
        if ($this->solicitou_adiamento == "" || $this->solicitou_adiamento == "SIM" || $this->solicitou_adiamento == "NÃO")
            return $this->solicitou_adiamento;
        else return null;
    }

    public function getInicioAdiamento()
    {
        if (!empty($this->inicio_adiamento))
            return $this->inicio_adiamento;
        else return null;
    }

    public function imprimeInicioAdiamento()
    {
        if (!empty($this->inicio_adiamento))
            return trata_data($this->inicio_adiamento);
        else return null;
    }

    public function getFimAdiamento()
    {
        if (!empty($this->fim_adiamento))
            return $this->fim_adiamento;
        else return null;
    }

    public function imprimeFimAdiamento()
    {
        if (!empty($this->fim_adiamento))
            return trata_data($this->fim_adiamento);
        else return null;
    }

    public function getEspecialidadeAdiamento()
    {
        if (!empty($this->especialidade_adiamento))
            return $this->especialidade_adiamento;
        else return null;
    }

    public function getTransferenciaFisemi()
    {
        if (!empty($this->transferencia_fisemi))
            return $this->transferencia_fisemi;
        else return null;
    }

    public function getTransferenciaFisemiExibe()
    {
        if ($this->transferencia_fisemi === '0')
            return "Não";
        if ($this->transferencia_fisemi === '1')
            return "Sim";

        return null;
    }

    public function getFisemiExibe()
    {
        if ($this->getTransferenciaFisemiExibe() != null || $this->getRmOrigemFisemi() != null || $this->getRmDestinoFisemi())
            return $trans_fisemi = $this->getTransferenciaFisemiExibe() . " " . $this->getRmOrigemFisemi() . "ª -> " . $this->getRmDestinoFisemi() . "ª";

        return null;
    }

    public function getRmOrigemFisemi()
    {
        return $this->rm_origem_fisemi;
    }

    public function getRmDestinoFisemi()
    {
        return $this->rm_destino_fisemi;
    }

    public function getNumeroAcao()
    {
        if (!empty($this->numero_acao))
            return $this->numero_acao;
        else return null;
    }

    public function getTransitouJulgado()
    {
        if ($this->transitou_julgado == "" || $this->transitou_julgado == "SIM" || $this->transitou_julgado == "NÃO")
            return $this->transitou_julgado;
        else return null;
    }

    public function getDataLiminar()
    {
        if (!empty($this->data_liminar))
            return $this->data_liminar;
        else return null;
    }


    public function imprimeDataLiminar()
    {
        if (!empty($this->data_liminar))
            return trata_data($this->data_liminar);
        else return null;
    }



    public function getFavoravel()
    {
        if ($this->favoravel == "" || $this->favoravel == "SIM" || $this->favoravel == "NÃO")
            return $this->favoravel;
        else return null;
    }


    public function getConvocado()
    {
        if ($this->convocado == "" || $this->convocado == "SIM" || $this->convocado == "NÃO")
            return $this->convocado;
        else return null;
    }

    public function getObsJisr()
    { {
            if (!empty($this->obs_jisr))
                return $this->obs_jisr;
            else return null;
        }
    }

    public function getDistribuicao()
    {
        if (
            $this->distribuicao == "" || $this->distribuicao  == "DESIGNADO - 1ª Distribuição" || $this->distribuicao  == "DESIGNADO - 2ª Distribuição" || $this->distribuicao  == "MAJORADO - 1ª Distribuição" ||
            $this->distribuicao  == "MAJORADO - 2ª Distribuição" || $this->distribuicao  == "EXCESSO CONTINGENTE" || $this->distribuicao  == "MARINHA" ||
            $this->distribuicao  == "FORÇA AÉREA"
        )
            return $this->distribuicao;
        else return null;
    }

    public function getOm1Fase()
    {
        if (!empty($this->om_1_fase))
            return $this->om_1_fase;
        else return null;
    }

    public function getCompareceuDesignacao()
    {
        if (!empty($this->compareceu_designacao))
            return $this->compareceu_designacao;
        else return null;
    }

    public function getLocalCompareceuDesignacao()
    {
        if (!empty($this->local_compareceu_designacao))
            return $this->local_compareceu_designacao;
        else return null;
    }

    public function getDataSelecaoComplementar()
    {
        if (!empty($this->data_selecao_complementar))
            return $this->data_selecao_complementar;
        else return null;
    }

    public function imprimeDataSelecaoComplementar()
    {
        if (!empty($this->data_selecao_complementar))
            return trata_data($this->data_selecao_complementar);
        else return null;
    }

    public function getResultadoRevisaoMedicaComplementar()
    {
        if (!empty($this->resultado_revisao_medica_complementar))
            return $this->resultado_revisao_medica_complementar;
        else return null;
    }

    public function getResultadoIsgr()
    {
        if ($this->resultado_isgr == "" || $this->resultado_isgr == "NÃO é o caso" || $this->resultado_isgr == "A" || $this->resultado_isgr == "B1" || $this->resultado_isgr == "B2" || $this->resultado_isgr == "C")
            return $this->resultado_isgr;
        else return null;
    }

    public function getData_Incorporacao()
    {
        if (!empty($this->data_incorporacao))
            return $this->data_incorporacao;
        else return null;
    }

    public function getOm2Fase()
    {
        if (!empty($this->om_2_fase))
            return $this->om_2_fase;
        else return null;
    }

    public function getObservacao()
    {
        if (!empty($this->observacao))
            return $this->observacao;
        else return null;
    }

    public function getEspecialidade2()
    {
        if (!empty($this->especialidade_2))
            return $this->especialidade_2;
        else return null;
    }

    public function getEspecialidade3()
    {
        if (!empty($this->especialidade_3))
            return $this->especialidade_3;
        else return null;
    }

    public function getApagado()
    {
        if ($this->apagado != null)
            return $this->apagado;
        else return null;
    }

    public function getDataCadastro()
    {
        if (!empty($this->data_cadastro))
            return $this->data_cadastro;
        else return false;
    }

    public function getUsuarioUltimaAtualizacao()
    {
        if (!empty($this->usuario_ultima_atualizacao))
            return $this->usuario_ultima_atualizacao;
        else return false;
    }

    public function getDataUltimaAtualizacao()
    {
        if (!empty($this->data_ultima_atualizacao))
            return $this->data_ultima_atualizacao;
        else  return false;
    }



    public function __toString()
    {
        return "ID: {$this->getId()} | Nome: {$this->getNomeCompleto()} | CPF: {$this->getCPF()}";
    }


    public function imprimeData_revisao_medica()
    {
        if (!empty($this->data_revisao_medica))
            return trata_data($this->data_revisao_medica);
        else
            return null;
    }



    /**
     * Get the value of prioridade_gu
     */
    public function getPrioridade_gu()
    {
        return $this->prioridade_gu;
    }

    /**
     * Set the value of prioridade_gu
     *
     * @return  self
     */
    public function setPrioridade_gu($prioridade_gu)
    {
        $this->prioridade_gu = $prioridade_gu;

        return $this;
    }

    /**
     * Get the value of data_revisao_medica
     */
    public function getData_revisao_medica()
    {

        return $this->data_revisao_medica;
    }

    /**
     * Set the value of data_revisao_medica
     *
     * @return  self
     */
    public function setData_revisao_medica($data_revisao_medica)
    {
        $this->data_revisao_medica = $data_revisao_medica;
    }

    /**
     * Get the value of cid_revisao_medica
     */
    public function getCid_revisao_medica()
    {
        return $this->cid_revisao_medica;
    }

    /**
     * Set the value of cid_revisao_medica
     *
     * @return  self
     */
    public function setCid_revisao_medica($cid_revisao_medica)
    {
        $this->cid_revisao_medica = $cid_revisao_medica;
    }

    /**
     * Get the value of obs_revisao_medica
     */
    public function getObs_revisao_medica()
    {
        return $this->obs_revisao_medica;
    }

    /**
     * Set the value of obs_revisao_medica
     *
     * @return  self
     */
    public function setObs_revisao_medica($obs_revisao_medica)
    {
        $this->obs_revisao_medica = $obs_revisao_medica;
    }

    /**
     * Get the value of incorporacao
     */
    public function getIncorporacao()
    {
        return $this->incorporacao;
    }

    /**
     * Set the value of incorporacao
     *
     * @return  self
     */
    public function setIncorporacao($incorporacao)
    {
        $this->incorporacao = $incorporacao;
    }

    /**
     * Get the value of bar_om_1_fase
     */
    public function getBar_om_1_fase()
    {
        return $this->bar_om_1_fase;
    }

    /**
     * Set the value of bar_om_1_fase
     *
     * @return  self
     */
    public function setBar_om_1_fase($bar_om_1_fase)
    {
        $this->bar_om_1_fase = $bar_om_1_fase;
    }

    /**
     * Get the value of data_isgr
     */
    public function getData_isgr()
    {
        return $this->data_isgr;
    }

    /**
     * Set the value of data_isgr
     *
     * @return  self
     */
    public function setData_isgr($data_isgr)
    {
        $this->data_isgr = $data_isgr;

        return $this;
    }

    /**
     * Get the value of cid_isgr
     */
    public function getCid_isgr()
    {
        return $this->cid_isgr;
    }

    /**
     * Set the value of cid_isgr
     *
     * @return  self
     */
    public function setCid_isgr($cid_isgr)
    {
        $this->cid_isgr = $cid_isgr;

        return $this;
    }

    /**
     * Get the value of observacao_isgr
     */
    public function getObservacao_isgr()
    {
        return $this->observacao_isgr;
    }

    /**
     * Set the value of observacao_isgr
     *
     * @return  self
     */
    public function setObservacao_isgr($observacao_isgr)
    {
        $this->observacao_isgr = $observacao_isgr;

        return $this;
    }
}
?>
