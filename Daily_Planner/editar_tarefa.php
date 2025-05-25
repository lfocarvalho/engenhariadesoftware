<?php
require 'config.php'; // Conexão com o banco de dados
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

// Verifica se o ID da tarefa foi fornecido
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Verifica se a tarefa existe no banco de dados
    try {
        $stmt = $pdo->prepare("SELECT * FROM tarefas WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $_SESSION['usuario']['id']]);
        $tarefa = $stmt->fetch();

        if (!$tarefa) {
            echo "Tarefa não encontrada!";
            exit;
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo "Erro ao buscar a tarefa.";
        exit;
    }
} else {
    echo "ID não fornecido!";
    exit;
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validação dos campos
    if (empty($_POST['titulo']) || empty($_POST['data_vencimento'])) {
        echo "Título e data de vencimento são obrigatórios!";
        exit;
    }

    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'] ?? '';
    $data_vencimento = $_POST['data_vencimento'];

    // Atualiza a tarefa no banco de dados
    try {
        $stmt = $pdo->prepare("UPDATE tarefas SET titulo = ?, descricao = ?, data_vencimento = ? WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$titulo, $descricao, $data_vencimento, $id, $_SESSION['usuario']['id']]);

        // Redireciona após sucesso
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo "Erro ao atualizar a tarefa.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarefa</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">
        <h1>Editar Tarefa</h1>

        <form action="editar_tarefa.php?id=<?= $id ?>" method="POST" class="formulario-tarefa">
            <input type="text" name="titulo" value="<?= htmlspecialchars($tarefa['titulo']) ?>" required>
            <textarea name="descricao" placeholder="Descrição..."><?= htmlspecialchars($tarefa['descricao']) ?></textarea>
            <input type="datetime-local" name="data_vencimento" value="<?= date('Y-m-d\TH:i', strtotime($tarefa['data_vencimento'])) ?>" required>
            <button type="submit">Salvar Alterações</button>
        </form>

        <a href="index.php">Voltar para a lista de tarefas</a>
    </div>
</body>
</html>
