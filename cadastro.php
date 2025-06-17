<?php
// Inicia a sessão
session_start();

// Configurações do banco de dados
$host = 'localhost';
$usuario = 'root';
$senha_bd = '';
$banco = 'epistock_db';

// Conecta ao banco de dados
$conexao = new mysqli($host, $usuario, $senha_bd, $banco);

// Verifica erros de conexão
if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

// Processa o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe e sanitiza os dados
    $nome = $conexao->real_escape_string($_POST['nome']);
    $email = $conexao->real_escape_string($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    $tipo_usuario = $conexao->real_escape_string($_POST['tipo_usuario']);
    $termos = isset($_POST['termos']) ? true : false;

    // Validações básicas
    $erro = false;
    $mensagens_erro = [];

    if (empty($nome) || strlen($nome) < 3) {
        $erro = true;
        $mensagens_erro['nome'] = 'Nome deve ter pelo menos 3 caracteres';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = true;
        $mensagens_erro['email'] = 'E-mail inválido';
    }

    if (strlen($senha) < 8) {
        $erro = true;
        $mensagens_erro['senha'] = 'Senha deve ter pelo menos 8 caracteres';
    } elseif ($senha !== $confirmar_senha) {
        $erro = true;
        $mensagens_erro['confirmar_senha'] = 'Senhas não coincidem';
    }

    if (!in_array($tipo_usuario, ['admin', 'usuario'])) {
        $erro = true;
        $mensagens_erro['tipo_usuario'] = 'Tipo de usuário inválido';
    }

    if (!$termos) {
        $erro = true;
        $mensagens_erro['termos'] = 'Você deve aceitar os termos';
    }

    // Se não houver erros, cadastra o usuário
    if (!$erro) {
        // Verifica se o e-mail já existe
        $verifica_email = $conexao->query("SELECT id FROM usuarios WHERE email = '$email'");
        
        if ($verifica_email->num_rows > 0) {
            $mensagens_erro['email'] = 'Este e-mail já está cadastrado';
        } else {
            // Criptografa a senha
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            
            // Insere no banco de dados
            $sql = "INSERT INTO usuarios (nome, email, senha, tipo_usuario) VALUES ('$nome', '$email', '$senha_hash', '$tipo_usuario')";
            
            if ($conexao->query($sql) === TRUE) {
                $_SESSION['cadastro_sucesso'] = true;
                header('Location: cadastro-sucesso.php');
                exit;
            } else {
                $mensagens_erro['general'] = 'Erro ao cadastrar: ' . $conexao->error;
            }
        }
    }
}

// Fecha a conexão
$conexao->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EPIStock - Cadastro</title>
    <link rel="stylesheet" href="CSS.css">
    
    <style>
      .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 5px;
        }
        
        /* Estilo adicional para a logo */
        .logo-img {
            width: 120px; /* Ajuste conforme necessário */
            height: auto;
            margin-bottom: 15px;
        }
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
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Alterado para flex-start para permitir rolagem */
            padding: 40px 20px; /* Aumentado o padding */
            position: relative;
            overflow-y: auto; /* Permite rolagem vertical */
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
        
        /* Container principal */
        .container {
            width: 100%;
            max-width: 550px; /* Aumentado de 480px para 550px */
            margin: 40px auto; /* Adicionado margem para espaçamento vertical */
        }
        
        /* Card de registro */
        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            width: 100%;
            padding: 50px 40px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transform-style: preserve-3d;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-bottom: 30px; /* Espaço para rolagem */
        }
        
        .register-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        
        .register-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(to right, var(--accent), var(--primary));
        }
        
        /* Logo */
      /* Logo */
.logo-img {
    height: 80px;
    width: auto; /* Mantém a proporção original */
    /* ... resto do código ... */
}

.logo-img {
    height: 80px; /* Aumentei de 50px para 80px */
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
        
        /* Mensagens de login */
        .login-link {
            margin-top: 20px;
            font-size: 14px;
            color: #6c757d;
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
        
        /* Termos */
        .terms-group {
            display: flex;
            align-items: center;
            margin: 25px 0;
            font-size: 14px;
        }
        
        .terms-group input {
            margin-right: 10px;
            accent-color: var(--accent);
        }
        
        .terms-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }
        
        .terms-link:hover {
            text-decoration: underline;
            color: var(--accent);
        }
        
        /* Dica do formulário */
        .form-hint {
            display: block;
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
        
        /* Responsividade */
        @media (max-width: 576px) {
            .register-card {
                padding: 40px 25px;
            }
            
            .container {
                max-width: 100%;
                padding: 20px 15px;
            }
        }
    </style>
    
</head>
<body>
   <div class="container">
        <div class="register-card">
            <div class="logo">
                <!-- Logo imagem PNG da Greeny Workwear -->
                <img src="imagens\logo-greeny-2025-1536x396.png" alt="Greeny Workwear Logo" class="logo-img">
                
                <div class="logo-text">EPIStock</div>
                <div class="logo-subtext">Crie sua conta</div>
            </div>
                
              

            <?php if (isset($_SESSION['cadastro_sucesso'])): ?>
                <div class="success-message" style="display: block;">
                    Cadastro realizado com sucesso! Redirecionando...
                </div>
                <?php unset($_SESSION['cadastro_sucesso']); ?>
                <script>
                    setTimeout(function() {
                        window.location.href = 'index.html';
                    }, 3000);
                </script>
            <?php else: ?>
                <?php if (!empty($mensagens_erro['general'])): ?>
                    <div class="error-message" style="display: block; text-align: center; margin-bottom: 20px;">
                        <?php echo $mensagens_erro['general']; ?>
                    </div>
                <?php endif; ?>

                <form id="registerForm" class="register-form" method="POST" novalidate>
                    <div class="form-group">
                        <label for="nome">Seu Nome</label>
                        <input type="text" id="nome" name="nome" placeholder="Digite seu nome completo" 
                               value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>" 
                               class="<?php echo !empty($mensagens_erro['nome']) ? 'input-error' : ''; ?>" required>
                        <?php if (!empty($mensagens_erro['nome'])): ?>
                            <span class="error-message" style="display: block;"><?php echo $mensagens_erro['nome']; ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" placeholder="seu@email.com" 
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                               class="<?php echo !empty($mensagens_erro['email']) ? 'input-error' : ''; ?>" required>
                        <?php if (!empty($mensagens_erro['email'])): ?>
                            <span class="error-message" style="display: block;"><?php echo $mensagens_erro['email']; ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="tipo_usuario">Tipo de Usuário</label>
                        <select id="tipo_usuario" name="tipo_usuario" class="<?php echo !empty($mensagens_erro['tipo_usuario']) ? 'input-error' : ''; ?>" required>
                            <option value="usuario" <?php echo (isset($_POST['tipo_usuario']) && $_POST['tipo_usuario'] == 'usuario') ? 'selected' : ''; ?>>Usuário Normal</option>
                            <option value="admin" <?php echo (isset($_POST['tipo_usuario']) && $_POST['tipo_usuario'] == 'admin') ? 'selected' : ''; ?>>Administrador</option>
                        </select>
                        <?php if (!empty($mensagens_erro['tipo_usuario'])): ?>
                            <span class="error-message" style="display: block;"><?php echo $mensagens_erro['tipo_usuario']; ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" placeholder="Crie uma senha segura" 
                               class="<?php echo !empty($mensagens_erro['senha']) ? 'input-error' : ''; ?>" required>
                        <?php if (!empty($mensagens_erro['senha'])): ?>
                            <span class="error-message" style="display: block;"><?php echo $mensagens_erro['senha']; ?></span>
                        <?php endif; ?>
                        <span class="form-hint">Mínimo 8 caracteres</span>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmar-senha">Confirmação de senha</label>
                        <input type="password" id="confirmar-senha" name="confirmar_senha" placeholder="Repita sua senha" 
                               class="<?php echo !empty($mensagens_erro['confirmar_senha']) ? 'input-error' : ''; ?>" required>
                        <?php if (!empty($mensagens_erro['confirmar_senha'])): ?>
                            <span class="error-message" style="display: block;"><?php echo $mensagens_erro['confirmar_senha']; ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="terms-group">
                        <input type="checkbox" id="termos" name="termos" <?php echo isset($_POST['termos']) ? 'checked' : ''; ?>>
                        <label for="termos">Concordo com os <a href="#" class="terms-link">Termos</a> e <a href="#" class="terms-link">Política de Privacidade</a></label>
                        <?php if (!empty($mensagens_erro['termos'])): ?>
                            <span class="error-message" style="display: block; width: 100%; margin-top: 5px;"><?php echo $mensagens_erro['termos']; ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" class="btn">Criar Conta</button>
                </form>
            <?php endif; ?>

            <p class="login-link">Já tem uma conta? <a href="logar.php">Faça login</a></p>
            <p class="login-link">V 1.0.0</p>
        </div>
    </div>

    <script>
        // Validação em tempo real do lado do cliente
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            let valid = true;
            
            // Validação do nome
            const nome = document.getElementById('nome');
            if (nome.value.trim().length < 3) {
                valid = false;
                nome.classList.add('input-error');
            } else {
                nome.classList.remove('input-error');
            }
            
            // Validação do email
            const email = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) {
                valid = false;
                email.classList.add('input-error');
            } else {
                email.classList.remove('input-error');
            }
            
            // Validação do tipo de usuário
            const tipoUsuario = document.getElementById('tipo_usuario');
            if (tipoUsuario.value !== 'admin' && tipoUsuario.value !== 'usuario') {
                valid = false;
                tipoUsuario.classList.add('input-error');
            } else {
                tipoUsuario.classList.remove('input-error');
            }
            
            // Validação da senha
            const senha = document.getElementById('senha');
            if (senha.value.length < 8) {
                valid = false;
                senha.classList.add('input-error');
            } else {
                senha.classList.remove('input-error');
            }
            
            // Validação da confirmação de senha
            const confirmarSenha = document.getElementById('confirmar-senha');
            if (senha.value !== confirmarSenha.value) {
                valid = false;
                confirmarSenha.classList.add('input-error');
            } else {
                confirmarSenha.classList.remove('input-error');
            }
            
            // Validação dos termos
            const termos = document.getElementById('termos');
            if (!termos.checked) {
                valid = false;
                termos.classList.add('input-error');
            } else {
                termos.classList.remove('input-error');
            }
            
            if (!valid) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos corretamente');
            }
        });
    </script>
</body>
</html>