<?php
require_once '../../config/config.php';
require_once __DIR__ . '/../models/TarefaModel.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo json_encode(['erro' => 'Usuário não autenticado']);
    exit;
}

$usuario_id = $_SESSION['usuario']['id'];
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todas';

$tarefaModel = new TarefaModel($pdo);
$tarefas = $tarefaModel->getAllByUserId($usuario_id, $filtro);

// Retorna as tarefas filtradas em JSON para o frontend
header('Content-Type: application/json');
echo json_encode($tarefas);
exit;
?>
