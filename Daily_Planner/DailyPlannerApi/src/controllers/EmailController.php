<?php

// engenhariadesoftware/Daily_Planner/DailyPlannerApi/src/controllers/EmailController.php

namespace DailyPlannerApi\Controllers;

use DailyPlannerApi\services\MailService; // Importa o MailService
use DailyPlannerApi\services\EmailSender; // Para instanciar o EmailSender
use Exception;

class EmailController
{
    private $mailService;

    public function __construct()
    {
        try {
            $emailSender = new EmailSender();
            $this->mailService = new MailService($emailSender);
        } catch (Exception $e) {
            error_log("Erro ao inicializar EmailController: " . $e->getMessage());
            throw new Exception("Erro interno ao configurar o serviço de e-mail. Por favor, tente mais tarde.");
        }
    }

    public function sendEmail(): void
    {
        header('Content-Type: application/json');

        // Recebe e decodifica o input JSON
        $input = json_decode(file_get_contents('php://input'), true);

        // Validação dos campos obrigatórios
        if (!isset($input['para']) || empty($input['para']) ||
            !isset($input['assunto']) || empty($input['assunto']) ||
            !isset($input['mensagem']) || empty($input['mensagem'])) {
            http_response_code(400); // Bad Request
            echo json_encode(['status' => 'erro', 'message' => 'Campos obrigatórios (para, assunto, mensagem) ausentes ou vazios.']);
            exit;
        }

        $toEmail = $input['para'];
        $toName = $input['nome'] ?? ''; // Nome do destinatário é opcional
        $subject = $input['assunto'];
        $body = $input['mensagem'];
        $isHtml = $input['isHtml'] ?? true; // Por padrão, considera a mensagem como HTML
        $fromEmail = $input['remetente_email'] ?? ''; // Remetente personalizado (opcional)
        $fromName = $input['remetente_nome'] ?? ''; // Nome do remetente personalizado (opcional)


        // Validação básica de formato de e-mail (pode ser mais robusta)
        if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['status' => 'erro', 'message' => 'O endereço de e-mail do destinatário é inválido.']);
            exit;
        }

        try {
            $this->mailService->sendEmail($toEmail, $toName, $subject, $body, $isHtml, $fromEmail, $fromName);

            http_response_code(200);
            echo json_encode(['status' => 'sucesso', 'message' => 'E-mail enviado com sucesso!']);

        } catch (Exception $e) {
            error_log("Erro no EmailController ao enviar e-mail: " . $e->getMessage());
            http_response_code(500); // Internal Server Error
            echo json_encode(['status' => 'erro', 'message' => 'Erro ao enviar e-mail: Falha ao enviar e-mail: Falha ao enviar e-mail: Erro de SMTP: Não foi possível autenticar.']);
        }
    }
}
?>