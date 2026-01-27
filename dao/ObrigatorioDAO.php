
<?php

interface ObrigatorioDAOInterface
{
    public function insert(Obrigatorio $obrigatorio);
    public function findAll();
}

class ObrigatorioDAO implements ObrigatorioDAOInterface
{

    private $conexao;

    public function __construct(PDO $conexao)
    {
        $this->conexao = $conexao;
    }

    public function buildObrigatorio($data)
    {
        $obrigatorio = new Obrigatorio($data['cpf']);

        $om_1_fase = new OM();
        $om_1_fase->setId($data['id_om_1_fase']);
        $om_1_fase->setNome($data['nome_om_1_fase']);
        $om_1_fase->setAbreviatura($data['abreviatura_om_1_fase']);
        $om_1_fase->setTelefone($data['telefone_om_1_fase']);
        $om_1_fase->setEndereco($data['endereco_om_1_fase']);
        $om_1_fase->setCidade($data['cidade_om_1_fase']);
        $om_1_fase->setCep($data['cep_om_1_fase']);
        $obrigatorio->setCompareceuDesignacao($data['compareceu_designacao']);
        $obrigatorio->setLocalCompareceuDesignacao($data['local_compareceu_designacao']);
        $obrigatorio->setOm1Fase($om_1_fase);
        $obrigatorio->setId($data['id']);
        $obrigatorio->setIdOm($data['id_om']);
        $obrigatorio->setNomeCompleto($data['nome_completo']);
        $obrigatorio->setCPF($data['cpf']);
        $obrigatorio->setTelefone($data['telefone']);
        $obrigatorio->setMail($data['mail']);
        $obrigatorio->setEstadoCivil($data['estado_civil']);
        $obrigatorio->setDataNascimento($data['data_nascimento']);
        $obrigatorio->setDataExpedicao($data['data_expedicao']);
        $obrigatorio->setNomePai($data['nome_pai']);
        $obrigatorio->setNomeMae($data['nome_mae']);
        $obrigatorio->setNacionalidade($data['nacionalidade']);
        $obrigatorio->setNaturalidade($data['naturalidade']);
        $obrigatorio->setIdentidade($data['identidade']);
        $obrigatorio->setDependentes($data['dependentes']);
        $obrigatorio->setEndereco($data['endereco']);
        $obrigatorio->setPrioridadeForca($data['prioridade_forca']);
        $obrigatorio->setApagado($data['apagado']);
        $obrigatorio->setVoluntario($data['voluntario']);
        $obrigatorio->setDocumentoMilitar($data['documento_militar']);
        $obrigatorio->setNumeroDocumentoMilitar($data['numero_documento_militar']);
        $obrigatorio->setDataExpedicao($data['data_expedicao']);
        $obrigatorio->setForca($data['forca']);
        $obrigatorio->setNomeInstitutoEnsino($data['nome_instituicao_ensino']);
        $obrigatorio->setAnoFormacao($data['ano_formacao']);
        $obrigatorio->setFormacao($data['formacao']);
        $obrigatorio->setCidadeInstituicaoEnsino($data['cidade_instituicao_ensino']);
        $obrigatorio->setJise($data['jise']);
        $obrigatorio->setCidJise($data['cid_jise']);
        $obrigatorio->setObservacaoJise($data['observacao_jise']);
        $obrigatorio->setJisr($data['jisr']);
        $obrigatorio->setCidJisr($data['cid_jisr']);
        $obrigatorio->setDataJisr($data['data_jisr']);
        $obrigatorio->setObsJisr($data['observacao_jisr']);
        $obrigatorio->setJisea1($data['jise_a_1']);
        $obrigatorio->setCidJisea1($data['cid_jise_a_1']);
        $obrigatorio->setDataJisea1($data['data_jise_a_1']);
        $obrigatorio->setObservacaoJisea1($data['observacao_jise_a_1']);
        $obrigatorio->setDataSelecaoGeral($data['data_selecao_geral']);
        $obrigatorio->setDataComparecimentoSelecaoGeral($data['data_comparecimento_selecao_geral']);
        $obrigatorio->setDataComparecimentoDesignacao($data['data_comparecimento_designacao']);
        $obrigatorio->setDataProximaApresentacao($data['data_proxima_apresentacao']);
        $obrigatorio->setSituacaoMilitar($data['situacao_militar']);
        $obrigatorio->setSolicitouAdiamento($data['solicitou_adiamento']);
        $obrigatorio->setInicioAdiamento($data['inicio_adiamento']);
        $obrigatorio->setFimAdiamento($data['fim_adiamento']);
        $obrigatorio->setEspecialidadeAdiamento($data['especialidade_adiamento']);
        $obrigatorio->setTransferenciaFisemi($data['transferencia_fisemi']);
        $obrigatorio->setRmOrigemFisemi($data['rm_origem_fisemi']);
        $obrigatorio->setRmDestinoFisemi($data['rm_destino_fisemi']);
        $obrigatorio->setNumeroAcao($data['numero_acao']);
        $obrigatorio->setTransitouJulgado($data['transitou_julgado']);
        $obrigatorio->setDataLiminar($data['data_liminar']);
        $obrigatorio->setFavoravel($data['favoravel']);
        $obrigatorio->setConvocado($data['convocado']);
        $obrigatorio->setDistribuicao($data['distribuicao']);
        $obrigatorio->setDataSelecaoComplementar($data['data_selecao_complementar']);
        $obrigatorio->setResultadoRevisaoMedicaComplementar($data['resultado_revisao_medica_complementar']);
        $obrigatorio->setResultadoIsgr($data['resultado_isgr']);
        $obrigatorio->setDataIncorporacao($data['data_incorporacao']);
        $obrigatorio->setOm2Fase($data['om_2_fase']);
        $obrigatorio->setObservacao($data['observacao']);
        $obrigatorio->setEspecialidade($data['especialidade_1']);
        $obrigatorio->setEspecialidade2($data['especialidade_2']);
        $obrigatorio->setEspecialidade3($data['especialidade_3']);
        $obrigatorio->setAnoResEspe1($data['ano_residencia_espe_1']);
        $obrigatorio->setAnoResEspe2($data['ano_residencia_espe_2']);
        $obrigatorio->setAnoResEspe3($data['ano_residencia_espe_3']);
        $obrigatorio->setData_revisao_medica($data['data_revisao_medica']);
        $obrigatorio->setCid_revisao_medica($data['cid_revisao_medica']);
        $obrigatorio->setObs_revisao_medica($data['obs_revisao_medica']);
        $obrigatorio->setIncorporacao($data['incorporacao']);
        $obrigatorio->setBar_om_1_fase($data['bar_om_1_fase']);
        $obrigatorio->setData_isgr($data['data_isgr']);
        $obrigatorio->setCid_isgr($data['cid_isgr']);
        $obrigatorio->setObservacao_isgr($data['observacao_isgr']);

        return $obrigatorio;
    }


    public function insert(Obrigatorio $obrigatorio)
    {
        $sql = "INSERT INTO obrigatorio 
                (id_om, nome_completo, cpf, telefone, mail, estado_civil, data_nascimento,  nome_pai, nome_mae,  nacionalidade,
                naturalidade, identidade, dependentes, endereco, prioridade_forca, ano_formacao, formacao, nome_instituicao_ensino, documento_militar,
                especialidade_1, especialidade_2, especialidade_3, ano_residencia_espe_1, ano_residencia_espe_2, ano_residencia_espe_3) 
              
        VALUES (:id_om, UPPER(:nome_completo), :cpf, :telefone, UPPER(:mail), UPPER(:estado_civil), :data_nascimento,  UPPER(:nome_pai),
                UPPER(:nome_mae),  :nacionalidade, :naturalidade, :identidade, :dependentes,  UPPER(:endereco), :prioridade_forca, :ano_formacao, :formacao,
                :nome_instituicao_ensino, :documento_militar,
                :especialidade_1, :especialidade_2, :especialidade_3, :ano_residencia_espe_1, :ano_residencia_espe_2, :ano_residencia_espe_3)";

        $this->conexao->beginTransaction();
        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(":id_om", $_SESSION['id_om_smo']);
        $stmt->bindValue(":nome_completo", $obrigatorio->getNomeCompleto());
        $stmt->bindValue(":cpf", $obrigatorio->getCPF());
        $stmt->bindValue(":telefone", $obrigatorio->getTelefone());
        $stmt->bindValue(":mail", $obrigatorio->getMail());
        $stmt->bindValue(":estado_civil", $obrigatorio->getEstadoCivil());
        $stmt->bindValue(":data_nascimento", $obrigatorio->getDataNascimento());
        $stmt->bindValue(":nome_pai", $obrigatorio->getNomePai());
        $stmt->bindValue(":nome_mae", $obrigatorio->getNomeMae());
        $stmt->bindValue(":nacionalidade", $obrigatorio->getNacionalidade());
        $stmt->bindValue(":naturalidade", $obrigatorio->getNaturalidade());
        $stmt->bindValue(":identidade", $obrigatorio->getIdentidade());
        $stmt->bindValue(":dependentes", $obrigatorio->getDependentes());
        $stmt->bindValue(":endereco", $obrigatorio->getEndereco());
        $stmt->bindValue(":prioridade_forca", $obrigatorio->getPrioridadeForca());
        $stmt->bindValue(":ano_formacao", $obrigatorio->getAnoFormacao());
        $stmt->bindValue(":formacao", $obrigatorio->getFormacao());
        $stmt->bindValue(":nome_instituicao_ensino", $obrigatorio->getNomeInstitutoEnsino());
        $stmt->bindValue(":documento_militar", $obrigatorio->getDocumentoMilitar());
        $stmt->bindValue(":especialidade_1", $obrigatorio->getEspecialidade());
        $stmt->bindValue(":especialidade_2", $obrigatorio->getEspecialidade2());
        $stmt->bindValue(":especialidade_3", $obrigatorio->getEspecialidade3());
        $stmt->bindValue(":ano_residencia_espe_1", $obrigatorio->getAnoResEspe1());
        $stmt->bindValue(":ano_residencia_espe_2", $obrigatorio->getAnoResEspe1());
        $stmt->bindValue(":ano_residencia_espe_3", $obrigatorio->getAnoResEspe1());


        if ($stmt->execute()) {
            $data =
                [
                    'id_adicionado' => $this->conexao->lastInsertId(),
                    'id_om' => $obrigatorio->getIdOm(),
                    'nome_completo' => $obrigatorio->getNomeCompleto(),
                    'cpf' => $obrigatorio->getCPF(),
                    'telefone' => $obrigatorio->getTelefone(),
                    'mail' => $obrigatorio->getMail(),
                    'estado_civil' => $obrigatorio->getEstadoCivil(),
                    'data_nascimento' => $obrigatorio->getDataNascimento(),
                    'nome_pai' => $obrigatorio->getNomePai(),
                    'nome_mae' => $obrigatorio->getNomeMae(),
                    'nacionalidade' => $obrigatorio->getNacionalidade(),
                    'naturalidade' => $obrigatorio->getNaturalidade(),
                    'identidade' => $obrigatorio->getIdentidade(),
                    'dependentes' => $obrigatorio->getDependentes(),
                    'endereco' => $obrigatorio->getEndereco(),
                    'prioridade_forca' => $obrigatorio->getPrioridadeForca(),
                    'ano_formacao' => $obrigatorio->getAnoFormacao(),
                    'formacao' => $obrigatorio->getFormacao(),
                    'nome_instituicao_ensino' => $obrigatorio->getNomeInstitutoEnsino(),
                    'documento_militar' => $obrigatorio->getDocumentoMilitar(),
                    'especialidade_1' => $obrigatorio->getEspecialidade(),
                    'especialidade_2' => $obrigatorio->getEspecialidade2(),
                    'especialidade_3' => $obrigatorio->getEspecialidade3(),
                    'ano_residencia_espe_1' => $obrigatorio->getAnoResEspe1(),
                    'ano_residencia_espe_2' => $obrigatorio->getAnoResEspe2(),
                    'ano_residencia_espe_3' => $obrigatorio->getAnoResEspe3()

                ];

            $this->conexao->commit();
            return $data;
        } else {
            print_r($stmt->errorInfo());
            exit();
            $this->conexao->rollBack();
            return false;
        }
    }

    public function update(Obrigatorio $obrigatorio)
    {
        $sql = "UPDATE obrigatorio set  nome_completo = UPPER(:nome_completo), cpf = :cpf,
        telefone = :telefone, mail = UPPER(:mail), estado_civil = :estado_civil, 
        data_nascimento = :data_nascimento, nome_pai = UPPER(:nome_pai), nome_mae = UPPER(:nome_mae), 
        nacionalidade = UPPER(:nacionalidade), naturalidade = :naturalidade, 
        identidade = :identidade, dependentes = :dependentes, endereco = UPPER(:endereco), prioridade_forca = :prioridade_forca, voluntario = :voluntario,
        documento_militar = :documento_militar, numero_documento_militar = :numero_documento_militar,
        data_expedicao = :data_expedicao,
        forca = :forca,
        nome_instituicao_ensino = :nome_instituicao_ensino,
        ano_formacao = :ano_formacao,
        formacao = :formacao,
        cidade_instituicao_ensino = :cidade_instituicao_ensino,
        jise = :jise,
        cid_jise = UPPER(:cid_jise),
        observacao_jise = UPPER(:observacao_jise),
        jisr = :jisr,
        cid_jisr = UPPER(:cid_jisr),
        data_jisr = :data_jisr,
        observacao_jisr = UPPER(:obs_jisr),
        jise_a_1 = :jise_a_1,
        cid_jise_a_1 = UPPER(:cid_jise_a_1),
        data_jise_a_1 = :data_jise_a_1,
        observacao_jise_a_1 = UPPER(:observacao_jise_a_1),
        _usuario_ultima_atualizacao = :_usuario_ultima_atualizacao,
        data_selecao_geral = :data_selecao_geral,
        data_comparecimento_selecao_geral = :data_comparecimento_selecao_geral,
        data_comparecimento_designacao = :data_comparecimento_designacao,
        data_proxima_apresentacao = :data_proxima_apresentacao, situacao_militar = :situacao_militar,
        solicitou_adiamento = :solicitou_adiamento,
        inicio_adiamento = :inicio_adiamento,
        fim_adiamento = :fim_adiamento,
        especialidade_adiamento = :especialidade_adiamento,
        transferencia_fisemi = :transferencia_fisemi,
        rm_origem_fisemi = :rm_origem_fisemi,
        rm_destino_fisemi = :rm_destino_fisemi,
        id_om_1_fase = :id_om_1_fase,
        numero_acao = :numero_acao,
        transitou_julgado = :transitou_julgado,
        data_liminar = :data_liminar,
        favoravel = :favoravel,
        convocado = :convocado,
        distribuicao = :distribuicao,
        data_selecao_complementar = :data_selecao_complementar,
        resultado_revisao_medica_complementar = :resultado_revisao_medica_complementar,
        resultado_isgr = :resultado_isgr,
        compareceu_designacao = :compareceu_designacao,
        local_compareceu_designacao = :local_compareceu_designacao,
        data_incorporacao = :data_incorporacao,
        om_2_fase = :om_2_fase,
        observacao = UPPER(:observacao),
        especialidade_1 = :especialidade_1,
        especialidade_2 = :especialidade_2,
        especialidade_3 = :especialidade_3,
        ano_residencia_espe_1 = :ano_residencia_espe_1,
        ano_residencia_espe_2 = :ano_residencia_espe_2,
        ano_residencia_espe_3 = :ano_residencia_espe_3,
        data_revisao_medica = :data_revisao_medica,
        cid_revisao_medica =  UPPER(:cid_revisao_medica),
        obs_revisao_medica =  UPPER(:obs_revisao_medica),
        incorporacao = UPPER(:incorporacao),
        bar_om_1_fase = UPPER(:bar_om_1_fase),

        data_isgr = :data_isgr,
        cid_isgr = UPPER(:cid_isgr),
        observacao_isgr = UPPER(:observacao_isgr)

        where id = :id";


        $this->conexao->beginTransaction();

        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(":id", $obrigatorio->getId());
        $stmt->bindValue(":nome_completo", $obrigatorio->getNomeCompleto());
        $stmt->bindValue(":cpf", $obrigatorio->getCpf());
        $stmt->bindValue(":telefone", $obrigatorio->getTelefone());
        $stmt->bindValue(":mail", $obrigatorio->getMail());
        $stmt->bindValue(":estado_civil", $obrigatorio->getEstadoCivil());
        $stmt->bindValue(":data_nascimento", $obrigatorio->getDataNascimento());
        $stmt->bindValue(":nome_pai", $obrigatorio->getNomePai());
        $stmt->bindValue(":nome_mae", $obrigatorio->getNomeMae());
        $stmt->bindValue(":nacionalidade", $obrigatorio->getNacionalidade());
        $stmt->bindValue(":naturalidade", $obrigatorio->getNaturalidade());
        $stmt->bindValue(":identidade", $obrigatorio->getIdentidade());
        $stmt->bindValue(":dependentes", $obrigatorio->getDependentes());
        $stmt->bindValue(":endereco", $obrigatorio->getEndereco());
        $stmt->bindValue(":prioridade_forca", $obrigatorio->getPrioridadeForca());
        $stmt->bindValue(":voluntario", $obrigatorio->getVoluntario());
        $stmt->bindValue(":documento_militar", $obrigatorio->getDocumentoMilitar());
        $stmt->bindValue(":numero_documento_militar", $obrigatorio->getNumeroDocumentoMilitar());
        $stmt->bindValue(":data_expedicao", $obrigatorio->getDataExpedicao());
        $stmt->bindValue(":forca", $obrigatorio->getForca());
        $stmt->bindValue(":nome_instituicao_ensino", $obrigatorio->getNomeInstitutoEnsino());
        $stmt->bindValue(":ano_formacao", $obrigatorio->getAnoFormacao());
        $stmt->bindValue(":formacao", $obrigatorio->getFormacao());
        $stmt->bindValue(":cidade_instituicao_ensino", $obrigatorio->getCidadeInstituicaoEnsino());
        $stmt->bindValue(":jise", $obrigatorio->getJise());
        $stmt->bindValue(":cid_jise", $obrigatorio->getCidJise());
        $stmt->bindValue(":_usuario_ultima_atualizacao", $_SESSION['id_usuario_smo']);
        $stmt->bindValue(":observacao_jise", $obrigatorio->getObservacaoJise());
        $stmt->bindValue(":jisr", $obrigatorio->getJisr());
        $stmt->bindValue(":cid_jisr", $obrigatorio->getCidJisr());
        $stmt->bindValue(":data_jisr", $obrigatorio->getDataJisr());
        $stmt->bindValue(":obs_jisr", $obrigatorio->getObsJisr());
        $stmt->bindValue(":jise_a_1", $obrigatorio->getJisea1());
        $stmt->bindValue(":cid_jise_a_1", $obrigatorio->getCidJisea1());
        $stmt->bindValue(":data_jise_a_1", $obrigatorio->getDataJisea1());
        $stmt->bindValue(":observacao_jise_a_1", $obrigatorio->getObservacaoJisea1());
        $stmt->bindValue(":data_selecao_geral", $obrigatorio->getDataSelecaoGeral());
        $stmt->bindValue(":data_comparecimento_selecao_geral", $obrigatorio->getDataComparecimentoSelecaoGeral());
        $stmt->bindValue(":data_comparecimento_designacao", $obrigatorio->getDataComparecimentoDesignacao());
        $stmt->bindValue(":data_proxima_apresentacao", $obrigatorio->getDataProximaApresentacao());
        $stmt->bindValue(":situacao_militar", $obrigatorio->getSituacaoMilitar());
        $stmt->bindValue(":solicitou_adiamento", $obrigatorio->getSolicitouAdiamento());
        $stmt->bindValue(":inicio_adiamento", $obrigatorio->getInicioAdiamento());
        $stmt->bindValue(":fim_adiamento", $obrigatorio->getFimAdiamento());
        $stmt->bindValue(":especialidade_adiamento", $obrigatorio->getEspecialidadeAdiamento());
        $stmt->bindValue(":transferencia_fisemi", $obrigatorio->getTransferenciaFisemi());
        $stmt->bindValue(":rm_origem_fisemi", $obrigatorio->getRmOrigemFisemi());
        $stmt->bindValue(":rm_destino_fisemi", $obrigatorio->getRmDestinoFisemi());
        $stmt->bindValue(":numero_acao", $obrigatorio->getNumeroAcao());
        $stmt->bindValue(":transitou_julgado", $obrigatorio->getTransitouJulgado());
        $stmt->bindValue(":data_liminar", $obrigatorio->getDataLiminar());
        $stmt->bindValue(":favoravel", $obrigatorio->getFavoravel());
        $stmt->bindValue(":convocado", $obrigatorio->getConvocado());
        $stmt->bindValue(":distribuicao", $obrigatorio->getDistribuicao());
        $stmt->bindValue(":data_selecao_complementar", $obrigatorio->getDataSelecaoComplementar());
        $stmt->bindValue(":resultado_revisao_medica_complementar", $obrigatorio->getResultadoRevisaoMedicaComplementar());
        $stmt->bindValue(":resultado_isgr", $obrigatorio->getResultadoIsgr());
        $stmt->bindValue(":compareceu_designacao", $obrigatorio->getCompareceuDesignacao());
        $stmt->bindValue(":local_compareceu_designacao", $obrigatorio->getLocalCompareceuDesignacao());
        $stmt->bindValue(":data_incorporacao", $obrigatorio->getDataIncorporacao());
        $stmt->bindValue(":om_2_fase", $obrigatorio->getOm2Fase());
        $stmt->bindValue(":id_om_1_fase", $obrigatorio->getOm1Fase()->getId());
        $stmt->bindValue(":observacao", $obrigatorio->getObservacao());
        $stmt->bindValue(":especialidade_1", $obrigatorio->getEspecialidade());
        $stmt->bindValue(":especialidade_2", $obrigatorio->getEspecialidade2());
        $stmt->bindValue(":especialidade_3", $obrigatorio->getEspecialidade3());
        $stmt->bindValue(":ano_residencia_espe_1", $obrigatorio->getAnoResEspe1());
        $stmt->bindValue(":ano_residencia_espe_2", $obrigatorio->getAnoResEspe2());
        $stmt->bindValue(":ano_residencia_espe_3", $obrigatorio->getAnoResEspe3());
        $stmt->bindValue(":data_revisao_medica", $obrigatorio->getData_revisao_medica());
        $stmt->bindValue(":cid_revisao_medica", $obrigatorio->getCid_revisao_medica());
        $stmt->bindValue(":obs_revisao_medica", $obrigatorio->getObs_revisao_medica());
        $stmt->bindValue(":incorporacao", $obrigatorio->getIncorporacao());
        $stmt->bindValue(":bar_om_1_fase", $obrigatorio->getBar_om_1_fase());

        $stmt->bindValue(":data_isgr", $obrigatorio->getData_isgr());
        $stmt->bindValue(":cid_isgr", $obrigatorio->getCid_isgr());
        $stmt->bindValue(":observacao_isgr", $obrigatorio->getObservacao_isgr());

        if ($stmt->execute()) {
            $data =
                [
                    'id_atualizado' => $obrigatorio->getId(),
                    'nome_completo' => $obrigatorio->getNomeCompleto(),
                    'CPF' => $obrigatorio->getCpf(),
                    'telefone' => $obrigatorio->getTelefone(),
                    'mail' => $obrigatorio->getMail(),
                    'estado_civil' => $obrigatorio->getEstadoCivil(),
                    'data_nascimento' => $obrigatorio->getDataNascimento(),
                    'nome_pai' => $obrigatorio->getNomePai(),
                    'nome_mae' => $obrigatorio->getNomeMae(),
                    'nacionalidade' => $obrigatorio->getNacionalidade(),
                    'naturalidade' => $obrigatorio->getNaturalidade(),
                    'Identidade' => $obrigatorio->getIdentidade(),
                    'dependentes' => $obrigatorio->getDependentes(),
                    'endereco' => $obrigatorio->getEndereco(),
                    'prioridade_forca' => $obrigatorio->getPrioridadeForca(),
                    'voluntario' => $obrigatorio->getVoluntario(),
                    'documento_militar' => $obrigatorio->getDocumentoMilitar(),
                    'numero_documento_militar' => $obrigatorio->getNumeroDocumentoMilitar(),
                    'data_expedicao' => $obrigatorio->getDataExpedicao(),
                    'forca' => $obrigatorio->getForca(),
                    'nome_instituicao_ensino' => $obrigatorio->getNomeInstitutoEnsino(),
                    'ano_formacao' => $obrigatorio->getAnoFormacao(),
                    'formacao' => $obrigatorio->getFormacao(),
                    'cidade_instituicao_ensino' => $obrigatorio->getCidadeInstituicaoEnsino(),
                    'jise' => $obrigatorio->getJise(),
                    'cid_jise' => $obrigatorio->getCidJise(),
                    'observacao_jise' => $obrigatorio->getObservacaoJise(),
                    'jisr' => $obrigatorio->getJisr(),
                    'cid_jisr' => $obrigatorio->getCidJisr(),
                    'data_jisr' => $obrigatorio->getDataJisr(),
                    'obs_jisr' => $obrigatorio->getObsJisr(),
                    'jise_a_1' => $obrigatorio->getJisea1(),
                    'cid_jise_a_1' => $obrigatorio->getCidJisea1(),
                    'data_jise_a_1' => $obrigatorio->getDataJisea1(),
                    'observacao_jise_a_1' => $obrigatorio->getObservacaoJisea1(),
                    'data_selecao_geral' => $obrigatorio->getDataSelecaoGeral(),
                    'data_comparecimento_selecao_geral' => $obrigatorio->getDataComparecimentoSelecaoGeral(),
                    'data_comparecimento_designacao' => $obrigatorio->getDataComparecimentoDesignacao(),
                    'data_proxima_apresentacao' => $obrigatorio->getDataProximaApresentacao(),
                    'situacao_militar' => $obrigatorio->getSituacaoMilitar(),
                    'solicitou_adiamento' => $obrigatorio->getSolicitouAdiamento(),
                    'inicio_adiamento' => $obrigatorio->getInicioAdiamento(),
                    'fim_adiamento' => $obrigatorio->getFimAdiamento(),
                    'especialidade_adiamento' => $obrigatorio->getEspecialidadeAdiamento(),
                    'transferencia_fisemi' => $obrigatorio->getTransferenciaFisemi(),
                    'rm_origem_fisemi' => $obrigatorio->getRmOrigemFisemi(),
                    'rm_destino_fisemi' => $obrigatorio->getRmDestinoFisemi(),
                    'numero_acao' => $obrigatorio->getNumeroAcao(),
                    'transitou_julgado' => $obrigatorio->getTransitouJulgado(),
                    'data_liminar' => $obrigatorio->getDataLiminar(),
                    'favoravel' => $obrigatorio->getFavoravel(),
                    'convocado' => $obrigatorio->getConvocado(),
                    'distribuicao' => $obrigatorio->getDistribuicao(),
                    'data_selecao_complementar' => $obrigatorio->getDataSelecaoComplementar(),
                    'resultado_revisao_medica_complementar' => $obrigatorio->getResultadoRevisaoMedicaComplementar(),
                    'resultado_isgr' => $obrigatorio->getResultadoIsgr(),
                    'compareceu_designacao' => $obrigatorio->getCompareceuDesignacao(),
                    'local_compareceu_designacao' => $obrigatorio->getLocalCompareceuDesignacao(),
                    'data_incorporacao' => $obrigatorio->getDataIncorporacao(),
                    'om_2_fase' => $obrigatorio->getOm2Fase(),
                    'id_om_1_fase' => $obrigatorio->getOm1Fase(),
                    'observacao' => $obrigatorio->getObservacao(),
                    'especialidade_1' => $obrigatorio->getEspecialidade(),
                    'especialidade_2' => $obrigatorio->getEspecialidade2(),
                    'especialidade_3' => $obrigatorio->getEspecialidade3(),
                    'ano_residencia_espe_1' => $obrigatorio->getAnoResEspe1(),
                    'ano_residencia_espe_2' => $obrigatorio->getAnoResEspe2(),
                    'ano_residencia_espe_3' => $obrigatorio->getAnoResEspe3(),
                    'data_revisao_medica' => $obrigatorio->getData_revisao_medica(),
                    'cid_revisao_medica' => $obrigatorio->getCid_revisao_medica(),
                    'obs_revisao_medica' => $obrigatorio->getObs_revisao_medica(),
                    'incorporacao' => $obrigatorio->getIncorporacao(),
                    'bar_om_1_fase' => $obrigatorio->getBar_om_1_fase(),
                    'data_isgr' => $obrigatorio->getData_isgr(),
                    'cid_isgr' => $obrigatorio->getCid_isgr(),
                    'observacao_isgr' => $obrigatorio->getObservacao_isgr(),

                ];

            $this->conexao->commit();
            return $data;
        } else {
            // print_r($stmt->errorInfo()); 
            // exit();
            $this->conexao->rollBack();
            return false;
        }
    }

    public function update_revisao_medica(Obrigatorio $obrigatorio)
    {
        $sql = "UPDATE obrigatorio set  
        data_revisao_medica = :data_revisao_medica,
        resultado_revisao_medica_complementar = :resultado_revisao_medica_complementar,
        cid_revisao_medica =  UPPER(:cid_revisao_medica),
        obs_revisao_medica =  UPPER(:obs_revisao_medica)

        where id = :id";


        $this->conexao->beginTransaction();

        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(":id", $obrigatorio->getId());
        $stmt->bindValue(":data_revisao_medica", $obrigatorio->getData_revisao_medica());
        $stmt->bindValue(":resultado_revisao_medica_complementar", $obrigatorio->getResultadoRevisaoMedicaComplementar());
        $stmt->bindValue(":cid_revisao_medica", $obrigatorio->getCid_revisao_medica());
        $stmt->bindValue(":obs_revisao_medica", $obrigatorio->getObs_revisao_medica());

        if ($stmt->execute()) {
            $data =
                [
                    'id_atualizado' => $obrigatorio->getId(),
                    'data_revisao_medica' => $obrigatorio->getData_revisao_medica(),
                    'resultado_revisao_medica_complementar' => $obrigatorio->getResultadoRevisaoMedicaComplementar(),
                    'cid_revisao_medica' => $obrigatorio->getCid_revisao_medica(),
                    'obs_revisao_medica' => $obrigatorio->getObs_revisao_medica(),

                ];

            $this->conexao->commit();
            return $data;
        } else {
            print_r($stmt->errorInfo());
            exit();
            $this->conexao->rollBack();
            return false;
        }
    }

    public function update_isgr(Obrigatorio $obrigatorio)
    {
        $sql = "UPDATE obrigatorio set  
        resultado_isgr = :resultado_isgr,
        data_isgr =  :data_isgr,
        cid_isgr =  UPPER(:cid_isgr),
        observacao_isgr = UPPER(:observacao_isgr)

        where id = :id";

        $this->conexao->beginTransaction();

        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(":id", $obrigatorio->getId());
        $stmt->bindValue(":resultado_isgr", $obrigatorio->getResultadoIsgr());
        $stmt->bindValue(":data_isgr", $obrigatorio->getData_isgr());
        $stmt->bindValue(":cid_isgr", $obrigatorio->getCid_isgr());
        $stmt->bindValue(":observacao_isgr", $obrigatorio->getObservacao_isgr());

        if ($stmt->execute()) {
            $data =
                [
                    'id_atualizado' => $obrigatorio->getId(),
                    'resultado_isgr' => $obrigatorio->getResultadoIsgr(),
                    'data_isgr' => $obrigatorio->getData_isgr(),
                    'cid_isgr' => $obrigatorio->getCid_isgr(),
                    'observacao_isgr' => $obrigatorio->getObservacao_isgr(),
                ];

            $this->conexao->commit();
            return $data;
        } else {
            //  print_r($stmt->errorInfo()); 
            //  exit();
            $this->conexao->rollBack();
            return false;
        }
    }


    public function update_incorporacao_om(Obrigatorio $obrigatorio)
    {

        $sql = "UPDATE obrigatorio set  
        incorporacao = :incorporacao,
        data_incorporacao =  :data_incorporacao,
        compareceu_designacao = :compareceu_designacao,
        id_om_1_fase =  :id_om_1_fase,
        bar_om_1_fase = UPPER(:bar_om_1_fase),
        om_2_fase = :om_2_fase

        where id = :id";

        $this->conexao->beginTransaction();

        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(":id", $obrigatorio->getId());
        $stmt->bindValue(":incorporacao", $obrigatorio->getIncorporacao());
        $stmt->bindValue(":data_incorporacao", $obrigatorio->getData_Incorporacao());
        $stmt->bindValue(":compareceu_designacao", $obrigatorio->getCompareceuDesignacao());
        $stmt->bindValue(":id_om_1_fase", $obrigatorio->getOm1Fase()->getId());
        $stmt->bindValue(":bar_om_1_fase", $obrigatorio->getBar_om_1_fase());
        $stmt->bindValue(":om_2_fase", $obrigatorio->getOm2Fase());

        if ($stmt->execute()) {
            $data =
                [
                    'id_atualizado' => $obrigatorio->getId(),
                    'incorporacao' => $obrigatorio->getIncorporacao(),
                    'compareceu_designacao' => $obrigatorio->getCompareceuDesignacao(),
                    'data_incorporacao' => $obrigatorio->getData_Incorporacao(),
                    'id_om_1_fase' => $obrigatorio->getOm1Fase(),
                    'bar_om_1_fase' => $obrigatorio->getBar_om_1_fase(),
                    'om_2_fase' => $obrigatorio->getOm2Fase(),
                ];

            $this->conexao->commit();
            return $data;
        } else {
            //   print_r($stmt->errorInfo()); 
            //   exit();
            $this->conexao->rollBack();
            return false;
        }
    }


    public function insertObrigatorioXGu($id_obrigatorio, $id_guarnicao, $prioridade)
    {
        $sql = "INSERT INTO `obrigatorio_x_guarnicao` (`id_obrigatorio`, `id_guarnicao`, `prioridade`) 
                            VALUES (:id_obrigatorio, :id_guarnicao, :prioridade)";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(':id_obrigatorio', $id_obrigatorio);
        $stmt->bindValue(':id_guarnicao', $id_guarnicao);
        $stmt->bindValue(':prioridade', $prioridade);

        $this->conexao->beginTransaction();

        if ($stmt->execute()) {
            $data =
                [
                    'id_obrigatorio' => $id_obrigatorio,
                    'id_guarnicao' => $id_guarnicao,
                    'prioridade' => $prioridade,
                ];

            $this->conexao->commit();
            return $data;
        } else {
            //print_r($stmt->errorInfo()); 
            //exit();
            $this->conexao->rollBack();
            return false;
        }
    }


    public function findPrioridade($id_obrigatorio)
    {
        if ((int)$id_obrigatorio > 0) {
            $stmt = $this->conexao->prepare("SELECT max(prioridade) prioridade FROM obrigatorio_x_guarnicao WHERE id_obrigatorio = :id_obrigatorio AND apagado = 0;");
            $stmt->bindValue(":id_obrigatorio", $id_obrigatorio);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetch();
                return $data;
            } else {
                //print_r($stmt->errorInfo()); 
                //exit();
                return false;
            }
        }
        return false;
    }



    public function findAllGuarnicaoPrioridade($id_obrigatorio)
    {
        if ((int)$id_obrigatorio > 0) {
            $stmt = $this->conexao->prepare("SELECT obrigar.prioridade, guarnicao.nome guarnicao, guarnicao.id id_guarnicao
                FROM obrigatorio_x_guarnicao AS obrigar
                INNER JOIN guarnicao ON obrigar.id_guarnicao = guarnicao.id 
                WHERE obrigar.apagado = 0 
                AND id_obrigatorio = :id_obrigatorio
                ORDER BY prioridade ");

            $stmt->bindValue(":id_obrigatorio", $id_obrigatorio);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetchAll();
                return $data;
            } else
                return false;
        } else
            return false;
    }



    public function findById($id)
    {
        if ((int)$id > 0) {
            $stmt = $this->conexao->prepare("
                                                select om.id id_om_1_fase, om.nome nome_om_1_fase, om.abreviatura abreviatura_om_1_fase, om.telefone telefone_om_1_fase, om.endereco endereco_om_1_fase, om.cidade cidade_om_1_fase, om.cep cep_om_1_fase , o.* 
                                                from obrigatorio o
                                                left join om on om.id = o.id_om_1_fase
                                                where o.id = :id
                                            ");
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetch();
                $user = $this->buildObrigatorio($data);
                return $user;
            } else
                return false;
        } else
            return false;
    }

    public function findByCPF($cpf)
    {
        if ((int)$cpf > 0) {
            $stmt = $this->conexao->prepare("
                                                select om.id id_om_1_fase, om.nome nome_om_1_fase, om.abreviatura abreviatura_om_1_fase, om.telefone telefone_om_1_fase, om.endereco endereco_om_1_fase, om.cidade cidade_om_1_fase, om.cep cep_om_1_fase , o.* 
                                                from obrigatorio o
                                                left join om on om.id = o.id_om_1_fase where o.cpf = :cpf
                                            ");
            $stmt->bindValue(":cpf", $cpf);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetch();
                $user = $this->buildObrigatorio($data);
                return $user;
            } else
                return false;
        } else
            return false;
    }

    public function deleteId($id_obrigatorio)
    {
        $sql = "update obrigatorio set apagado = 1, _usuario_ultima_atualizacao =:_usuario_ultima_atualizacao where id = :id";
        $this->conexao->beginTransaction();
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(":id", $id_obrigatorio);
        $stmt->bindValue(":_usuario_ultima_atualizacao", $_SESSION['id_usuario_smo']);

        if ($stmt->execute()) {
            $this->conexao->commit();
            return true;
        } else {
            // print_r($stmt->errorInfo()); 
            // exit();
            $this->conexao->rollBack();
            return false;
        }
    }

    public function deleteListaPrioridades($id_obrigatorio)
    {
        $sql = "UPDATE obrigatorio_x_guarnicao SET apagado = 1, _usuario_ultima_atualizacao = :_usuario_ultima_atualizacao WHERE id_obrigatorio = :id_obrigatorio";
        $this->conexao->beginTransaction();
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(":id_obrigatorio", $id_obrigatorio);
        $stmt->bindValue(":_usuario_ultima_atualizacao", $_SESSION['id_usuario_smo']);

        if ($stmt->execute()) {
            $this->conexao->commit();
            return true;
        } else {
            //print_r($stmt->errorInfo()); 
            //exit();
            $this->conexao->rollBack();
            return false;
        }
    }

    public function findAllAtivos()
    {
        $obrigatorios = [];
        $stmt = $this->conexao->prepare("
                                            select om.id id_om_1_fase, om.nome nome_om_1_fase, om.abreviatura abreviatura_om_1_fase, om.telefone telefone_om_1_fase, om.endereco endereco_om_1_fase, om.cidade cidade_om_1_fase, om.cep cep_om_1_fase , o.* 
                                            from obrigatorio o
                                            left join om on om.id = o.id_om_1_fase where o.apagado = 0 and id_om = :id_om order by nome_completo asc
                                        ");
        $stmt->bindValue(":id_om", $_SESSION['id_om_smo']);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $data = $stmt->fetchAll();

            foreach ($data as $item) {
                $obrigatorio = $this->buildObrigatorio($item);
                $obrigatorios[] = $obrigatorio;
            }
            return $obrigatorios;
        } else
            return false;
    }

    public function findAllAtivosdaOM()
    {
        $obrigatorios = [];
        $stmt = $this->conexao->prepare("
                                            select om.id id_om_1_fase, om.nome nome_om_1_fase, om.abreviatura abreviatura_om_1_fase, om.telefone telefone_om_1_fase, om.endereco endereco_om_1_fase, om.cidade cidade_om_1_fase, om.cep cep_om_1_fase , o.* 
                                            from obrigatorio o
                                            left join om on om.id = o.id_om_1_fase where o.apagado = 0 and id_om_1_fase = :id_om order by nome_completo asc
                                        ");
        $stmt->bindValue(":id_om", $_SESSION['id_om_smo']);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $data = $stmt->fetchAll();

            foreach ($data as $item) {
                $obrigatorio = $this->buildObrigatorio($item);
                $obrigatorios[] = $obrigatorio;
            }
            return $obrigatorios;
        } else
            return false;
    }

    public function findAll()
    {
        $obrigatorios = [];
        $stmt = $this->conexao->query("
                                        select om.id id_om_1_fase, om.nome nome_om_1_fase, om.abreviatura abreviatura_om_1_fase, om.telefone telefone_om_1_fase, om.endereco endereco_om_1_fase, om.cidade cidade_om_1_fase, om.cep cep_om_1_fase , o.* 
                                        from obrigatorio o
                                        left join om on om.id = o.id_om_1_fase order by nome_completo
                                    ");
        $data = $stmt->fetchAll();

        foreach ($data as $item) {
            $obrigatorio = $this->buildObrigatorio($item);
            $obrigatorios[] = $obrigatorio;
        }

        return $obrigatorios;
    }

    /**
     * Conta o total de obrigatórios inspecionados no ano atual
     * @return int Quantidade de obrigatórios
     */
    public function countObrigatoriosAnoAtual()
    {
        $anoAtual = date('Y');

        $stmt = $this->conexao->prepare("
            SELECT COUNT(*) as total 
            FROM obrigatorio 
            WHERE apagado = 0 
            AND YEAR(data_selecao_geral) = :ano
        ");

        $stmt->bindValue(":ano", $anoAtual);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $data = $stmt->fetch();
            return (int)$data['total'];
        }

        return 0;
    }

    /**
     * Conta o total de obrigatórios incorporados no ano atual
     * @return int Quantidade de obrigatórios incorporados
     */
    public function countObrigatoriosIncorporadosAnoAtual()
    {
        $anoAtual = date('Y');

        $stmt = $this->conexao->prepare("
            SELECT COUNT(*) as total 
            FROM obrigatorio 
            WHERE apagado = 0 
            AND YEAR(data_incorporacao) = :ano
        ");

        $stmt->bindValue(":ano", $anoAtual);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $data = $stmt->fetch();
            return (int)$data['total'];
        }

        return 0;
    }

    /**
     * Conta o total de obrigatórios que tiveram seleção concluída ou em andamento
     * @return int Quantidade de obrigatórios com processo de seleção
     */
    public function countObrigatoriosSelecaoConcluida()
    {
        $stmt = $this->conexao->prepare("
            SELECT COUNT(*) as total 
            FROM obrigatorio 
            WHERE apagado = 0
        ");

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $data = $stmt->fetch();
            return (int)$data['total'];
        }

        return 0;
    }
}


?>

