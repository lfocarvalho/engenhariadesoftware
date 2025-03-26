<?php
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $arquivo = 'tarefas.json'; // Corrigido o nome do arquivo
    
    if (file_exists($arquivo)) {
        $tarefas = json_decode(file_get_contents($arquivo), true);
        
        // Remove a tarefa com o ID informado
        unset($tarefas[$id]);
        
        // Salva as alterações
        file_put_contents($arquivo, json_encode($tarefas));
    }
}

header('Location: index.php'); // Corrigido o redirecionamento
exit;
?>