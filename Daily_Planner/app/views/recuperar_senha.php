<?php

require_once __DIR__ . '/../config/db.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Verifica se o e-mail existe no banco
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        // Gera um token único
        $token = bin2hex(random_bytes(32));
        $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Salva o token e a data de expiração no banco
        $stmt = $pdo->prepare("UPDATE usuarios SET token_recuperacao = ?, token_expira = ? WHERE id = ?");
        $stmt->execute([$token, $expira, $usuario['id']]);

        // Monta o link de recuperação
        $link = "http://localhost/engenhariadesoftware/Daily_Planner/app/views/nova_senha.php?token=$token";

        // Envia o e-mail (simples, para testes)
        $assunto = "Recuperação de senha - Daily Planner";
        $mensagemEmail = "Clique no link para redefinir sua senha: $link";
        $headers = "From: no-reply@dailyplanner.com\r\n";

        mail($email, $assunto, $mensagemEmail, $headers);

        $mensagem = "Enviamos um link para seu e-mail!";

        // Exibe o link na tela para testes locais
        $mensagem .= "<br><small><a href='$link' target='_blank'>Clique aqui para redefinir sua senha</a></small>";
    } else {
        $mensagem = "E-mail não encontrado!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <div class="cabecalho-logo">
            <h1 id="titulo-login">Recuperar Senha</h1>
        </div>

        <?php if (!empty($mensagem)): ?>
             <p class="mensagem-sucesso"><?php echo $mensagem; ?></p>
        <?php endif; ?>

        <form class="formulario-login" method="POST">
            <div class="form-group">
                <label for="email">Digite seu e-mail cadastrado</label>
                <input type="email" id="email" name="email" class="input-field" required>
            </div>
            <button type="submit" class="login-button">Enviar link de recuperação</button>
            <a href="login.php" class="forgot-password">Voltar ao login</a>
        </form>
    </div>
</body>
</html>