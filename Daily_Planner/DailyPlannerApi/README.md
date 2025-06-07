### Step 1: Create the Entry Point

Create a file named `api.php` in your `public` directory. This file will serve as the entry point for your API.

```php
// filepath: c:\xampp\htdocs\engenhariadesoftware\Daily_Planner\public\api.php
<?php
require __DIR__ . '/../config/config.php'; // Include your configuration file
require __DIR__ . '/../app/controllers/EmailController.php'; // Include the EmailController

// Set the content type to JSON
header('Content-Type: application/json');

// Get the request method and URI
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

// Basic routing
switch ($requestMethod) {
    case 'POST':
        if ($requestUri[1] === 'send-email') {
            $controller = new EmailController();
            $controller->sendEmail();
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}
```

### Step 2: Create the Email Controller

Create a file named `EmailController.php` in your `app/controllers` directory. This controller will handle the logic for sending emails.

```php
// filepath: c:\xampp\htdocs\engenhariadesoftware\Daily_Planner\app\controllers\EmailController.php
<?php
require_once __DIR__ . '/../models/user_model.php'; // Include the UserModel

class EmailController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function sendEmail() {
        // Get the JSON input
        $input = json_decode(file_get_contents('php://input'), true);

        // Validate input
        if (!isset($input['email']) || !isset($input['subject']) || !isset($input['message'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid input']);
            return;
        }

        $email = $input['email'];
        $subject = $input['subject'];
        $message = $input['message'];

        // Here you would implement your email sending logic
        // For example, using PHP's mail function or a library like PHPMailer
        if ($this->sendMail($email, $subject, $message)) {
            http_response_code(200);
            echo json_encode(['message' => 'Email sent successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to send email']);
        }
    }

    private function sendMail($to, $subject, $message) {
        // Example using PHP's mail function (make sure your server is configured to send emails)
        return mail($to, $subject, $message);
    }
}
```

### Step 3: Testing the API

You can test the API using tools like Postman or cURL. Here’s an example of how to send a POST request to the API:

```bash
curl -X POST http://localhost/engenhariadesoftware/Daily_Planner/public/api.php/send-email \
-H "Content-Type: application/json" \
-d '{"email": "user@example.com", "subject": "Test Email", "message": "This is a test email."}'
```

### Step 4: Error Handling and Security

1. **Error Handling**: Ensure that you handle errors gracefully and return appropriate HTTP status codes.
2. **Security**: Consider implementing authentication (e.g., API keys, OAuth) to secure your API endpoints.
3. **Validation**: Validate email addresses and sanitize inputs to prevent injection attacks.

### Conclusion

This setup provides a basic structure for an API that can send emails to users. You can expand upon this by adding more features, such as logging, email templates, or integrating with third-party email services like SendGrid or Mailgun for better email delivery.