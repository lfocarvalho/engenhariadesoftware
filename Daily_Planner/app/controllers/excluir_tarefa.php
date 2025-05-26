<?php
require_once '../../config/config.php';
require_once __DIR__ . '/../models/TarefaModel.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../login.php');
    exit;
}

$usuario_id = $_SESSION['usuario']['id'];

if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $tarefaModel = new TarefaModel($pdo);

    // Usa apenas o Model para excluir
    $result = $tarefaModel->delete($id, $usuario_id);

    if ($result) {
        header('Location: ../views/dashboard.html?msg=tarefa_excluida');
    } else {
        header('Location: ../views/dashboard.html?erro=tarefa_nao_encontrada');
    }
} else {
    header('Location: ../views/dashboard.html?erro=sem_id');
}
exit;
