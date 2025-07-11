<?php
// Caminho: Daily_Planner/app/controllers/alterar_status.php
require_once __DIR__ . '/../../config/config.php'; // CAMINHO CORRIGIDO
require_once __DIR__ . '/../models/TarefaModel.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


header('Content-Type: application/json');


if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
    http_response_code(401); 
    echo json_encode(['success' => false, 'error' => 'Usuário não autenticado.']);
    exit;
}
$usuario_id = $_SESSION['usuario']['id']; 


$id_tarefa = $_GET['id'] ?? null;

if (!$id_tarefa) {
    http_response_code(400); 
    echo json_encode(['success' => false, 'error' => 'ID da tarefa não fornecido para alterar status.']);
    exit;
}

$tarefaModel = new TarefaModel($pdo); 

try {
   
    $resultadoToggle = $tarefaModel->toggleStatus($id_tarefa, $usuario_id);

    if ($resultadoToggle) {
       
        echo json_encode([
            'success' => true, 
            'message' => 'Status da tarefa alterado com sucesso!'
    
        ]);
    } else {
    
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Erro ao alterar status. Verifique se a tarefa existe e pertence a você.']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    error_log("PDOException ao alterar status da tarefa ID $id_tarefa: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Erro no banco de dados ao alterar status da tarefa.']);
} catch (Exception $e) {
    http_response_code(500);
    error_log("Erro geral ao alterar status da tarefa ID $id_tarefa: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Ocorreu um erro inesperado ao alterar o status da tarefa.']);
}
exit;
?>