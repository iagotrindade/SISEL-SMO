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
$omDAO = new OmDAO($conexao);
$logDAO = new LogDAO($conexao);

// Parâmetros do formulário
$omsIds = isset($_POST['oms']) ? $_POST['oms'] : [];
$ano = isset($_POST['ano']) ? (int)$_POST['ano'] : (int)date('Y');
$incluir = isset($_POST['incluir']) ? $_POST['incluir'] : ['estatisticas', 'especialidades', 'listagem'];

// Se "todas" estiver selecionado, busca todas as OMs
$todasOMs = false;
if (in_array('todas', $omsIds) || empty($omsIds)) {
    $todasOMs = true;
    $oms = $omDAO->findAllAtivos();
} else {
    $oms = [];
    foreach ($omsIds as $omId) {
        $om = $omDAO->findById($omId);
        if ($om) $oms[] = $om;
    }
}

// CSS do relatório
$css = "
<style>
    body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10pt; color: #333; }
    .cabecalho { text-align: center; margin-bottom: 15px; }
    .cabecalho img { margin-bottom: 5px; }
    .cabecalho-texto { font-size: 9pt; line-height: 1.3; }
    .titulo-principal { font-size: 14pt; font-weight: bold; text-align: center; margin: 10px 0; color: #006400; }
    .subtitulo { font-size: 11pt; text-align: center; margin-bottom: 15px; color: #666; }
    .secao-titulo { font-size: 11pt; font-weight: bold; color: #006400; border-bottom: 2px solid #006400; padding-bottom: 5px; margin: 15px 0 10px 0; }
    .om-titulo { font-size: 12pt; font-weight: bold; color: #fff; background-color: #006400; padding: 8px; margin: 15px 0 10px 0; border-radius: 3px; }
    .tabela { width: 100%; border-collapse: collapse; margin: 10px 0; }
    .tabela th { background-color: #006400; color: white; padding: 6px; text-align: left; font-size: 9pt; }
    .tabela td { padding: 5px 6px; border-bottom: 1px solid #ddd; font-size: 9pt; }
    .tabela tr:nth-child(even) { background-color: #f9f9f9; }
    .tabela-resumo { width: 100%; margin: 10px 0; }
    .tabela-resumo td { padding: 8px; text-align: center; vertical-align: top; }
    .card-numero { font-size: 20pt; font-weight: bold; color: #006400; }
    .card-label { font-size: 8pt; color: #666; }
    .rodape { font-size: 8pt; color: #999; text-align: center; margin-top: 15px; border-top: 1px solid #ddd; padding-top: 8px; }
    .barra-grafico { height: 15px; background-color: #006400; border-radius: 3px; }
    .barra-container { background-color: #e9ecef; border-radius: 3px; width: 100%; }
    .pagebreak { page-break-before: always; }
    .info-box { background: #f8f9fa; padding: 10px; border-radius: 5px; margin: 10px 0; }
</style>";

// Cabeçalho
$html = $css . "
<div class='cabecalho'>
    <img src='../imagens/brasao.png' width='60'>
    <div class='cabecalho-texto'>
        MINISTÉRIO DA DEFESA - EXÉRCITO BRASILEIRO<br>
        COMANDO MILITAR DO SUL - COMANDO DA 3ª REGIÃO MILITAR
    </div>
</div>

<div class='titulo-principal'>RELATÓRIO POR ORGANIZAÇÃO MILITAR</div>
<div class='subtitulo'>Ano: $ano | " . ($todasOMs ? "Todas as OMs" : count($oms) . " OM(s) selecionada(s)") . " | Gerado em: " . date('d/m/Y H:i') . "</div>";

// Comparativo entre OMs (se selecionado e múltiplas OMs)
if (in_array('comparativo', $incluir) && count($oms) > 1) {
    $html .= "
    <div class='secao-titulo'>COMPARATIVO ENTRE OMs</div>
    <table class='tabela'>
        <tr>
            <th style='width: 25%;'>Organização Militar</th>
            <th style='width: 15%; text-align: center;'>Designados</th>
            <th style='width: 15%; text-align: center;'>Aptos</th>
            <th style='width: 15%; text-align: center;'>Inaptos</th>
            <th style='width: 15%; text-align: center;'>Pendentes</th>
            <th style='width: 15%; text-align: center;'>% Aptos</th>
        </tr>";

    $totalGeral = ['designados' => 0, 'aptos' => 0, 'inaptos' => 0, 'pendentes' => 0];

    foreach ($oms as $om) {
        $designados = $ObrigatorioDAO->countDesignadosOM($om->getId(), $ano);
        $aptos = $ObrigatorioDAO->countAptosOM($om->getId(), $ano);
        $inaptos = $ObrigatorioDAO->countInaptosOM($om->getId(), $ano);
        $pendentes = $ObrigatorioDAO->countPendentesRevisaoOM($om->getId(), $ano);
        $percAptos = $designados > 0 ? round(($aptos / $designados) * 100, 1) : 0;

        $totalGeral['designados'] += $designados;
        $totalGeral['aptos'] += $aptos;
        $totalGeral['inaptos'] += $inaptos;
        $totalGeral['pendentes'] += $pendentes;

        $html .= "
        <tr>
            <td>" . htmlspecialchars($om->getAbreviatura() . ' - ' . $om->getNome()) . "</td>
            <td style='text-align: center;'>$designados</td>
            <td style='text-align: center;'>$aptos</td>
            <td style='text-align: center;'>$inaptos</td>
            <td style='text-align: center;'>$pendentes</td>
            <td style='text-align: center;'>$percAptos%</td>
        </tr>";
    }

    $percTotalAptos = $totalGeral['designados'] > 0 ? round(($totalGeral['aptos'] / $totalGeral['designados']) * 100, 1) : 0;
    $html .= "
        <tr style='background: #f0f7f0; font-weight: bold;'>
            <td>TOTAL GERAL</td>
            <td style='text-align: center;'>{$totalGeral['designados']}</td>
            <td style='text-align: center;'>{$totalGeral['aptos']}</td>
            <td style='text-align: center;'>{$totalGeral['inaptos']}</td>
            <td style='text-align: center;'>{$totalGeral['pendentes']}</td>
            <td style='text-align: center;'>$percTotalAptos%</td>
        </tr>
    </table>";
}

// Detalhes por OM
$primeiraOM = true;
foreach ($oms as $om) {
    if (!$primeiraOM) {
        $html .= "<div class='pagebreak'></div>";
    }
    $primeiraOM = false;

    $omNome = htmlspecialchars($om->getAbreviatura() . ' - ' . $om->getNome());
    $html .= "<div class='om-titulo'>$omNome</div>";

    // Informações da OM
    $html .= "
    <div class='info-box'>
        <strong>Cidade:</strong> " . htmlspecialchars($om->getCidade() ?? 'N/I') . " |
        <strong>Telefone:</strong> " . htmlspecialchars($om->getTelefone() ?? 'N/I') . " |
        <strong>Endereço:</strong> " . htmlspecialchars($om->getEndereco() ?? 'N/I') . "
    </div>";

    // Estatísticas da OM
    if (in_array('estatisticas', $incluir)) {
        $designados = $ObrigatorioDAO->countDesignadosOM($om->getId(), $ano);
        $aptos = $ObrigatorioDAO->countAptosOM($om->getId(), $ano);
        $inaptos = $ObrigatorioDAO->countInaptosOM($om->getId(), $ano);
        $pendentes = $ObrigatorioDAO->countPendentesRevisaoOM($om->getId(), $ano);

        $html .= "
        <div class='secao-titulo'>Estatísticas Gerais</div>
        <table class='tabela-resumo'>
            <tr>
                <td style='width: 25%; background: #f0f7f0; border-radius: 5px;'>
                    <div class='card-numero'>$designados</div>
                    <div class='card-label'>Designados</div>
                </td>
                <td style='width: 25%; background: #d4edda; border-radius: 5px;'>
                    <div class='card-numero' style='color: #28a745;'>$aptos</div>
                    <div class='card-label'>Aptos</div>
                </td>
                <td style='width: 25%; background: #f8d7da; border-radius: 5px;'>
                    <div class='card-numero' style='color: #dc3545;'>$inaptos</div>
                    <div class='card-label'>Inaptos</div>
                </td>
                <td style='width: 25%; background: #fff3cd; border-radius: 5px;'>
                    <div class='card-numero' style='color: #856404;'>$pendentes</div>
                    <div class='card-label'>Pendentes Revisão</div>
                </td>
            </tr>
        </table>";
    }

    // Especialidades da OM
    if (in_array('especialidades', $incluir)) {
        $estatEsp = $ObrigatorioDAO->getEstatisticasEspecialidadeOM($om->getId(), $ano);

        if (!empty($estatEsp['labels'])) {
            $html .= "
            <div class='secao-titulo'>Distribuição por Especialidade</div>
            <table class='tabela'>
                <tr>
                    <th style='width: 50%;'>Especialidade</th>
                    <th style='width: 35%;'>Distribuição</th>
                    <th style='width: 15%; text-align: center;'>Qtd</th>
                </tr>";

            $maxEsp = max($estatEsp['data']);
            for ($i = 0; $i < count($estatEsp['labels']); $i++) {
                $label = htmlspecialchars($estatEsp['labels'][$i]);
                $valor = $estatEsp['data'][$i];
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
                </tr>";
            }
            $html .= "</table>";
        }
    }

    // Listagem de designados
    if (in_array('listagem', $incluir)) {
        $obrigatorios = $ObrigatorioDAO->findByOMsAno($om->getId(), $ano);

        if (!empty($obrigatorios)) {
            $html .= "
            <div class='secao-titulo'>Listagem de Designados (" . count($obrigatorios) . " registro(s))</div>
            <table class='tabela'>
                <tr>
                    <th style='width: 5%;'>Nº</th>
                    <th style='width: 35%;'>Nome</th>
                    <th style='width: 15%;'>CPF</th>
                    <th style='width: 20%;'>Especialidade</th>
                    <th style='width: 15%;'>Distribuição</th>
                    <th style='width: 10%;'>Revisão</th>
                </tr>";

            $contador = 1;
            foreach ($obrigatorios as $obr) {
                $nome = htmlspecialchars(mb_strimwidth($obr->getNomeCompleto(), 0, 40, '...'));
                $cpf = htmlspecialchars($obr->getCPF());
                $esp = htmlspecialchars(mb_strimwidth($obr->getEspecialidade() ?? 'N/I', 0, 25, '...'));
                $dist = htmlspecialchars(mb_strimwidth($obr->getDistribuicao() ?? 'N/D', 0, 18, '...'));
                $revisao = htmlspecialchars($obr->getResultadoRevisaoMedicaComplementar() ?? 'PENDENTE');

                $corRevisao = '';
                if ($revisao == 'APTO') $corRevisao = 'color: #28a745;';
                elseif ($revisao == 'INAPTO') $corRevisao = 'color: #dc3545;';
                elseif ($revisao == 'PENDENTE') $corRevisao = 'color: #856404;';

                $html .= "
                <tr>
                    <td style='text-align: center;'>$contador</td>
                    <td>$nome</td>
                    <td>$cpf</td>
                    <td>$esp</td>
                    <td>$dist</td>
                    <td style='$corRevisao'>$revisao</td>
                </tr>";
                $contador++;
            }
            $html .= "</table>";
        } else {
            $html .= "<p><em>Nenhum designado encontrado para esta OM no período selecionado.</em></p>";
        }
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
$logDAO->insertLog(4011, "PDF", null, "Gerou Relatório por OM - Ano: $ano, OMs: " . count($oms), "Relatório por OM gerado.", null);

ob_end_clean();
$nomeArquivo = "relatorio_por_om_{$ano}_" . date('Ymd_His') . ".pdf";
$mpdf->Output($nomeArquivo, 'I');
?>
