<?php
require __DIR__ . '/../../config/config.php';

// Verifica se a sessão já foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $senha = trim($_POST['senha']);

    if (empty($login) || empty($senha)) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
        try {
            // Consulta o banco de dados para verificar o login
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
            $stmt->execute([$login]);
            $usuario = $stmt->fetch();

            if ($usuario && password_verify($senha, $usuario['senha'])) {
                // Login bem-sucedido, cria a sessão do usuário
                $_SESSION['usuario'] = [
                    'id' => $usuario['id'],
                    'nome' => $usuario['nome'],
                    'email' => $usuario['email']
                ];

                // Redireciona para a próxima página (exemplo: dashboard.php)
                header('Location: dashboard.html');
                exit();
            } else {
                $erro = "Login ou senha inválidos.";
            }
        } catch (PDOException $e) {
            $erro = "Erro ao acessar o banco de dados: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bem-vindo</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="login.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>
    <div class="background-pattern">
        <!-- Tiles gerados via CSS pseudo-elementos -->
    </div>
    
    <div class="login-container">
        <div class="logo">
            <div class="logo-icon"></div>
        </div>
        
        <div class="login-box">
            <h1>Bem-vindo de volta!</h1>
            <p class="subtitle">Vamos descobrir juntos o que você vai realizar hoje?</p>

            <?php if (!empty($erro)): ?>
                <p class="mensagem-erro"><?php echo $erro; ?></p>
            <?php endif; ?>

            <?php if (isset($_GET['senha_alterada']) && $_GET['senha_alterada'] == '1'): ?>
                <p class="mensagem-sucesso">Senha alterada com sucesso! Faça login novamente.</p>
            <?php endif; ?>
            
            <form class="login-form" action="login.php" method="POST">
                <div class="form-group">
                    <label for="login">Login</label>
                    <input type="text" id="login" name="login" class="input-field" required>
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" class="input-field active-field" required>
                </div>
                
                <!-- Botão "ENTRAR" acima do link -->
                <button type="submit" class="login-button">ENTRAR</button>
                <a href="#" class="forgot-password">Esqueci minha senha</a>
            </form>
        </div>
    </div>

    <!-- Adicione logo após <body> -->
    <a href="javascript:history.back()" class="back-button">&#8592; Voltar</a>
</body>
</html>