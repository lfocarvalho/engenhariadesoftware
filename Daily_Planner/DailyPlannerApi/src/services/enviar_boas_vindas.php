<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../../../vendor/autoload.php';

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
        <p>Bem-vindo(a) ao Daily Planner!</p>
        <a href="{$url_do_site}">Acessar o Site</a>
        HTML;

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
        return false;
    }
}