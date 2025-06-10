<?php
// engenhariadesoftware/Daily_Planner/DailyPlannerApi/src/services/EmailSender.php

namespace DailyPlannerApi\services; // CORRIGIDO: de 'services' para 'Services'

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailSender
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true); // 'true' habilita exceções para depuração

        try {
            // Configurações do servidor SMTP
            $this->mail->isSMTP();
            $this->mail->Host = $_ENV['GMAIL_HOST'] ?? 'localhost';
            $this->mail->SMTPAuth = true;
            $this->mail->Username = $_ENV['GMAIL_USERNAME'] ?? '';
            $this->mail->Password = $_ENV['GMAIL_PASSWORD'] ?? '';
            $this->mail->SMTPSecure = $_ENV['GMAIL_ENCRYPTION'] ?? PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port = $_ENV['GMAIL_PORT'] ?? 587;

            // Configurações de depuração (útil para ver o que o PHPMailer está fazendo)
            $this->mail->SMTPDebug = 2; // Nível de debug (0 = off, 1 = client, 2 = client+server)
            $this->mail->Debugoutput = 'error_log'; // Saída para o log de erros do PHP

            $this->mail->CharSet = 'UTF-8';
            $this->mail->setLanguage('pt_br'); // Define o idioma para mensagens de erro

        } catch (Exception $e) {
            error_log("Erro ao configurar PHPMailer: " . $e->getMessage());
            throw new Exception("Não foi possível configurar o serviço de e-mail: " . $e->getMessage());
        }
    }

    public function addRecipient(string $toEmail, string $toName = ''): self
    {
        $this->mail->addAddress($toEmail, $toName);
        return $this;
    }

    public function setSubject(string $subject): self
    {
        $this->mail->Subject = $subject;
        return $this;
    }

    public function setBody(string $body, bool $isHtml = true): self
    {
        if ($isHtml) {
            $this->mail->isHTML(true);
            $this->mail->Body = $body;
            $this->mail->AltBody = strip_tags($body); // Versão em texto plano
        } else {
            $this->mail->isHTML(false);
            $this->mail->Body = $body;
        }
        return $this;
    }

    public function setFrom(string $fromEmail, string $fromName = ''): self
    {
        $this->mail->setFrom($fromEmail, $fromName);
        return $this;
    }

    public function send(): bool
    {
        try {
            return $this->mail->send();
        } catch (Exception $e) {
            error_log("Erro ao enviar e-mail: " . $e->getMessage());
            throw new Exception("Falha ao enviar e-mail: " . $e->getMessage());
        }
    }
}