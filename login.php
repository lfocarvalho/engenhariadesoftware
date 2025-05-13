<?php

require_once 'C:\Users\letic\EngSoft\engenhariadesoftware\config.php';

// Verifica se uma sessão já foi iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$erro = '';
$mensagem = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);

    if (empty($email) || empty($senha)) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
        try {
            $sql = "SELECT * FROM usuarios WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            $usuario = $stmt->fetch();

            if ($usuario) {
                if (password_verify($senha, $usuario['senha'])) {
                    $_SESSION["usuario"] = [
                        "id" => $usuario["id"],
                        "nome" => $usuario["nome"],
                        "tipo" => $usuario["tipo"]
                    ];
                    $mensagem = "Bem-vindo, " . htmlspecialchars($usuario["nome"]) . "! Você está logado como " . $usuario["tipo"] . ".";
                    // Redirecionar para a página principal após o login bem-sucedido
                    header("Location: index.php"); // Substitua "index.php" pelo nome da sua página principal
                    exit();
                } else {
                    $erro = "Senha incorreta.";
                }
            } else {
                $erro = "Usuário não encontrado.";
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
    <title>Login - Daily Planner</title>
    <style>
        /* Estilos Gerais (copiados do seu CSS) */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: pink;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        #titulo {
            display: flex;
        }

        .container {
            max-width: 400px; /* Reduzi a largura para o formulário de login */
            margin: 100px auto; /* Centraliza vertical e horizontalmente */
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* Estilos específicos para o formulário de login */
        .formulario-login {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 20px;
        }

        .formulario-login label {
            font-weight: bold;
        }

        .formulario-login input[type="email"],
        .formulario-login input[type="password"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
        }

        .formulario-login button {
            padding: 12px 20px;
            background: #007bff; /* Cor primária para o botão de login */
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
            font-size: 18px;
        }

        .formulario-login button:hover {
            background: #0056b3;
        }

        .mensagem-erro {
            color: red;
            margin-bottom: 10px;
            text-align: center;
        }

        .mensagem-sucesso {
            color: green;
            margin-bottom: 10px;
            text-align: center;
        }

        .cabecalho-logo {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            justify-content: center; /* Centraliza o logo e o título */
        }

        #logo {
            width: 80px; /* Ajuste o tamanho do logo conforme necessário */
            height: auto;
        }

        #titulo-login {
            color: rgb(223, 46, 76);
            font-size: 2em;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="cabecalho-logo">
            <h1 id="titulo-login">Daily Planner</h1>
        </div>

        <?php if (!empty($erro)): ?>
            <p class="mensagem-erro"><?php echo $erro; ?></p>
        <?php endif; ?>

        <?php if (!empty($mensagem)): ?>
            <p class="mensagem-sucesso"><?php echo $mensagem; ?></p>
        <?php endif; ?>

        <?php if (empty($mensagem)): ?>
            <form method="POST" class="formulario-login">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">

                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>

                <button type="submit">Entrar</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>