<?php
/**
 * DAO para Notificações
 * Inclui lógica de geração automática de notificações
 */
class NotificacaoDAO
{
    private $conexao;

    public function __construct($conexao)
    {
        $this->conexao = $conexao;
    }

    /**
     * Constrói objeto Notificacao a partir de array
     */
    private function buildNotificacao($data)
    {
        $notificacao = new Notificacao();
        $notificacao->setId($data['id'] ?? null);
        $notificacao->setTipo($data['tipo'] ?? null);
        $notificacao->setCriticidade($data['criticidade'] ?? 'media');
        $notificacao->setTitulo($data['titulo'] ?? '');
        $notificacao->setMensagem($data['mensagem'] ?? '');
        $notificacao->setIdObrigatorio($data['id_obrigatorio'] ?? null);
        $notificacao->setIdOm($data['id_om'] ?? null);
        $notificacao->setDataCriacao($data['data_criacao'] ?? null);
        $notificacao->setLida($data['lida'] ?? 0);
        $notificacao->setDataLeitura($data['data_leitura'] ?? null);
        $notificacao->setIdUsuarioLeitura($data['id_usuario_leitura'] ?? null);
        $notificacao->setAtiva($data['ativa'] ?? 1);

        // Dados relacionados
        $notificacao->setObrigatorioNome($data['obrigatorio_nome'] ?? null);
        $notificacao->setObrigatorioCpf($data['obrigatorio_cpf'] ?? null);
        $notificacao->setOmAbreviatura($data['om_abreviatura'] ?? null);
        $notificacao->setOmNome($data['om_nome'] ?? null);

        return $notificacao;
    }

    // ==================== CRUD ====================

    /**
     * Insere nova notificação
     */
    public function insert(Notificacao $notificacao)
    {
        try {
            $sql = "INSERT INTO notificacao (tipo, criticidade, titulo, mensagem, id_obrigatorio, id_om)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([
                $notificacao->getTipo(),
                $notificacao->getCriticidade(),
                $notificacao->getTitulo(),
                $notificacao->getMensagem(),
                $notificacao->getIdObrigatorio(),
                $notificacao->getIdOm()
            ]);
            return $this->conexao->lastInsertId();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Busca notificação por ID
     */
    public function findById($id)
    {
        try {
            $sql = "SELECT * FROM vw_notificacoes WHERE id = ?";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data ? $this->buildNotificacao($data) : null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Marca notificação como lida
     */
    public function marcarComoLida($id, $idUsuario)
    {
        try {
            $sql = "UPDATE notificacao SET lida = 1, data_leitura = NOW(), id_usuario_leitura = ? WHERE id = ?";
            $stmt = $this->conexao->prepare($sql);
            return $stmt->execute([$idUsuario, $id]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Marca várias notificações como lidas
     */
    public function marcarTodasComoLidas($idUsuario, $perfil, $idOm = null)
    {
        try {
            $sql = "UPDATE notificacao SET lida = 1, data_leitura = NOW(), id_usuario_leitura = ?
                    WHERE ativa = 1 AND lida = 0";
            $params = [$idUsuario];

            if ($perfil != 'admin') {
                if ($idOm !== null) {
                    $sql .= " AND id_om = ?";
                    $params[] = $idOm;
                } else {
                    $sql .= " AND 1=0";
                }
            }

            $stmt = $this->conexao->prepare($sql);
            return $stmt->execute($params);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Arquiva notificação (desativa)
     */
    public function arquivar($id)
    {
        try {
            $sql = "UPDATE notificacao SET ativa = 0 WHERE id = ?";
            $stmt = $this->conexao->prepare($sql);
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Remove notificação
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM notificacao WHERE id = ?";
            $stmt = $this->conexao->prepare($sql);
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            return false;
        }
    }

    // ==================== CONSULTAS ====================

    /**
     * Busca notificações ativas para um usuário
     * @param string $perfil 'admin' ou 'operador'
     * @param int|null $idOm ID da OM (para operadores)
     * @param array $filtros Filtros opcionais
     */
    public function findAtivas($perfil, $idOm = null, $filtros = [])
    {
        try {
            $sql = "SELECT * FROM vw_notificacoes WHERE ativa = 1";
            $params = [];

            // Filtro de visibilidade por perfil
            // Admin vê tudo, operador vê apenas da sua OM (sem globais)
            if ($perfil != 'admin') {
                if ($idOm !== null) {
                    $sql .= " AND id_om = ?";
                    $params[] = $idOm;
                } else {
                    // Operador sem OM definida não vê nenhuma notificação
                    $sql .= " AND 1=0";
                }
            }

            // Filtro de criticidade
            if (!empty($filtros['criticidade'])) {
                $sql .= " AND criticidade = ?";
                $params[] = $filtros['criticidade'];
            }

            // Filtro de tipo
            if (!empty($filtros['tipo'])) {
                $sql .= " AND tipo = ?";
                $params[] = $filtros['tipo'];
            }

            // Filtro de status (lida/não lida)
            if (isset($filtros['lida'])) {
                $sql .= " AND lida = ?";
                $params[] = (int)$filtros['lida'];
            }

            $sql .= " ORDER BY
                        FIELD(criticidade, 'alta', 'media', 'baixa'),
                        lida ASC,
                        data_criacao DESC";

            // Limite
            if (!empty($filtros['limite'])) {
                $sql .= " LIMIT " . (int)$filtros['limite'];
            }

            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($params);

            $notificacoes = [];
            while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $notificacoes[] = $this->buildNotificacao($data);
            }

            return $notificacoes;
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Conta notificações não lidas
     */
    public function countNaoLidas($perfil, $idOm = null)
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM notificacao WHERE ativa = 1 AND lida = 0";
            $params = [];

            if ($perfil != 'admin') {
                if ($idOm !== null) {
                    $sql .= " AND id_om = ?";
                    $params[] = $idOm;
                } else {
                    $sql .= " AND 1=0";
                }
            }

            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)($result['total'] ?? 0);
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Conta notificações por criticidade
     */
    public function countPorCriticidade($perfil, $idOm = null)
    {
        try {
            $sql = "SELECT criticidade, COUNT(*) as total
                    FROM notificacao
                    WHERE ativa = 1 AND lida = 0";
            $params = [];

            if ($perfil != 'admin') {
                if ($idOm !== null) {
                    $sql .= " AND id_om = ?";
                    $params[] = $idOm;
                } else {
                    $sql .= " AND 1=0";
                }
            }

            $sql .= " GROUP BY criticidade";

            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($params);

            $counts = ['alta' => 0, 'media' => 0, 'baixa' => 0];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $counts[$row['criticidade']] = (int)$row['total'];
            }

            return $counts;
        } catch (Exception $e) {
            return ['alta' => 0, 'media' => 0, 'baixa' => 0];
        }
    }

    // ==================== GERAÇÃO AUTOMÁTICA ====================

    /**
     * Verifica se deve executar a verificação diária
     */
    public function deveExecutarVerificacaoDiaria()
    {
        try {
            $sql = "SELECT valor FROM sistema_config WHERE chave = 'ultima_verificacao_notificacoes'";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result || empty($result['valor'])) {
                return true;
            }

            $ultimaExecucao = new DateTime($result['valor']);
            $agora = new DateTime();
            $diff = $agora->diff($ultimaExecucao);

            // Executa se passou mais de 23 horas
            return ($diff->days > 0 || $diff->h >= 23);
        } catch (Exception $e) {
            return true;
        }
    }

    /**
     * Atualiza timestamp da última verificação
     */
    private function atualizarUltimaVerificacao()
    {
        try {
            $sql = "INSERT INTO sistema_config (chave, valor)
                    VALUES ('ultima_verificacao_notificacoes', NOW())
                    ON DUPLICATE KEY UPDATE valor = NOW()";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute();
        } catch (Exception $e) {
            // Silencioso
        }
    }

    /**
     * Executa geração automática de notificações
     * Chamado uma vez por dia (ou forçado)
     * @param bool $forcar Se true, ignora o limite de 23h
     */
    public function gerarNotificacoesAutomaticas($forcar = false)
    {
        if (!$forcar && !$this->deveExecutarVerificacaoDiaria()) {
            return ['executado' => false, 'motivo' => 'Já executado hoje'];
        }

        $resultado = [
            'executado' => true,
            'adiamentos_vencendo' => 0,
            'incorporacoes_sem_revisao' => 0
        ];

        // Limpa notificações antigas do mesmo tipo (evita duplicatas)
        $this->limparNotificacoesAntigas();

        // 1. Verifica adiamentos vencendo
        $resultado['adiamentos_vencendo'] = $this->gerarNotificacoesAdiamento();

        // 2. Verifica incorporações sem revisão médica
        $resultado['incorporacoes_sem_revisao'] = $this->gerarNotificacoesIncorporacao();

        // Atualiza timestamp
        $this->atualizarUltimaVerificacao();

        return $resultado;
    }

    /**
     * Limpa notificações antigas não lidas do mesmo obrigatório
     * (para evitar duplicatas quando a situação persiste)
     */
    private function limparNotificacoesAntigas()
    {
        try {
            // Remove notificações não lidas com mais de 30 dias
            $sql = "DELETE FROM notificacao
                    WHERE lida = 0
                    AND data_criacao < DATE_SUB(NOW(), INTERVAL 30 DAY)";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute();
        } catch (Exception $e) {
            // Silencioso
        }
    }

    /**
     * Verifica se já existe notificação ativa para este obrigatório e tipo
     */
    private function existeNotificacaoAtiva($idObrigatorio, $tipo)
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM notificacao
                    WHERE id_obrigatorio = ? AND tipo = ? AND ativa = 1 AND lida = 0";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([$idObrigatorio, $tipo]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)($result['total'] ?? 0) > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Gera notificações para adiamentos vencendo
     */
    private function gerarNotificacoesAdiamento()
    {
        $count = 0;

        try {
            // Busca obrigatórios com adiamento próximo do vencimento
            // Usa os campos corretos: solicitou_adiamento e fim_adiamento
            $sql = "SELECT o.id, o.nome_completo, o.id_om_1_fase, o.situacao_militar,
                           o.fim_adiamento, o.especialidade_adiamento,
                           DATEDIFF(o.fim_adiamento, CURDATE()) as dias_restantes
                    FROM obrigatorio o
                    WHERE o.apagado = 0
                    AND o.situacao_militar = 'Em Dia - ADIADO CURSANDO RESIDÊNCIA'
                    AND o.fim_adiamento IS NOT NULL
                    AND o.fim_adiamento >= CURDATE()
                    AND o.fim_adiamento <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
                    ORDER BY o.fim_adiamento ASC";

            $stmt = $this->conexao->prepare($sql);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Verifica se já existe notificação ativa
                if ($this->existeNotificacaoAtiva($row['id'], Notificacao::TIPO_ADIAMENTO_VENCENDO)) {
                    continue;
                }

                $diasRestantes = (int)$row['dias_restantes'];

                // Define criticidade baseada nos dias restantes
                if ($diasRestantes <= 7) {
                    $criticidade = Notificacao::CRITICIDADE_ALTA;
                } elseif ($diasRestantes <= 15) {
                    $criticidade = Notificacao::CRITICIDADE_MEDIA;
                } else {
                    $criticidade = Notificacao::CRITICIDADE_BAIXA;
                }

                $notificacao = new Notificacao();
                $notificacao->setTipo(Notificacao::TIPO_ADIAMENTO_VENCENDO);
                $notificacao->setCriticidade($criticidade);
                $notificacao->setTitulo("Adiamento vence em {$diasRestantes} dia(s)");
                $especialidade = !empty($row['especialidade_adiamento']) ? " ({$row['especialidade_adiamento']})" : "";
                $notificacao->setMensagem(
                    "O adiamento de {$row['nome_completo']}{$especialidade} vence em " .
                    date('d/m/Y', strtotime($row['fim_adiamento'])) . "."
                );
                $notificacao->setIdObrigatorio($row['id']);
                $notificacao->setIdOm($row['id_om_1_fase']);

                if ($this->insert($notificacao)) {
                    $count++;
                }
            }
        } catch (Exception $e) {
            // Log de erro se necessário
        }

        return $count;
    }

    /**
     * Gera notificações para incorporações sem revisão médica
     */
    private function gerarNotificacoesIncorporacao()
    {
        $count = 0;

        try {
            // Busca obrigatórios com data de incorporação próxima mas sem revisão médica
            $sql = "SELECT o.id, o.nome_completo, o.id_om_1_fase, o.distribuicao,
                           o.data_incorporacao,
                           DATEDIFF(o.data_incorporacao, CURDATE()) as dias_restantes
                    FROM obrigatorio o
                    WHERE o.apagado = 0
                    AND o.data_incorporacao IS NOT NULL
                    AND o.data_incorporacao >= CURDATE()
                    AND o.data_incorporacao <= DATE_ADD(CURDATE(), INTERVAL 15 DAY)
                    AND (o.resultado_revisao_medica_complementar IS NULL OR o.resultado_revisao_medica_complementar = '')
                    AND o.distribuicao IS NOT NULL
                    AND o.distribuicao != ''
                    AND o.distribuicao NOT IN ('EXCESSO CONTINGENTE', 'MARINHA', 'FORÇA AÉREA')
                    ORDER BY o.data_incorporacao ASC";

            $stmt = $this->conexao->prepare($sql);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Verifica se já existe notificação ativa
                if ($this->existeNotificacaoAtiva($row['id'], Notificacao::TIPO_INCORPORACAO_SEM_REVISAO)) {
                    continue;
                }

                $diasRestantes = (int)$row['dias_restantes'];

                // Define criticidade baseada nos dias restantes
                if ($diasRestantes <= 7) {
                    $criticidade = Notificacao::CRITICIDADE_ALTA;
                } else {
                    $criticidade = Notificacao::CRITICIDADE_MEDIA;
                }

                $notificacao = new Notificacao();
                $notificacao->setTipo(Notificacao::TIPO_INCORPORACAO_SEM_REVISAO);
                $notificacao->setCriticidade($criticidade);
                $notificacao->setTitulo("Incorporação em {$diasRestantes} dia(s) sem revisão");
                $notificacao->setMensagem(
                    "{$row['nome_completo']} tem incorporação prevista para " .
                    date('d/m/Y', strtotime($row['data_incorporacao'])) . " mas ainda não possui " .
                    "resultado de revisão médica complementar. Distribuição: {$row['distribuicao']}"
                );
                $notificacao->setIdObrigatorio($row['id']);
                $notificacao->setIdOm($row['id_om_1_fase']);

                if ($this->insert($notificacao)) {
                    $count++;
                }
            }
        } catch (Exception $e) {
            // Log de erro se necessário
        }

        return $count;
    }
}
?>
