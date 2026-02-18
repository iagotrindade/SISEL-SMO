
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

    /**
     * Busca todos os obrigatórios ativos (para admin - sem filtro de OM)
     * @return array|false
     */
    public function findAllAtivosAdmin()
    {
        $obrigatorios = [];
        $stmt = $this->conexao->prepare("
            SELECT om.id id_om_1_fase, om.nome nome_om_1_fase, om.abreviatura abreviatura_om_1_fase,
                   om.telefone telefone_om_1_fase, om.endereco endereco_om_1_fase,
                   om.cidade cidade_om_1_fase, om.cep cep_om_1_fase, o.*
            FROM obrigatorio o
            LEFT JOIN om ON om.id = o.id_om_1_fase
            WHERE o.apagado = 0
            ORDER BY o.nome_completo ASC
        ");
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $data = $stmt->fetchAll();
            foreach ($data as $item) {
                $obrigatorio = $this->buildObrigatorio($item);
                $obrigatorios[] = $obrigatorio;
            }
            return $obrigatorios;
        }
        return [];
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
     * Conta o total de obrigatórios incorporados por ano e período
     * @param int|null $ano Ano para filtrar
     * @param array $meses Meses para filtrar
     * @return int Quantidade de obrigatórios incorporados
     */
    public function countIncorporados($ano = null, $meses = [])
    {
        try {
            $params = [];
            $whereAno = "";

            if ($ano !== null) {
                $whereAno = " AND YEAR(data_incorporacao) = ?";
                $params[] = $ano;

                if (!empty($meses)) {
                    $mesesPlaceholders = implode(',', array_fill(0, count($meses), '?'));
                    $whereAno .= " AND MONTH(data_incorporacao) IN ($mesesPlaceholders)";
                    $params = array_merge($params, $meses);
                }
            }

            $sql = "SELECT COUNT(*) as total FROM obrigatorio
                    WHERE apagado = 0
                    AND data_incorporacao IS NOT NULL
                    $whereAno";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result ? (int)$result['total'] : 0;
        } catch (Exception $e) {
            return 0;
        }
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

    /**
     * Conta o total de obrigatórios ativos para uma OM
     * @return int
     */
    public function countAtivos()
    {
        $stmt = $this->conexao->prepare("
            SELECT COUNT(*) as total
            FROM obrigatorio
            WHERE apagado = 0 AND id_om = :id_om
        ");
        $stmt->bindValue(":id_om", $_SESSION['id_om_smo']);
        $stmt->execute();
        $data = $stmt->fetch();
        return (int)($data['total'] ?? 0);
    }

    /**
     * Busca obrigatórios com filtros, paginação e busca
     * @param array $filters Filtros a aplicar
     * @param int $start Offset para paginação
     * @param int $length Quantidade de registros
     * @param string $search Termo de busca
     * @param string $orderColumn Coluna para ordenação
     * @param string $orderDir Direção da ordenação (ASC/DESC)
     * @return array
     */
    public function findAtivosComFiltrosPaginado($filters, $start, $length, $search = '', $orderColumns = null, $orderDir = 'ASC')
    {
        // Retrocompatibilidade: se orderColumns for string, converter para formato array
        if (!is_array($orderColumns)) {
            $orderColumn = $orderColumns ?? 'nome_completo';
            $orderColumns = [['column' => $orderColumn, 'dir' => $orderDir]];
        }

        $params = [];
        $where = ["o.apagado = 0", "o.id_om = :id_om"];
        $params[':id_om'] = $_SESSION['id_om_smo'];

        // Filtro de busca global (nome ou CPF)
        if (!empty($search)) {
            $where[] = "(o.nome_completo LIKE :search OR o.cpf LIKE :search_cpf)";
            $params[':search'] = '%' . $search . '%';
            $params[':search_cpf'] = '%' . $search . '%';
        }

        // Aplicar filtros
        $this->aplicarFiltros($filters, $where, $params);

        // Colunas permitidas para ordenação
        $allowedColumns = [
            'nome_completo' => 'o.nome_completo',
            'cpf' => 'o.cpf',
            'formacao' => 'o.formacao',
            'nome_instituicao_ensino' => 'o.nome_instituicao_ensino',
            'data_nascimento' => 'o.data_nascimento',
            'situacao_militar' => 'o.situacao_militar',
            'especialidade_1' => 'o.especialidade_1',
            'ano_residencia_espe_1' => 'o.ano_residencia_espe_1',
            'rm_destino_fisemi' => 'o.rm_destino_fisemi',
            'abreviatura_om_1_fase' => 'om.abreviatura',
            'compareceu_designacao' => 'o.compareceu_designacao',
            'distribuicao' => 'o.distribuicao',
            'prioridade_forca' => 'o.prioridade_forca',
            'prioridade_gu' => 'prioridade_gu_ordem'
        ];

        // Construir múltiplas cláusulas ORDER BY
        $orderClauses = [];
        $prioridadeGuSelect = "NULL AS prioridade_gu_ordem";

        foreach ($orderColumns as $order) {
            $orderColumn = $order['column'];
            $orderDirection = strtoupper($order['dir']) === 'DESC' ? 'DESC' : 'ASC';

            if (isset($allowedColumns[$orderColumn])) {
                $orderCol = $allowedColumns[$orderColumn];

                // FORMAÇÃO / ANO: ordena por formacao E ano_formacao juntos
                if ($orderColumn === 'formacao') {
                    $orderClauses[] = "o.formacao $orderDirection, o.ano_formacao $orderDirection";
                } else {
                    $orderClauses[] = "$orderCol $orderDirection";
                }

                // Subquery para ordenação por prioridade de guarnição
                if ($orderColumn === 'prioridade_gu' && !empty($filters['guarnicao_filtro'])) {
                    $guIds = array_map('intval', $filters['guarnicao_filtro']);
                    $guIdsStr = implode(',', $guIds);
                    $prioridadeGuSelect = "(SELECT MIN(oxg_ord.prioridade) FROM obrigatorio_x_guarnicao oxg_ord WHERE oxg_ord.id_obrigatorio = o.id AND oxg_ord.apagado = 0 AND oxg_ord.id_guarnicao IN ($guIdsStr)) AS prioridade_gu_ordem";
                }
            }
        }

        // Se nenhuma ordenação válida foi definida, usar padrão
        if (empty($orderClauses)) {
            $orderClauses[] = "o.nome_completo ASC";
        }

        $orderByClause = implode(', ', $orderClauses);

        // LOG: Verificar cláusula ORDER BY gerada
        error_log("DAO - ORDER BY: $orderByClause");

        $sql = "
            SELECT om.id id_om_1_fase, om.nome nome_om_1_fase, om.abreviatura abreviatura_om_1_fase,
                   om.telefone telefone_om_1_fase, om.endereco endereco_om_1_fase,
                   om.cidade cidade_om_1_fase, om.cep cep_om_1_fase, o.*,
                   $prioridadeGuSelect
            FROM obrigatorio o
            LEFT JOIN om ON om.id = o.id_om_1_fase
            WHERE " . implode(" AND ", $where) . "
            ORDER BY $orderByClause
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->conexao->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', (int)$length, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$start, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Conta obrigatórios com filtros e busca aplicados
     * @param array $filters Filtros a aplicar
     * @param string $search Termo de busca
     * @return int
     */
    public function countAtivosComFiltros($filters, $search = '')
    {
        $params = [];
        $where = ["o.apagado = 0", "o.id_om = :id_om"];
        $params[':id_om'] = $_SESSION['id_om_smo'];

        // Filtro de busca global
        if (!empty($search)) {
            $where[] = "(o.nome_completo LIKE :search OR o.cpf LIKE :search_cpf)";
            $params[':search'] = '%' . $search . '%';
            $params[':search_cpf'] = '%' . $search . '%';
        }

        // Aplicar filtros
        $this->aplicarFiltros($filters, $where, $params);

        $sql = "
            SELECT COUNT(*) as total
            FROM obrigatorio o
            LEFT JOIN om ON om.id = o.id_om_1_fase
            WHERE " . implode(" AND ", $where);

        $stmt = $this->conexao->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $data = $stmt->fetch();
        return (int)($data['total'] ?? 0);
    }

    /**
     * Aplica filtros à query
     * @param array $filters Filtros
     * @param array &$where Referência para condições WHERE
     * @param array &$params Referência para parâmetros
     */
    private function aplicarFiltros($filters, &$where, &$params)
    {
        // Voluntário
        if (!empty($filters['voluntario_filtro'])) {
            $placeholders = [];
            foreach ($filters['voluntario_filtro'] as $i => $val) {
                $key = ":vol_$i";
                $placeholders[] = $key;
                $params[$key] = $val;
            }
            $where[] = "o.voluntario IN (" . implode(',', $placeholders) . ")";
        }

        // Dependentes
        if (!empty($filters['dependentes_filtro'])) {
            if ($filters['dependentes_filtro'] === 'nenhum') {
                $where[] = "(o.dependentes = 0 OR o.dependentes IS NULL)";
            } elseif ($filters['dependentes_filtro'] === 'possui_dependente') {
                $where[] = "(o.dependentes > 0)";
            }
        }

        // Faculdade
        if (!empty($filters['faculdade_filtro'])) {
            $placeholders = [];
            foreach ($filters['faculdade_filtro'] as $i => $val) {
                $key = ":fac_$i";
                $placeholders[] = $key;
                $params[$key] = $val;
            }
            $where[] = "o.nome_instituicao_ensino IN (" . implode(',', $placeholders) . ")";
        }

        // JISE
        if (!empty($filters['jise_filtro'])) {
            $placeholders = [];
            foreach ($filters['jise_filtro'] as $i => $val) {
                $key = ":jise_$i";
                $placeholders[] = $key;
                $params[$key] = $val;
            }
            $where[] = "o.jise IN (" . implode(',', $placeholders) . ")";
        }

        // JISR
        if (!empty($filters['jisr_filtro'])) {
            $placeholders = [];
            foreach ($filters['jisr_filtro'] as $i => $val) {
                $key = ":jisr_$i";
                $placeholders[] = $key;
                $params[$key] = $val;
            }
            $where[] = "o.jisr IN (" . implode(',', $placeholders) . ")";
        }

        // Distribuição
        if (!empty($filters['distribuicao_filtro'])) {
            $placeholders = [];
            foreach ($filters['distribuicao_filtro'] as $i => $val) {
                $key = ":dist_$i";
                $placeholders[] = $key;
                $params[$key] = $val;
            }
            $where[] = "o.distribuicao IN (" . implode(',', $placeholders) . ")";
        }

        // OM 1ª Fase
        if (!empty($filters['om_1_fase_filtro'])) {
            $placeholders = [];
            foreach ($filters['om_1_fase_filtro'] as $i => $val) {
                $key = ":om1_$i";
                $placeholders[] = $key;
                $params[$key] = $val;
            }
            $where[] = "om.abreviatura IN (" . implode(',', $placeholders) . ")";
        }

        // Resultado Revisão
        if (!empty($filters['resultado_revisao_filtro'])) {
            $placeholders = [];
            foreach ($filters['resultado_revisao_filtro'] as $i => $val) {
                $key = ":rev_$i";
                $placeholders[] = $key;
                $params[$key] = $val;
            }
            $where[] = "o.resultado_revisao_medica_complementar IN (" . implode(',', $placeholders) . ")";
        }

        // ISGR
        if (!empty($filters['isgr_filtro'])) {
            $placeholders = [];
            foreach ($filters['isgr_filtro'] as $i => $val) {
                $key = ":isgr_$i";
                $placeholders[] = $key;
                $params[$key] = $val;
            }
            $where[] = "o.resultado_isgr IN (" . implode(',', $placeholders) . ")";
        }

        // Data Seleção Geral
        if (!empty($filters['sel_geral_filtro'])) {
            $placeholders = [];
            foreach ($filters['sel_geral_filtro'] as $i => $val) {
                $key = ":sel_$i";
                $placeholders[] = $key;
                $params[$key] = $val;
            }
            $where[] = "o.data_selecao_geral IN (" . implode(',', $placeholders) . ")";
        }

        // Comparecimento Designação
        if (!empty($filters['comp_designacao_filtro'])) {
            $placeholders = [];
            foreach ($filters['comp_designacao_filtro'] as $i => $val) {
                $key = ":comp_$i";
                $placeholders[] = $key;
                $params[$key] = $val;
            }
            $where[] = "o.data_comparecimento_designacao IN (" . implode(',', $placeholders) . ")";
        }

        // Incorporação
        if (!empty($filters['incorporacao_filtro'])) {
            $placeholders = [];
            foreach ($filters['incorporacao_filtro'] as $i => $val) {
                $key = ":incorp_$i";
                $placeholders[] = $key;
                $params[$key] = $val;
            }
            $where[] = "o.data_incorporacao IN (" . implode(',', $placeholders) . ")";
        }

        // Seleção Complementar
        if (!empty($filters['sel_complementar_filtro'])) {
            $placeholders = [];
            foreach ($filters['sel_complementar_filtro'] as $i => $val) {
                $key = ":selcomp_$i";
                $placeholders[] = $key;
                $params[$key] = $val;
            }
            $where[] = "o.data_selecao_complementar IN (" . implode(',', $placeholders) . ")";
        }

        // Situação Militar
        if (!empty($filters['situacao_militar'])) {
            $placeholders = [];
            foreach ($filters['situacao_militar'] as $i => $val) {
                $key = ":sit_$i";
                $placeholders[] = $key;
                $params[$key] = $val;
            }
            $where[] = "o.situacao_militar IN (" . implode(',', $placeholders) . ")";
        }

        // RM Destino
        if (!empty($filters['rm_destino_filtro'])) {
            $placeholders = [];
            foreach ($filters['rm_destino_filtro'] as $i => $val) {
                $key = ":rm_$i";
                $placeholders[] = $key;
                $params[$key] = $val;
            }
            $where[] = "o.rm_destino_fisemi IN (" . implode(',', $placeholders) . ")";
        }

        // Especialidade
        if (!empty($filters['especialidade_filtro'])) {
            if (!in_array('todas_espec', $filters['especialidade_filtro'])) {
                $placeholders = [];
                foreach ($filters['especialidade_filtro'] as $i => $val) {
                    $key = ":esp_$i";
                    $placeholders[] = $key;
                    $params[$key] = $val;
                }
                $where[] = "o.especialidade_1 IN (" . implode(',', $placeholders) . ")";
            } else {
                $where[] = "o.especialidade_1 IS NOT NULL";
            }
        }

        // Prioridade Força
        if (!empty($filters['prioridade_forca_filtro'])) {
            $placeholders = [];
            foreach ($filters['prioridade_forca_filtro'] as $i => $val) {
                $key = ":prio_$i";
                $placeholders[] = $key;
                $params[$key] = $val;
            }
            $where[] = "o.prioridade_forca IN (" . implode(',', $placeholders) . ")";
        }

        // Guarnição (prioridade de guarnição)
        if (!empty($filters['guarnicao_filtro'])) {
            $placeholders = [];
            foreach ($filters['guarnicao_filtro'] as $i => $val) {
                $key = ":gu_$i";
                $placeholders[] = $key;
                $params[$key] = $val;
            }
            $where[] = "EXISTS (SELECT 1 FROM obrigatorio_x_guarnicao oxg WHERE oxg.id_obrigatorio = o.id AND oxg.apagado = 0 AND oxg.id_guarnicao IN (" . implode(',', $placeholders) . "))";
        }

        // Semestre (range de datas)
        if (!empty($filters['data_semestre'])) {
            $where[] = "o.data_selecao_geral BETWEEN :sem_inicio AND :sem_fim";
            $params[':sem_inicio'] = $filters['data_semestre'][0];
            $params[':sem_fim'] = $filters['data_semestre'][1];
        }

        // Formação
        if (!empty($filters['formacao_filtro'])) {
            $where[] = "o.formacao = :formacao";
            $params[':formacao'] = $filters['formacao_filtro'];
        }

        // Compareceu Designação (sim/nao)
        if (!empty($filters['compareceu_designacao_filtro'])) {
            $where[] = "o.compareceu_designacao = :comp_desig";
            $params[':comp_desig'] = $filters['compareceu_designacao_filtro'];
        }

        // Local Compareceu Designação
        if (!empty($filters['local_compareceu_designacao_filtro'])) {
            $where[] = "o.local_compareceu_designacao = :local_comp";
            $params[':local_comp'] = $filters['local_compareceu_designacao_filtro'];
        }
    }

    /**
     * Busca obrigatorios para o Aditamento BAR
     * @param string $distribuicao Tipo de distribuicao
     * @param int $ano Ano do filtro data_comparecimento_designacao
     * @return array Lista de objetos Obrigatorio
     */
    public function findParaAditamentoBAR($distribuicao, $ano)
    {
        $obrigatorios = [];

        $sql = "SELECT om.id id_om_1_fase, om.nome nome_om_1_fase, om.abreviatura abreviatura_om_1_fase,
                       om.telefone telefone_om_1_fase, om.endereco endereco_om_1_fase,
                       om.cidade cidade_om_1_fase, om.cep cep_om_1_fase, o.*,
                       COALESCE(ofi.data_cabecalho, o.data_incorporacao) as data_incorporacao
                FROM obrigatorio o
                LEFT JOIN om ON om.id = o.id_om_1_fase
                LEFT JOIN oficio ofi ON ofi.id_obrigatorio = o.id
                WHERE o.apagado = 0
                AND o.distribuicao = :distribuicao
                AND o.data_comparecimento_designacao IS NOT NULL
                AND YEAR(o.data_comparecimento_designacao) = :ano
                ORDER BY om.cidade ASC, o.nome_completo ASC";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':distribuicao', $distribuicao);
        $stmt->bindValue(':ano', (int)$ano, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $data = $stmt->fetchAll();

            foreach ($data as $item) {
                $obrigatorio = $this->buildObrigatorio($item);
                $obrigatorios[] = $obrigatorio;
            }
            return $obrigatorios;
        }

        return [];
    }

    // ==================== MÉTODOS PARA DASHBOARD ANALÍTICO ====================

    /**
     * Estatísticas por Especialidade
     * @param int $ano Ano para filtrar
     * @return array ['labels' => [...], 'data' => [...]]
     */
    public function getEstatisticasPorEspecialidade($ano = null, $meses = [])
    {
        $ano = $ano ?? date('Y');
        $meses = !empty($meses) ? $meses : [1,2,3,4,5,6,7,8,9,10,11,12];

        $mesesPlaceholders = implode(',', array_fill(0, count($meses), '?'));

        $sql = "SELECT
                    COALESCE(NULLIF(especialidade_1, ''), 'Não informada') as especialidade,
                    COUNT(*) as total
                FROM obrigatorio
                WHERE apagado = 0 AND YEAR(data_selecao_geral) = ? AND MONTH(data_selecao_geral) IN ($mesesPlaceholders)
                GROUP BY COALESCE(NULLIF(especialidade_1, ''), 'Não informada')
                ORDER BY total DESC
                LIMIT 10";

        $stmt = $this->conexao->prepare($sql);
        $params = array_merge([(int)$ano], $meses);
        $stmt->execute($params);

        $labels = [];
        $data = [];

        if ($stmt->rowCount() > 0) {
            $results = $stmt->fetchAll();
            foreach ($results as $row) {
                $labels[] = $row['especialidade'];
                $data[] = (int)$row['total'];
            }
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Estatísticas por Situação Militar
     * @param int $ano Ano para filtrar
     * @return array ['labels' => [...], 'data' => [...]]
     */
    public function getEstatisticasPorSituacaoMilitar($ano = null, $meses = [])
    {
        $ano = $ano ?? date('Y');
        $meses = !empty($meses) ? $meses : [1,2,3,4,5,6,7,8,9,10,11,12];

        $mesesPlaceholders = implode(',', array_fill(0, count($meses), '?'));

        $sql = "SELECT
                    COALESCE(NULLIF(situacao_militar, ''), 'Não informada') as situacao,
                    COUNT(*) as total
                FROM obrigatorio
                WHERE apagado = 0 AND YEAR(data_selecao_geral) = ? AND MONTH(data_selecao_geral) IN ($mesesPlaceholders)
                GROUP BY COALESCE(NULLIF(situacao_militar, ''), 'Não informada')
                ORDER BY total DESC";

        $stmt = $this->conexao->prepare($sql);
        $params = array_merge([(int)$ano], $meses);
        $stmt->execute($params);

        $labels = [];
        $data = [];

        if ($stmt->rowCount() > 0) {
            $results = $stmt->fetchAll();
            foreach ($results as $row) {
                $labels[] = $row['situacao'];
                $data[] = (int)$row['total'];
            }
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Estatísticas por Resultado de Revisão Médica
     * @param int $ano Ano para filtrar
     * @return array ['labels' => [...], 'data' => [...]]
     */
    public function getEstatisticasPorResultadoRevisao($ano = null, $meses = [])
    {
        $ano = $ano ?? date('Y');
        $meses = !empty($meses) ? $meses : [1,2,3,4,5,6,7,8,9,10,11,12];

        $mesesPlaceholders = implode(',', array_fill(0, count($meses), '?'));

        $sql = "SELECT
                    CASE
                        WHEN resultado_revisao_medica_complementar IS NULL OR resultado_revisao_medica_complementar = '' THEN 'PENDENTE'
                        ELSE resultado_revisao_medica_complementar
                    END as resultado,
                    COUNT(*) as total
                FROM obrigatorio
                WHERE apagado = 0 AND YEAR(data_selecao_geral) = ? AND MONTH(data_selecao_geral) IN ($mesesPlaceholders)
                GROUP BY resultado
                ORDER BY total DESC";

        $stmt = $this->conexao->prepare($sql);
        $params = array_merge([(int)$ano], $meses);
        $stmt->execute($params);

        $labels = [];
        $data = [];

        if ($stmt->rowCount() > 0) {
            $results = $stmt->fetchAll();
            foreach ($results as $row) {
                $labels[] = $row['resultado'];
                $data[] = (int)$row['total'];
            }
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Estatísticas de Evolução Mensal de Incorporações
     * @param int $ano Ano para filtrar
     * @return array ['labels' => [...], 'data' => [...]]
     */
    public function getEstatisticasMensais($ano = null, $meses = [])
    {
        $ano = $ano ?? date('Y');
        $mesesFiltro = !empty($meses) ? $meses : [1,2,3,4,5,6,7,8,9,10,11,12];

        $mesesPlaceholders = implode(',', array_fill(0, count($mesesFiltro), '?'));

        $sql = "SELECT
                    MONTH(data_incorporacao) as mes,
                    COUNT(*) as total
                FROM obrigatorio
                WHERE apagado = 0
                AND data_incorporacao IS NOT NULL
                AND YEAR(data_incorporacao) = ?
                AND MONTH(data_incorporacao) IN ($mesesPlaceholders)
                GROUP BY MONTH(data_incorporacao)
                ORDER BY mes ASC";

        $stmt = $this->conexao->prepare($sql);
        $params = array_merge([(int)$ano], $mesesFiltro);
        $stmt->execute($params);

        $nomeMeses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

        // Filtra apenas os meses selecionados
        $labelsExibir = [];
        $dadosExibir = [];
        $dadosTemp = array_fill(0, 12, 0);

        if ($stmt->rowCount() > 0) {
            $results = $stmt->fetchAll();
            foreach ($results as $row) {
                $mesIndex = (int)$row['mes'] - 1;
                $dadosTemp[$mesIndex] = (int)$row['total'];
            }
        }

        // Exibe apenas os meses filtrados
        foreach ($mesesFiltro as $mes) {
            $labelsExibir[] = $nomeMeses[$mes - 1];
            $dadosExibir[] = $dadosTemp[$mes - 1];
        }

        return ['labels' => $labelsExibir, 'data' => $dadosExibir];
    }

    /**
     * Estatísticas por OM 1ª Fase
     * @param int $ano Ano para filtrar
     * @return array ['labels' => [...], 'data' => [...]]
     */
    public function getEstatisticasPorOM($ano = null, $meses = [])
    {
        $ano = $ano ?? date('Y');
        $meses = !empty($meses) ? $meses : [1,2,3,4,5,6,7,8,9,10,11,12];

        $mesesPlaceholders = implode(',', array_fill(0, count($meses), '?'));

        $sql = "SELECT
                    COALESCE(om.abreviatura, 'Não distribuído') as om_nome,
                    COUNT(*) as total
                FROM obrigatorio o
                LEFT JOIN om ON om.id = o.id_om_1_fase
                WHERE o.apagado = 0 AND YEAR(o.data_selecao_geral) = ? AND MONTH(o.data_selecao_geral) IN ($mesesPlaceholders)
                GROUP BY om.abreviatura
                ORDER BY total DESC
                LIMIT 10";

        $stmt = $this->conexao->prepare($sql);
        $params = array_merge([(int)$ano], $meses);
        $stmt->execute($params);

        $labels = [];
        $data = [];

        if ($stmt->rowCount() > 0) {
            $results = $stmt->fetchAll();
            foreach ($results as $row) {
                $labels[] = $row['om_nome'];
                $data[] = (int)$row['total'];
            }
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Estatísticas por Classificação JISE
     * @param int $ano Ano para filtrar
     * @return array ['labels' => [...], 'data' => [...]]
     */
    public function getEstatisticasPorJISE($ano = null, $meses = [])
    {
        $ano = $ano ?? date('Y');
        $meses = !empty($meses) ? $meses : [1,2,3,4,5,6,7,8,9,10,11,12];

        $mesesPlaceholders = implode(',', array_fill(0, count($meses), '?'));

        $sql = "SELECT
                    CASE
                        WHEN jise IS NULL OR jise = '' THEN 'Não avaliado'
                        ELSE jise
                    END as classificacao,
                    COUNT(*) as total
                FROM obrigatorio
                WHERE apagado = 0 AND YEAR(data_selecao_geral) = ? AND MONTH(data_selecao_geral) IN ($mesesPlaceholders)
                GROUP BY classificacao
                ORDER BY
                    CASE classificacao
                        WHEN 'A' THEN 1
                        WHEN 'B1' THEN 2
                        WHEN 'B2' THEN 3
                        WHEN 'C' THEN 4
                        ELSE 5
                    END";

        $stmt = $this->conexao->prepare($sql);
        $params = array_merge([(int)$ano], $meses);
        $stmt->execute($params);

        $labels = [];
        $data = [];

        if ($stmt->rowCount() > 0) {
            $results = $stmt->fetchAll();
            foreach ($results as $row) {
                $labels[] = $row['classificacao'];
                $data[] = (int)$row['total'];
            }
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Conta obrigatórios pendentes de distribuição (sem OM de 1ª fase)
     * @param int|null $ano Ano para filtrar (null = todos)
     * @param array $meses Meses para filtrar (vazio = todos)
     * @return int
     */
    public function countPendentesDistribuicao($ano = null, $meses = [])
    {
        try {
            $params = [];
            $whereAno = "";

            if ($ano !== null) {
                $whereAno = " AND YEAR(data_selecao_geral) = ?";
                $params[] = $ano;

                if (!empty($meses)) {
                    $mesesPlaceholders = implode(',', array_fill(0, count($meses), '?'));
                    $whereAno .= " AND MONTH(data_selecao_geral) IN ($mesesPlaceholders)";
                    $params = array_merge($params, $meses);
                }
            }

            $sql = "SELECT COUNT(*) as total FROM obrigatorio
                    WHERE apagado = 0
                    AND data_selecao_geral IS NOT NULL
                    $whereAno
                    AND (id_om_1_fase IS NULL OR id_om_1_fase = 0 OR id_om_1_fase = '')
                    AND (distribuicao IS NULL OR distribuicao = '')
                    AND (resultado_revisao_medica_complementar IS NULL OR resultado_revisao_medica_complementar NOT IN ('INAPTO', 'NÃO COMPARECEU'))";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result ? (int)$result['total'] : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Conta obrigatórios pendentes de revisão médica (já distribuídos mas sem resultado)
     * @param int|null $ano Ano para filtrar (null = todos)
     * @param array $meses Meses para filtrar (vazio = todos)
     * @return int
     */
    public function countPendentesRevisaoMedica($ano = null, $meses = [])
    {
        try {
            $params = [];
            $whereAno = "";

            if ($ano !== null) {
                $whereAno = " AND YEAR(data_selecao_geral) = ?";
                $params[] = $ano;

                if (!empty($meses)) {
                    $mesesPlaceholders = implode(',', array_fill(0, count($meses), '?'));
                    $whereAno .= " AND MONTH(data_selecao_geral) IN ($mesesPlaceholders)";
                    $params = array_merge($params, $meses);
                }
            }

            // Filtro consistente com countPendentesRevisaoOM - exclui distribuições de outras forças
            $sql = "SELECT COUNT(*) as total FROM obrigatorio
                    WHERE apagado = 0
                    AND data_selecao_geral IS NOT NULL
                    $whereAno
                    AND id_om_1_fase IS NOT NULL
                    AND id_om_1_fase > 0
                    AND distribuicao IS NOT NULL
                    AND distribuicao != ''
                    AND distribuicao NOT IN ('EXCESSO CONTINGENTE', 'MARINHA', 'FORÇA AÉREA')
                    AND (resultado_revisao_medica_complementar IS NULL OR resultado_revisao_medica_complementar = '')";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result ? (int)$result['total'] : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Conta obrigatórios com adiamento ativo (solicitou adiamento e ainda está vigente)
     * @return int
     */
    public function countAdiamentosAtivos()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM obrigatorio
                    WHERE apagado = 0
                    AND situacao_militar = 'Em Dia - ADIADO CURSANDO RESIDÊNCIA'";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result ? (int)$result['total'] : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Conta obrigatórios inaptos na revisão médica
     * @param int|null $ano Ano para filtrar (null = todos)
     * @param array $meses Meses para filtrar (vazio = todos)
     * @return int
     */
    public function countInaptos($ano = null, $meses = [])
    {
        try {
            $params = [];
            $whereAno = "";

            if ($ano !== null) {
                $whereAno = " AND YEAR(data_selecao_geral) = ?";
                $params[] = $ano;

                if (!empty($meses)) {
                    $mesesPlaceholders = implode(',', array_fill(0, count($meses), '?'));
                    $whereAno .= " AND MONTH(data_selecao_geral) IN ($mesesPlaceholders)";
                    $params = array_merge($params, $meses);
                }
            }

            // Filtro consistente com countInaptosOM - exclui distribuições de outras forças
            $sql = "SELECT COUNT(*) as total FROM obrigatorio
                    WHERE apagado = 0
                    AND data_selecao_geral IS NOT NULL
                    $whereAno
                    AND resultado_revisao_medica_complementar = 'INAPTO'
                    AND distribuicao IS NOT NULL
                    AND distribuicao != ''
                    AND distribuicao NOT IN ('EXCESSO CONTINGENTE', 'MARINHA', 'FORÇA AÉREA')";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result ? (int)$result['total'] : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Conta total de obrigatórios designados para OM (já têm OM atribuída)
     * @param int|null $ano Ano para filtrar
     * @param array $meses Meses para filtrar
     * @return int
     */
    public function countDesignados($ano = null, $meses = [])
    {
        try {
            $params = [];
            $whereAno = "";

            if ($ano !== null) {
                $whereAno = " AND YEAR(data_selecao_geral) = ?";
                $params[] = $ano;

                if (!empty($meses)) {
                    $mesesPlaceholders = implode(',', array_fill(0, count($meses), '?'));
                    $whereAno .= " AND MONTH(data_selecao_geral) IN ($mesesPlaceholders)";
                    $params = array_merge($params, $meses);
                }
            }

            // Filtro consistente com countDesignadosOM - exclui distribuições de outras forças
            $sql = "SELECT COUNT(*) as total FROM obrigatorio
                    WHERE apagado = 0
                    AND data_selecao_geral IS NOT NULL
                    $whereAno
                    AND id_om_1_fase IS NOT NULL
                    AND id_om_1_fase > 0
                    AND distribuicao IS NOT NULL
                    AND distribuicao != ''
                    AND distribuicao NOT IN ('EXCESSO CONTINGENTE', 'MARINHA', 'FORÇA AÉREA')";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result ? (int)$result['total'] : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Conta total de obrigatórios APTOS na revisão médica
     * @param int|null $ano Ano para filtrar
     * @param array $meses Meses para filtrar
     * @return int
     */
    public function countAptos($ano = null, $meses = [])
    {
        try {
            $params = [];
            $whereAno = "";

            if ($ano !== null) {
                $whereAno = " AND YEAR(data_selecao_geral) = ?";
                $params[] = $ano;

                if (!empty($meses)) {
                    $mesesPlaceholders = implode(',', array_fill(0, count($meses), '?'));
                    $whereAno .= " AND MONTH(data_selecao_geral) IN ($mesesPlaceholders)";
                    $params = array_merge($params, $meses);
                }
            }

            // Filtro consistente com countAptosOM - exclui distribuições de outras forças
            $sql = "SELECT COUNT(*) as total FROM obrigatorio
                    WHERE apagado = 0
                    AND data_selecao_geral IS NOT NULL
                    $whereAno
                    AND resultado_revisao_medica_complementar = 'APTO'
                    AND distribuicao IS NOT NULL
                    AND distribuicao != ''
                    AND distribuicao NOT IN ('EXCESSO CONTINGENTE', 'MARINHA', 'FORÇA AÉREA')";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result ? (int)$result['total'] : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Conta incorporados por ano de SELEÇÃO (não por ano de incorporação)
     * Mantém consistência com os outros indicadores
     * @param int|null $ano Ano para filtrar (da seleção)
     * @param array $meses Meses para filtrar
     * @return int
     */
    public function countIncorporadosPorAnoSelecao($ano = null, $meses = [])
    {
        try {
            $params = [];
            $whereAno = "";

            if ($ano !== null) {
                $whereAno = " AND YEAR(data_selecao_geral) = ?";
                $params[] = $ano;

                if (!empty($meses)) {
                    $mesesPlaceholders = implode(',', array_fill(0, count($meses), '?'));
                    $whereAno .= " AND MONTH(data_selecao_geral) IN ($mesesPlaceholders)";
                    $params = array_merge($params, $meses);
                }
            }

            // Filtro consistente - exclui distribuições de outras forças
            $sql = "SELECT COUNT(*) as total FROM obrigatorio
                    WHERE apagado = 0
                    AND data_selecao_geral IS NOT NULL
                    $whereAno
                    AND data_incorporacao IS NOT NULL
                    AND distribuicao IS NOT NULL
                    AND distribuicao != ''
                    AND distribuicao NOT IN ('EXCESSO CONTINGENTE', 'MARINHA', 'FORÇA AÉREA')";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result ? (int)$result['total'] : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Conta APTOS aguardando incorporação (aprovados mas não incorporados)
     * @param int|null $ano Ano para filtrar
     * @param array $meses Meses para filtrar
     * @return int
     */
    public function countAguardandoIncorporacao($ano = null, $meses = [])
    {
        try {
            $params = [];
            $whereAno = "";

            if ($ano !== null) {
                $whereAno = " AND YEAR(data_selecao_geral) = ?";
                $params[] = $ano;

                if (!empty($meses)) {
                    $mesesPlaceholders = implode(',', array_fill(0, count($meses), '?'));
                    $whereAno .= " AND MONTH(data_selecao_geral) IN ($mesesPlaceholders)";
                    $params = array_merge($params, $meses);
                }
            }

            // Filtro consistente - exclui distribuições de outras forças
            $sql = "SELECT COUNT(*) as total FROM obrigatorio
                    WHERE apagado = 0
                    AND data_selecao_geral IS NOT NULL
                    $whereAno
                    AND resultado_revisao_medica_complementar = 'APTO'
                    AND (data_incorporacao IS NULL)
                    AND distribuicao IS NOT NULL
                    AND distribuicao != ''
                    AND distribuicao NOT IN ('EXCESSO CONTINGENTE', 'MARINHA', 'FORÇA AÉREA')";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result ? (int)$result['total'] : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    // ==========================================
    // MÉTODOS ESPECÍFICOS PARA OPERADORES DE OM
    // ==========================================

    /**
     * Conta total de designados para uma OM específica
     * @param int $id_om ID da OM
     * @param int $ano Ano para filtrar
     * @return int
     */
    public function countDesignadosOM($id_om, $ano = null)
    {
        try {
            $ano = $ano ?? date('Y');
            $sql = "SELECT COUNT(*) as total FROM obrigatorio
                    WHERE apagado = 0
                    AND id_om_1_fase = ?
                    AND YEAR(data_selecao_geral) = ?
                    AND distribuicao IS NOT NULL
                    AND distribuicao != ''
                    AND distribuicao NOT IN ('EXCESSO CONTINGENTE', 'MARINHA', 'FORÇA AÉREA')";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([$id_om, $ano]);
            $result = $stmt->fetch();
            return $result ? (int)$result['total'] : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Conta total de aptos para uma OM específica
     * @param int $id_om ID da OM
     * @param int $ano Ano para filtrar
     * @return int
     */
    public function countAptosOM($id_om, $ano = null)
    {
        try {
            $ano = $ano ?? date('Y');
            $sql = "SELECT COUNT(*) as total FROM obrigatorio
                    WHERE apagado = 0
                    AND id_om_1_fase = ?
                    AND YEAR(data_selecao_geral) = ?
                    AND resultado_revisao_medica_complementar = 'APTO'
                    AND distribuicao IS NOT NULL
                    AND distribuicao != ''
                    AND distribuicao NOT IN ('EXCESSO CONTINGENTE', 'MARINHA', 'FORÇA AÉREA')";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([$id_om, $ano]);
            $result = $stmt->fetch();
            return $result ? (int)$result['total'] : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Conta total de inaptos para uma OM específica
     * @param int $id_om ID da OM
     * @param int $ano Ano para filtrar
     * @return int
     */
    public function countInaptosOM($id_om, $ano = null)
    {
        try {
            $ano = $ano ?? date('Y');
            $sql = "SELECT COUNT(*) as total FROM obrigatorio
                    WHERE apagado = 0
                    AND id_om_1_fase = ?
                    AND YEAR(data_selecao_geral) = ?
                    AND resultado_revisao_medica_complementar = 'INAPTO'
                    AND distribuicao IS NOT NULL
                    AND distribuicao != ''
                    AND distribuicao NOT IN ('EXCESSO CONTINGENTE', 'MARINHA', 'FORÇA AÉREA')";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([$id_om, $ano]);
            $result = $stmt->fetch();
            return $result ? (int)$result['total'] : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Conta pendentes de revisão médica para uma OM específica
     * @param int $id_om ID da OM
     * @param int $ano Ano para filtrar
     * @return int
     */
    public function countPendentesRevisaoOM($id_om, $ano = null)
    {
        try {
            $ano = $ano ?? date('Y');
            $sql = "SELECT COUNT(*) as total FROM obrigatorio
                    WHERE apagado = 0
                    AND id_om_1_fase = ?
                    AND YEAR(data_selecao_geral) = ?
                    AND (resultado_revisao_medica_complementar IS NULL OR resultado_revisao_medica_complementar = '')
                    AND distribuicao IS NOT NULL
                    AND distribuicao != ''
                    AND distribuicao NOT IN ('EXCESSO CONTINGENTE', 'MARINHA', 'FORÇA AÉREA')";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([$id_om, $ano]);
            $result = $stmt->fetch();
            return $result ? (int)$result['total'] : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Estatísticas de Resultado de Revisão Médica para uma OM
     * @param int $id_om ID da OM
     * @param int $ano Ano para filtrar
     * @return array ['labels' => [...], 'data' => [...]]
     */
    public function getEstatisticasResultadoRevisaoOM($id_om, $ano = null)
    {
        try {
            $ano = $ano ?? date('Y');
            $sql = "SELECT
                        CASE
                            WHEN resultado_revisao_medica_complementar IS NULL OR resultado_revisao_medica_complementar = '' THEN 'PENDENTE'
                            ELSE resultado_revisao_medica_complementar
                        END as resultado,
                        COUNT(*) as total
                    FROM obrigatorio
                    WHERE apagado = 0
                    AND id_om_1_fase = ?
                    AND YEAR(data_selecao_geral) = ?
                    AND distribuicao IS NOT NULL
                    AND distribuicao != ''
                    AND distribuicao NOT IN ('EXCESSO CONTINGENTE', 'MARINHA', 'FORÇA AÉREA')
                    GROUP BY resultado
                    ORDER BY total DESC";

            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([$id_om, $ano]);

            $labels = [];
            $data = [];

            if ($stmt->rowCount() > 0) {
                $results = $stmt->fetchAll();
                foreach ($results as $row) {
                    $labels[] = $row['resultado'];
                    $data[] = (int)$row['total'];
                }
            }

            return ['labels' => $labels, 'data' => $data];
        } catch (Exception $e) {
            return ['labels' => [], 'data' => []];
        }
    }

    /**
     * Estatísticas por Especialidade para uma OM
     * @param int $id_om ID da OM
     * @param int $ano Ano para filtrar
     * @return array ['labels' => [...], 'data' => [...]]
     */
    public function getEstatisticasEspecialidadeOM($id_om, $ano = null)
    {
        try {
            $ano = $ano ?? date('Y');
            $sql = "SELECT
                        COALESCE(NULLIF(especialidade_1, ''), 'Não informada') as especialidade,
                        COUNT(*) as total
                    FROM obrigatorio
                    WHERE apagado = 0
                    AND id_om_1_fase = ?
                    AND YEAR(data_selecao_geral) = ?
                    AND distribuicao IS NOT NULL
                    AND distribuicao != ''
                    AND distribuicao NOT IN ('EXCESSO CONTINGENTE', 'MARINHA', 'FORÇA AÉREA')
                    GROUP BY especialidade
                    ORDER BY total DESC
                    LIMIT 10";

            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([$id_om, $ano]);

            $labels = [];
            $data = [];

            if ($stmt->rowCount() > 0) {
                $results = $stmt->fetchAll();
                foreach ($results as $row) {
                    $labels[] = $row['especialidade'];
                    $data[] = (int)$row['total'];
                }
            }

            return ['labels' => $labels, 'data' => $data];
        } catch (Exception $e) {
            return ['labels' => [], 'data' => []];
        }
    }

    /**
     * Estatísticas por Tipo de Distribuição para uma OM
     * @param int $id_om ID da OM
     * @param int $ano Ano para filtrar
     * @return array ['labels' => [...], 'data' => [...]]
     */
    public function getEstatisticasDistribuicaoOM($id_om, $ano = null)
    {
        try {
            $ano = $ano ?? date('Y');
            $sql = "SELECT
                        COALESCE(NULLIF(distribuicao, ''), 'Não definido') as tipo_distribuicao,
                        COUNT(*) as total
                    FROM obrigatorio
                    WHERE apagado = 0
                    AND id_om_1_fase = ?
                    AND YEAR(data_selecao_geral) = ?
                    AND distribuicao IS NOT NULL
                    AND distribuicao != ''
                    AND distribuicao NOT IN ('EXCESSO CONTINGENTE', 'MARINHA', 'FORÇA AÉREA')
                    GROUP BY tipo_distribuicao
                    ORDER BY total DESC";

            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([$id_om, $ano]);

            $labels = [];
            $data = [];

            if ($stmt->rowCount() > 0) {
                $results = $stmt->fetchAll();
                foreach ($results as $row) {
                    $labels[] = $row['tipo_distribuicao'];
                    $data[] = (int)$row['total'];
                }
            }

            return ['labels' => $labels, 'data' => $data];
        } catch (Exception $e) {
            return ['labels' => [], 'data' => []];
        }
    }

    /**
     * Busca todos os obrigatórios ativos por ano e período
     * @param int $ano Ano para filtrar
     * @param array $meses Meses para filtrar
     * @return array Lista de obrigatórios
     */
    public function findAllAtivosPorAno($ano = null, $meses = [])
    {
        try {
            $ano = $ano ?? date('Y');
            $meses = !empty($meses) ? $meses : [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

            $mesesPlaceholders = implode(',', array_fill(0, count($meses), '?'));

            $sql = "SELECT om.id id_om_1_fase, om.nome nome_om_1_fase, om.abreviatura abreviatura_om_1_fase,
                           om.telefone telefone_om_1_fase, om.endereco endereco_om_1_fase,
                           om.cidade cidade_om_1_fase, om.cep cep_om_1_fase, o.*
                    FROM obrigatorio o
                    LEFT JOIN om ON om.id = o.id_om_1_fase
                    WHERE o.apagado = 0
                    AND YEAR(o.data_selecao_geral) = ?
                    AND MONTH(o.data_selecao_geral) IN ($mesesPlaceholders)
                    ORDER BY o.nome_completo ASC";

            $stmt = $this->conexao->prepare($sql);
            $params = array_merge([(int)$ano], $meses);
            $stmt->execute($params);

            $obrigatorios = [];
            while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $obrigatorios[] = $this->buildObrigatorio($data);
            }

            return $obrigatorios;
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Busca obrigatórios por OM e ano
     * @param int|array $oms ID da OM ou array de IDs
     * @param int $ano Ano para filtrar
     * @return array Lista de obrigatórios
     */
    public function findByOMsAno($oms, $ano = null)
    {
        try {
            $ano = $ano ?? date('Y');

            if (!is_array($oms)) {
                $oms = [$oms];
            }

            $omsPlaceholders = implode(',', array_fill(0, count($oms), '?'));

            // Filtro consistente com count*OM - exclui distribuições de outras forças
            $sql = "SELECT om.id id_om_1_fase, om.nome nome_om_1_fase, om.abreviatura abreviatura_om_1_fase,
                           om.telefone telefone_om_1_fase, om.endereco endereco_om_1_fase,
                           om.cidade cidade_om_1_fase, om.cep cep_om_1_fase, o.*
                    FROM obrigatorio o
                    LEFT JOIN om ON om.id = o.id_om_1_fase
                    WHERE o.apagado = 0
                    AND YEAR(o.data_selecao_geral) = ?
                    AND o.id_om_1_fase IN ($omsPlaceholders)
                    AND o.distribuicao IS NOT NULL
                    AND o.distribuicao != ''
                    AND o.distribuicao NOT IN ('EXCESSO CONTINGENTE', 'MARINHA', 'FORÇA AÉREA')
                    ORDER BY om.abreviatura ASC, o.nome_completo ASC";

            $stmt = $this->conexao->prepare($sql);
            $params = array_merge([(int)$ano], $oms);
            $stmt->execute($params);

            $obrigatorios = [];
            while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $obrigatorios[] = $this->buildObrigatorio($data);
            }

            return $obrigatorios;
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Estatísticas comparativas entre dois períodos
     * @param int $ano1 Primeiro ano
     * @param array $meses1 Meses do primeiro período
     * @param int $ano2 Segundo ano
     * @param array $meses2 Meses do segundo período
     * @return array Dados comparativos
     */
    public function getEstatisticasComparativas($ano1, $meses1, $ano2, $meses2)
    {
        try {
            $dados = [
                'periodo1' => [
                    'total' => 0,
                    'incorporados' => 0,
                    'aptos' => 0,
                    'inaptos' => 0,
                    'pendentes' => 0
                ],
                'periodo2' => [
                    'total' => 0,
                    'incorporados' => 0,
                    'aptos' => 0,
                    'inaptos' => 0,
                    'pendentes' => 0
                ]
            ];

            // Período 1 - Usando data_selecao_geral para consistência
            $estatSituacao1 = $this->getEstatisticasPorSituacaoMilitar($ano1, $meses1);
            $dados['periodo1']['total'] = array_sum($estatSituacao1['data']);
            $dados['periodo1']['incorporados'] = $this->countIncorporadosPorAnoSelecao($ano1, $meses1);
            $dados['periodo1']['aptos'] = $this->countAptos($ano1, $meses1);
            $dados['periodo1']['inaptos'] = $this->countInaptos($ano1, $meses1);
            $dados['periodo1']['pendentes'] = $this->countPendentesRevisaoMedica($ano1, $meses1);

            // Período 2 - Usando data_selecao_geral para consistência
            $estatSituacao2 = $this->getEstatisticasPorSituacaoMilitar($ano2, $meses2);
            $dados['periodo2']['total'] = array_sum($estatSituacao2['data']);
            $dados['periodo2']['incorporados'] = $this->countIncorporadosPorAnoSelecao($ano2, $meses2);
            $dados['periodo2']['aptos'] = $this->countAptos($ano2, $meses2);
            $dados['periodo2']['inaptos'] = $this->countInaptos($ano2, $meses2);
            $dados['periodo2']['pendentes'] = $this->countPendentesRevisaoMedica($ano2, $meses2);

            return $dados;
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Distribuição geográfica para o mapa do dashboard
     * Retorna origens (cidades dos obrigatórios) e destinos (cidades das OMs)
     * @param int $ano Ano para filtrar
     * @param array $meses Meses para filtrar
     * @return array ['origens' => [...], 'destinos' => [...]]
     */
    public function getDistribuicaoGeografica($ano = null, $meses = [])
    {
        $ano = $ano ?? date('Y');
        $meses = !empty($meses) ? $meses : [1,2,3,4,5,6,7,8,9,10,11,12];

        $mesesPlaceholders = implode(',', array_fill(0, count($meses), '?'));
        $params = array_merge([(int)$ano], $meses);

        // Origens: cidades de naturalidade dos obrigatórios distribuídos
        $sqlOrigens = "SELECT
                    TRIM(o.naturalidade) as cidade,
                    COUNT(*) as total
                FROM obrigatorio o
                WHERE o.apagado = 0
                    AND o.id_om_1_fase IS NOT NULL
                    AND o.distribuicao IS NOT NULL
                    AND TRIM(o.naturalidade) IS NOT NULL
                    AND TRIM(o.naturalidade) != ''
                    AND o.data_comparecimento_designacao IS NOT NULL
                    AND YEAR(o.data_comparecimento_designacao) = ?
                    AND MONTH(o.data_comparecimento_designacao) IN ($mesesPlaceholders)
                GROUP BY TRIM(o.naturalidade)
                ORDER BY total DESC";

        $stmt = $this->conexao->prepare($sqlOrigens);
        $stmt->execute($params);

        $origens = [];
        if ($stmt->rowCount() > 0) {
            foreach ($stmt->fetchAll() as $row) {
                $origens[] = ['cidade' => $row['cidade'], 'total' => (int)$row['total']];
            }
        }

        // Destinos: cidades das OMs onde são distribuídos
        $sqlDestinos = "SELECT
                    COALESCE(NULLIF(TRIM(om.cidade), ''), om.nome) as cidade,
                    om.abreviatura as om,
                    COUNT(*) as total
                FROM obrigatorio o
                INNER JOIN om ON om.id = o.id_om_1_fase
                WHERE o.apagado = 0
                    AND o.distribuicao IS NOT NULL
                    AND o.data_comparecimento_designacao IS NOT NULL
                    AND YEAR(o.data_comparecimento_designacao) = ?
                    AND MONTH(o.data_comparecimento_designacao) IN ($mesesPlaceholders)
                GROUP BY COALESCE(NULLIF(TRIM(om.cidade), ''), om.nome), om.abreviatura
                ORDER BY total DESC";

        $stmt2 = $this->conexao->prepare($sqlDestinos);
        $stmt2->execute($params);

        $destinos = [];
        if ($stmt2->rowCount() > 0) {
            foreach ($stmt2->fetchAll() as $row) {
                $destinos[] = ['cidade' => $row['cidade'], 'om' => $row['om'], 'total' => (int)$row['total']];
            }
        }

        return ['origens' => $origens, 'destinos' => $destinos];
    }

    /**
     * Estatísticas de distribuição por Cidade (Local Comparecimento)
     * @param int $ano Ano para filtrar
     * @param array $meses Meses para filtrar
     * @return array ['labels' => [...], 'data' => [...]]
     */
    public function getEstatisticasPorCidade($ano = null, $meses = [])
    {
        $ano = $ano ?? date('Y');
        $meses = !empty($meses) ? $meses : [1,2,3,4,5,6,7,8,9,10,11,12];

        $mesesPlaceholders = implode(',', array_fill(0, count($meses), '?'));

        $sql = "SELECT
                    COALESCE(NULLIF(TRIM(o.local_compareceu_designacao), ''), 'Não definido') as cidade,
                    COUNT(*) as total
                FROM obrigatorio o
                WHERE o.apagado = 0
                    AND YEAR(o.data_selecao_geral) = ?
                    AND MONTH(o.data_selecao_geral) IN ($mesesPlaceholders)
                GROUP BY COALESCE(NULLIF(TRIM(o.local_compareceu_designacao), ''), 'Não definido')
                ORDER BY total DESC
                LIMIT 15";

        $stmt = $this->conexao->prepare($sql);
        $params = array_merge([(int)$ano], $meses);
        $stmt->execute($params);

        $labels = [];
        $data = [];

        if ($stmt->rowCount() > 0) {
            $results = $stmt->fetchAll();
            foreach ($results as $row) {
                $labels[] = $row['cidade'];
                $data[] = (int)$row['total'];
            }
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Busca próximos eventos/datas importantes do sistema
     * @param int $dias Número de dias para buscar (padrão 30)
     * @return array Lista de eventos ordenados por data
     */
    public function getProximosEventos($dias = 30)
    {
        $eventos = [];
        $hoje = date('Y-m-d');
        $dataLimite = date('Y-m-d', strtotime("+{$dias} days"));

        // 1. Próximas incorporações programadas
        $sql = "SELECT id, nome_completo, data_incorporacao, om_2_fase
                FROM obrigatorio
                WHERE apagado = 0
                    AND data_incorporacao IS NOT NULL
                    AND data_incorporacao >= ?
                    AND data_incorporacao <= ?
                ORDER BY data_incorporacao ASC
                LIMIT 10";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$hoje, $dataLimite]);
        $incorporacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($incorporacoes as $row) {
            $eventos[] = [
                'tipo' => 'incorporacao',
                'icone' => 'fa-user-plus',
                'cor' => 'success',
                'titulo' => 'Incorporação',
                'descricao' => $row['nome_completo'] . ($row['om_2_fase'] ? ' - ' . $row['om_2_fase'] : ''),
                'data' => $row['data_incorporacao'],
                'id_obrigatorio' => $row['id']
            ];
        }

        // 2. Próximas seleções gerais
        $sql = "SELECT id, nome_completo, data_selecao_geral
                FROM obrigatorio
                WHERE apagado = 0
                    AND data_selecao_geral IS NOT NULL
                    AND data_selecao_geral >= ?
                    AND data_selecao_geral <= ?
                ORDER BY data_selecao_geral ASC
                LIMIT 10";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$hoje, $dataLimite]);
        $selecoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($selecoes as $row) {
            $eventos[] = [
                'tipo' => 'selecao',
                'icone' => 'fa-clipboard-check',
                'cor' => 'primary',
                'titulo' => 'Seleção Geral',
                'descricao' => $row['nome_completo'],
                'data' => $row['data_selecao_geral'],
                'id_obrigatorio' => $row['id']
            ];
        }

        // 3. Próximas seleções complementares
        $sql = "SELECT id, nome_completo, data_selecao_complementar
                FROM obrigatorio
                WHERE apagado = 0
                    AND data_selecao_complementar IS NOT NULL
                    AND data_selecao_complementar >= ?
                    AND data_selecao_complementar <= ?
                ORDER BY data_selecao_complementar ASC
                LIMIT 10";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$hoje, $dataLimite]);
        $selecoesComp = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($selecoesComp as $row) {
            $eventos[] = [
                'tipo' => 'selecao_complementar',
                'icone' => 'fa-clipboard-list',
                'cor' => 'info',
                'titulo' => 'Seleção Complementar',
                'descricao' => $row['nome_completo'],
                'data' => $row['data_selecao_complementar'],
                'id_obrigatorio' => $row['id']
            ];
        }

        // 4. Fim de adiamentos (vencimentos próximos)
        $sql = "SELECT id, nome_completo, fim_adiamento, especialidade_adiamento
                FROM obrigatorio
                WHERE apagado = 0
                    AND fim_adiamento IS NOT NULL
                    AND fim_adiamento >= ?
                    AND fim_adiamento <= ?
                ORDER BY fim_adiamento ASC
                LIMIT 10";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$hoje, $dataLimite]);
        $adiamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($adiamentos as $row) {
            $eventos[] = [
                'tipo' => 'adiamento',
                'icone' => 'fa-calendar-times',
                'cor' => 'warning',
                'titulo' => 'Fim de Adiamento',
                'descricao' => $row['nome_completo'] . ($row['especialidade_adiamento'] ? ' (' . $row['especialidade_adiamento'] . ')' : ''),
                'data' => $row['fim_adiamento'],
                'id_obrigatorio' => $row['id']
            ];
        }

        // 5. Próximas revisões médicas
        $sql = "SELECT id, nome_completo, data_revisao_medica
                FROM obrigatorio
                WHERE apagado = 0
                    AND data_revisao_medica IS NOT NULL
                    AND data_revisao_medica >= ?
                    AND data_revisao_medica <= ?
                ORDER BY data_revisao_medica ASC
                LIMIT 10";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$hoje, $dataLimite]);
        $revisoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($revisoes as $row) {
            $eventos[] = [
                'tipo' => 'revisao_medica',
                'icone' => 'fa-stethoscope',
                'cor' => 'danger',
                'titulo' => 'Revisão Médica',
                'descricao' => $row['nome_completo'],
                'data' => $row['data_revisao_medica'],
                'id_obrigatorio' => $row['id']
            ];
        }

        // Ordena todos os eventos por data
        usort($eventos, function($a, $b) {
            return strtotime($a['data']) - strtotime($b['data']);
        });

        // Limita a 15 eventos
        return array_slice($eventos, 0, 15);
    }

    /**
     * Busca eventos agrupados por data para visualização em calendário
     * @param int $mes Mês (1-12)
     * @param int $ano Ano
     * @return array Eventos agrupados por dia
     */
    public function getEventosPorMes($mes, $ano)
    {
        $eventos = [];
        $primeiroDia = sprintf('%04d-%02d-01', $ano, $mes);
        $ultimoDia = date('Y-m-t', strtotime($primeiroDia));

        // Busca incorporações do mês
        $sql = "SELECT DATE(data_incorporacao) as data, COUNT(*) as total
                FROM obrigatorio
                WHERE apagado = 0
                    AND data_incorporacao BETWEEN ? AND ?
                GROUP BY DATE(data_incorporacao)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$primeiroDia, $ultimoDia]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $dia = (int)date('j', strtotime($row['data']));
            if (!isset($eventos[$dia])) {
                $eventos[$dia] = [];
            }
            $eventos[$dia][] = [
                'tipo' => 'incorporacao',
                'cor' => 'success',
                'total' => (int)$row['total']
            ];
        }

        // Busca fim de adiamentos do mês
        $sql = "SELECT DATE(fim_adiamento) as data, COUNT(*) as total
                FROM obrigatorio
                WHERE apagado = 0
                    AND fim_adiamento BETWEEN ? AND ?
                GROUP BY DATE(fim_adiamento)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$primeiroDia, $ultimoDia]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $dia = (int)date('j', strtotime($row['data']));
            if (!isset($eventos[$dia])) {
                $eventos[$dia] = [];
            }
            $eventos[$dia][] = [
                'tipo' => 'adiamento',
                'cor' => 'warning',
                'total' => (int)$row['total']
            ];
        }

        // Busca revisões médicas do mês
        $sql = "SELECT DATE(data_revisao_medica) as data, COUNT(*) as total
                FROM obrigatorio
                WHERE apagado = 0
                    AND data_revisao_medica BETWEEN ? AND ?
                GROUP BY DATE(data_revisao_medica)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$primeiroDia, $ultimoDia]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $dia = (int)date('j', strtotime($row['data']));
            if (!isset($eventos[$dia])) {
                $eventos[$dia] = [];
            }
            $eventos[$dia][] = [
                'tipo' => 'revisao',
                'cor' => 'danger',
                'total' => (int)$row['total']
            ];
        }

        return $eventos;
    }

    /**
     * Busca obrigatórios filtrados para lista de presença
     * @param string $instituicaoEnsino Nome da instituição de ensino
     * @param array $situacoesMilitares Array com as situações militares permitidas
     * @return array|false
     */
    public function findParaListaPresenca($instituicaoEnsino, $situacoesMilitares = [])
    {
        $obrigatorios = [];

        // Remove valores vazios do array
        $situacoesMilitares = array_filter($situacoesMilitares, function($valor) {
            return !empty($valor);
        });

        if (empty($instituicaoEnsino) || empty($situacoesMilitares)) {
            return [];
        }

        // Cria placeholders para o IN clause
        $placeholders = implode(',', array_fill(0, count($situacoesMilitares), '?'));

        $sql = "SELECT om.id id_om_1_fase, om.nome nome_om_1_fase, om.abreviatura abreviatura_om_1_fase,
                       om.telefone telefone_om_1_fase, om.endereco endereco_om_1_fase,
                       om.cidade cidade_om_1_fase, om.cep cep_om_1_fase, o.*
                FROM obrigatorio o
                LEFT JOIN om ON om.id = o.id_om_1_fase
                WHERE o.apagado = 0
                  AND o.nome_instituicao_ensino = ?
                  AND o.situacao_militar IN ($placeholders)
                  AND o.situacao_militar IS NOT NULL
                ORDER BY o.nome_completo ASC";

        $stmt = $this->conexao->prepare($sql);

        // Bind dos parâmetros
        $params = array_merge([$instituicaoEnsino], $situacoesMilitares);
        $stmt->execute($params);

        if ($stmt->rowCount() > 0) {
            $data = $stmt->fetchAll();
            foreach ($data as $item) {
                $obrigatorio = $this->buildObrigatorio($item);
                $obrigatorios[] = $obrigatorio;
            }
            return $obrigatorios;
        }

        return [];
    }

    /**
     * Busca obrigatórios filtrados para inspeção de saúde
     * @param string|null $dataInicial Data inicial (formato Y-m-d)
     * @param string|null $dataFinal Data final (formato Y-m-d)
     * @return array|false
     */
    public function findParaInspecaoSaude($dataInicial = null, $dataFinal = null)
    {
        $obrigatorios = [];

        // Limpa strings vazias
        $dataInicial = !empty(trim($dataInicial)) ? trim($dataInicial) : null;
        $dataFinal = !empty(trim($dataFinal)) ? trim($dataFinal) : null;

        $sql = "SELECT o.*
                FROM obrigatorio o
                WHERE o.apagado = 0";

        $params = [];

        // Adiciona filtro de data se fornecido
        if ($dataInicial !== null && $dataFinal !== null) {
            $sql .= " AND o.data_selecao_geral >= ? AND o.data_selecao_geral <= ?";
            $params[] = $dataInicial;
            $params[] = $dataFinal;
        }

        $sql .= " ORDER BY o.data_selecao_geral ASC, o.nome_completo ASC";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute($params);

        if ($stmt->rowCount() > 0) {
            $data = $stmt->fetchAll();
            foreach ($data as $item) {
                $obrigatorio = $this->buildObrigatorio($item);
                $obrigatorios[] = $obrigatorio;
            }
            return $obrigatorios;
        }

        return [];
    }
}


?>

