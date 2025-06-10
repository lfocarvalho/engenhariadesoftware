<?php
// engenhariadesoftware/Daily_Planner/DailyPlannerApi/src/routes/api.php

namespace DailyPlannerApi\Routes;

use DailyPlannerApi\Controllers\EmailController;
use Exception;

// Configura o cabeçalho para JSON
header('Content-Type: application/json');

// Obtém o método da requisição (GET, POST, etc.)
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Obtém a URI da requisição e a limpa para facilitar o roteamento
$uri = trim($_SERVER['REQUEST_URI'], '/');

// Define o basePath. IMPORTANTE: Ajuste isso para o caminho da sua pasta 'public'
// relativo ao Document Root do seu servidor web.
// Ex: Se sua URL é http://localhost/Daily_Planner/DailyPlannerApi/public/api/send-email
// E o document root do Apache é C:\xampp\htdocs\
// O basePath seria '/Daily_Planner/DailyPlannerApi/public/'
// Se você configurou o Apache para ter a pasta 'public' como Document Root, seria '/'
$basePath = '/engenhariadesoftware/Daily_Planner/DailyPlannerApi/public/'; 

// Remove o basePath da URI para obter apenas os segmentos relevantes para o roteamento da API
if (strpos($uri, trim($basePath, '/')) === 0) {
    $pathAfterBase = substr($uri, strlen(trim($basePath, '/')));
    $requestUriSegments = explode('/', trim($pathAfterBase, '/'));
} else {
    // Fallback se o basePath não for encontrado (ex: se o index.php estiver no document root)
    $requestUriSegments = explode('/', $uri);
}


try {
    $controller = new EmailController();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro ao inicializar o controlador de e-mail: ' . $e->getMessage()]);
    exit();
}

// Lógica de roteamento
// Esperamos URLs como: /api/send-email
if (!empty($requestUriSegments) && $requestUriSegments[0] === 'api') {
    if (isset($requestUriSegments[1])) {
        switch ($requestUriSegments[1]) {
            case 'send-email':
                if ($requestMethod === 'POST') {
                    $controller->sendEmail();
                } else {
                    http_response_code(405); // Method Not Allowed
                    echo json_encode(['message' => 'Método de requisição não permitido para este endpoint. Use POST.']);
                }
                break;
            default:
                http_response_code(404); // Not Found
                echo json_encode(['message' => 'Endpoint não encontrado.']);
                break;
        }
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(['message' => 'Requisição inválida. Especifique um endpoint da API.']);
    }
} else {
    http_response_code(404); // Not Found
    echo json_encode(['message' => 'Rota base da API não reconhecida.']);
}