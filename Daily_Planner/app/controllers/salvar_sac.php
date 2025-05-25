<?php
require 'config.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['titulo']) || empty($_POST['descricao'])) {
        header('Location: index.php?erro=1');
        exit;
    }

    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $usuario_id = $_SESSION['usuario']['id'];
    $data_criacao = date('Y-m-d H:i:s');

    try {
        $stmt = $pdo->prepare("INSERT INTO sac_tarefa (titulo, descricao, data_vencimento, usuario_id, data_criacao) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$titulo, $descricao, $data_criacao, $usuario_id, $data_criacao]);
        header('Location: dashboard.html?sucesso_sac=1');
    } catch (PDOException $e) {
        header('Location: dashboard.html?erro=2');
    }
    exit;
}

header('Location: dashboard.html?sucesso_sac=1');