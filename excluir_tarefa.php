<?php
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $arquivo = 'tarefas.json'; 
    
   // excluir 
    if (file_exists($arquivo)) {
        $conteudo = file_get_contents($arquivo);
        $tarefas = json_decode($conteudo, true);

        $tarefas = array_combine(array_map('strval', array_keys($tarefas)), array_values($tarefas));

        unset($tarefas[(string)$id]);

        if (empty($tarefas)) {
            file_put_contents($arquivo, json_encode(new stdClass()));
        } else {
            file_put_contents($arquivo, json_encode($tarefas, JSON_FORCE_OBJECT));
        }
    }
}


header('Location: index.php');
exit;
?>
