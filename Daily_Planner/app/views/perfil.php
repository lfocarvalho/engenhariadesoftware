<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Se o usuário já estiver logado, redireciona para o dashboard
if (isset($_SESSION['usuario'])) {
    header('Location: dashboard.html');
    exit();
}

$mensagem = '';
$tipo_mensagem = ''; // 'sucesso' ou 'erro'

// Verifica se há alguma mensagem vinda de outra página (como a de troca de senha)
if (isset($_GET['status']) && isset($_GET['msg'])) {
    
    // MENSAGEM DE SUCESSO DA TROCA DE SENHA
    if ($_GET['status'] === 'sucesso' && $_GET['msg'] === 'senha_alterada') {
        $tipo_mensagem = 'sucesso';
        $mensagem = 'Senha alterada com sucesso! Por favor, faça login com sua nova senha.';
    }
    
    // Mensagem de erro de login
    if ($_GET['status'] === 'erro' && $_GET['msg'] === 'login_invalido') {
        $tipo_mensagem = 'erro';
        $mensagem = 'E-mail ou senha inválidos. Tente novamente.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Daily Planner</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #fdfaf7; /* Cor de fundo suave do seu app */
        }
        .login-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 6px 32px rgba(0,0,0,0.1);
            padding: 2.5rem;
            width: 100%;
            max-width: 400px;
        }
        .login-card h2 {
            text-align: center;
            margin-bottom: 2rem;
            color: #755F52; /* Cor principal do seu tema */
            font-size: 2rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: .5rem;
            color: #7c5a3a;
        }
        .form-group input {
            width: 100%;
            padding: .8rem 1rem;
            border-radius: 10px;
            border: 1.5px solid #e0d6c3;
            font-size: 1rem;
        }
        .login-button {
            width: 100%;
            background: #755F52;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: .8rem 1.5rem;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background .2s;
            font-weight: 600;
        }
        .login-button:hover {
            background: #604A3E;
        }
        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #777;
        }
        .register-link a {
            color: #a86f3c;
            font-weight: 600;
            text-decoration: none;
        }

        /* ESTILOS PARA AS MENSAGENS */
        .mensagem { padding: 15px; margin-bottom: 20px; border-radius: 8px; text-align: center; font-size: 1rem; font-weight: 500; }
        .mensagem.sucesso { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .mensagem.erro { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Login</h2>

        <?php if (!empty($mensagem)): ?>
            <div class="mensagem <?php echo htmlspecialchars($tipo_mensagem); ?>">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <form action="../controllers/login.php" method="POST">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <button type="submit" class="login-button">Entrar</button>
        </form>

        <div class="register-link">
            Não tem uma conta? <a href="cadastrar_usuario.php">Cadastre-se</a>
        </div>
    </div>
</body>
</html>