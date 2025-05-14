<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $email, $senha]);

        header("Location: login.php?success=1");
        exit();
    } catch (PDOException $e) {
        $erro = "Erro ao cadastrar: " . (strpos($e->getMessage(), 'Duplicate') ? 'Email já existe' : 'Erro no sistema');
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Daily Planner</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <h1 class="register-title">Crie sua conta</h1>
            <p class="register-subtitle">Preencha os campos abaixo para começar</p>

            <?php if (!empty($erro)): ?>
                <p class="mensagem-erro"><?php echo $erro; ?></p>
            <?php endif; ?>

            <form method="POST" class="register-form">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" placeholder="Digite seu nome" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Digite seu email" required>
                </div>

                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
                </div>

                <button type="submit" class="register-button">Cadastrar</button>
            </form>

            <div class="register-footer">
                <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
            </div>
        </div>
    </div>
</body>
</html>
