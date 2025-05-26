<?php
<?php
require_once '../../config/config.php';
require_once __DIR__ . '/../models/tarefamodel.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo json_encode(['erro' => 'Usuário não autenticado']);
    exit;
}

$usuario_id = $_SESSION['usuario']['id'];
$data = isset($_GET['date']) ? $_GET['date'] : null;

$tarefaModel = new TarefaModel($pdo);

if ($data) {
    // Busca tarefas apenas do dia selecionado
    $tarefas = $tarefaModel->getByDateAndUserId($usuario_id, $data);
} else {
    // Se não passar data, retorna todas as tarefas do usuário
    $tarefas = $tarefaModel->getAllByUserId($usuario_id);
}

header('Content-Type: application/json');
echo json_encode($tarefas);
exit;