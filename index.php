<?php
require 'config.php';

// Verifica se uma sessão já foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Simulação de usuário (remova isso quando implementar autenticação real)
$_SESSION['usuario']['id'] = 1; // Exemplo: usuário ID 1

$usuario_id = $_SESSION['usuario']['id'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daily Planner</title>
  <link rel="stylesheet" href="style.css">
  <style>
    /* Estilos gerais */
    body {
        margin: 0;
        padding: 0;
        font-family: 'Roboto', sans-serif;
        background-image: url('img/background.svg'); /* Caminho atualizado para a imagem SVG */
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        color: #755F52;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    /* Cabeçalho */
    .header-bg {
        width: 100%;
        height: 104px;
        background: #FFFFFF; /* Define o fundo da barra como branco */
        box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 30px;
        box-sizing: border-box; /* Garante que o padding não ultrapasse a largura */
    }

    .logo {
        font-size: 32px;
        font-weight: 500;
        color: #806359;
    }

    .menu {
        display: flex;
        gap: 20px;
    }

    .menu a {
        font-size: 18px;
        font-weight: 500;
        color: #755F52; /* Mantém a cor do texto harmoniosa com o design */
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .menu a:hover {
        color: #604A3E; /* Cor ao passar o mouse */
    }

    /* Botões */
    .login-button {
        padding: 8px 16px;
        background: transparent;
        color: #755F52;
        font-size: 14px;
        font-weight: 500;
        text-align: center;
        border: 2px solid #755F52;
        border-radius: 8px;
        text-decoration: none;
        transition: background 0.3s ease, color 0.3s ease, transform 0.2s ease;
        margin-right: 10px; /* Reduz o espaçamento entre os botões */
    }

    .register-button {
        padding: 10px 20px;
        background: #755F52;
        color: #FFF7F7;
        font-size: 16px;
        font-weight: 500;
        text-align: center;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .login-button:hover {
        background: #755F52;
        color: #FFF7F7;
        transform: scale(1.05);
    }

    .register-button:hover {
        background: #604A3E;
        transform: scale(1.05);
    }

    /* Conteúdo principal */
    .container {
        position: relative;
        z-index: 1; /* Garante que o conteúdo fique acima do fundo */
        background: transparent; /* Remove qualquer fundo que possa estar sobrepondo */
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        text-align: left;
        flex: 1; /* Faz o conteúdo principal ocupar o espaço disponível */
    }

    .slogan {
        margin-top: 200px;
        position: absolute;
        left: 81px;
        top: 354px;
    }

    .slogan h1 {
        font-size: 64px;
        font-weight: 800;
        color: #806359;
        line-height: 1.2;
        text-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
    }

    .slogan p {
        font-size: 20px;
        font-weight: 500;
        color: #755F52;
        margin-top: 20px;
        max-width: 527px;
    }

    .button.comecar-agora {
        width: 161px;
        height: 50px;
        background: #755F52;
        color: #FFF7F7;
        font-size: 18px;
        font-weight: 500;
        text-align: center;
        line-height: 50px;
        border-radius: 10px;
        text-decoration: none;
        box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.25);
        position: absolute;
        left: 81px;
        top: 620px;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .button.comecar-agora:hover {
        background: #604A3E;
        transform: scale(1.05);
    }

    /* Rodapé */
    footer {
        background: #755F52;
        color: #FFF7F7;
        text-align: center;
        padding: 20px 0;
        box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
    }

    .divider {
        height: 2px;
        background: #F2E7D9;
        margin: 10px auto;
        width: 80%;
    }

    footer p {
        margin: 0;
        font-size: 14px;
    }
  </style>
</head>
<body>
  <header class="header-bg">
    <div class="logo">Daily Planner</div>
    <nav class="menu">
      <a href="#inicio">Início</a>
      <a href="#como-funciona">Como funciona</a>
      <a href="#funcionalidades">Funcionalidades</a>
      <a href="#preco">Preço</a>
      <a href="#faq">FAQ</a>
      <a href="#contato">Contato</a>
    </nav>
    <div>
      <a href="login.php" class="login-button">Login</a>
      <a href="cadastrar_usuario.php" class="register-button">Cadastre-se</a>
    </div>
  </header>

  <main class="container">
    <section class="slogan">
      <h1>Planeje com calma,<br>Realize com leveza</h1>
      <p>
        Organize seu dia com ajuda da nossa mascote e descubra o poder de pequenos hábitos. Tudo de forma simples e eficiente.
      </p>
      <a href="cadastrar_usuario.php" class="button comecar-agora">Começar agora</a>
    </section>

    <!-- Ajustando o botão -->
    <div style="width: 200px; height: 50px; background: #755F52; box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.25); border-radius: 10px; text-align: center; line-height: 50px; margin: 30px auto;">
      <a href="cadastrar_usuario.php" style="color: #FFF7F7; font-size: 18px; font-family: Roboto; font-weight: 500; text-decoration: none;">
        Clique Aqui
      </a>
    </div>
  </main>

  <footer>
    <div class="divider"></div>
    <p>© 2025 Daily Planner. Todos os direitos reservados.</p>
  </footer>
</body>
</html>
