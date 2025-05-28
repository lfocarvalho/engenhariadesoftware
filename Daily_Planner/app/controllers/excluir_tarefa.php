<?php
// Caminho: Daily_Planner/app/controllers/excluir_tarefa.php
require_once '../../config/config.php';
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


$id_tarefa_para_excluir = $_GET['id'] ?? null;

if (!$id_tarefa_para_excluir) {
    http_response_code(400); 
    echo json_encode(['success' => false, 'error' => 'ID da tarefa não fornecido para exclusão.']);
    exit;
}

$tarefaModel = new TarefaModel($pdo); 

try {
   
    if ($tarefaModel->delete($id_tarefa_para_excluir, $usuario_id)) {
        echo json_encode(['success' => true, 'message' => 'Tarefa excluída com sucesso!']);
    } else {
        
        http_response_code(404); 
        echo json_encode(['success' => false, 'error' => 'Erro ao excluir tarefa. Verifique se a tarefa existe e pertence a você.']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    error_log("PDOException ao excluir tarefa ID $id_tarefa_para_excluir: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Erro no banco de dados ao excluir tarefa.']);
} catch (Exception $e) {
    http_response_code(500);
    error_log("Erro geral ao excluir tarefa ID $id_tarefa_para_excluir: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Ocorreu um erro inesperado ao excluir a tarefa.']);
}
exit;
?>