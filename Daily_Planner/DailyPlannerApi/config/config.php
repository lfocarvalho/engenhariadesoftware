// filepath: c:\xampp\htdocs\engenhariadesoftware\Daily_Planner\public\api.php
<?php
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../app/daily-planner-api/src/controllers/EmailController.php';

// Autoload classes if using an autoloader
// spl_autoload_register(function ($class_name) {
//     include __DIR__ . '/../app/models/' . $class_name . '.php';
// });

header("Content-Type: application/json");

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

$controller = new EmailController();

switch ($requestMethod) {
    case 'POST':
        if ($requestUri[1] === 'send-email') {
            $controller->sendEmail();
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Endpoint not found']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method not allowed']);
        break;
}