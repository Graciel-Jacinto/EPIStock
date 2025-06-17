<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EPIStock - Login</title>
    <style>
        :root {
            --primary: #204b72; /* Azul */
            --primary-dark: #163552; /* Azul mais escuro */
            --accent: #0c9404; /* Verde */
            --text: #2b2d42;
            --light-bg: #f8f9fa;
            --border: #dee2e6;
            --error: #ef233c;
            --radius: 16px;
            --shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            background: linear-gradient(135deg, #e0f0e5 0%, #f0f8ff 50%, #e0e9f0 100%);
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        /* Efeito de partículas no fundo */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 30%, rgba(12, 148, 4, 0.1) 0%, transparent 20%),
                radial-gradient(circle at 80% 70%, rgba(32, 75, 114, 0.1) 0%, transparent 20%),
                radial-gradient(circle at 40% 80%, rgba(22, 53, 82, 0.1) 0%, transparent 20%);
            z-index: -1;
        }
        
        /* Card de login */
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            width: 100%;
            max-width: 480px;
            padding: 50px 40px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transform-style: preserve-3d;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .login-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        
        .login-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(to right, var(--accent), var(--primary));
        }
        
        /* Logo */
        .logo {
            margin-bottom: 35px;
            position: relative;
        }
        
        .logo-img {
            height: 60px;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
        }
        
        .logo-img:hover {
            transform: scale(1.05);
        }
        
        .logo-text {
            font-size: 24px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 5px;
            background: linear-gradient(to right, var(--accent), var(--primary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .logo-subtext {
            font-size: 14px;
            color: var(--primary);
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        
        /* Formulário */
        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: var(--text);
            font-weight: 500;
            font-size: 14px;
        }
        
        .form-group input {
            width: 100%;
            padding: 16px 20px;
            border: 1px solid var(--border);
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s;
            background-color: rgba(248, 249, 250, 0.7);
        }
        
        .form-group input:focus {
            border-color: var(--accent);
            outline: none;
            box-shadow: 0 0 0 3px rgba(12, 148, 4, 0.2);
            background-color: white;
        }
        
        /* Opções */
        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .checkbox-label {
            display: flex;
            align-items: center;
            cursor: pointer;
            font-size: 14px;
        }
        
        .checkbox-label input {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            accent-color: var(--primary);
        }
        
        /* Links */
        .link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            position: relative;
            transition: all 0.3s;
        }
        
        .link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: var(--accent);
            transition: width 0.3s;
        }
        
        .link:hover {
            color: var(--accent);
        }
        
        .link:hover::after {
            width: 100%;
        }
        
        /* Botão */
        .btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(to right, var(--accent), var(--primary));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 6px 15px rgba(12, 148, 4, 0.4);
            position: relative;
            overflow: hidden;
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(12, 148, 4, 0.5);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }
        
        .btn:hover::before {
            left: 100%;
        }
        
        /* Rodapé */
        .footer-text {
            margin-top: 30px;
            font-size: 14px;
            color: #6c757d;
        }
        
        /* Responsividade */
        @media (max-width: 576px) {
            .login-card {
                padding: 40px 25px;
            }
            
            .flex-between {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
        
        /* Adicionar estilos para mensagens de erro */
        .error-message {
            color: #ef233c;
            font-size: 13px;
            margin-top: 5px;
            display: none;
        }
        
        .input-error {
            border-color: #ef233c !important;
        }
        
        .alert {
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 14px;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo">
            <img src="imagens\logo-greeny-2025-1536x396.png" alt="EPIStock Logo" class="logo-img">
            <div class="logo-text">EPIStock</div>
            <div class="logo-subtext">Gerenciamento de EPIs</div>
        </div>

        <!-- Mensagens do sistema -->
        <?php if (isset($_GET['status'])): ?>
            <div class="alert <?php echo $_GET['status'] === 'sucesso' ? 'alert-success' : 'alert-danger'; ?>">
                <?php 
                    if ($_GET['status'] === 'sucesso') {
                        echo 'Login realizado com sucesso!';
                    } elseif ($_GET['status'] === 'erro') {
                        echo 'E-mail ou senha incorretos!';
                    } elseif ($_GET['status'] === 'campos') {
                        echo 'Por favor, preencha todos os campos!';
                    }
                ?>
            </div>
        <?php endif; ?>

        <form id="loginForm" action="validar_login.php" method="POST" class="login-form" novalidate>
            <div class="form-group">
                <label for="email">E-mail ou telefone</label>
                <input type="text" id="email" name="email" placeholder="usuario@empresa.com" required>
                <div class="error-message" id="email-error">Por favor, insira um e-mail ou telefone válido</div>
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="••••••••" required minlength="6">
                <div class="error-message" id="senha-error">A senha deve ter pelo menos 6 caracteres</div>
            </div>

            <div class="flex-between">
                <label class="checkbox-label">
                    <input type="checkbox" name="lembrar"> Lembrar-me
                </label>
                <a href="#" class="link">Esqueceu a senha?</a>
            </div>

            <button type="submit" class="btn">Entrar no Sistema</button>
        </form>

        <p class="footer-text">Não tem uma conta? <a href="cadastro.php" class="link">Solicitar acesso</a></p>
        <p class="footer-text">V 1.0.0</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const emailInput = document.getElementById('email');
            const senhaInput = document.getElementById('senha');
            const emailError = document.getElementById('email-error');
            const senhaError = document.getElementById('senha-error');

            // Validação em tempo real
            emailInput.addEventListener('input', validateEmail);
            senhaInput.addEventListener('input', validateSenha);

            // Validação no submit
            form.addEventListener('submit', function(event) {
                let isValid = true;
                
                if (!validateEmail()) isValid = false;
                if (!validateSenha()) isValid = false;
                
                if (!isValid) {
                    event.preventDefault();
                }
            });

            function validateEmail() {
                const value = emailInput.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const phoneRegex = /^(\+\d{1,3})?\d{10,15}$/;
                
                if (value === '') {
                    showError(emailInput, emailError, 'Campo obrigatório');
                    return false;
                } else if (!emailRegex.test(value) && !phoneRegex.test(value)) {
                    showError(emailInput, emailError, 'E-mail ou telefone inválido');
                    return false;
                } else {
                    hideError(emailInput, emailError);
                    return true;
                }
            }

            function validateSenha() {
                const value = senhaInput.value;
                
                if (value === '') {
                    showError(senhaInput, senhaError, 'Campo obrigatório');
                    return false;
                } else if (value.length < 6) {
                    showError(senhaInput, senhaError, 'Mínimo 6 caracteres');
                    return false;
                } else {
                    hideError(senhaInput, senhaError);
                    return true;
                }
            }

            function showError(input, errorElement, message) {
                input.classList.add('input-error');
                errorElement.textContent = message;
                errorElement.style.display = 'block';
            }

            function hideError(input, errorElement) {
                input.classList.remove('input-error');
                errorElement.style.display = 'none';
            }
        });
    </script>
</body>
</html>