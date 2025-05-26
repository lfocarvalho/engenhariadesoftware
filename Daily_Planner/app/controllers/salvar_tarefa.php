<?php
require '../../config/config.php'; 
require_once __DIR__ . '/../models/TarefaModel.php'; // Caminho para TarefaModel
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$usuario_id = $_SESSION['usuario']['id'];

// Verifica se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['titulo']) || empty($_POST['data_vencimento'])) {
        // Idealmente, redirecionar com uma mensagem de erro mais específica
        // ou exibir a mensagem na página de criação.
        header('Location: ..\views\dashboard.html?erro=campos_obrigatorios'); 
        exit;
    }
    // Coleta os dados do formulário
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'] ?? '';
    $data_vencimento = $_POST['data_vencimento']; // O formato 'datetime-local' já é Y-m-d\TH:i

    $tarefaModel = new TarefaModel($pdo);
    // Insere a tarefa no banco de dados
    try {
        if ($tarefaModel->create($titulo, $descricao, $data_vencimento, $usuario_id)) {
            // Redireciona após o sucesso
            header('Location: ../views/dashboard.html?sucesso=tarefa_criada');
            exit;
        } else {
            // Se create() retornar false (embora o execute() do PDO geralmente retorne true/false baseado no sucesso da query)
            error_log("Falha ao criar tarefa para usuário ID: $usuario_id (Model retornou false).");
            header('Location: ../views/dashboard.html?sucesso=tarefa_criada'); // Adjusted path            exit;
        }
    } catch (PDOException $e) {
        error_log("PDOException ao criar tarefa: " . $e->getMessage());
            header('Location: ../views/dashboard.html?erro=falha_criar_tarefa_model'); // Adjusted path
        exit;
    }
    } else {
    // Se não for POST, redireciona para a página principal ou exibe um erro
    header('Location: ../views/dashboard.html'); // Adjusted path and removed .php
    exit;
}
?>
