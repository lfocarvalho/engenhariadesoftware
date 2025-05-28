<?php
// Caminho: Daily_Planner/app/controllers/alterar_status.php
require_once '../../config/config.php';
require_once __DIR__ . '/../models/TarefaModel.php';

// Inicia a sessão se não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define o tipo de conteúdo da resposta como JSON
header('Content-Type: application/json');

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
    http_response_code(401); // Não Autorizado
    echo json_encode(['success' => false, 'error' => 'Usuário não autenticado.']);
    exit;
}
$usuario_id = $_SESSION['usuario']['id']; // ID do usuário logado

// O ID da tarefa cujo status será alterado é passado via GET
$id_tarefa = $_GET['id'] ?? null;

if (!$id_tarefa) {
    http_response_code(400); // Requisição Inválida
    echo json_encode(['success' => false, 'error' => 'ID da tarefa não fornecido para alterar status.']);
    exit;
}

$tarefaModel = new TarefaModel($pdo); // Instância do modelo

try {
    // Tenta alterar o status da tarefa (concluída <-> pendente)
    // O método toggleStatus() no modelo deve verificar se a tarefa pertence ao usuário_id
    $resultadoToggle = $tarefaModel->toggleStatus($id_tarefa, $usuario_id);

    if ($resultadoToggle) {
        // Você pode, opcionalmente, buscar a tarefa atualizada para retornar o novo estado
        // $tarefaAtualizada = $tarefaModel->getByIdAndUserId($id_tarefa, $usuario_id);
        echo json_encode([
            'success' => true, 
            'message' => 'Status da tarefa alterado com sucesso!'
            // 'tarefa' => $tarefaAtualizada // Opcional: retornar tarefa com novo status
        ]);
    } else {
        // Se toggleStatus() retornar false
        http_response_code(404); // Ou 403
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