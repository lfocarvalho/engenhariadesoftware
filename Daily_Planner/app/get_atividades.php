<?php
require 'config.php';

// Verifica se a sessão já foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['error' => 'Usuário não autorizado']);
    exit;
}

$usuario_id = $_SESSION['usuario']['id'];

try {
    // Busca as tarefas do usuário logado
    $stmt = $pdo->prepare('SELECT id, titulo, descricao, DATE_FORMAT(data_vencimento, "%d/%m/%Y %H:%i") AS data_vencimento FROM tarefas WHERE usuario_id = ? ORDER BY data_vencimento ASC');
    $stmt->execute([$usuario_id]);
    $atividades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retorna as atividades no formato JSON
    echo json_encode($atividades);
} catch (PDOException $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'Erro ao buscar atividades: ' . $e->getMessage()]);
}
?>