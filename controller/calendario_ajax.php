<?php
/**
 * Endpoint AJAX para dados do Calendário de Eventos
 * Retorna próximos eventos e eventos por mês
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['id_usuario_smo']) || $_SESSION['perfil_smo'] != 'admin') {
    echo json_encode(['success' => false, 'error' => 'Não autorizado']);
    exit();
}

include_once '../funcoes.php';
include_once $BASE_URL . '/dao/conecta_banco.php';
include_once $BASE_URL . '/models/Obrigatorio.php';
include_once $BASE_URL . '/dao/ObrigatorioDAO.php';

$obrigatorioDAO = new ObrigatorioDAO($conexao);

$acao = isset($_GET['acao']) ? $_GET['acao'] : 'proximos';

try {
    switch ($acao) {
        case 'proximos':
            // Retorna próximos eventos (próximos 30 dias por padrão)
            $dias = isset($_GET['dias']) ? (int)$_GET['dias'] : 30;
            $eventos = $obrigatorioDAO->getProximosEventos($dias);

            // Formata as datas para exibição
            foreach ($eventos as &$evento) {
                $timestamp = strtotime($evento['data']);
                $evento['data_formatada'] = date('d/m/Y', $timestamp);
                $evento['dia_semana'] = strftime('%A', $timestamp);

                // Calcula dias restantes
                $hoje = strtotime(date('Y-m-d'));
                $diasRestantes = floor(($timestamp - $hoje) / 86400);

                if ($diasRestantes == 0) {
                    $evento['quando'] = 'Hoje';
                } elseif ($diasRestantes == 1) {
                    $evento['quando'] = 'Amanhã';
                } elseif ($diasRestantes <= 7) {
                    $evento['quando'] = "Em {$diasRestantes} dias";
                } else {
                    $evento['quando'] = date('d/m', $timestamp);
                }
            }

            echo json_encode([
                'success' => true,
                'eventos' => $eventos,
                'total' => count($eventos)
            ]);
            break;

        case 'mes':
            // Retorna eventos de um mês específico
            $mes = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
            $ano = isset($_GET['ano']) ? (int)$_GET['ano'] : (int)date('Y');

            // Valida mês e ano
            if ($mes < 1 || $mes > 12) $mes = (int)date('m');
            if ($ano < 2020 || $ano > 2030) $ano = (int)date('Y');

            $eventos = $obrigatorioDAO->getEventosPorMes($mes, $ano);

            // Informações do mês
            $primeiroDia = mktime(0, 0, 0, $mes, 1, $ano);
            $diasNoMes = (int)date('t', $primeiroDia);
            $diaSemanaInicio = (int)date('w', $primeiroDia); // 0 = Domingo

            $meses = ['', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                      'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

            echo json_encode([
                'success' => true,
                'mes' => $mes,
                'ano' => $ano,
                'nome_mes' => $meses[$mes],
                'dias_no_mes' => $diasNoMes,
                'dia_semana_inicio' => $diaSemanaInicio,
                'eventos' => $eventos
            ]);
            break;

        default:
            echo json_encode(['success' => false, 'error' => 'Ação inválida']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
