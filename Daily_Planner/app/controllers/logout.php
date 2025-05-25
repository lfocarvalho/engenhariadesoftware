<?php
session_start();

// Remove todas as variáveis de sessão
session_unset();

// Destrói a sessão
session_destroy();

header('Location: ../../index.html'); // Redireciona para a página inicial ou de login
exit;