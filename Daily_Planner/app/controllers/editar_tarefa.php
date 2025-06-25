<?php
// Caminho: Daily_Planner/app/controllers/editar_tarefa.php
require_once __DIR__ . '/../../config/config.php'; // CAMINHO CORRIGIDO
require_once __DIR__ . '/../models/TarefaModel.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


header('Content-Type: application/json');


if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
    http_response_code(401); 
    echo json_encode(['success' => false, 'error' => 'Usuário não autenticado. Faça login para continuar.']);
    exit;
}
$usuario_id = $_SESSION['usuario']['id']; 
$tarefaModel = new TarefaModel($pdo); 


$id_tarefa_para_editar = $_GET['id'] ?? null;

if (!$id_tarefa_para_editar) {
    http_response_code(400); 
    echo json_encode(['success' => false, 'error' => 'ID da tarefa não foi fornecido para edição.']);
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    $titulo = $_POST['titulo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
 
    $data_vencimento = $_POST['data_vencimento'] ?? '';

  
    if (empty($titulo) || empty($data_vencimento)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Título e data de vencimento completa (com hora) são obrigatórios.']);
        exit;
    }

    try {
        if ($tarefaModel->update($id_tarefa_para_editar, $titulo, $descricao, $data_vencimento, $usuario_id)) {
            echo json_encode(['success' => true, 'message' => 'Tarefa atualizada com sucesso!']);
            exit;
        } else {
           
            http_response_code(404); 
            echo json_encode(['success' => false, 'error' => 'Erro ao atualizar a tarefa. Verifique se a tarefa existe e pertence a você.']);
            exit;
        }
    } catch (PDOException $e) {
        http_response_code(500);
        error_log("PDOException ao editar tarefa ID $id_tarefa_para_editar: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Erro no banco de dados ao atualizar tarefa.']);
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        error_log("Erro geral ao editar tarefa ID $id_tarefa_para_editar: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Ocorreu um erro inesperado ao atualizar a tarefa.']);
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    echo json_encode(['success' => false, 'error' => 'Use POST para atualizar ou get_atividades.php para buscar dados da tarefa.']);
    exit;
} else {
    http_response_code(405); 
    echo json_encode(['success' => false, 'error' => 'Método de requisição não permitido.']);
    exit;
}
?>