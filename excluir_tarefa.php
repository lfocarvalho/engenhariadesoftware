<?php
require 'config.php'; // A conexão com o banco de dados
session_start();

// Verifica se o usuário está logado
//if (!isset($_SESSION['usuario'])) {
  //  header('Location: login.php');
    //exit;
//}

// Verifica se o ID da tarefa foi passado pela URL
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    
    // Conecta ao banco de dados e tenta excluir a tarefa
    try {
        // Preparação da consulta SQL para excluir a tarefa
        $stmt = $pdo->prepare("DELETE FROM tarefas WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $_SESSION['usuario']['id']]);

        // Verifica se alguma linha foi afetada (ou seja, se a tarefa foi realmente excluída)
        if ($stmt->rowCount() > 0) {
            header('Location: index.php?msg=tarefa_excluida'); // Mensagem de sucesso
        } else {
            header('Location: index.php?erro=tarefa_nao_encontrada'); // Mensagem de erro
        }
    } catch (PDOException $e) {
        // Caso ocorra algum erro, você pode logar o erro (opcional)
        error_log($e->getMessage());
        header('Location: index.php?erro=erro_exclusao');
    }
} else {
    header('Location: index.php?erro=sem_id');
}

exit;
