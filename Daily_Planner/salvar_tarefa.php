<?php
require 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

// Verifica se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se os campos obrigatórios foram enviados
    if (empty($_POST['titulo']) || empty($_POST['date']) || empty($_POST['time'])) {
        // Redireciona de volta ao dashboard com mensagem de erro
        header('Location: dashboard.html?error=Campos obrigatórios não preenchidos');
        exit;
    }

    // Coleta os dados do formulário
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'] ?? '';
    $date = $_POST['date'];
    $time = $_POST['time'];
    $data_hora = $date . ' ' . $time; // Combina data e hora em um único campo
    $usuario_id = $_SESSION['usuario']['id'];

    try {
        if ($id) {
            // Atualiza a atividade existente
            $stmt = $pdo->prepare("UPDATE tarefas SET titulo = ?, descricao = ?, data_vencimento = ? WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$titulo, $descricao, $data_hora, $id, $usuario_id]);
        } else {
            // Insere uma nova atividade
            $stmt = $pdo->prepare("INSERT INTO tarefas (titulo, descricao, data_vencimento, usuario_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$titulo, $descricao, $data_hora, $usuario_id]);
        }

        // Redireciona de volta ao dashboard com mensagem de sucesso
        header('Location: dashboard.html?success=Tarefa salva com sucesso');
        exit;
    } catch (PDOException $e) {
        // Redireciona de volta ao dashboard com mensagem de erro
        header('Location: dashboard.html?error=' . urlencode('Erro ao salvar a tarefa: ' . $e->getMessage()));
        exit;
    }
}
?>
