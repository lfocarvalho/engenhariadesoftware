<?php
// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validação básica
    if (empty($_POST['titulo']) || empty($_POST['data_vencimento'])) {
        header('Location: index.php?erro=1');
        exit;
    }

    $arquivo = 'tarefas.json';
    $tarefas = [];

    // Carrega tarefas existentes
    if (file_exists($arquivo)) {
        $conteudo = file_get_contents($arquivo);
        $tarefas = json_decode($conteudo, true) ?: [];
    }

    // Prepara nova tarefa
    $novaTarefa = [
        'titulo' => $_POST['titulo'],
        'descricao' => $_POST['descricao'] ?? '',
        'data_vencimento' => $_POST['data_vencimento'],
        'concluida' => false
    ];

    // Adiciona ao array (o ID será o índice automático)
    $tarefas[] = $novaTarefa;

    // Salva no arquivo
    file_put_contents($arquivo, json_encode($tarefas, JSON_PRETTY_PRINT));
}

header('Location: index.php');
exit;
?>