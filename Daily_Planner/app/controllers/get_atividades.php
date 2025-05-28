<?php
// Configurações de depuração (remover ou ajustar em produção)
// Estas linhas ajudam a ver erros PHP diretamente na saída se você acessar este script via navegador.
// Se o frontend estiver esperando JSON, erros HTML quebrarão o parse.
ini_set('display_errors', 0); // Em produção, idealmente 0. Para debug, pode ser 1.
ini_set('log_errors', 1); // Envia erros para o log do servidor.
error_reporting(E_ALL);

// Inicia a sessão ANTES de qualquer outra coisa que possa enviar saída para o navegador.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define o cabeçalho da resposta como JSON o mais cedo possível.
// Deve ser chamado antes de qualquer 'echo', 'print', ou HTML.
header('Content-Type: application/json');

// Bloco try-catch para carregar dependências e configurações básicas
try {
    // Tenta incluir os arquivos de configuração e modelo.
    // O '@' suprime erros de 'require_once' para que possamos tratá-los de forma controlada.
    if (!@include_once '../../config/config.php') {
        throw new Exception("Falha ao carregar config.php. Verifique o caminho e permissões.");
    }
    if (!@include_once __DIR__ . '/../models/tarefamodel.php') {
        throw new Exception("Falha ao carregar tarefamodel.php. Verifique o caminho e permissões.");
    }

    // Verifica se a conexão PDO ($pdo) foi estabelecida em config.php
    if (!isset($pdo)) {
        throw new Exception("A conexão com o banco de dados (\$pdo) não foi configurada corretamente em config.php.");
    }

} catch (Throwable $e) { // Throwable captura Errors e Exceptions no PHP 7+
    http_response_code(500); // Erro Interno do Servidor
    // Loga o erro no servidor para análise posterior
    error_log("Erro crítico em get_atividades.php (dependências): " . $e->getMessage() . " na linha " . $e->getLine() . " do arquivo " . $e->getFile());
    // Envia uma resposta JSON clara sobre o erro
    echo json_encode(['success' => false, 'error' => 'Erro crítico na configuração do servidor.', 'detail' => $e->getMessage()]);
    exit;
}


// Verifica se o usuário está logado.
// Esta verificação deve ocorrer APÓS as inclusões e session_start.
if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
    http_response_code(401); // Não Autorizado
    echo json_encode(['success' => false, 'error' => 'Sessão de usuário inválida ou expirada. Por favor, faça login novamente.']);
    exit;
}

$usuario_id = $_SESSION['usuario']['id'];
error_log("get_atividades.php: Usuário ID da sessão: " . $usuario_id);
$tarefaModel = new TarefaModel($pdo); // Cria uma instância do modelo de tarefas.

try {
    $response_data = null; // Inicializa a variável de dados da resposta.

    if (!empty($_GET['id'])) {
        // Busca uma atividade específica pelo ID.
        $id = $_GET['id'];
        $atividade = $tarefaModel->getByIdAndUserId($id, $usuario_id);
        $response_data = $atividade;
    } elseif (isset($_GET['date'])) {
        // Busca tarefas para uma data específica.
        $data = $_GET['date'];
        $response_data = $tarefaModel->getByDateAndUserId($usuario_id, $data);
    } else {
        // Busca todas as tarefas, com filtro se houver
        $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todas';
        $response_data = $tarefaModel->getAllByUserId($usuario_id, $filtro);
    }

    // Codifica a resposta como JSON e envia para o cliente.
    // Se $response_data for null (por exemplo, 'id' não encontrado e não setado como array de erro acima),
    // json_encode(null) resultará em "null", o que é JSON válido.
    // Se for um array vazio (busca bem-sucedida sem resultados), resultará em "[]".
    echo json_encode($response_data);

} catch (PDOException $e) {
    http_response_code(500); // Erro Interno do Servidor devido a problema no DB.
    error_log("Erro de PDO em get_atividades.php (usuário $usuario_id): " . $e->getMessage()); // Log interno.
    echo json_encode(['success' => false, 'error' => 'Erro no banco de dados ao buscar atividades.', 'detail_dev' => $e->getMessage()]); // Não exponha e->getMessage() em produção.
} catch (Exception $e) {
    http_response_code(500); // Erro Interno do Servidor genérico.
    error_log("Erro geral em get_atividades.php (usuário $usuario_id): " . $e->getMessage()); // Log interno.
    echo json_encode(['success' => false, 'error' => 'Ocorreu um erro ao processar sua solicitação.', 'detail_dev' => $e->getMessage()]); // Não exponha e->getMessage() em produção.
}
exit; // Garante que o script termina aqui.
?>