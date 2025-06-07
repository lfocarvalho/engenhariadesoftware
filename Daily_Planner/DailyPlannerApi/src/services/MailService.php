<?php
// engenhariadesoftware/Daily_Planner/DailyPlannerApi/src/services/MailService.php

namespace DailyPlannerApi\services;

use DailyPlannerApi\services\EmailSender;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    private $emailSender;

    public function __construct(EmailSender $emailSender)
    {
        $this->emailSender = $emailSender;
    }

    public function sendEmail(
        string $toEmail,
        string $toName = '',
        string $subject,
        string $body,
        bool $isHtml = true,
        string $fromEmail = '', // Adicionado para permitir definir o remetente
        string $fromName = ''   // Adicionado para permitir definir o remetente
    ): bool {
        try {
            // Se o remetente não for fornecido, use um padrão ou o Username do PHPMailer
            $fromEmail = !empty($fromEmail) ? $fromEmail : ($_ENV['GMAIL_USERNAME'] ?? 'no-reply@example.com');
            $fromName = !empty($fromName) ? $fromName : 'Daily Planner';

            $this->emailSender
                 ->setFrom($fromEmail, $fromName) // Define o remetente
                 ->addRecipient($toEmail, $toName)
                 ->setSubject($subject)
                 ->setBody($body, $isHtml);

            return $this->emailSender->send();

        } catch (Exception $e) {
            error_log("Erro em MailService ao enviar e-mail para {$toEmail}: " . $e->getMessage());
            throw new Exception("Falha ao enviar e-mail: " . $e->getMessage());
        }
    }
}