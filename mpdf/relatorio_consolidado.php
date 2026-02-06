<?php
ob_start();

if (!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../models/Obrigatorio.php';
include_once '../models/Om.php';
include_once '../dao/ObrigatorioDAO.php';
include_once '../dao/OmDAO.php';
include_once '../dao/LogDAO.php';
include("mpdf60/mpdf.php");

$mpdf = new mPDF('C', 'A4-P', 0, '', 15, 15, 16, 16, 9, 9);
$mpdf->SetDisplayMode('fullpage');

if ($_SESSION['perfil_smo'] != "admin") {
    erro($BASE_URL, 2, 6549874951, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");
}

$ObrigatorioDAO = new ObrigatorioDAO($conexao);
$logDAO = new LogDAO($conexao);

// Parâmetros do formulário
$ano = isset($_POST['ano']) ? (int)$_POST['ano'] : (int)date('Y');
$periodo = isset($_POST['periodo']) ? $_POST['periodo'] : 'ano';
$secoes = isset($_POST['secoes']) ? $_POST['secoes'] : ['resumo', 'especialidades', 'situacao', 'oms', 'revisao', 'mensal'];

// Converte período para meses e nome
$mesesFiltro = [];
$nomePeriodo = 'Ano Completo';
switch ($periodo) {
    case '1sem':
        $mesesFiltro = [1, 2, 3, 4, 5, 6];
        $nomePeriodo = '1º Semestre';
        break;
    case '2sem':
        $mesesFiltro = [7, 8, 9, 10, 11, 12];
        $nomePeriodo = '2º Semestre';
        break;
    case '1tri':
        $mesesFiltro = [1, 2, 3];
        $nomePeriodo = '1º Trimestre';
        break;
    case '2tri':
        $mesesFiltro = [4, 5, 6];
        $nomePeriodo = '2º Trimestre';
        break;
    case '3tri':
        $mesesFiltro = [7, 8, 9];
        $nomePeriodo = '3º Trimestre';
        break;
    case '4tri':
        $mesesFiltro = [10, 11, 12];
        $nomePeriodo = '4º Trimestre';
        break;
    default:
        $mesesFiltro = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
}

// Busca estatísticas
$estatisticasEspecialidade = $ObrigatorioDAO->getEstatisticasPorEspecialidade($ano, $mesesFiltro);
$estatisticasSituacao = $ObrigatorioDAO->getEstatisticasPorSituacaoMilitar($ano, $mesesFiltro);
$estatisticasRevisao = $ObrigatorioDAO->getEstatisticasPorResultadoRevisao($ano, $mesesFiltro);
$estatisticasMensal = $ObrigatorioDAO->getEstatisticasMensais($ano, $mesesFiltro);
$estatisticasOM = $ObrigatorioDAO->getEstatisticasPorOM($ano, $mesesFiltro);
$estatisticasJISE = $ObrigatorioDAO->getEstatisticasPorJISE($ano, $mesesFiltro);

// Totais - Usando data_selecao_geral como base para consistência
$totalObrigatorios = array_sum($estatisticasSituacao['data']);
$totalDesignados = $ObrigatorioDAO->countDesignados($ano, $mesesFiltro);
$pendentesRevisao = $ObrigatorioDAO->countPendentesRevisaoMedica($ano, $mesesFiltro);
$totalAptos = $ObrigatorioDAO->countAptos($ano, $mesesFiltro);
$totalIncorporados = $ObrigatorioDAO->countIncorporadosPorAnoSelecao($ano, $mesesFiltro);
$aguardandoIncorporacao = $ObrigatorioDAO->countAguardandoIncorporacao($ano, $mesesFiltro);
$totalInaptos = $ObrigatorioDAO->countInaptos($ano, $mesesFiltro);
$aguardandoDistribuicao = $totalObrigatorios - $totalDesignados;

// CSS do relatório
$css = "
<style>
    body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10pt; color: #333; }
    .cabecalho { text-align: center; margin-bottom: 20px; }
    .cabecalho img { margin-bottom: 5px; }
    .cabecalho-texto { font-size: 9pt; line-height: 1.3; }
    .titulo-principal { font-size: 16pt; font-weight: bold; text-align: center; margin: 15px 0; color: #006400; }
    .subtitulo { font-size: 12pt; text-align: center; margin-bottom: 20px; color: #666; }
    .secao-titulo { font-size: 12pt; font-weight: bold; color: #006400; border-bottom: 2px solid #006400; padding-bottom: 5px; margin: 20px 0 10px 0; }
    .card-resumo { background-color: #f8f9fa; border-radius: 5px; padding: 15px; margin-bottom: 15px; }
    .card-numero { font-size: 24pt; font-weight: bold; color: #006400; }
    .card-label { font-size: 9pt; color: #666; }
    .tabela { width: 100%; border-collapse: collapse; margin: 10px 0; }
    .tabela th { background-color: #006400; color: white; padding: 8px; text-align: left; font-size: 9pt; }
    .tabela td { padding: 6px 8px; border-bottom: 1px solid #ddd; font-size: 9pt; }
    .tabela tr:nth-child(even) { background-color: #f9f9f9; }
    .tabela-resumo { width: 100%; margin: 15px 0; }
    .tabela-resumo td { padding: 10px; text-align: center; vertical-align: top; }
    .destaque { color: #006400; font-weight: bold; }
    .rodape { font-size: 8pt; color: #999; text-align: center; margin-top: 20px; border-top: 1px solid #ddd; padding-top: 10px; }
    .barra-grafico { height: 20px; background-color: #006400; border-radius: 3px; margin: 2px 0; }
    .barra-container { background-color: #e9ecef; border-radius: 3px; width: 100%; }
    .legenda { font-size: 8pt; color: #666; }
    .pagebreak { page-break-before: always; }
</style>";

// Cabeçalho
$html = $css . "
<div class='cabecalho'>
    <img src='../imagens/brasao.png' width='70'>
    <div class='cabecalho-texto'>
        MINISTÉRIO DA DEFESA<br>
        EXÉRCITO BRASILEIRO<br>
        COMANDO MILITAR DO SUL<br>
        COMANDO DA 3ª REGIÃO MILITAR
    </div>
</div>

<div class='titulo-principal'>RELATÓRIO CONSOLIDADO - SERVIÇO MILITAR OBRIGATÓRIO</div>
<div class='subtitulo'>Ano: $ano | Período: $nomePeriodo | Gerado em: " . date('d/m/Y H:i') . "</div>";

// Seção: Resumo Executivo
if (in_array('resumo', $secoes)) {
    $html .= "
    <div class='secao-titulo'>1. RESUMO EXECUTIVO</div>
    <table class='tabela-resumo'>
        <tr>
            <td style='width: 16.6%; background: #f0f7f0; border-radius: 5px;'>
                <div class='card-numero'>$totalObrigatorios</div>
                <div class='card-label'>Total de Obrigatórios</div>
            </td>
            <td style='width: 16.6%; background: #e3f2fd; border-radius: 5px;'>
                <div class='card-numero' style='color: #1565c0;'>$totalDesignados</div>
                <div class='card-label'>Designados p/ OM</div>
            </td>
            <td style='width: 16.6%; background: #fff3cd; border-radius: 5px;'>
                <div class='card-numero' style='color: #856404;'>$pendentesRevisao</div>
                <div class='card-label'>Pendentes Revisão</div>
            </td>
            <td style='width: 16.6%; background: #d4edda; border-radius: 5px;'>
                <div class='card-numero' style='color: #28a745;'>$totalAptos</div>
                <div class='card-label'>Aptos</div>
            </td>
            <td style='width: 16.6%; background: #d1ecf1; border-radius: 5px;'>
                <div class='card-numero' style='color: #0c5460;'>$totalIncorporados</div>
                <div class='card-label'>Incorporados</div>
            </td>
            <td style='width: 16.6%; background: #f8d7da; border-radius: 5px;'>
                <div class='card-numero' style='color: #721c24;'>$totalInaptos</div>
                <div class='card-label'>Inaptos</div>
            </td>
        </tr>
    </table>
    <p style='font-size: 8pt; color: #666; margin-top: 5px;'>
        <strong>Legenda:</strong> Aguardando Distribuição: $aguardandoDistribuicao | Aguardando Incorporação: $aguardandoIncorporacao
    </p>";
}

// Seção: Por Especialidade
if (in_array('especialidades', $secoes) && !empty($estatisticasEspecialidade['labels'])) {
    $totalEsp = array_sum($estatisticasEspecialidade['data']);
    $maxEsp = max($estatisticasEspecialidade['data']);

    $html .= "
    <div class='secao-titulo'>2. DISTRIBUIÇÃO POR ESPECIALIDADE</div>
    <table class='tabela'>
        <tr>
            <th style='width: 40%;'>Especialidade</th>
            <th style='width: 40%;'>Distribuição Visual</th>
            <th style='width: 10%; text-align: center;'>Qtd</th>
            <th style='width: 10%; text-align: center;'>%</th>
        </tr>";

    for ($i = 0; $i < count($estatisticasEspecialidade['labels']); $i++) {
        $label = htmlspecialchars($estatisticasEspecialidade['labels'][$i]);
        $valor = $estatisticasEspecialidade['data'][$i];
        $percentual = $totalEsp > 0 ? round(($valor / $totalEsp) * 100, 1) : 0;
        $larguraBarra = $maxEsp > 0 ? round(($valor / $maxEsp) * 100) : 0;

        $html .= "
        <tr>
            <td>$label</td>
            <td>
                <div class='barra-container'>
                    <div class='barra-grafico' style='width: {$larguraBarra}%;'></div>
                </div>
            </td>
            <td style='text-align: center;'>$valor</td>
            <td style='text-align: center;'>$percentual%</td>
        </tr>";
    }

    $html .= "
        <tr style='background: #f0f7f0; font-weight: bold;'>
            <td>TOTAL</td>
            <td></td>
            <td style='text-align: center;'>$totalEsp</td>
            <td style='text-align: center;'>100%</td>
        </tr>
    </table>";
}

// Seção: Situação Militar
if (in_array('situacao', $secoes) && !empty($estatisticasSituacao['labels'])) {
    $totalSit = array_sum($estatisticasSituacao['data']);
    $maxSit = max($estatisticasSituacao['data']);

    $html .= "
    <div class='secao-titulo'>3. SITUAÇÃO MILITAR</div>
    <table class='tabela'>
        <tr>
            <th style='width: 50%;'>Situação</th>
            <th style='width: 30%;'>Distribuição</th>
            <th style='width: 10%; text-align: center;'>Qtd</th>
            <th style='width: 10%; text-align: center;'>%</th>
        </tr>";

    for ($i = 0; $i < count($estatisticasSituacao['labels']); $i++) {
        $label = htmlspecialchars($estatisticasSituacao['labels'][$i]);
        $valor = $estatisticasSituacao['data'][$i];
        $percentual = $totalSit > 0 ? round(($valor / $totalSit) * 100, 1) : 0;
        $larguraBarra = $maxSit > 0 ? round(($valor / $maxSit) * 100) : 0;

        // Cores diferentes baseadas no tipo de situação
        $corBarra = '#006400';
        if (strpos($label, 'Débito') !== false) $corBarra = '#dc3545';
        elseif (strpos($label, 'Quite') !== false) $corBarra = '#28a745';

        $html .= "
        <tr>
            <td>$label</td>
            <td>
                <div class='barra-container'>
                    <div class='barra-grafico' style='width: {$larguraBarra}%; background-color: $corBarra;'></div>
                </div>
            </td>
            <td style='text-align: center;'>$valor</td>
            <td style='text-align: center;'>$percentual%</td>
        </tr>";
    }

    $html .= "
        <tr style='background: #f0f7f0; font-weight: bold;'>
            <td>TOTAL</td>
            <td></td>
            <td style='text-align: center;'>$totalSit</td>
            <td style='text-align: center;'>100%</td>
        </tr>
    </table>";
}

// Nova página para próximas seções
if (in_array('oms', $secoes) || in_array('revisao', $secoes)) {
    $html .= "<div class='pagebreak'></div>";
}

// Seção: Por OM
if (in_array('oms', $secoes) && !empty($estatisticasOM['labels'])) {
    $totalOM = array_sum($estatisticasOM['data']);
    $maxOM = max($estatisticasOM['data']);

    $html .= "
    <div class='secao-titulo'>4. DISTRIBUIÇÃO POR OM (TOP 10)</div>
    <table class='tabela'>
        <tr>
            <th style='width: 30%;'>Organização Militar</th>
            <th style='width: 50%;'>Distribuição</th>
            <th style='width: 10%; text-align: center;'>Qtd</th>
            <th style='width: 10%; text-align: center;'>%</th>
        </tr>";

    for ($i = 0; $i < count($estatisticasOM['labels']); $i++) {
        $label = htmlspecialchars($estatisticasOM['labels'][$i]);
        $valor = $estatisticasOM['data'][$i];
        $percentual = $totalOM > 0 ? round(($valor / $totalOM) * 100, 1) : 0;
        $larguraBarra = $maxOM > 0 ? round(($valor / $maxOM) * 100) : 0;

        $html .= "
        <tr>
            <td>$label</td>
            <td>
                <div class='barra-container'>
                    <div class='barra-grafico' style='width: {$larguraBarra}%;'></div>
                </div>
            </td>
            <td style='text-align: center;'>$valor</td>
            <td style='text-align: center;'>$percentual%</td>
        </tr>";
    }

    $html .= "</table>";
}

// Seção: Revisão Médica
if (in_array('revisao', $secoes) && !empty($estatisticasRevisao['labels'])) {
    $totalRev = array_sum($estatisticasRevisao['data']);

    $html .= "
    <div class='secao-titulo'>5. RESULTADO DA REVISÃO MÉDICA</div>
    <table class='tabela'>
        <tr>
            <th style='width: 40%;'>Resultado</th>
            <th style='width: 40%;'>Distribuição</th>
            <th style='width: 10%; text-align: center;'>Qtd</th>
            <th style='width: 10%; text-align: center;'>%</th>
        </tr>";

    for ($i = 0; $i < count($estatisticasRevisao['labels']); $i++) {
        $label = htmlspecialchars($estatisticasRevisao['labels'][$i]);
        $valor = $estatisticasRevisao['data'][$i];
        $percentual = $totalRev > 0 ? round(($valor / $totalRev) * 100, 1) : 0;
        $larguraBarra = $totalRev > 0 ? round(($valor / $totalRev) * 100) : 0;

        // Cores baseadas no resultado
        $corBarra = '#006400';
        if ($label == 'APTO') $corBarra = '#28a745';
        elseif ($label == 'INAPTO') $corBarra = '#dc3545';
        elseif ($label == 'PENDENTE') $corBarra = '#ffc107';
        elseif (strpos($label, 'NÃO COMPARECEU') !== false) $corBarra = '#6c757d';

        $html .= "
        <tr>
            <td>$label</td>
            <td>
                <div class='barra-container'>
                    <div class='barra-grafico' style='width: {$larguraBarra}%; background-color: $corBarra;'></div>
                </div>
            </td>
            <td style='text-align: center;'>$valor</td>
            <td style='text-align: center;'>$percentual%</td>
        </tr>";
    }

    $html .= "
        <tr style='background: #f0f7f0; font-weight: bold;'>
            <td>TOTAL</td>
            <td></td>
            <td style='text-align: center;'>$totalRev</td>
            <td style='text-align: center;'>100%</td>
        </tr>
    </table>";
}

// Seção: Evolução Mensal
if (in_array('mensal', $secoes) && !empty($estatisticasMensal['labels'])) {
    $totalMensal = array_sum($estatisticasMensal['data']);
    $maxMensal = !empty($estatisticasMensal['data']) ? max($estatisticasMensal['data']) : 1;

    $html .= "
    <div class='secao-titulo'>6. INCORPORAÇÕES POR MÊS</div>
    <table class='tabela'>
        <tr>
            <th style='width: 15%;'>Mês</th>
            <th style='width: 65%;'>Distribuição</th>
            <th style='width: 10%; text-align: center;'>Qtd</th>
            <th style='width: 10%; text-align: center;'>%</th>
        </tr>";

    for ($i = 0; $i < count($estatisticasMensal['labels']); $i++) {
        $label = htmlspecialchars($estatisticasMensal['labels'][$i]);
        $valor = $estatisticasMensal['data'][$i];
        $percentual = $totalMensal > 0 ? round(($valor / $totalMensal) * 100, 1) : 0;
        $larguraBarra = $maxMensal > 0 ? round(($valor / $maxMensal) * 100) : 0;

        $html .= "
        <tr>
            <td>$label</td>
            <td>
                <div class='barra-container'>
                    <div class='barra-grafico' style='width: {$larguraBarra}%;'></div>
                </div>
            </td>
            <td style='text-align: center;'>$valor</td>
            <td style='text-align: center;'>$percentual%</td>
        </tr>";
    }

    $html .= "
        <tr style='background: #f0f7f0; font-weight: bold;'>
            <td>TOTAL</td>
            <td></td>
            <td style='text-align: center;'>$totalMensal</td>
            <td style='text-align: center;'>100%</td>
        </tr>
    </table>";
}

// Listagem completa (se selecionado)
if (in_array('listagem', $secoes)) {
    $obrigatorios = $ObrigatorioDAO->findAllAtivosPorAno($ano, $mesesFiltro);

    if (!empty($obrigatorios)) {
        $html .= "<div class='pagebreak'></div>";
        $html .= "
        <div class='secao-titulo'>7. LISTAGEM COMPLETA DE OBRIGATÓRIOS</div>
        <p class='legenda'>Total de registros: " . count($obrigatorios) . "</p>
        <table class='tabela'>
            <tr>
                <th style='width: 5%;'>Nº</th>
                <th style='width: 30%;'>Nome</th>
                <th style='width: 12%;'>CPF</th>
                <th style='width: 18%;'>Especialidade</th>
                <th style='width: 10%;'>OM</th>
                <th style='width: 15%;'>Situação</th>
                <th style='width: 10%;'>Revisão</th>
            </tr>";

        $contador = 1;
        foreach ($obrigatorios as $obr) {
            $nome = htmlspecialchars(mb_strimwidth($obr->getNomeCompleto(), 0, 35, '...'));
            $cpf = htmlspecialchars($obr->getCPF());
            $esp = htmlspecialchars(mb_strimwidth($obr->getEspecialidade() ?? 'N/I', 0, 20, '...'));
            $om = htmlspecialchars($obr->getOm1Fase() ? mb_strimwidth($obr->getOm1Fase()->getAbreviatura() ?? '', 0, 10, '...') : 'N/D');
            $situacao = htmlspecialchars(mb_strimwidth($obr->getSituacaoMilitar() ?? 'N/I', 0, 18, '...'));
            $revisao = htmlspecialchars($obr->getResultadoRevisaoMedicaComplementar() ?? 'PENDENTE');

            $html .= "
            <tr>
                <td style='text-align: center;'>$contador</td>
                <td>$nome</td>
                <td>$cpf</td>
                <td>$esp</td>
                <td>$om</td>
                <td>$situacao</td>
                <td>$revisao</td>
            </tr>";
            $contador++;

            // Quebra de página a cada 40 registros
            if ($contador % 40 == 0 && $contador < count($obrigatorios)) {
                $html .= "</table><div class='pagebreak'></div>
                <table class='tabela'>
                    <tr>
                        <th style='width: 5%;'>Nº</th>
                        <th style='width: 30%;'>Nome</th>
                        <th style='width: 12%;'>CPF</th>
                        <th style='width: 18%;'>Especialidade</th>
                        <th style='width: 10%;'>OM</th>
                        <th style='width: 15%;'>Situação</th>
                        <th style='width: 10%;'>Revisão</th>
                    </tr>";
            }
        }

        $html .= "</table>";
    }
}

// Rodapé
$html .= "
<div class='rodape'>
    Relatório gerado pelo Sistema SISEL-SMO | 3ª Região Militar<br>
    Gerado em " . date('d/m/Y H:i:s') . " por " . $_SESSION['nome_guerra_smo']
. "</div>";

$mpdf->WriteHTML($html);

// Log
$logDAO->insertLog(4010, "PDF", null, "Gerou Relatório Consolidado - Ano: $ano, Período: $nomePeriodo", "Relatório consolidado gerado.", null);

ob_end_clean();
$nomeArquivo = "relatorio_consolidado_{$ano}_{$periodo}_" . date('Ymd_His') . ".pdf";
$mpdf->Output($nomeArquivo, 'I');
?>
