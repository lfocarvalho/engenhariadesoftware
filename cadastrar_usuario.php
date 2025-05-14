<?php
require_once 'config.php';

$sucesso = false; // Variável para controlar o pop-up de sucesso

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $email, $senha]);

        $sucesso = true; // Define que o cadastro foi bem-sucedido
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
    <style>
        body {
            background: url('img/cadastro.svg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .register-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.9); /* Fundo branco com transparência */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 350px;
            width: 100%;
            text-align: center;
        }

        .register-title {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .register-subtitle {
            font-size: 16px;
            margin-bottom: 20px;
            color: #555;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .register-button {
            width: 100%;
            padding: 10px;
            background: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .register-button:hover {
            background: #45a049;
        }

        .register-footer {
            margin-top: 15px;
            font-size: 14px;
        }

        .register-footer a {
            color: #007bff;
            text-decoration: none;
        }

        .register-footer a:hover {
            text-decoration: underline;
        }

        .mensagem-erro {
            color: red;
            margin-bottom: 15px;
        }

        /* Estilo do pop-up */
        .popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            visibility: hidden;
            opacity: 0;
            transition: visibility 0s, opacity 0.3s;
        }

        .popup.active {
            visibility: visible;
            opacity: 1;
        }

        .popup-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .popup-content h2 {
            margin-bottom: 10px;
        }

        .popup-content button {
            padding: 10px 20px;
            background: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .popup-content button:hover {
            background: #45a049;
        }
    </style>
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

    <!-- Pop-up de sucesso -->
    <div class="popup" id="success-popup">
        <div class="popup-content">
            <h2>Cadastro realizado com sucesso!</h2>
            <p>Você será redirecionado para a página de login.</p>
            <button onclick="redirectToLogin()">OK</button>
        </div>
    </div>

    <script>
        // Exibe o pop-up se o cadastro foi bem-sucedido
        <?php if ($sucesso): ?>
            document.getElementById('success-popup').classList.add('active');
        <?php endif; ?>

        // Redireciona para a página de login
        function redirectToLogin() {
            window.location.href = 'login.php';
        }
    </script>
</body>
</html>
