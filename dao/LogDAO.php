<?php

    if (file_exists('../funcoes.php'))
        include_once '../funcoes.php';
    if (file_exists('funcoes.php'))
        include_once 'funcoes.php';

    class LogDAO
    {
        private $conexao;
        
        public function __construct(PDO $conexao) 
        {
            $this->conexao = $conexao;
        }
        
        public function insertLog($codigo, $tabela, $id_alterado, $alteracao, $alteracao_detalhada)
        {
            try
            {
                $id_usuario = null;
                if(isset($_SESSION['id_usuario_smo'])) $id_usuario = $_SESSION['id_usuario_smo'];

                $navegador = getBrowser();
                $navegador = $navegador['platform'] . " - " . $navegador['name']. " ". $navegador['version'] ;
                $ip = $_SERVER['REMOTE_ADDR'];

                $sql = "INSERT INTO log 
                        (id_usuario, codigo, tabela, id_alterado, alteracao, alteracao_detalhada, ip, sistema) 
                        VALUES (:id_usuario, :codigo, :tabela, :id_alterado, :alteracao, :alteracao_detalhada, :ip, :sistema)";
                
                $this->conexao->beginTransaction();
                $stmt = $this->conexao->prepare($sql);

                $stmt->bindValue(":id_usuario", $id_usuario);
                $stmt->bindValue(":codigo", $codigo);
                $stmt->bindValue(":tabela", $tabela);
                $stmt->bindValue(":id_alterado", $id_alterado);
                $stmt->bindValue(":alteracao", $alteracao);
                $stmt->bindValue(":alteracao_detalhada", $alteracao_detalhada);
                $stmt->bindValue(":ip", $ip);
                $stmt->bindValue(":sistema", $navegador);
                
                if($stmt->execute())
                {
                    $this->conexao->commit();
                    return true;
                }
                else
                {
                    print_r($stmt->errorInfo()); 
                    exit();
                    return false;
                }
                    
                
            } 
            catch (Exception $ex) 
            {
                //print_r($stmt->errorInfo()); 
                //exit();
                return false;
                //echo $ex->getMessage();
                //print_r($query->errorInfo()); 
                //exit();
            }
        }
        
        public function insertAcessoPagina($pagina, $url)
        {
            try
            {
                $id_usuario = null;
                if(isset($_SESSION['id_usuario_smo'])) $id_usuario = $_SESSION['id_usuario_smo'];

                $navegador = getBrowser();
                $navegador = $navegador['platform'] . " - " . $navegador['name']. " ". $navegador['version'] ;
                $ip = $_SERVER['REMOTE_ADDR'];

                $sql = "INSERT INTO acesso
                        (id_usuario, pagina, url, ip, sistema) 
                        VALUES (:id_usuario, :pagina, :url, :ip, :sistema)";
                
                $this->conexao->beginTransaction();
                $stmt = $this->conexao->prepare($sql);

                $stmt->bindValue(":id_usuario", $id_usuario);
                $stmt->bindValue(":pagina", $pagina);
                $stmt->bindValue(":url", $url);
                $stmt->bindValue(":ip", $ip);
                $stmt->bindValue(":sistema", $navegador);
                
                if($stmt->execute())
                {
                $this->conexao->commit();
                return true;
                }
                else
                    return false;
                
            } 
            catch (Exception $ex) 
            {
                return false;
                //echo $ex->getMessage();
                //print_r($query->errorInfo()); 
                //exit();
            }
        }
        
        public function insertErro($nivel, $codigo, $arquivo, $descricao, $retorno_usuario)
        {
            try
            {
                $id_usuario = null;
                if(isset($_SESSION['id_usuario_smo'])) $id_usuario = $_SESSION['id_usuario_smo'];

                $navegador = getBrowser();
                $navegador = $navegador['platform'] . " - " . $navegador['name']. " ". $navegador['version'] ;
                $ip = $_SERVER['REMOTE_ADDR'];

                $sql = "INSERT INTO erro 
                        (id_usuario, nivel, codigo, arquivo, retorno_usuario, descricao, ip, sistema) 
                        VALUES (:id_usuario, :nivel, :codigo, :arquivo, :retorno_usuario, :descricao, :ip, :sistema)";
                
                $this->conexao->beginTransaction();
                $stmt = $this->conexao->prepare($sql);

                $stmt->bindValue(":id_usuario", $id_usuario);
                $stmt->bindValue(":codigo", $codigo);
                $stmt->bindValue(":nivel", $nivel);
                $stmt->bindValue(":arquivo", $arquivo);
                $stmt->bindValue(":retorno_usuario", $retorno_usuario);
                $stmt->bindValue(":descricao", $descricao);
                $stmt->bindValue(":ip", $ip);
                $stmt->bindValue(":sistema", $navegador);
                
                if($stmt->execute())
                {
                $this->conexao->commit();
                return true;
                }
                else
                    return false;
                
            } 
            catch (Exception $ex) 
            {
                return false;
                //echo $e->getMessage();
                //print_r($query->errorInfo()); 
                //exit();
            }
        }
        
        public function quantidadeTentativasLogin($ip)
        {
            if($ip != null)
            {
                $sql = 'SELECT COUNT(*) as total FROM tentativa_login WHERE DATE_FORMAT(data, "%Y-%m-%d") = DATE_FORMAT(now(), "%Y-%m-%d") AND ip = :ip';

                $stmt = $this->conexao->prepare($sql);
                $stmt->bindValue(":ip", $ip);
                $stmt->execute();

                if($stmt->rowCount() > 0)
                {
                    $data = $stmt->fetch();
                    return (int)$data['total'];
                }
                else
                    return 0;
            }
            else
                return 0;
        }
        
        public function findAllLimite($limite)
        {
                $sql = "select u.usuario, log.* from 
                        log
                        left join usuario u on u.id = log.id_usuario
                        where u.id_om = :id_om
                        order by log.id desc
                        limit :limite
                        ";
                
                $stmt = $this->conexao->prepare($sql);
                $stmt->bindValue(":limite", $limite, PDO::PARAM_INT);
                $stmt->bindValue(":id_om", $_SESSION['id_om_smo']);
                $stmt->execute();

                $data = $stmt->fetchAll();
                
                if(count($data) > 0)
                    return $data;
                else
                    return false;

        }

        public function findAll()
        {
                $sql = "select u.usuario, log.* from 
                        log
                        left join usuario u on u.id = log.id_usuario
                        where u.id_om = :id_om
                        order by log.id desc
                        ";
                
                $stmt = $this->conexao->prepare($sql);
                $stmt->bindValue(":id_om", $_SESSION['id_om_smo']);
                $stmt->execute();

                $data = $stmt->fetchAll();
                
                if(count($data) > 0)
                    return $data;
                else
                    return false;

        }

        public function findAllCodigo($codigo_pesquisa)
        {
                $sql = "select u.usuario, log.* from
                        log
                        left join usuario u on u.id = log.id_usuario
                        where log.codigo = :codigo_pesquisa
                        order by log.id desc

                        ";

                $stmt = $this->conexao->prepare($sql);
                $stmt->bindValue(":codigo_pesquisa", $codigo_pesquisa, PDO::PARAM_INT);
                $stmt->bindValue(":id_om", $_SESSION['id_om_smo']);
                $stmt->execute();

                $data = $stmt->fetchAll();

                if(count($data) > 0)
                    return $data;
                else
                    return false;

        }

        /**
         * Busca todos os logs de alterações de um obrigatório específico
         * Inclui logs da tabela obrigatorio e tabelas relacionadas
         * @param int $id_obrigatorio ID do obrigatório
         * @return array|false
         */
        public function findByIdObrigatorio($id_obrigatorio)
        {
                $sql = "SELECT * FROM log WHERE tabela = 'obrigatorio' AND id_alterado = ? ORDER BY data DESC, id DESC";
                $stmt = $this->conexao->prepare($sql);
                $stmt->execute([$id_obrigatorio]);
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if($data && count($data) > 0)
                    return $data;
                else
                    return false;
        }

        /**
         * Busca as últimas atividades do sistema
         * @param int $limite Número máximo de registros
         * @param int|null $id_usuario Filtrar por usuário específico (opcional)
         * @return array
         */
        public function getUltimasAtividades($limite = 5, $id_usuario = null)
        {
                $limite = (int)$limite;

                $sql = "SELECT l.*, u.nome_guerra as nome_usuario
                        FROM log l
                        LEFT JOIN usuario u ON u.id = l.id_usuario
                        WHERE 1=1";

                $params = [];

                if ($id_usuario !== null) {
                    $sql .= " AND l.id_usuario = ?";
                    $params[] = $id_usuario;
                }

                $sql .= " ORDER BY l.data DESC, l.id DESC LIMIT " . $limite;

                $stmt = $this->conexao->prepare($sql);
                $stmt->execute($params);

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * Busca logs com filtros avançados
         * @param array $filtros Filtros: data_inicio, data_fim, tipo_operacao, usuario, codigo, tabela, busca
         * @param int $limite
         * @return array
         */
        public function findComFiltros($filtros = [], $limite = 1000)
        {
            $sql = "SELECT l.*, u.usuario, u.nome_guerra
                    FROM log l
                    LEFT JOIN usuario u ON u.id = l.id_usuario
                    WHERE 1=1";
            $params = [];

            // Filtro por data início
            if (!empty($filtros['data_inicio'])) {
                $sql .= " AND DATE(l.data) >= ?";
                $params[] = $filtros['data_inicio'];
            }

            // Filtro por data fim
            if (!empty($filtros['data_fim'])) {
                $sql .= " AND DATE(l.data) <= ?";
                $params[] = $filtros['data_fim'];
            }

            // Filtro por tipo de operação (baseado no código)
            if (!empty($filtros['tipo_operacao'])) {
                switch ($filtros['tipo_operacao']) {
                    case 'INSERT':
                        $sql .= " AND l.codigo BETWEEN 1000 AND 1999";
                        break;
                    case 'DELETE':
                        $sql .= " AND l.codigo BETWEEN 2000 AND 2999";
                        break;
                    case 'UPDATE':
                        $sql .= " AND l.codigo BETWEEN 3000 AND 3999";
                        break;
                    case 'PDF':
                        $sql .= " AND l.codigo BETWEEN 4000 AND 4999";
                        break;
                    case 'LOGIN':
                        $sql .= " AND l.codigo IN (9001, 9002)";
                        break;
                }
            }

            // Filtro por código específico
            if (!empty($filtros['codigo'])) {
                $sql .= " AND l.codigo = ?";
                $params[] = (int)$filtros['codigo'];
            }

            // Filtro por usuário
            if (!empty($filtros['usuario'])) {
                $sql .= " AND (u.usuario LIKE ? OR u.nome_guerra LIKE ?)";
                $params[] = '%' . $filtros['usuario'] . '%';
                $params[] = '%' . $filtros['usuario'] . '%';
            }

            // Filtro por tabela
            if (!empty($filtros['tabela'])) {
                $sql .= " AND l.tabela = ?";
                $params[] = $filtros['tabela'];
            }

            // Busca geral na alteração
            if (!empty($filtros['busca'])) {
                $sql .= " AND (l.alteracao LIKE ? OR l.alteracao_detalhada LIKE ?)";
                $params[] = '%' . $filtros['busca'] . '%';
                $params[] = '%' . $filtros['busca'] . '%';
            }

            // Filtro por ID do registro alterado
            if (!empty($filtros['id_alterado'])) {
                $sql .= " AND l.id_alterado = ?";
                $params[] = (int)$filtros['id_alterado'];
            }

            $sql .= " ORDER BY l.data DESC, l.id DESC";

            if ($limite > 0) {
                $sql .= " LIMIT " . (int)$limite;
            }

            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * Retorna lista de tabelas distintas nos logs
         */
        public function getTabelasDistintas()
        {
            $sql = "SELECT DISTINCT tabela FROM log WHERE tabela IS NOT NULL AND tabela != '' ORDER BY tabela";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        /**
         * Conta logs por tipo de operação para estatísticas
         */
        public function countPorTipoOperacao($filtros = [])
        {
            $baseWhere = "1=1";
            $params = [];

            if (!empty($filtros['data_inicio'])) {
                $baseWhere .= " AND DATE(data) >= ?";
                $params[] = $filtros['data_inicio'];
            }
            if (!empty($filtros['data_fim'])) {
                $baseWhere .= " AND DATE(data) <= ?";
                $params[] = $filtros['data_fim'];
            }

            $sql = "SELECT
                        SUM(CASE WHEN codigo BETWEEN 1000 AND 1999 THEN 1 ELSE 0 END) as inserts,
                        SUM(CASE WHEN codigo BETWEEN 2000 AND 2999 THEN 1 ELSE 0 END) as deletes,
                        SUM(CASE WHEN codigo BETWEEN 3000 AND 3999 THEN 1 ELSE 0 END) as updates,
                        SUM(CASE WHEN codigo BETWEEN 4000 AND 4999 THEN 1 ELSE 0 END) as pdfs,
                        SUM(CASE WHEN codigo IN (9001, 9002) THEN 1 ELSE 0 END) as logins,
                        COUNT(*) as total
                    FROM log WHERE {$baseWhere}";

            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        /**
         * Busca histórico completo de um obrigatório com detalhes
         */
        public function getHistoricoObrigatorio($id_obrigatorio)
        {
            $sql = "SELECT l.*, u.usuario, u.nome_guerra,
                           CASE
                               WHEN l.codigo BETWEEN 1000 AND 1999 THEN 'INSERT'
                               WHEN l.codigo BETWEEN 2000 AND 2999 THEN 'DELETE'
                               WHEN l.codigo BETWEEN 3000 AND 3999 THEN 'UPDATE'
                               WHEN l.codigo BETWEEN 4000 AND 4999 THEN 'PDF'
                               ELSE 'OUTRO'
                           END as tipo_operacao
                    FROM log l
                    LEFT JOIN usuario u ON u.id = l.id_usuario
                    WHERE l.tabela = 'obrigatorio' AND l.id_alterado = ?
                    ORDER BY l.data DESC, l.id DESC";

            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([$id_obrigatorio]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * Retorna a descrição legível do código de operação
         * @param int $codigo Código da operação
         * @return array Informações da operação (tipo, descrição, icone, cor)
         */
        public static function getOperacaoInfo($codigo)
        {
            $operacoes = [
                // LOGIN/LOGOUT
                9001 => ['tipo' => 'LOGIN', 'descricao' => 'Login realizado', 'icone' => 'fa-sign-in-alt', 'cor' => 'info'],
                9002 => ['tipo' => 'LOGOUT', 'descricao' => 'Logout realizado', 'icone' => 'fa-sign-out-alt', 'cor' => 'secondary'],

                // INSERT (1xxx)
                1001 => ['tipo' => 'INSERT', 'descricao' => 'Usuário cadastrado', 'icone' => 'fa-user-plus', 'cor' => 'success'],
                1002 => ['tipo' => 'INSERT', 'descricao' => 'Arquivo anexado', 'icone' => 'fa-paperclip', 'cor' => 'success'],
                1003 => ['tipo' => 'INSERT', 'descricao' => 'Junta de Saúde cadastrada', 'icone' => 'fa-notes-medical', 'cor' => 'success'],
                1004 => ['tipo' => 'INSERT', 'descricao' => 'Obrigatório cadastrado', 'icone' => 'fa-user-plus', 'cor' => 'success'],
                1005 => ['tipo' => 'INSERT', 'descricao' => 'Ofício cadastrado', 'icone' => 'fa-file-alt', 'cor' => 'success'],
                1006 => ['tipo' => 'INSERT', 'descricao' => 'Prioridade de guarnição definida', 'icone' => 'fa-map-marker-alt', 'cor' => 'success'],

                // DELETE (2xxx)
                2001 => ['tipo' => 'DELETE', 'descricao' => 'Usuário apagado', 'icone' => 'fa-user-minus', 'cor' => 'danger'],
                2002 => ['tipo' => 'DELETE', 'descricao' => 'Arquivo removido', 'icone' => 'fa-trash-alt', 'cor' => 'danger'],
                2003 => ['tipo' => 'DELETE', 'descricao' => 'Obrigatório apagado', 'icone' => 'fa-user-times', 'cor' => 'danger'],
                2004 => ['tipo' => 'DELETE', 'descricao' => 'Junta de Saúde apagada', 'icone' => 'fa-trash-alt', 'cor' => 'danger'],
                2005 => ['tipo' => 'DELETE', 'descricao' => 'Lista de prioridades apagada', 'icone' => 'fa-trash-alt', 'cor' => 'danger'],

                // UPDATE (3xxx)
                3001 => ['tipo' => 'UPDATE', 'descricao' => 'Usuário atualizado', 'icone' => 'fa-user-edit', 'cor' => 'primary'],
                3002 => ['tipo' => 'UPDATE', 'descricao' => 'Senha alterada', 'icone' => 'fa-key', 'cor' => 'warning'],
                3003 => ['tipo' => 'UPDATE', 'descricao' => 'Obrigatório atualizado', 'icone' => 'fa-edit', 'cor' => 'primary'],
                3004 => ['tipo' => 'UPDATE', 'descricao' => 'Ofício atualizado', 'icone' => 'fa-file-signature', 'cor' => 'primary'],
                3005 => ['tipo' => 'UPDATE', 'descricao' => 'Senha padrão alterada', 'icone' => 'fa-key', 'cor' => 'warning'],
                3006 => ['tipo' => 'UPDATE', 'descricao' => 'Pré-distribuição atualizada', 'icone' => 'fa-map-marker-alt', 'cor' => 'primary'],
                3007 => ['tipo' => 'UPDATE', 'descricao' => 'Identificação atualizada', 'icone' => 'fa-id-card', 'cor' => 'primary'],
                3008 => ['tipo' => 'UPDATE', 'descricao' => 'FISEMI atualizado', 'icone' => 'fa-random', 'cor' => 'primary'],
                3009 => ['tipo' => 'UPDATE', 'descricao' => 'Justiça atualizada', 'icone' => 'fa-gavel', 'cor' => 'primary'],
                3010 => ['tipo' => 'UPDATE', 'descricao' => 'Adiamento atualizado', 'icone' => 'fa-calendar-alt', 'cor' => 'primary'],
                3011 => ['tipo' => 'UPDATE', 'descricao' => 'Distribuição atualizada', 'icone' => 'fa-map-marker-alt', 'cor' => 'primary'],
                3012 => ['tipo' => 'UPDATE', 'descricao' => 'Junta de Saúde editada', 'icone' => 'fa-notes-medical', 'cor' => 'primary'],
                3013 => ['tipo' => 'UPDATE', 'descricao' => 'Incorporação atualizada', 'icone' => 'fa-building', 'cor' => 'primary'],
                3014 => ['tipo' => 'UPDATE', 'descricao' => 'Revisão médica atualizada', 'icone' => 'fa-stethoscope', 'cor' => 'primary'],
                3015 => ['tipo' => 'UPDATE', 'descricao' => 'ISGR atualizado', 'icone' => 'fa-clipboard-check', 'cor' => 'primary'],

                // PDF (4xxx)
                4001 => ['tipo' => 'PDF', 'descricao' => 'Relatório JISE gerado', 'icone' => 'fa-file-pdf', 'cor' => 'secondary'],
                4002 => ['tipo' => 'PDF', 'descricao' => 'Relatório JISR gerado', 'icone' => 'fa-file-pdf', 'cor' => 'secondary'],
                4003 => ['tipo' => 'PDF', 'descricao' => 'Ficha gerada', 'icone' => 'fa-file-pdf', 'cor' => 'secondary'],
                4004 => ['tipo' => 'PDF', 'descricao' => 'Ofício gerado', 'icone' => 'fa-file-pdf', 'cor' => 'secondary'],
                4005 => ['tipo' => 'PDF', 'descricao' => 'Comparecimento gerado', 'icone' => 'fa-file-pdf', 'cor' => 'secondary'],
                4006 => ['tipo' => 'PDF', 'descricao' => 'Lista de presença gerada', 'icone' => 'fa-file-pdf', 'cor' => 'secondary'],
            ];

            return $operacoes[$codigo] ?? ['tipo' => 'OUTRO', 'descricao' => 'Operação não identificada', 'icone' => 'fa-question', 'cor' => 'dark'];
        }

    }


?>

