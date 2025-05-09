<?php
if (file_exists('tarefas.json')) {
    $json_tarefas = file_get_contents('tarefas.json');
    $tarefas = json_decode($json_tarefas, true);
} else {
    $tarefas = [];
}

// Verifica o filtro ativo
$filtro = $_GET['filtro'] ?? 'todas';

// Inclui o arquivo de filtros
require 'filtros.php';

// Aplica o filtro
$tarefasFiltradas = filtrarTarefas($tarefas, $filtro);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tarefas</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">
        <h1>📝 Minha Lista de Tarefas</h1>

        <!-- Formulário para adicionar tarefas -->
        <form action="salvar_tarefa.php" method="POST" class="formulario-tarefa">
            <input type="text" name="titulo" placeholder="Título (ex: Academia)" required>
            <textarea name="descricao" placeholder="Descrição..."></textarea>
            <input type="datetime-local" name="data_vencimento" required>
            <button type="submit">Adicionar Tarefa</button>
        </form>

        <!-- Filtros -->
        <div class="filtros">
            <a href="?filtro=todas" class="<?= $filtro === 'todas' ? 'ativo' : '' ?>">Todas</a>
            <a href="?filtro=pendentes" class="<?= $filtro === 'pendentes' ? 'ativo' : '' ?>">Pendentes</a>
            <a href="?filtro=concluidas" class="<?= $filtro === 'concluidas' ? 'ativo' : '' ?>">Concluídas</a>
        </div>

        <!-- Lista de tarefas -->
        <div class="tarefas">
            <?php if (empty($tarefasFiltradas)) : ?>
                <p class="vazio">Nenhuma tarefa encontrada. 🎉</p>
            <?php else : ?>
                <?php foreach ($tarefasFiltradas as $id => $tarefa) : ?>
                    <div class="tarefa <?= $tarefa['concluida'] ? 'concluida' : '' ?>">
                        <div class="cabecalho-tarefa">
                            <h3><?= htmlspecialchars($tarefa['titulo']) ?></h3>
                            <span class="data-vencimento"><?= date('d/m/Y H:i', strtotime($tarefa['data_vencimento'])) ?></span>
                        </div>
                        
                        <p class="descricao"><?= htmlspecialchars($tarefa['descricao']) ?></p>
                        
                        <div class="acoes">
                            <a href="alterar_status.php?id=<?= $id ?>" class="status">
                                <?= $tarefa['concluida'] ? '✅ Concluído' : '🕒 Pendente' ?>
                            </a>
                            <a href="editar_tarefa.php?id=<?= $id ?>" class="editar">✏️ Editar</a>
                            <a href="excluir_tarefa.php?id=<?= $id ?>" class="excluir" onclick="return confirm('Tem certeza que deseja excluir esta tarefa?');">🗑️ Excluir</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>