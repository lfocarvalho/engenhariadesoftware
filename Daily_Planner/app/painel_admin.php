<?php
require_once 'config.php';

if (!isAdmin()) {
    header("Location: index.php");
    exit();
}

// Listar todos os usuários
$usuarios = $pdo->query("SELECT * FROM usuarios")->fetchAll();

// Listar todas as tarefas
$tarefas = $pdo->query("SELECT t.*, u.nome as usuario_nome FROM tarefas t JOIN usuarios u ON t.usuario_id = u.id")->fetchAll();
?>

<h1>Painel Administrativo</h1>

<h2>Usuários</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Tipo</th>
    </tr>
    <?php foreach ($usuarios as $usuario): ?>
    <tr>
        <td><?= $usuario['id'] ?></td>
        <td><?= htmlspecialchars($usuario['nome']) ?></td>
        <td><?= htmlspecialchars($usuario['email']) ?></td>
        <td><?= $usuario['tipo'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<h2>Tarefas</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Usuário</th>
        <th>Data Vencimento</th>
    </tr>
    <?php foreach ($tarefas as $tarefa): ?>
    <tr>
        <td><?= $tarefa['id'] ?></td>
        <td><?= htmlspecialchars($tarefa['titulo']) ?></td>
        <td><?= htmlspecialchars($tarefa['usuario_nome']) ?></td>
        <td><?= $tarefa['data_vencimento'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>