<?php
// Ativar exibição de erros (apenas para desenvolvimento)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar sessão
session_start();

// Verificar se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php?status=erro&motivo=metodo-invalido');
    exit();
}

// Validar campos obrigatórios
if (empty($_POST['email']) || empty($_POST['senha'])) {
    header('Location: login.php?status=erro&motivo=campos-vazios');
    exit();
}

// Sanitizar e validar os dados
$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$senha = trim($_POST['senha']);

// Validação do e-mail
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: login.php?status=erro&motivo=email-invalido');
    exit();
}

// Conexão com o banco de dados (substitua com seus dados)
$host = 'localhost';
$dbname = 'epistock_db'; // substitua pelo nome do seu banco
$username = 'root'; // substitua pelo seu usuário
$password = ''; // substitua pela sua senha

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Buscar usuário no banco de dados
    $stmt = $pdo->prepare("SELECT id, nome, email, senha FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() === 1) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verificar senha (compara a senha digitada com o hash no banco)
        if (password_verify($senha, $usuario['senha'])) {
            // Autenticação bem-sucedida
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            
            // Redirecionar para dashboard
            header('Location: dashboard.php');
            exit();
        } else {
            // Senha incorreta
            header('Location: login.php?status=erro&motivo=credenciais-incorretas');
            exit();
        }
    } else {
        // Usuário não encontrado
        header('Location: login.php?status=erro&motivo=usuario-nao-encontrado');
        exit();
    }
    
} catch (PDOException $e) {
    // Log do erro
    error_log("Erro no login: " . $e->getMessage());
    
    // Redirecionar com mensagem de erro
    header('Location: login.php?status=erro&motivo=erro-banco-dados');
    exit();
}
?>