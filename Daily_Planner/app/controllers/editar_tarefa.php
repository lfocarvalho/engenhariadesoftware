<?php
// Caminho: Daily_Planner/app/controllers/editar_tarefa.php
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
    echo json_encode(['success' => false, 'error' => 'Usuário não autenticado. Faça login para continuar.']);
    exit;
}
$usuario_id = $_SESSION['usuario']['id']; // ID do usuário logado
$tarefaModel = new TarefaModel($pdo); // Instância do modelo

// O ID da tarefa a ser editada é passado via parâmetro GET na URL da action do formulário
$id_tarefa_para_editar = $_GET['id'] ?? null;

if (!$id_tarefa_para_editar) {
    http_response_code(400); // Requisição Inválida
    echo json_encode(['success' => false, 'error' => 'ID da tarefa não foi fornecido para edição.']);
    exit;
}

// Verifica se a requisição é do tipo POST (para submissão do formulário de edição)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados do formulário
    $titulo = $_POST['titulo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    // O JavaScript envia 'data_vencimento' já combinado como 'YYYY-MM-DD HH:MM:SS'
    $data_vencimento = $_POST['data_vencimento'] ?? '';

    // Validação dos campos
    if (empty($titulo) || empty($data_vencimento)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Título e data de vencimento completa (com hora) são obrigatórios.']);
        exit;
    }

    try {
        // Tenta atualizar a tarefa no banco de dados
        if ($tarefaModel->update($id_tarefa_para_editar, $titulo, $descricao, $data_vencimento, $usuario_id)) {
            echo json_encode(['success' => true, 'message' => 'Tarefa atualizada com sucesso!']);
            exit;
        } else {
            // Se update() retornar false (ex: tarefa não pertence ao usuário ou não encontrada)
            http_response_code(404); // Ou 403 Forbidden se for questão de permissão
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
    // Se a requisição for GET para este script com um ID, geralmente é para obter os dados da tarefa
    // para preencher um formulário de edição (embora o modal já faça isso via get_atividades.php?id=X).
    // Para consistência, pode-se adicionar essa lógica aqui também, se necessário.
    // No entanto, o fluxo atual do modal não depende de um GET direto para editar_tarefa.php para popular o form.
    http_response_code(405); // Método não permitido para popular formulário aqui (já é feito por get_atividades.php)
    echo json_encode(['success' => false, 'error' => 'Use POST para atualizar ou get_atividades.php para buscar dados da tarefa.']);
    exit;
} else {
    http_response_code(405); // Método Não Permitido
    echo json_encode(['success' => false, 'error' => 'Método de requisição não permitido.']);
    exit;
}
?>