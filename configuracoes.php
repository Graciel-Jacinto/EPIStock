<?php
require_once 'auth.php';

// Verifica se o usuário está logado e se os dados estão corretos
if (!isset($_SESSION['usuario']) || !is_array($_SESSION['usuario'])) {
    header('Location: logar.php');
    exit();
}

$usuario = $_SESSION['usuario'];

// Verifica a página atual
$paginaAtual = basename($_SERVER['PHP_SELF']);
$isDashboard = ($paginaAtual == 'dashboard.php');
$isPedidos = ($paginaAtual == 'pedidos.php');
$isProdutos = ($paginaAtual == 'produtos.php');
$isColaboradores = ($paginaAtual == 'colaboradores.php');
$isFinancas = ($paginaAtual == 'financas.php');
$isConfiguracoes = ($paginaAtual == 'configuracoes.php');

// Conexão com o banco de dados (substitua com suas credenciais)
require_once 'db_config.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verifica conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Processamento do formulário de alteração de senha
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['alterar_senha'])) {
    $senha_atual = $_POST['senha_atual'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    
    // Verifica se as novas senhas coincidem
    if ($nova_senha !== $confirmar_senha) {
        $erro_senha = "As novas senhas não coincidem!";
    } else {
        // Busca a senha atual no banco de dados
        $stmt = $conn->prepare("SELECT senha FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $usuario['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $senha_hash = $row['senha'];
            
            // Verifica se a senha atual está correta
            if (password_verify($senha_atual, $senha_hash)) {
                // Atualiza a senha no banco de dados
                $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
                $stmt->bind_param("si", $nova_senha_hash, $usuario['id']);
                
                if ($stmt->execute()) {
                    $sucesso_senha = "Senha alterada com sucesso!";
                } else {
                    $erro_senha = "Erro ao atualizar a senha: " . $conn->error;
                }
            } else {
                $erro_senha = "Senha atual incorreta!";
            }
        }
    }
}

// Processamento do formulário de atualização de informações
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['atualizar_info'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    
    // Atualiza as informações no banco de dados
    $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, email = ?, telefone = ? WHERE id = ?");
    $stmt->bind_param("sssi", $nome, $email, $telefone, $usuario['id']);
    
    if ($stmt->execute()) {
        // Atualiza os dados na sessão
        $_SESSION['usuario']['nome'] = $nome;
        $_SESSION['usuario']['email'] = $email;
        $_SESSION['usuario']['telefone'] = $telefone;
        $usuario = $_SESSION['usuario'];
        $sucesso_info = "Informações atualizadas com sucesso!";
    } else {
        $erro_info = "Erro ao atualizar informações: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EPIStock - Configurações</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 280px;
            --primary-dark: #163552;
            --primary-light: #2a4e6e;
            --accent: #0c9404;
            --accent-hover: #0a7a03;
            --text-color: #f8f9fa;
            --text-dark: #333;
            --text-light: #777;
            --content-bg: #f5f7fa;
            --card-bg: #ffffff;
            --border-radius: 12px;
            --font-size-base: 16px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: var(--content-bg);
            color: var(--text-dark);
            display: flex;
            min-height: 100vh;
            font-size: var(--font-size-base);
            line-height: 1.6;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-light));
            color: var(--text-color);
            height: 100vh;
            position: fixed;
            padding: 25px 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: all 0.3s ease;
        }
        
        .logo-container {
            text-align: center;
            padding: 0 25px 25px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 25px;
        }
        
        .logo-img {
            max-width: 100%;
            height: auto;
            max-height: 70px;
            margin-bottom: 15px;
            object-fit: contain;
        }
        
        .system-name {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .company-name {
            font-size: 0.9rem;
            opacity: 0.8;
            font-weight: 300;
        }
        
        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
            overflow-y: auto;
        }
        
        .nav-item {
            padding: 14px 25px;
            transition: all 0.3s;
            font-size: 1rem;
            position: relative;
        }
        
        .nav-item:hover {
            background-color: rgba(12, 148, 4, 0.2);
            cursor: pointer;
        }
        
        .nav-item.active {
            background-color: var(--accent);
        }
        
        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: var(--text-color);
        }
        
        .nav-item a {
            color: var(--text-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            font-weight: 500;
        }
        
        .nav-item i {
            margin-right: 15px;
            width: 24px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .system-version {
            padding: 15px;
            font-size: 0.75rem;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: auto;
            opacity: 0.7;
        }
        
        /* Main Content Styles */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px 5%;
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        /* Container de configurações */
        .config-container {
            max-width: 1200px;
            margin: 0 auto;
            background: var(--card-bg);
            border-radius: var(--border-radius);
            padding: 40px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .config-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .config-header i {
            font-size: 3.5rem;
            color: var(--accent);
            margin-bottom: 20px;
        }
        
        .config-header h1 {
            color: var(--primary-dark);
            margin-bottom: 15px;
            font-size: 2.2rem;
        }
        
        .config-header p {
            font-size: 1.1rem;
            color: var(--text-light);
            max-width: 700px;
            margin: 0 auto;
        }
        
        /* Seções de configuração */
        .config-section {
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 1px solid #eee;
        }
        
        .config-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .config-section h2 {
            color: var(--primary-dark);
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            font-size: 1.6rem;
        }
        
        .config-section h2 i {
            margin-right: 15px;
            color: var(--accent);
            font-size: 1.5rem;
        }
        
        /* Informações do usuário */
        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            background: #f9f9f9;
            padding: 30px;
            border-radius: var(--border-radius);
        }
        
        .user-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 30px;
            color: white;
            font-size: 3rem;
            font-weight: bold;
            flex-shrink: 0;
        }
        
        .user-details {
            flex-grow: 1;
        }
        
        .user-name {
            font-size: 1.7rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--primary-dark);
        }
        
        .user-role {
            color: var(--accent);
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 1.2rem;
        }
        
        .user-email {
            color: var(--text-light);
            font-size: 1.1rem;
        }
        
        /* Formulários */
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            color: var(--primary-dark);
            font-size: 1.1rem;
        }
        
        .form-control {
            width: 100%;
            padding: 15px 20px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--accent);
            outline: none;
            box-shadow: 0 0 0 3px rgba(12, 148, 4, 0.1);
        }
        
        /* Mensagens de alerta */
        .alert {
            padding: 15px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            font-size: 1rem;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        /* Botões */
        .btn {
            background-color: var(--accent);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn:hover {
            background-color: var(--accent-hover);
            transform: translateY(-2px);
        }
        
        .btn i {
            margin-right: 10px;
        }
        
        .btn-block {
            display: block;
            width: 100%;
        }
        
        /* Opções de configuração */
        .config-options {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .option-card {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid #eee;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100%;
        }
        
        .option-card:hover {
            border-color: var(--accent);
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .option-card i {
            font-size: 2.8rem;
            color: var(--accent);
            margin-bottom: 20px;
        }
        
        .option-card h3 {
            color: var(--primary-dark);
            margin-bottom: 15px;
            font-size: 1.4rem;
        }
        
        .option-card p {
            color: var(--text-light);
            font-size: 1.05rem;
            line-height: 1.6;
        }
        
        /* Seção sobre o sistema */
        .about-system {
            background: #f9f9f9;
            padding: 40px;
            border-radius: var(--border-radius);
            margin-top: 50px;
        }
        
        .about-system h2 {
            color: var(--primary-dark);
            margin-bottom: 20px;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
        }
        
        .about-system h2 i {
            margin-right: 15px;
            color: var(--accent);
        }
        
        .about-system p {
            margin-bottom: 15px;
            line-height: 1.7;
            font-size: 1.1rem;
        }
        
        .about-system p strong {
            color: var(--primary-dark);
        }
        
        /* Responsividade */
        @media (max-width: 1200px) {
            .main-content {
                padding: 30px;
            }
            
            .config-container {
                padding: 35px;
            }
            
            .user-avatar {
                width: 100px;
                height: 100px;
                font-size: 2.5rem;
            }
            
            .user-name {
                font-size: 1.5rem;
            }
        }
        
        @media (max-width: 992px) {
            :root {
                --sidebar-width: 80px;
            }
            
            .logo-container, .system-name, .company-name, .nav-item span {
                display: none;
            }
            
            .logo-img {
                height: 40px;
                margin-bottom: 0;
            }
            
            .nav-item {
                text-align: center;
                padding: 15px 10px;
            }
            
            .nav-item i {
                margin-right: 0;
                font-size: 1.2rem;
                width: auto;
            }
            
            .main-content {
                padding: 25px;
                margin-left: 80px;
            }
            
            .system-version {
                display: none;
            }
            
            .config-header h1 {
                font-size: 1.9rem;
            }
            
            .config-options {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            }
        }
        
        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }
            
            .config-container {
                padding: 30px;
            }
            
            .user-info {
                flex-direction: column;
                text-align: center;
                padding: 25px;
            }
            
            .user-avatar {
                margin-right: 0;
                margin-bottom: 20px;
            }
            
            .config-section h2 {
                font-size: 1.4rem;
            }
            
            .option-card {
                padding: 25px;
            }
            
            .option-card i {
                font-size: 2.2rem;
            }
        }
        
        @media (max-width: 576px) {
            body {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                flex-direction: row;
                align-items: center;
                padding: 15px;
            }
            
            .logo-container {
                display: flex;
                align-items: center;
                padding: 0;
                border-bottom: none;
                margin-bottom: 0;
                margin-right: 20px;
            }
            
            .logo-img {
                height: 35px;
                margin-bottom: 0;
                margin-right: 0;
            }
            
            .nav-menu {
                display: flex;
                flex-grow: 1;
                justify-content: space-around;
            }
            
            .nav-item {
                padding: 10px 8px;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 20px 15px;
                margin-top: 70px;
            }
            
            .config-options {
                grid-template-columns: 1fr;
            }
            
            .config-header h1 {
                font-size: 1.7rem;
            }
            
            .config-section h2 {
                font-size: 1.3rem;
            }
            
            .about-system {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo-container">
            <img src="https://greenyworkwear.com/wp-content/uploads/2025/02/Logo-Greeny-01-scaled-e1746525528179-1536x523.png" alt="Greeny Workwear Logo" class="logo-img">
            <div>
                <div class="system-name">EPIStock</div>
                <div class="company-name">Greeny Workwear</div>
            </div>
        </div>
        
        <ul class="nav-menu">
            <li class="nav-item <?php echo $isDashboard ? 'active' : ''; ?>">
                <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a>
            </li>
            <li class="nav-item <?php echo $isPedidos ? 'active' : ''; ?>">
                <a href="pedidos.php"><i class="fas fa-clipboard-list"></i> <span>Pedidos</span></a>
            </li>
            <li class="nav-item <?php echo $isProdutos ? 'active' : ''; ?>">
                <a href="produtos.php"><i class="fas fa-boxes"></i> <span>Produtos</span></a>
            </li>
            <li class="nav-item <?php echo $isColaboradores ? 'active' : ''; ?>">
                <a href="colaboradores.php"><i class="fas fa-users"></i> <span>Colaboradores</span></a>
            </li>
            <li class="nav-item <?php echo $isFinancas ? 'active' : ''; ?>">
                <a href="financas.php"><i class="fas fa-chart-line"></i> <span>Finanças</span></a>
            </li>
            <li class="nav-item <?php echo $isConfiguracoes ? 'active' : ''; ?>">
                <a href="configuracoes.php"><i class="fas fa-cog"></i> <span>Configurações</span></a>
            </li>
        </ul>
        
        <div class="system-version">
            v1.0.0 - Desenvolvido por Moz Websites
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="config-container">
            <div class="config-header">
                <i class="fas fa-user-cog"></i>
                <h1>Configurações da Conta</h1>
                <p>Gerencie suas informações pessoais e preferências do sistema</p>
            </div>
            
            <div class="config-section">
                <h2><i class="fas fa-user"></i> Informações Pessoais</h2>
                
                <?php if (isset($sucesso_info)): ?>
                    <div class="alert alert-success">
                        <?php echo $sucesso_info; ?>
                    </div>
                <?php elseif (isset($erro_info)): ?>
                    <div class="alert alert-danger">
                        <?php echo $erro_info; ?>
                    </div>
                <?php endif; ?>
                
                <div class="user-info">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($usuario['nome'], 0, 1)); ?>
                    </div>
                    <div class="user-details">
                        <div class="user-name"><?php echo htmlspecialchars($usuario['nome']); ?></div>
                        <div class="user-role"><?php echo htmlspecialchars($usuario['tipo_usuario']); ?></div>
                        <div class="user-email"><?php echo htmlspecialchars($usuario['email']); ?></div>
                    </div>
                </div>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="nome">Nome Completo</label>
                        <input type="text" id="nome" name="nome" class="form-control" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefone">Telefone</label>
                        <input type="tel" id="telefone" name="telefone" class="form-control" value="<?php echo isset($usuario['telefone']) ? htmlspecialchars($usuario['telefone']) : ''; ?>" placeholder="(+258) 84 000 0000">
                    </div>
                    
                    <button type="submit" name="atualizar_info" class="btn btn-block">
                        <i class="fas fa-save"></i> Atualizar Informações
                    </button>
                </form>
            </div>
            
            <div class="config-section">
                <h2><i class="fas fa-lock"></i> Segurança</h2>
                
                <?php if (isset($sucesso_senha)): ?>
                    <div class="alert alert-success">
                        <?php echo $sucesso_senha; ?>
                    </div>
                <?php elseif (isset($erro_senha)): ?>
                    <div class="alert alert-danger">
                        <?php echo $erro_senha; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="senha_atual">Senha Atual</label>
                        <input type="password" id="senha_atual" name="senha_atual" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="nova_senha">Nova Senha</label>
                        <input type="password" id="nova_senha" name="nova_senha" class="form-control" required minlength="8">
                        <small class="text-muted">Mínimo de 8 caracteres</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmar_senha">Confirmar Nova Senha</label>
                        <input type="password" id="confirmar_senha" name="confirmar_senha" class="form-control" required minlength="8">
                    </div>
                    
                    <button type="submit" name="alterar_senha" class="btn btn-block">
                        <i class="fas fa-key"></i> Alterar Senha
                    </button>
                </form>
            </div>
            
            <div class="config-section">
                <h2><i class="fas fa-sliders-h"></i> Preferências do Sistema</h2>
                
                <div class="config-options">
                    <div class="option-card" onclick="alert('Funcionalidade em desenvolvimento')">
                        <i class="fas fa-palette"></i>
                        <h3>Tema</h3>
                        <p>Altere o tema visual do sistema para claro, escuro ou personalizado</p>
                    </div>
                    
                    <div class="option-card" onclick="alert('Funcionalidade em desenvolvimento')">
                        <i class="fas fa-bell"></i>
                        <h3>Notificações</h3>
                        <p>Configure alertas e notificações por e-mail e no sistema</p>
                    </div>
                    
                    <div class="option-card" onclick="alert('Funcionalidade em desenvolvimento')">
                        <i class="fas fa-language"></i>
                        <h3>Idioma</h3>
                        <p>Altere o idioma de exibição do sistema</p>
                    </div>
                    
                    <div class="option-card" onclick="alert('Funcionalidade em desenvolvimento')">
                        <i class="fas fa-file-export"></i>
                        <h3>Exportar Dados</h3>
                        <p>Exporte seus dados do sistema em diferentes formatos</p>
                    </div>
                </div>
            </div>
            
            <div class="about-system">
                <h2><i class="fas fa-info-circle"></i> Sobre o Sistema</h2>
                <p><strong>EPIStock v1.0.0</strong></p>
                <p>Sistema desenvolvido por <strong>Moz Websites</strong> para gestão de EPIs da Greeny Workwear.</p>
                <p>Este sistema permite o controle completo de estoque, pedidos e distribuição de Equipamentos de Proteção Individual.</p>
                <p>&copy; <?php echo date('Y'); ?> Greeny Workwear. Todos os direitos reservados.</p>
            </div>
        </div>
    </div>

    <script>
        // Adiciona máscara para o campo de telefone
        document.getElementById('telefone').addEventListener('input', function (e) {
            var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,2})(\d{0,3})(\d{0,4})/);
            e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? ' ' + x[3] : '') + (x[4] ? '-' + x[4] : '');
        });

        // Validação do formulário de senha
        document.querySelector('form[name="alterar_senha"]').addEventListener('submit', function(e) {
            var novaSenha = document.getElementById('nova_senha').value;
            var confirmarSenha = document.getElementById('confirmar_senha').value;
            
            if (novaSenha !== confirmarSenha) {
                alert('As senhas não coincidem!');
                e.preventDefault();
            }
            
            if (novaSenha.length < 8) {
                alert('A senha deve ter no mínimo 8 caracteres!');
                e.preventDefault();
            }
        });
    </script>
</body>
</html>