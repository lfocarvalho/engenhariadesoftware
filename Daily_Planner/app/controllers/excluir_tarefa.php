<?php
// Caminho: Daily_Planner/app/controllers/excluir_tarefa.php
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

// O ID da tarefa a ser excluída é passado via parâmetro GET
$id_tarefa_para_excluir = $_GET['id'] ?? null;

if (!$id_tarefa_para_excluir) {
    http_response_code(400); // Requisição Inválida
    echo json_encode(['success' => false, 'error' => 'ID da tarefa não fornecido para exclusão.']);
    exit;
}

$tarefaModel = new TarefaModel($pdo); // Instância do modelo

try {
    // Tenta excluir a tarefa
    // O método delete() no modelo deve verificar se a tarefa pertence ao usuário_id
    if ($tarefaModel->delete($id_tarefa_para_excluir, $usuario_id)) {
        echo json_encode(['success' => true, 'message' => 'Tarefa excluída com sucesso!']);
    } else {
        // Se delete() retornar false (ex: tarefa não encontrada ou não pertence ao usuário)
        http_response_code(404); // Ou 403 Forbidden
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