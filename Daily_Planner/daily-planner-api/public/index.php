<?php
require __DIR__ . '/../config/config.php'; // Include your configuration file
require __DIR__ . '/../src/controllers/EmailController.php'; // Include the EmailController
require_once __DIR__ . '/../src/Security/errors.php';
require_once __DIR__ . '/../src/Security/auth.php';
autenticar();

// Simple routing mechanism
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

// Define the API endpoint
if ($requestUri[0] === 'api' && $requestUri[1] === 'send-email') {
    $emailController = new EmailController();

    switch ($requestMethod) {
        case 'POST':
            $emailController->sendEmail();
            break;
        default:
            http_response_code(405); // Method Not Allowed
            echo json_encode(['message' => 'Method Not Allowed']);
            break;
    }
} else {
    http_response_code(404); // Not Found
    echo json_encode(['message' => 'Not Found']);
}