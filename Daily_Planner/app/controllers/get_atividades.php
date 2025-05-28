<?php

ini_set('display_errors', 0); 
ini_set('log_errors', 1); 
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');


try {

    if (!@include_once '../../config/config.php') {
        throw new Exception("Falha ao carregar config.php. Verifique o caminho e permissões.");
    }
    if (!@include_once __DIR__ . '/../models/tarefamodel.php') {
        throw new Exception("Falha ao carregar tarefamodel.php. Verifique o caminho e permissões.");
    }


    if (!isset($pdo)) {
        throw new Exception("A conexão com o banco de dados (\$pdo) não foi configurada corretamente em config.php.");
    }

} catch (Throwable $e) { 
    http_response_code(500); 

    error_log("Erro crítico em get_atividades.php (dependências): " . $e->getMessage() . " na linha " . $e->getLine() . " do arquivo " . $e->getFile());

    echo json_encode(['success' => false, 'error' => 'Erro crítico na configuração do servidor.', 'detail' => $e->getMessage()]);
    exit;
}



if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
    http_response_code(401); 
    echo json_encode(['success' => false, 'error' => 'Sessão de usuário inválida ou expirada. Por favor, faça login novamente.']);
    exit;
}

$usuario_id = $_SESSION['usuario']['id'];
error_log("get_atividades.php: Usuário ID da sessão: " . $usuario_id);
$tarefaModel = new TarefaModel($pdo); 

try {
    $response_data = null; 

    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
        $atividade = $tarefaModel->getByIdAndUserId($id, $usuario_id);

        $response_data = $atividade ? [$atividade] : [];
    } elseif (isset($_GET['date'])) {

        $data = $_GET['date'];
        $response_data = $tarefaModel->getByDateAndUserId($usuario_id, $data);
    } else {

        $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todas';
        $response_data = $tarefaModel->getAllByUserId($usuario_id, $filtro);
    }


    echo json_encode($response_data);

} catch (PDOException $e) {
    http_response_code(500); 
    error_log("Erro de PDO em get_atividades.php (usuário $usuario_id): " . $e->getMessage()); 
    echo json_encode(['success' => false, 'error' => 'Erro no banco de dados ao buscar atividades.', 'detail_dev' => $e->getMessage()]); 
} catch (Exception $e) {
    http_response_code(500); 
    error_log("Erro geral em get_atividades.php (usuário $usuario_id): " . $e->getMessage()); 
    echo json_encode(['success' => false, 'error' => 'Ocorreu um erro ao processar sua solicitação.', 'detail_dev' => $e->getMessage()]); 
}
exit; 
?>