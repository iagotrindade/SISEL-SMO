<?php
/**
 * Controller para cadastro em lote de obrigatórios via FISEMI OCR
 * Recebe JSON: { obrigatorios: [{nome, cpf}, ...], crip: "hash" }
 * Retorna JSON com resultado
 */

if (!isset($_SESSION)) session_start();

header('Content-Type: application/json');

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Obrigatorio.php';
include_once '../models/Om.php';
include_once '../dao/ObrigatorioDAO.php';
include_once '../dao/LogDAO.php';

// Verifica autenticação e perfil
if (!isset($_SESSION['id_usuario_smo']) || $_SESSION['perfil_smo'] != 'admin') {
    echo json_encode(['error' => 'Não autorizado']);
    exit();
}

// Lê o JSON do body
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['obrigatorios']) || !is_array($input['obrigatorios'])) {
    echo json_encode(['error' => 'Dados inválidos']);
    exit();
}

// Valida CSRF
if (!isset($input['crip']) || $input['crip'] != hash('sha256', $_SESSION['chave'] . "fisemi_lote")) {
    echo json_encode(['error' => 'Token de segurança inválido']);
    exit();
}

$logDAO = new LogDAO($conexao);
$obrigatorioDAO = new ObrigatorioDAO($conexao);

$cadastrados = 0;
$duplicados = 0;
$erros = 0;
$detalhes = [];

foreach ($input['obrigatorios'] as $item) {
    $nome = isset($item['nome']) ? trim($item['nome']) : '';
    $cpf = isset($item['cpf']) ? trim($item['cpf']) : '';

    // Limpa CPF
    $cpf_limpo = preg_replace('/[^0-9]/', '', $cpf);

    // Validações
    if (empty($nome) || empty($cpf_limpo)) {
        $erros++;
        $detalhes[] = ['nome' => $nome, 'cpf' => $cpf, 'status' => 'erro', 'motivo' => 'Nome ou CPF vazio'];
        continue;
    }

    if (!valida_cpf($cpf_limpo)) {
        $erros++;
        $detalhes[] = ['nome' => $nome, 'cpf' => $cpf, 'status' => 'erro', 'motivo' => 'CPF inválido'];
        continue;
    }

    // Verifica duplicidade
    $existente = $obrigatorioDAO->findByCPF($cpf_limpo);
    if ($existente) {
        $duplicados++;
        $detalhes[] = ['nome' => $nome, 'cpf' => $cpf, 'status' => 'duplicado', 'motivo' => 'Já cadastrado'];
        continue;
    }

    // Cria e insere
    try {
        $obrigatorio = new Obrigatorio($cpf_limpo);
        $obrigatorio->setIdOm($_SESSION['id_om_smo']);
        $obrigatorio->setNomeCompleto(strtoupper($nome));
        $obrigatorio->setCPF($cpf_limpo);

        $data = $obrigatorioDAO->insert($obrigatorio);

        if ($data) {
            $cadastrados++;
            $detalhes[] = ['nome' => strtoupper($nome), 'cpf' => $cpf, 'status' => 'cadastrado'];

            $alteracao = "Cadastrou Obrigatório via FISEMI OCR: " . strtoupper($nome);
            $logDAO->insertLog(1004, "obrigatorio", $data['id_adicionado'], $alteracao, "CPF: $cpf_limpo - Import FISEMI em lote");
        } else {
            $erros++;
            $detalhes[] = ['nome' => $nome, 'cpf' => $cpf, 'status' => 'erro', 'motivo' => 'Falha ao inserir'];
        }
    } catch (Exception $e) {
        $erros++;
        $detalhes[] = ['nome' => $nome, 'cpf' => $cpf, 'status' => 'erro', 'motivo' => $e->getMessage()];
    }
}

// Log geral da operação
$logDAO->insertLog(1005, "fisemi_lote", null,
    "Importação FISEMI em lote: $cadastrados cadastrados, $duplicados duplicados, $erros erros",
    "Total processado: " . count($input['obrigatorios'])
);

echo json_encode([
    'cadastrados' => $cadastrados,
    'duplicados' => $duplicados,
    'erros' => $erros,
    'total' => count($input['obrigatorios']),
    'detalhes' => $detalhes
]);
?>
