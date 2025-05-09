<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $arquivo = 'tarefas.json';

    if (file_exists($arquivo)) {
        $conteudo = file_get_contents($arquivo);
        $tarefas = json_decode($conteudo, true);
        if (!is_array($tarefas)) $tarefas = [];

        // Usa array_key_exists para permitir excluir índice 0
        if (array_key_exists($id, $tarefas)) {
            unset($tarefas[$id]);

            // Reindexa o array para manter consistência
            $tarefas = array_values($tarefas);

            file_put_contents($arquivo, json_encode($tarefas, JSON_PRETTY_PRINT));
        }
    }
}

header('Location: index.php');
exit;
