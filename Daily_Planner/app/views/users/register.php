<?php
// app/views/users/register.php
// Variáveis $base_url, $error_message, $success_message são passadas pelo UserController
if (empty($base_url)) { die('Variável $base_url não definida. Verifique o UserController.'); }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Daily Planner</title>
    <!-- Adapte o CSS conforme necessário, similar ao login.css -->
    <link rel="stylesheet" href="<?= htmlspecialchars($base_url) ?>assets/css/login.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>
    <div class="background-pattern"></div>
    
    <div class="login-container">
        <div class="logo">
            <div class="logo-icon"></div>
        </div>
        
        <div class="login-box">
            <h1>Crie sua conta</h1>
            <p class="subtitle">Junte-se a nós e organize seu dia!</p>

            <?php if (!empty($error_message)): ?>
                <p class="mensagem-erro"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>

            <?php if (!empty($success_message)): ?>
                <p class="mensagem-sucesso"><?php echo htmlspecialchars($success_message); ?></p>
            <?php endif; ?>
            
            <form class="login-form" action="<?= htmlspecialchars($base_url) ?>index.php?controller=user&action=register" method="POST">
                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" class="input-field" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="input-field" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" class="input-field" required>
                </div>
                <div class="form-group">
                    <label for="confirmar_senha">Confirmar Senha</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" class="input-field" required>
                </div>
                <button type="submit" class="login-button">CADASTRAR</button>
            </form>
            <p style="text-align: center; margin-top: 15px;">Já tem uma conta? <a href="<?= htmlspecialchars($base_url) ?>index.php?controller=user&action=login">Faça login</a></p>
        </div>
    </div>
    <a href="<?= htmlspecialchars($base_url) ?>index.php?controller=page&action=index" class="back-button">&#8592; Voltar à Home</a>
</body>
</html>