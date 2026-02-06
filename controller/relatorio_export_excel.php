<?php
/**
 * Endpoint para exportar dados avançados para Excel (CSV com BOM)
 * Exporta dados completos de obrigatórios com filtros avançados
 */

// Inicia sessão primeiro - antes de qualquer output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['id_usuario_smo']) || !isset($_SESSION['perfil_smo']) || $_SESSION['perfil_smo'] != 'admin') {
    header('HTTP/1.1 403 Forbidden');
    echo 'Não autorizado';
    exit();
}

// Includes usando caminho relativo
include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Obrigatorio.php';
include_once '../models/Om.php';
include_once '../dao/ObrigatorioDAO.php';
include_once '../dao/OmDAO.php';
include_once '../dao/LogDAO.php';

$obrigatorioDAO = new ObrigatorioDAO($conexao);
$omDAO = new OmDAO($conexao);
$logDAO = new LogDAO($conexao);

// Pega parâmetros
$tipoDados = isset($_POST['tipo_dados']) ? $_POST['tipo_dados'] : 'obrigatorios';
$ano = isset($_POST['ano']) && $_POST['ano'] != '' ? (int)$_POST['ano'] : null;
$periodo = isset($_POST['periodo']) ? $_POST['periodo'] : 'ano';
$omFiltro = isset($_POST['om_filtro']) && $_POST['om_filtro'] != '' ? (int)$_POST['om_filtro'] : null;
$especialidadeFiltro = isset($_POST['especialidade_filtro']) && $_POST['especialidade_filtro'] != '' ? $_POST['especialidade_filtro'] : null;
$colunas = isset($_POST['colunas']) ? $_POST['colunas'] : ['nome', 'cpf', 'especialidade', 'situacao', 'om', 'distribuicao', 'revisao', 'jise'];

// Converte período para meses
$mesesFiltro = [];
$nomePeriodo = 'Todos os Períodos';
if ($ano !== null) {
    switch ($periodo) {
        case '1sem':
            $mesesFiltro = [1, 2, 3, 4, 5, 6];
            $nomePeriodo = '1º Semestre';
            break;
        case '2sem':
            $mesesFiltro = [7, 8, 9, 10, 11, 12];
            $nomePeriodo = '2º Semestre';
            break;
        default:
            $mesesFiltro = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
            $nomePeriodo = 'Ano Completo';
    }
}

// Nome do arquivo
$nomeArquivo = "export_smo_{$tipoDados}_" . ($ano ?? 'todos') . "_" . date('Y-m-d_H-i-s') . ".csv";

// Headers para download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $nomeArquivo . '"');
header('Pragma: no-cache');
header('Expires: 0');

// Abre output
$output = fopen('php://output', 'w');

// BOM para UTF-8 (necessário para Excel reconhecer caracteres especiais)
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Cabeçalho do relatório
fputcsv($output, ['EXPORTAÇÃO DE DADOS - SISEL SMO'], ';');
fputcsv($output, ['Tipo: ' . ucfirst($tipoDados)], ';');
fputcsv($output, ['Ano: ' . ($ano ?? 'Todos')], ';');
fputcsv($output, ['Período: ' . $nomePeriodo], ';');
fputcsv($output, ['Gerado em: ' . date('d/m/Y H:i:s')], ';');
fputcsv($output, ['Gerado por: ' . $_SESSION['nome_guerra_smo']], ';');
fputcsv($output, [''], ';');

// Exportação de estatísticas
if ($tipoDados == 'estatisticas') {
    // Por Especialidade
    fputcsv($output, ['DISTRIBUIÇÃO POR ESPECIALIDADE'], ';');
    fputcsv($output, ['Especialidade', 'Quantidade', 'Percentual'], ';');

    $esp = $obrigatorioDAO->getEstatisticasPorEspecialidade($ano, $mesesFiltro);
    $totalEsp = array_sum($esp['data']);

    for ($i = 0; $i < count($esp['labels']); $i++) {
        $perc = $totalEsp > 0 ? round(($esp['data'][$i] / $totalEsp) * 100, 1) . '%' : '0%';
        fputcsv($output, [$esp['labels'][$i], $esp['data'][$i], $perc], ';');
    }
    fputcsv($output, ['TOTAL', $totalEsp, '100%'], ';');
    fputcsv($output, [''], ';');

    // Por Situação Militar
    fputcsv($output, ['SITUAÇÃO MILITAR'], ';');
    fputcsv($output, ['Situação', 'Quantidade', 'Percentual'], ';');

    $sit = $obrigatorioDAO->getEstatisticasPorSituacaoMilitar($ano, $mesesFiltro);
    $totalSit = array_sum($sit['data']);

    for ($i = 0; $i < count($sit['labels']); $i++) {
        $perc = $totalSit > 0 ? round(($sit['data'][$i] / $totalSit) * 100, 1) . '%' : '0%';
        fputcsv($output, [$sit['labels'][$i], $sit['data'][$i], $perc], ';');
    }
    fputcsv($output, ['TOTAL', $totalSit, '100%'], ';');
    fputcsv($output, [''], ';');

    // Por Revisão Médica
    fputcsv($output, ['RESULTADO REVISÃO MÉDICA'], ';');
    fputcsv($output, ['Resultado', 'Quantidade', 'Percentual'], ';');

    $rev = $obrigatorioDAO->getEstatisticasPorResultadoRevisao($ano, $mesesFiltro);
    $totalRev = array_sum($rev['data']);

    for ($i = 0; $i < count($rev['labels']); $i++) {
        $perc = $totalRev > 0 ? round(($rev['data'][$i] / $totalRev) * 100, 1) . '%' : '0%';
        fputcsv($output, [$rev['labels'][$i], $rev['data'][$i], $perc], ';');
    }
    fputcsv($output, ['TOTAL', $totalRev, '100%'], ';');
    fputcsv($output, [''], ';');

    // Por OM
    fputcsv($output, ['DISTRIBUIÇÃO POR OM'], ';');
    fputcsv($output, ['OM', 'Quantidade', 'Percentual'], ';');

    $oms = $obrigatorioDAO->getEstatisticasPorOM($ano, $mesesFiltro);
    $totalOMs = array_sum($oms['data']);

    for ($i = 0; $i < count($oms['labels']); $i++) {
        $perc = $totalOMs > 0 ? round(($oms['data'][$i] / $totalOMs) * 100, 1) . '%' : '0%';
        fputcsv($output, [$oms['labels'][$i], $oms['data'][$i], $perc], ';');
    }
    fputcsv($output, ['TOTAL', $totalOMs, '100%'], ';');
    fputcsv($output, [''], ';');

    // Evolução Mensal
    fputcsv($output, ['INCORPORAÇÕES POR MÊS'], ';');
    fputcsv($output, ['Mês', 'Quantidade'], ';');

    $mensal = $obrigatorioDAO->getEstatisticasMensais($ano, $mesesFiltro);

    for ($i = 0; $i < count($mensal['labels']); $i++) {
        fputcsv($output, [$mensal['labels'][$i], $mensal['data'][$i]], ';');
    }
    fputcsv($output, ['TOTAL', array_sum($mensal['data'])], ';');

} else {
    // Exportação de listagem de obrigatórios

    // Monta cabeçalho baseado nas colunas selecionadas
    $cabecalho = [];
    $mapaColunas = [
        'nome' => 'Nome Completo',
        'cpf' => 'CPF',
        'especialidade' => 'Especialidade',
        'situacao' => 'Situação Militar',
        'om' => 'OM 1ª Fase',
        'distribuicao' => 'Distribuição',
        'revisao' => 'Resultado Revisão',
        'jise' => 'JISE',
        'jisr' => 'JISR',
        'telefone' => 'Telefone',
        'email' => 'E-mail',
        'endereco' => 'Endereço',
        'formacao' => 'Formação/Ano',
        'data_nascimento' => 'Data Nascimento',
        'data_selecao' => 'Data Seleção Geral',
        'data_incorporacao' => 'Data Incorporação'
    ];

    foreach ($colunas as $col) {
        if (isset($mapaColunas[$col])) {
            $cabecalho[] = $mapaColunas[$col];
        }
    }

    fputcsv($output, $cabecalho, ';');

    // Busca obrigatórios baseado no tipo de dados
    $obrigatorios = [];

    if ($ano !== null) {
        $obrigatorios = $obrigatorioDAO->findAllAtivosPorAno($ano, $mesesFiltro);
    } else {
        // Admin sem filtro de ano - busca todos
        $obrigatorios = $obrigatorioDAO->findAllAtivosAdmin();
    }

    // Garante que é um array
    if (!is_array($obrigatorios)) {
        $obrigatorios = [];
    }

    // Filtra por tipo de dados
    foreach ($obrigatorios as $obr) {
        $incluir = true;

        switch ($tipoDados) {
            case 'designados':
                $incluir = !empty($obr->getDistribuicao()) &&
                           !in_array($obr->getDistribuicao(), ['EXCESSO CONTINGENTE', 'MARINHA', 'FORÇA AÉREA']);
                break;
            case 'incorporados':
                $incluir = !empty($obr->getDataIncorporacao());
                break;
            case 'pendentes':
                $incluir = empty($obr->getResultadoRevisaoMedicaComplementar()) &&
                           !empty($obr->getDistribuicao()) &&
                           !in_array($obr->getDistribuicao(), ['EXCESSO CONTINGENTE', 'MARINHA', 'FORÇA AÉREA']);
                break;
            case 'adiados':
                $incluir = $obr->getSolicitouAdiamento() == 'SIM' ||
                           strpos($obr->getSituacaoMilitar() ?? '', 'ADIADO') !== false;
                break;
        }

        // Filtra por OM
        if ($incluir && $omFiltro !== null) {
            $omObr = $obr->getOm1Fase();
            $incluir = $omObr && $omObr->getId() == $omFiltro;
        }

        // Filtra por especialidade
        if ($incluir && $especialidadeFiltro !== null) {
            $incluir = $obr->getEspecialidade() == $especialidadeFiltro;
        }

        if (!$incluir) continue;

        // Monta linha com base nas colunas selecionadas
        $linha = [];
        foreach ($colunas as $col) {
            switch ($col) {
                case 'nome':
                    $linha[] = $obr->getNomeCompleto();
                    break;
                case 'cpf':
                    $linha[] = $obr->getCPF();
                    break;
                case 'especialidade':
                    $linha[] = $obr->getEspecialidade() ?? '';
                    break;
                case 'situacao':
                    $linha[] = $obr->getSituacaoMilitar() ?? '';
                    break;
                case 'om':
                    $omObr = $obr->getOm1Fase();
                    $linha[] = $omObr ? $omObr->getAbreviatura() : '';
                    break;
                case 'distribuicao':
                    $linha[] = $obr->getDistribuicao() ?? '';
                    break;
                case 'revisao':
                    $linha[] = $obr->getResultadoRevisaoMedicaComplementar() ?? 'PENDENTE';
                    break;
                case 'jise':
                    $linha[] = $obr->getJise() ?? '';
                    break;
                case 'jisr':
                    $linha[] = $obr->getJisr() ?? '';
                    break;
                case 'telefone':
                    $linha[] = $obr->getTelefone() ?? '';
                    break;
                case 'email':
                    $linha[] = $obr->getMail() ?? '';
                    break;
                case 'endereco':
                    $linha[] = $obr->getEndereco() ?? '';
                    break;
                case 'formacao':
                    $linha[] = ($obr->getFormacao() ?? '') . ' / ' . ($obr->getAnoFormacao() ?? '');
                    break;
                case 'data_nascimento':
                    $data = $obr->getDataNascimento();
                    $linha[] = $data ? date('d/m/Y', strtotime($data)) : '';
                    break;
                case 'data_selecao':
                    $data = $obr->getDataSelecaoGeral();
                    $linha[] = $data ? date('d/m/Y', strtotime($data)) : '';
                    break;
                case 'data_incorporacao':
                    $data = $obr->getDataIncorporacao();
                    $linha[] = $data ? date('d/m/Y', strtotime($data)) : '';
                    break;
                default:
                    $linha[] = '';
            }
        }

        fputcsv($output, $linha, ';');
    }
}

fclose($output);

// Log
$logDAO->insertLog(4015, "Excel", null, "Exportou dados - Tipo: $tipoDados, Ano: " . ($ano ?? 'Todos'), "Exportação Excel realizada.", null);

exit();
?>
