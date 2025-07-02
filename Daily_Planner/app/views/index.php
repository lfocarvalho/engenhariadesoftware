<?php
require __DIR__ . '/../../config/config.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['usuario']['id'] = 1; 

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

    body {
        margin: 0;
        padding: 0;
        font-family: 'Roboto', sans-serif;
        background-image: url('/engenhariadesoftware/Daily_Planner/app/img/welcome.svg'); 
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        color: #755F52;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .header-bg {
        width: 100%;
        height: 104px;
        background: #FFFFFF; 
        box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 30px;
        box-sizing: border-box; 
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
        color: #755F52; 
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .menu a:hover {
        color: #604A3E; 
    }


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
        margin-right: 10px; 
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
        z-index: 1;
        background: transparent;
        max-width: 1200px;      
        margin: 0 auto;         
        padding: 0 32px;        
        text-align: left;
        flex: 1;
    }

    .slogan {
        margin-top: 200px;
        position: relative;
        text-align: left;
        max-width: none;
        margin-left: 0;
    }

    .slogan h1 {
        font-size: 64px;
        font-weight: 800;
        color: #806359;
        line-height: 1.2;
        text-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
        margin-bottom: 20px;
    }

    .slogan p {
        font-size: 20px;
        font-weight: 500;
        color: #755F52;
        margin-bottom: 20px; 
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
        transition: background 0.3s ease, transform 0.2s ease;
        margin-left: 0; 
    }

    .button.comecar-agora:hover {
        background: #604A3E;
        transform: scale(1.05);
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

    .slogan-mascote {
        display: flex;
        align-items: center;
        justify-content: flex-start; 
        gap: 56px;                  
        min-height: 70vh;
        margin-top: 0;
        margin-bottom: 0;
        padding-left: 5vw;         
    }

    .slogan-texto {
        max-width: 520px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;
        text-align: left;
    }

    .slogan-texto h1 {
        font-size: 48px;
        font-weight: 800;
        color: #806359;
        line-height: 1.1;
        margin-bottom: 18px;
        text-shadow: 0px 4px 12px rgba(0,0,0,0.10);
        white-space: normal; 
    }

    .slogan-texto p {
        font-size: 16px;
        color: #755F52;
        font-weight: 400;
        margin-bottom: 22px;
        line-height: 1.4;
        max-width: 420px;
    }

    .button.comecar-agora {
        margin-left: 0;
    }

    .slogan-mascote-img {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .slogan-mascote-img img {
        width: 480px;             
        max-width: 40vw;
        height: auto;
        filter: drop-shadow(0 8px 32px #bca18c55);
        border-radius: 24px;
    }

    @media (max-width: 900px) {
        .slogan-mascote {
            flex-direction: column;
            gap: 24px;
            min-height: 0;
            margin-top: 40px;
        }
        .slogan-mascote-img img {
            width: 180px;
            max-width: 60vw;
        }
        .slogan-texto h1 {
            font-size: 32px;
        }
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
    <section class="slogan-mascote">
      <div class="slogan-texto">
        <h1>Planeje com calma, Realize com leveza</h1>
        <p>
          Organize seu dia com ajuda da nossa mascote e descubra o poder de pequenos hábitos. Tudo de forma simples e eficiente.
        </p>
        <a href="cadastrar_usuario.php" class="button comecar-agora">Começar agora</a>
      </div>
      <div class="slogan-mascote-img">
        <img src="/engenhariadesoftware/Daily_Planner/app/img/mascote.svg" alt="Mascote Daily Planner" />
      </div>
    </section>
  </main>
</body>
</html>
