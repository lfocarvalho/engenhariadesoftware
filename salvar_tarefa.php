<?php
require 'config.php';
session_start();

// Verifica se o usuário está logado
//if (!isset($_SESSION['usuario'])) {
  //  header('Location: login.php');
    //exit;
//}

// Verifica se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['titulo']) || empty($_POST['data_vencimento'])) {
        header('Location: index.php?erro=1');
        exit;
    }

    // Coleta os dados do formulário
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'] ?? '';
    date_default_timezone_set('America/Sao_Paulo');
    $data_vencimento = date('Y-m-d H:i:s', strtotime($_POST['data_vencimento']));
    $usuario_id = $_SESSION['usuario']['id'];
    // Insere a tarefa no banco de dados
    try {
        $stmt = $pdo->prepare("INSERT INTO tarefas (titulo, descricao, data_vencimento, usuario_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$titulo, $descricao, $data_vencimento, $usuario_id]);
    } catch (PDOException $e) {
        header('Location: index.php?erro=2');
        exit;
    }

    // Redireciona após o sucesso
    header('Location: index.php');
    exit;
}
?>
