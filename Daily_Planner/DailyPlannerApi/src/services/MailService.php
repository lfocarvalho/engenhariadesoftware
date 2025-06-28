<?php
// engenhariadesoftware/Daily_Planner/DailyPlannerApi/src/services/MailService.php

namespace DailyPlannerApi\services;

use DailyPlannerApi\services\EmailSender;
use Exception; // Use a exceção genérica do PHP, consistente com EmailSender

class MailService
{
    private $emailSender;

    public function __construct(EmailSender $emailSender)
    {
        $this->emailSender = $emailSender;
    }

    /**
     * Envia um e-mail através do EmailSender.
     *
     * @param string $toEmail O endereço de e-mail do destinatário.
     * @param string $subject O assunto do e-mail.
     * @param string $body O corpo do e-mail.
     * @param string $toName O nome do destinatário (opcional).
     * @param bool $isHtml Se o corpo do e-mail é HTML (opcional, padrão true).
     * @param string $fromEmail O endereço de e-mail do remetente (opcional).
     * @param string $fromName O nome do remetente (opcional).
     * @return bool True se o e-mail foi enviado com sucesso.
     * @throws Exception Em caso de qualquer falha no envio do e-mail (validação, SMTP, etc.).
     */
    public function sendEmail(
        string $toEmail,
        string $subject,
        string $body,
        string $toName = '',
        bool $isHtml = true,
        string $fromEmail = '',
        string $fromName = ''
    ): bool {
        try {
            $fromEmail = !empty($fromEmail) ? $fromEmail : ($_ENV['GMAIL_USERNAME'] ?? 'no-reply@example.com');
            $fromName = !empty($fromName) ? $fromName : 'Daily Planner';

            $this->emailSender
                     ->setFrom($fromEmail, $fromName)
                     ->addRecipient($toEmail, $toName)
                     ->setSubject($subject)
                     ->setBody($body, $isHtml);

            // Tenta enviar o e-mail
            $success = $this->emailSender->send();

            // Se o EmailSender->send() retornar false, lançamos uma exceção
            if (!$success) {
                // Lança uma exceção específica para que o teste capture
                throw new Exception("O EmailSender indicou falha no envio.");
            }

            return true; // Se chegou aqui, o envio foi bem-sucedido

        } catch (Exception $e) { // Captura qualquer exceção (seja do PHPMailer ou de outra origem)
            error_log("Erro em MailService ao enviar e-mail para {$toEmail}: " . $e->getMessage());
            // Relança com uma mensagem genérica, mas incluindo a mensagem original e a exceção anterior
            throw new Exception("Falha ao enviar e-mail: " . $e->getMessage(), 0, $e);
        }
    }
}
