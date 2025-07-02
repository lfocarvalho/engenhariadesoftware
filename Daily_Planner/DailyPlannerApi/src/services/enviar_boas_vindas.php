<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require_once __DIR__ . '/../../vendor/autoload.php';;

function enviarEmailBoasVindas($para_email, $para_nome, $url_do_site) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'natnerys@gmail.com'; // seu e-mail
        $mail->Password   = 'wsut ogrm howq pyek';    // senha de app do Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('nao-responda@seusite.com.br', 'Equipe Daily Planner');
        $mail->addAddress($para_email, $para_nome);

        $mail->isHTML(true);
        $mail->Subject = 'Bem-vindo(a) ao Daily Planner!';
        $mail->Body    = <<<HTML
    <h2>Olá, {$para_nome}!</h2>
    <p>Prezado(a) {$para_nome},</p>
    <p>
        É com grande satisfação que confirmamos a criação da sua conta no Daily Planner, sua nova central de produtividade.<br>
        Nossa plataforma foi desenhada para ajudá-lo(a) a otimizar seu tempo, gerenciar tarefas e alcançar seus objetivos com máxima eficiência. A partir de agora, você tem as ferramentas necessárias para assumir o controle total da sua rotina!
    </p>
    <a href="{$url_do_site}">Acessar o Site</a>
HTML;

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
        return false;
    }
}