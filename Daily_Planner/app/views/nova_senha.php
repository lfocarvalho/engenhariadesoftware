<?php
require_once '../config/db.php';

$token = $_GET['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';

    if ($nova_senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem.";
    } elseif (strlen($nova_senha) < 6) {
        $erro = "A senha deve ter pelo menos 6 caracteres.";
    } else {
        // Verifica o token
        $stmt = $pdo->prepare("SELECT id, token_expira FROM usuarios WHERE token_recuperacao = ?");
        $stmt->execute([$token]);
        $usuario = $stmt->fetch();

        if ($usuario && strtotime($usuario['token_expira']) > time()) {
            // Atualiza a senha e remove o token
            
            $hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE usuarios SET senha = ?, token_recuperacao = NULL, token_expira = NULL WHERE id = ?");
            $stmt->execute([$hash, $usuario['id']]);
            
            header("Location: login.php?senha_alterada=1");
            exit;
        } else {
            $erro = "Token inválido ou expirado.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Senha</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <div class="cabecalho-logo">
            <h1 id="titulo-login">Redefinir Senha</h1>
        </div>
        <?php if (!empty($erro)): ?>
            <p class="mensagem-erro"><?php echo htmlspecialchars($erro); ?></p>
        <?php endif; ?>
        <form class="formulario-login" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <div class="form-group">
                <label for="nova_senha">Nova senha</label>
                <input type="password" id="nova_senha" name="nova_senha" class="input-field" required>
            </div>
            <div class="form-group">
                <label for="confirmar_senha">Confirmar nova senha</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" class="input-field" required>
            </div>
            <button type="submit" class="login-button">Redefinir Senha</button>
            <a href="login.php" class="forgot-password">Voltar ao login</a>
        </form>
    </div>
</body>
</html>