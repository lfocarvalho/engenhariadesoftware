<?php
require_once '../../config/config.php';
require_once __DIR__ . '/../models/TarefaModel.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../login.php');
    exit;
}

$usuario_id = $_SESSION['usuario']['id'];
$tarefa = null;
$erro = '';

$tarefaModel = new TarefaModel($pdo);

// Verifica se o ID da tarefa foi fornecido
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $tarefa = $tarefaModel->getByIdAndUserId($id, $usuario_id);

    if (!$tarefa) {
        $erro = "Tarefa não encontrada!";
    }
} else {
    $erro = "ID não fornecido!";
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$erro) {
    if (empty($_POST['titulo']) || empty($_POST['data_vencimento'])) {
        $erro = "Título e data de vencimento são obrigatórios!";
    } else {
        $titulo = $_POST['titulo'];
        $descricao = $_POST['descricao'] ?? '';
        $data_vencimento = $_POST['data_vencimento'];

        if ($tarefaModel->update($id, $titulo, $descricao, $data_vencimento, $usuario_id)) {
            header('Location: ../views/dashboard.html?sucesso=tarefa_editada');
            exit;
        } else {
            $erro = "Erro ao atualizar a tarefa.";
        }
    }
}

// Inclui a view
require_once __DIR__ . '/../views/editar_tarefa.php';
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

        <?php if ($erro): ?>
            <div class="erro"><?= $erro ?></div>
        <?php endif; ?>

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
