<?php
// Caminho: Daily_Planner/app/controllers/salvar_tarefa.php
require '../../config/config.php'; 
require_once __DIR__ . '/../models/TarefaModel.php'; // Caminho para o Modelo de Tarefa

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

// Verifica se a requisição é do tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados do formulário enviados via POST
    $titulo = $_POST['titulo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    // O JavaScript agora envia 'data_vencimento' já combinado como 'YYYY-MM-DD HH:MM:SS'
    $data_vencimento = $_POST['data_vencimento'] ?? '';

    // Validação básica dos campos obrigatórios
    if (empty($titulo) || empty($data_vencimento)) {
        http_response_code(400); // Requisição Inválida
        echo json_encode(['success' => false, 'error' => 'Título e data de vencimento completa (com hora) são obrigatórios.']);
        exit;
    }

    // Cria uma instância do modelo de tarefas
    $tarefaModel = new TarefaModel($pdo);
    
    // Tenta inserir a tarefa no banco de dados
    try {
        if ($tarefaModel->create($titulo, $descricao, $data_vencimento, $usuario_id)) {
            // Se a criação for bem-sucedida
            echo json_encode(['success' => true, 'message' => 'Tarefa criada com sucesso!']);
            exit;
        } else {
            // Se o método create() do modelo retornar false por algum motivo não capturado por exceção
            http_response_code(500); // Erro Interno do Servidor
            echo json_encode(['success' => false, 'error' => 'Falha ao criar tarefa no modelo.']);
            exit;
        }
    } catch (PDOException $e) {
        // Se ocorrer um erro específico do PDO (banco de dados)
        http_response_code(500);
        error_log("PDOException ao criar tarefa para usuário ID $usuario_id: " . $e->getMessage()); // Log do erro
        echo json_encode(['success' => false, 'error' => 'Erro no banco de dados ao criar tarefa. Por favor, tente novamente.']);
        exit;
    } catch (Exception $e) {
        // Captura qualquer outra exceção genérica
        http_response_code(500);
        error_log("Erro geral ao criar tarefa para usuário ID $usuario_id: " . $e->getMessage()); // Log do erro
        echo json_encode(['success' => false, 'error' => 'Ocorreu um erro inesperado ao criar a tarefa.']);
        exit;
    }
} else {
    // Se o método da requisição não for POST
    http_response_code(405); // Método Não Permitido
    echo json_encode(['success' => false, 'error' => 'Método de requisição não permitido. Use POST.']);
    exit;
}
?>