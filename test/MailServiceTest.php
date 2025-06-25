<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;


require_once __DIR__ . '/../Daily_Planner/DailyPlannerApi/src/services/MailService.php';
require_once __DIR__ . '/../Daily_Planner/DailyPlannerApi/src/services/EmailSender.php';


use DailyPlannerApi\services\MailService;
use DailyPlannerApi\services\EmailSender;


class MailServiceTest extends TestCase
{
    private MailService $mailService;
    private MockObject $emailSenderMock;

    protected function setUp(): void
    {
       
        $this->emailSenderMock = $this->createMock(EmailSender::class);

        
        $this->mailService = new MailService($this->emailSenderMock);

       
        $_ENV['GMAIL_USERNAME'] = 'test@example.com';
    }

    protected function tearDown(): void
    {
        
        unset($_ENV['GMAIL_USERNAME']);
    }

   
    public function testEmailDeBoasVindasEnviado(): void
    {
        $toEmail = "novo.usuario@example.com";
        $toName = "Novo Usuário";
        $subject = "Bem-vindo ao Daily Planner!";
        $body = "Olá, obrigado por se cadastrar no Daily Planner.";
        $fromEmail = 'test@example.com';
        $fromName = 'Daily Planner';

        
        $this->emailSenderMock->expects($this->once())
            ->method('setFrom')
            ->with($this->equalTo($fromEmail), $this->equalTo($fromName))
            ->willReturn($this->emailSenderMock);

        $this->emailSenderMock->expects($this->once())
            ->method('addRecipient')
            ->with($this->equalTo($toEmail), $this->equalTo($toName))
            ->willReturn($this->emailSenderMock);

        $this->emailSenderMock->expects($this->once())
            ->method('setSubject')
            ->with($this->equalTo($subject))
            ->willReturn($this->emailSenderMock);

        $this->emailSenderMock->expects($this->once())
            ->method('setBody')
            ->with($this->equalTo($body), $this->equalTo(true))
            ->willReturn($this->emailSenderMock);

        $this->emailSenderMock->expects($this->once())
            ->method('send')
            ->willReturn(true); 

        
        $result = $this->mailService->sendEmail($toEmail, $subject, $body, $toName, true, $fromEmail, $fromName);

        
        $this->assertTrue($result);
    }

   
    public function testErroEnvioEmailInvalido(): void
    {
        $toEmail = "email_invalido";
        $toName = "Usuário Inválido";
        $subject = "Teste de E-mail Inválido";
        $body = "Este é um e-mail com formato inválido.";
        $fromEmail = 'test@example.com';
        $fromName = 'Daily Planner';

      
        $this->emailSenderMock->expects($this->once())
            ->method('setFrom')
            ->with($this->equalTo($fromEmail), $this->equalTo($fromName))
            ->willReturn($this->emailSenderMock);

    
        $this->emailSenderMock->expects($this->once())
            ->method('addRecipient')
            ->with($this->equalTo($toEmail), $this->equalTo($toName))
            ->willThrowException(new Exception("Endereço de e-mail inválido: " . $toEmail)); // Exceção genérica


        $this->expectException(Exception::class);
        $this->expectExceptionMessageMatches('/^Falha ao enviar e-mail: Endereço de e-mail inválido: .*$/'); // Mensagem mais flexível

        
        $this->mailService->sendEmail($toEmail, $subject, $body, $toName, true, $fromEmail, $fromName);
    }

   
    public function testFalhaNoEnvioDoEmailSender(): void
    {
        $toEmail = "usuario@example.com";
        $toName = "Usuário Teste";
        $subject = "Teste de Falha";
        $body = "Corpo de teste para falha.";
        $fromEmail = 'test@example.com';
        $fromName = 'Daily Planner';

        $this->emailSenderMock->method('setFrom')->willReturn($this->emailSenderMock);
        $this->emailSenderMock->method('addRecipient')->willReturn($this->emailSenderMock);
        $this->emailSenderMock->method('setSubject')->willReturn($this->emailSenderMock);
        $this->emailSenderMock->method('setBody')->willReturn($this->emailSenderMock);

    
        $this->emailSenderMock->expects($this->once())
            ->method('send')
            ->willReturn(false);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Falha ao enviar e-mail: O EmailSender indicou falha no envio.");

        $this->mailService->sendEmail($toEmail, $subject, $body, $toName, true, $fromEmail, $fromName);
    }
}
