<?php

// Puxar o ID do arquivo para a tarefa
$id = $_GET['id'];

// Nessa parte abre o arquivo de tarefas
$arquivo = 'tarefas.json';
$tarefas = json_decode(file_get_contents($arquivo), true);

// Essa fase muda o status se era true vira false, se era false vira true
$tarefas[$id]['concluida'] = !$tarefas[$id]['concluida'];

//  Vai salvar as alterações do arquivo
file_put_contents($arquivo, json_encode($tarefas));

// Retorna pra a página principal
header('Location: index.php');
?>