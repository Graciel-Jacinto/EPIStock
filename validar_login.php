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
    header('Location: login.php?status=campos');
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
$dbname = 'epistock_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Buscar usuário no banco de dados com todos os campos necessários
    $stmt = $pdo->prepare("SELECT id, nome, email, senha, tipo_usuario, data_cadastro FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() === 1) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verificar senha (compara a senha digitada com o hash no banco)
        if (password_verify($senha, $usuario['senha'])) {
            // Autenticação bem-sucedida - armazenar dados na sessão
            $_SESSION['usuario'] = [
                'id' => $usuario['id'],
                'nome' => $usuario['nome'],
                'email' => $usuario['email'],
                'tipo_usuario' => $usuario['tipo_usuario'],
                'data_cadastro' => $usuario['data_cadastro']
            ];
            
            // Redirecionar para dashboard
            header('Location: dashboard.php');
            exit();
        } else {
            // Senha incorreta
            header('Location: login.php?status=erro');
            exit();
        }
    } else {
        // Usuário não encontrado
        header('Location: login.php?status=erro');
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