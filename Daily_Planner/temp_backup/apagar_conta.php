<?php
require 'config.php';
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php'); // ou index.php, dependendo do seu fluxo
    exit;
}

$usuario_id = $_SESSION['usuario']['id'];

try {
    // Exclui o usuário (as tarefas serão apagadas automaticamente via ON DELETE CASCADE)
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);

    // Destroi a sessão
    session_destroy();

    // Redireciona para a página inicial ou de login
    header('Location: index.php?msg=conta_excluida');
    exit;
} catch (PDOException $e) {
    // Você pode logar o erro em error_log() se desejar
    header('Location: perfil.php?erro=nao_excluiu');
    exit;
    
}