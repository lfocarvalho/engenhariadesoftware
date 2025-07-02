<?php
// Caminho: Daily_Planner/app/controllers/salvar_tarefa.php
require '../../config/config.php'; 
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $data_vencimento = $_POST['data_vencimento'] ?? '';

    if (empty($titulo) || empty($data_vencimento)) {
        http_response_code(400); 
        echo json_encode(['success' => false, 'error' => 'Título e data de vencimento completa (com hora) são obrigatórios.']);
        exit;
    }

    $tarefaModel = new TarefaModel($pdo);
    
    try {
        if ($tarefaModel->create($titulo, $descricao, $data_vencimento, $usuario_id)) {

            echo json_encode(['success' => true, 'message' => 'Tarefa criada com sucesso!']);
            exit;
        } else {
            http_response_code(500); 
            echo json_encode(['success' => false, 'error' => 'Falha ao criar tarefa no modelo.']);
            exit;
        }
    } catch (PDOException $e) {
        http_response_code(500);
        error_log("PDOException ao criar tarefa para usuário ID $usuario_id: " . $e->getMessage()); 
        echo json_encode(['success' => false, 'error' => 'Erro no banco de dados ao criar tarefa. Por favor, tente novamente.']);
        exit;
    } catch (Exception $e) {

        http_response_code(500);
        error_log("Erro geral ao criar tarefa para usuário ID $usuario_id: " . $e->getMessage()); // Log do erro
        echo json_encode(['success' => false, 'error' => 'Ocorreu um erro inesperado ao criar a tarefa.']);
        exit;
    }
} else {
    http_response_code(405); 
    echo json_encode(['success' => false, 'error' => 'Método de requisição não permitido. Use POST.']);
    exit;
}
?>