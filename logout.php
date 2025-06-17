<?php
// logout.php - Versão com página de feedback

session_start();

// Registrar ação de logout
if (isset($_SESSION['usuario'])) {
    $usuarioNome = $_SESSION['usuario']['nome'];
    
    // Limpar sessão
    $_SESSION = array();
    
    // Destruir cookie de sessão
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(), 
            '', 
            time() - 42000,
            $params["path"], 
            $params["domain"],
            $params["secure"], 
            $params["httponly"]
        );
    }
    
    session_destroy();
    
    // Mostrar página de logout
    mostrarPaginaLogout($usuarioNome);
} else {
    header('Location: login.php');
    exit();
}

function mostrarPaginaLogout($nomeUsuario) {
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>EPIStock - Logout</title>
        <style>
            body {
                font-family: 'Inter', sans-serif;
                background: linear-gradient(135deg, #e0e3ff 0%, #f0f4ff 50%, #e0e9ff 100%);
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                margin: 0;
                padding: 20px;
            }
            .logout-container {
                background: white;
                padding: 40px;
                border-radius: 16px;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
                text-align: center;
                max-width: 500px;
                width: 100%;
            }
            .logout-icon {
                font-size: 72px;
                color: #4361ee;
                margin-bottom: 20px;
            }
            h1 {
                color: #2b2d42;
                margin-bottom: 15px;
            }
            p {
                color: #6c757d;
                margin-bottom: 25px;
            }
            .btn {
                display: inline-block;
                padding: 12px 24px;
                background: #4361ee;
                color: white;
                text-decoration: none;
                border-radius: 8px;
                transition: all 0.3s;
            }
            .btn:hover {
                background: #3a56d4;
                transform: translateY(-2px);
            }
        </style>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="logout-container">
            <div class="logout-icon">👋</div>
            <h1>Até logo, <?php echo htmlspecialchars($nomeUsuario); ?>!</h1>
            <p>Você saiu com segurança do sistema EPIStock.</p>
            <a href="login.php" class="btn">Voltar ao login</a>
            
            <!-- Redirecionamento automático após 5 segundos -->
            <script>
                setTimeout(function() {
                    window.location.href = 'logar.php';
                }, 5000);
            </script>
        </div>
    </body>
    </html>
    <?php
    exit();
}
?>