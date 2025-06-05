<?php
require __DIR__ . '/../config/config.php'; // Include your configuration file
require __DIR__ . '/../app/daily-planner-api/src/controllers/EmailController.php'; // Include the EmailController

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