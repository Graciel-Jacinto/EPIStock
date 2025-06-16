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
        /* [Todo o seu CSS original permanece aqui] */
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 5px;
        }
    </style>
    
</head>
<body>
    <div class="container">
        <div class="register-card">
            <div class="logo">
                <div class="logo-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                    </svg>
                </div>
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

            <p class="login-link">Já tem uma conta? <a href="index.php">Faça login</a></p>
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