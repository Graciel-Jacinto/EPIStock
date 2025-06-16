<?php
// Inicia a sessão
session_start();

// Verifica se o usuário veio de um cadastro válido
if (!isset($_SESSION['cadastro_sucesso'])) {
    header('Location: index.html');
    exit;
}

// Limpa a sessão após uso
unset($_SESSION['cadastro_sucesso']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Concluído - EPIStock</title>
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3f37c9;
            --success: #4cc9f0;
            --text: #2b2d42;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            background: linear-gradient(135deg, #e0e3ff 0%, #f0f4ff 50%, #e0e9ff 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            text-align: center;
        }
        
        .success-container {
            max-width: 500px;
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .success-icon {
            width: 80px;
            height: 80px;
            background: var(--success);
            border-radius: 50%;
            display: grid;
            place-items: center;
            margin: 0 auto 20px;
        }
        
        .success-icon svg {
            width: 40px;
            height: 40px;
            fill: white;
        }
        
        h1 {
            color: var(--text);
            margin-bottom: 15px;
            font-size: 24px;
        }
        
        p {
            color: var(--text);
            margin-bottom: 25px;
            line-height: 1.5;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
            </svg>
        </div>
        <h1>Cadastro realizado com sucesso!</h1>
        <p>Seu cadastro foi concluído com êxito. Agora você pode acessar todos os recursos do EPIStock.</p>
        <a href="index.html" class="btn">Ir para Login</a>
    </div>

    <script>
        // Redireciona após 5 segundos
        setTimeout(function() {
            window.location.href = 'index.html';
        }, 5000);
    </script>
</body>
</html>