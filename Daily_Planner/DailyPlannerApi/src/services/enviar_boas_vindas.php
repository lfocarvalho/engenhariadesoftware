<?php

function enviarEmailBoasVindas($para_email, $para_nome, $url_do_site) {
    $remetente_nome = "Equipe Daily Planner";
    $remetente_email = "nao-responda@seusite.com.br";
    $assunto = "Bem-vindo(a) ao Daily Planner!";

    $corpo_do_email = <<<HTML
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bem-vindo(a) ao Daily Planner</title>
    </head>
    <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td style="padding: 20px 0;">
                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; background-color: #ffffff; border: 1px solid #dddddd;">
                        <tr>
                            <td align="center" style="padding: 40px 30px; background-color: #333333; color: #ffffff;">
                                <h1 style="margin: 0; font-size: 28px;">Seja Bem-Vindo(a) ao Daily Planner!</h1>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 40px 30px; color: #555555; font-size: 16px; line-height: 1.6;">
                                <h2 style="margin: 0 0 20px 0; color: #333333;">Olá, {$para_nome}!</h2>
                                <p style="margin: 0 0 20px 0;">Estamos muito felizes por você ter se juntado a nós. O Daily Planner foi feito para ajudar a organizar suas tarefas e transformar sua produtividade.</p>
                                <p style="margin: 0;">Clique no botão abaixo para acessar o site e começar a planejar o seu dia agora mesmo!</p>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" style="padding: 0 30px 40px 30px;">
                                <table border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td align="center" style="border-radius: 8px;" bgcolor="#4CAF50">
                                            <a href="{$url_do_site}" target="_blank" style="font-size: 18px; color: #ffffff; text-decoration: none; padding: 15px 30px; border-radius: 8px; display: inline-block; font-weight: bold;">
                                                Acessar o Site
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" style="padding: 20px 30px; background-color: #f2f2f2; color: #888888; font-size: 12px;">
                                <p style="margin: 0;">Você recebeu este e-mail porque se cadastrou no Daily Planner.</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>
    HTML;

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: {$remetente_nome} <{$remetente_email}>" . "\r\n";
    $headers .= "Reply-To: {$remetente_email}" . "\r\n";

    return mail($para_email, $assunto, $corpo_do_email, $headers);
}