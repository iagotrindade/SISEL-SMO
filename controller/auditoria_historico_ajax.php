<?php
/**
 * Controller AJAX para buscar histórico de alterações de um obrigatório
 */

// Inicia output buffering
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Limpa buffer e define header
ob_end_clean();
header('Content-Type: application/json; charset=utf-8');

// Verificar permissão - apenas admin
if (!isset($_SESSION['perfil_smo']) || $_SESSION['perfil_smo'] != "admin" || !isset($_SESSION['id_usuario_smo'])) {
    echo json_encode(['success' => false, 'error' => 'Não autorizado']);
    exit();
}

// Função para responder com erro
function responderErro($mensagem) {
    echo json_encode(['success' => false, 'error' => $mensagem]);
    exit();
}

try {
    // Inclui apenas o necessário
    include_once '../dao/conecta_banco.php';

    if (!isset($conexao)) {
        responderErro('Erro de conexão com banco de dados');
    }

    include_once '../dao/LogDAO.php';

    $id_obrigatorio = isset($_GET['id_obrigatorio']) ? (int)$_GET['id_obrigatorio'] : 0;

    if ($id_obrigatorio <= 0) {
        responderErro('ID inválido');
    }

    $logDAO = new LogDAO($conexao);

    // Busca nome do obrigatório diretamente via SQL simples
    $stmtNome = $conexao->prepare("SELECT nome_completo FROM obrigatorio WHERE id = ?");
    $stmtNome->execute([$id_obrigatorio]);
    $nomeResult = $stmtNome->fetch(PDO::FETCH_ASSOC);

    $info = [
        'id' => $id_obrigatorio,
        'nome' => $nomeResult ? $nomeResult['nome_completo'] : null
    ];

    // Busca histórico
    $historico = $logDAO->getHistoricoObrigatorio($id_obrigatorio);

    // Formata os dados para o frontend
    $historicoFormatado = [];
    if ($historico && is_array($historico)) {
        foreach ($historico as $item) {
            // Formata a data
            $dataFormatada = '-';
            if (!empty($item['data'])) {
                $timestamp = strtotime($item['data']);
                $dataFormatada = date('d/m/Y H:i', $timestamp);
            }

            $historicoFormatado[] = [
                'id' => $item['id'],
                'data' => $item['data'] ?? null,
                'data_formatada' => $dataFormatada,
                'usuario' => $item['usuario'] ?? null,
                'nome_guerra' => $item['nome_guerra'] ?? null,
                'codigo' => $item['codigo'] ?? null,
                'tipo_operacao' => $item['tipo_operacao'] ?? 'OUTRO',
                'alteracao' => $item['alteracao'] ?? '',
                'alteracao_detalhada' => $item['alteracao_detalhada'] ?? null,
                'ip' => $item['ip'] ?? null
            ];
        }
    }

    echo json_encode([
        'success' => true,
        'info' => $info,
        'historico' => $historicoFormatado
    ]);

} catch (PDOException $e) {
    responderErro('Erro de banco de dados: ' . $e->getMessage());
} catch (Exception $e) {
    responderErro('Erro: ' . $e->getMessage());
}
?>
