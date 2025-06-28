<?php
header('Content-Type: application/json');
session_start();

// Garante que todos os erros sejam reportados para depuração
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
        $today = new DateTime();
        $daysInMonth = (int)$today->format('t');
        $numWeeks = (int)ceil($daysInMonth / 7);
        $currentMonth = $today->format('Y-m');

        $chart_data = array_fill(0, $numWeeks, 0);

        foreach ($all_tasks as $task) {
            if ($task['concluida']) {
                $date_completed = new DateTime($task['data_vencimento']);
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
        $chart_data = array_fill(0, 7, 0);
        $today = new DateTime();
        $startOfWeek = (clone $today)->modify('Sunday last week');

        foreach ($all_tasks as $task) {
            if ($task['concluida']) {
                $date_completed = new DateTime($task['data_vencimento']);
                if ($date_completed >= $startOfWeek && $date_completed <= $today) {
                    $dayOfWeek = (int)$date_completed->format('w');
                    $chart_data[$dayOfWeek]++;
                }
            }
        }
        $chart_labels = ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'];
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