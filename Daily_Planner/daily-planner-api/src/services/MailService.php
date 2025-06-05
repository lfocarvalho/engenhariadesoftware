// filepath: c:\xampp\htdocs\engenhariadesoftware\Daily_Planner\public\api.php
<?php
require __DIR__ . '/../config/config.php'; // Include your configuration file

// Autoload classes (if using an autoloader)
spl_autoload_register(function ($class_name) {
    include __DIR__ . '/../app/daily-planner-api/src/controllers/' . $class_name . '.php';
});

// Get the request method and URI
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

// Basic routing
switch ($requestUri[1]) {
    case 'send-email':
        $controller = new EmailController();
        switch ($requestMethod) {
            case 'POST':
                $controller->sendEmail();
                break;
            default:
                http_response_code(405); // Method Not Allowed
                echo json_encode(['message' => 'Method Not Allowed']);
                break;
        }
        break;
    default:
        http_response_code(404); // Not Found
        echo json_encode(['message' => 'Not Found']);
        break;
}