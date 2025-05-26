<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Daily Planner</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <div class="cabecalho-logo">
            <h1 id="titulo-login">Daily Planner</h1>
        </div>

        <?php if (!empty($erro)): ?>
            <p class="mensagem-erro"><?php echo $erro; ?></p>
        <?php endif; ?>

        <?php if (isset($_GET['senha_alterada']) && $_GET['senha_alterada'] == '1'): ?>
            <p class="mensagem-sucesso">Senha alterada com sucesso! Faça login novamente.</p>
        <?php endif; ?>
        
        <form class="formulario-login" action="../controllers/login.php" method="POST">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" class="input-field" required>
            </div>
            
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" class="input-field active-field" required>
            </div>
            
            <button type="submit" class="login-button">ENTRAR</button>
            <a href="#" class="forgot-password">Esqueci minha senha</a>
        </form>
    </div>
</body>
</html>