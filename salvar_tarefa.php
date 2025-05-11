<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['titulo']) || empty($_POST['data_vencimento'])) {
        header('Location: index.php?erro=1');
        exit;
    }

    $arquivo = 'tarefas.json';
    $tarefas = [];

    if (file_exists($arquivo)) {
        $conteudo = file_get_contents($arquivo);
        $tarefas = json_decode($conteudo, true) ?: [];
    }

    $tarefas = is_array($tarefas) ? array_combine(array_map('strval', array_keys($tarefas)), array_values($tarefas)) : [];


    $novaTarefa = [
        'titulo' => $_POST['titulo'],
        'descricao' => $_POST['descricao'] ?? '',
        'data_vencimento' => $_POST['data_vencimento'],
        'concluida' => false
    ];

    $id = uniqid();

    $tarefas[$id] = $novaTarefa;


    file_put_contents($arquivo, json_encode($tarefas, JSON_PRETTY_PRINT | JSON_FORCE_OBJECT));
}


header('Location: index.php');
exit;
?>
