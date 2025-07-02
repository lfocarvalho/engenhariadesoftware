<?php
header('Content-Type: application/json');
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../config/config.php';
require_once __DIR__ . '/../models/tarefamodel.php';

if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Usuário não autenticado.']);
    exit;
}

$usuario_id = $_SESSION['usuario']['id'];
$tarefaModel = new TarefaModel($pdo);
$view = $_GET['view'] ?? 'semanal';

try {
    $all_tasks = $tarefaModel->getAllByUserId($usuario_id, 'todas');
    $completed_tasks = 0;
    $total_tasks = count($all_tasks);

    foreach ($all_tasks as $task) {
        if ($task['concluida']) {
            $completed_tasks++;
        }
    }
    
    $pending_tasks = $total_tasks - $completed_tasks;
    $chart_labels = [];
    $chart_data = [];

    if ($view === 'mensal') {
        // Lógica para a visão mensal (mantida como estava)
        $today = new DateTime();
        $daysInMonth = (int)$today->format('t');
        $numWeeks = (int)ceil($daysInMonth / 7);
        $currentMonth = $today->format('Y-m');

        $chart_data = array_fill(0, $numWeeks, 0);

        foreach ($all_tasks as $task) {
            if ($task['concluida']) {
                $date_to_check_str = isset($task['data_conclusao']) && $task['data_conclusao'] ? $task['data_conclusao'] : $task['data_vencimento'];
                $date_completed = new DateTime($date_to_check_str);

                if (strpos($date_completed->format('Y-m'), $currentMonth) === 0) {
                    $dayOfMonth = (int)$date_completed->format('d');
                    $weekIndex = floor(($dayOfMonth - 1) / 7);
                    if (isset($chart_data[$weekIndex])) {
                        $chart_data[$weekIndex]++;
                    }
                }
            }
        }
        
        for ($i = 1; $i <= $numWeeks; $i++) {
            $chart_labels[] = $i . 'ª Semana';
        }

    } else { // Semanal
        $chart_labels = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
        $chart_data = array_fill(0, 7, 0);
        $today = new DateTime();
        
        // --- CORREÇÃO APLICADA AQUI ---
        // Cálculo robusto para o início da semana (Domingo)
        $dayNumInWeek = (int)$today->format('w'); // 0 para Domingo, 1 para Segunda, etc.
        $startOfWeek = (clone $today)->modify("-{$dayNumInWeek} days")->setTime(0, 0, 0);
        $endOfWeek = (clone $startOfWeek)->modify("+6 days")->setTime(23, 59, 59);

        foreach ($all_tasks as $task) {
            if ($task['concluida']) {
                try {
                    // --- CORREÇÃO APLICADA AQUI ---
                    // Prioriza a data de conclusão, se existir. Senão, usa a data de vencimento.
                    $date_to_check_str = isset($task['data_conclusao']) && $task['data_conclusao'] ? $task['data_conclusao'] : $task['data_vencimento'];
                    $date_to_check = new DateTime($date_to_check_str);

                    // Verifica se a data está dentro do intervalo da semana atual
                    if ($date_to_check >= $startOfWeek && $date_to_check <= $endOfWeek) {
                        $dayOfWeek = (int)$date_to_check->format('w');
                        if (isset($chart_data[$dayOfWeek])) {
                            $chart_data[$dayOfWeek]++;
                        }
                    }
                } catch (Exception $e) {
                    // Ignora datas inválidas que podem estar no banco
                    continue;
                }
            }
        }
    }
    
    $progress_data = [
        'total' => $total_tasks,
        'completed' => $completed_tasks,
        'pending' => $pending_tasks,
        'chartData' => $chart_data,
        'chartLabels' => $chart_labels
    ];

    echo json_encode(['success' => true, 'progress' => $progress_data]);

} catch (Exception $e) {
    http_response_code(500);
    error_log('Erro em get_progresso.php: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Ocorreu um erro no servidor.', 'detail' => $e->getMessage()]);
}
?>