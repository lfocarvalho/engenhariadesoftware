<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Daily Planner</title>
    <style>
        body {
            background: url('/engenhariadesoftware/Daily_Planner/app/img/cadastro.svg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #604A3E;
        }

        .back-button {
            position: absolute;
            top: 32px;
            left: 32px;
            background: #fff;
            color: #604A3E;
            border: 2px solid #755F52;
            border-radius: 50px;
            padding: 12px 28px;
            font-size: 17px;
            font-weight: 700;
            box-shadow: 0 4px 16px rgba(120, 90, 70, 0.18);
            cursor: pointer;
            transition: background 0.2s, color 0.2s, border 0.2s;
            text-decoration: none;
            z-index: 20;
            display: flex;
            align-items: center;
            gap: 10px;
            opacity: 0.98;
        }
        .back-button:hover {
            background: #755F52;
            color: #fff;
            border: 2px solid #604A3E;
        }

        .register-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.93);
            padding: 38px 32px 32px 32px;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(120, 90, 70, 0.13);
            max-width: 350px;
            width: 100%;
            text-align: center;
        }

        .register-title {
            font-size: 26px;
            margin-bottom: 10px;
            color: #755F52;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .register-subtitle {
            font-size: 15px;
            margin-bottom: 20px;
            color: #8a7b6e;
        }

        .register-form {
            width: 100%;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .form-group {
            width: 100%;
            margin: 0 0 15px 0;
            padding: 0;
            box-sizing: border-box;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #604A3E;
            text-align: left; 
        }

        .form-group input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e3d9d2;
            border-radius: 6px;
            font-size: 15px;
            background: #faf7f5;
            color: #604A3E;
            transition: border 0.2s;
            box-sizing: border-box;
        }
        .form-group input:focus {
            border: 1.5px solid #bca18c;
            outline: none;
        }

        .register-button {
            width: 100%;
            padding: 11px 0;
            background: linear-gradient(90deg, #755F52 60%, #604A3E 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 6px;
            box-shadow: 0 2px 8px rgba(120, 90, 70, 0.07);
            transition: background 0.2s, transform 0.2s;
        }
        .register-button:hover {
            background: linear-gradient(90deg, #604A3E 60%, #755F52 100%);
            transform: translateY(-2px) scale(1.03);
        }

        .register-footer {
            margin-top: 15px;
            font-size: 14px;
        }

        .register-footer a {
            color: #755F52;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .register-footer a:hover {
            color: #604A3E;
            text-decoration: underline;
        }

        .mensagem-erro {
            color: #c0392b;
            margin-bottom: 15px;
            font-weight: 500;
        }

    
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
            z-index: 100;
        }

        .popup.active {
            visibility: visible;
            opacity: 1;
        }

        .popup-content {
            background: #fff;
            padding: 24px 32px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 16px rgba(120, 90, 70, 0.18);
        }

        .popup-content h2 {
            margin-bottom: 10px;
            color: #755F52;
        }

        .popup-content button {
            padding: 10px 24px;
            background: linear-gradient(90deg, #755F52 60%, #604A3E 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.2s, transform 0.2s;
        }

        .popup-content button:hover {
            background: linear-gradient(90deg, #604A3E 60%, #755F52 100%);
            transform: translateY(-2px) scale(1.03);
        }

        @media (max-width: 500px) {
            .register-card {
                min-width: 90vw;
                max-width: 98vw;
                padding: 24px 8px 18px 8px;
            }
            .back-button {
                top: 12px;
                left: 10px;
                padding: 8px 14px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <a href="javascript:history.back()" class="back-button">&#8592; Voltar</a>
    <div class="register-container">
        <div class="register-card">
            <h1 class="register-title">Crie sua conta</h1>
            <p class="register-subtitle">Preencha os campos abaixo para começar</p>

           

            <form method="POST" class="register-form" action="../controllers/cadastrar_usuario.php">
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

  
    <div class="popup" id="success-popup" style="display:none;">
        <div class="popup-content">
            <h2>Cadastro realizado com sucesso!</h2>
            <p>Você será redirecionado para a página de login.</p>
            <button onclick="redirectToLogin()">OK</button>
        </div>
    </div>

    <script>
        function redirectToLogin() {
            window.location.href = 'login.php';
        }
    </script>
</body>
</html>

