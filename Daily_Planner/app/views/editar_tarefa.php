<?php
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
            <div class="erro"><?= htmlspecialchars($erro) ?></div>
        <?php elseif ($tarefa): ?>
            <form action="../controllers/editar_tarefa.php?id=<?= $tarefa['id'] ?>" method="POST" class="formulario-tarefa">
                <input type="text" name="titulo" value="<?= htmlspecialchars($tarefa['titulo']) ?>" required>
                <textarea name="descricao" placeholder="Descrição..."><?= htmlspecialchars($tarefa['descricao']) ?></textarea>
                <input type="datetime-local" name="data_vencimento" value="<?= date('Y-m-d\TH:i', strtotime($tarefa['data_vencimento'])) ?>" required>
                <button type="submit">Salvar Alterações</button>
            </form>
        <?php endif; ?>

        <a href="dashboard.html">Voltar para o dashboard</a>
    </div>
</body>
</html>