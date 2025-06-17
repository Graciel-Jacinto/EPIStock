<?php
require_once 'auth.php';

$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EPIStock - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 280px; /* Aumentei a largura */
            --primary-dark: #163552;
            --accent: #0c9404;
            --text-color: #f8f9fa;
            --hover-color: #0a7a03;
            --content-bg: #f8f9fa;
            --card-bg: #ffffff;
            --font-size-base: 16px; /* Tamanho base aumentado */
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: var(--content-bg);
            display: flex;
            min-height: 100vh;
            font-size: var(--font-size-base);
        }
        
        /* Sidebar Styles - Aumentei todos os tamanhos */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--primary-dark);
            color: var(--text-color);
            height: 100vh;
            position: fixed;
            padding: 25px 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }
        
        .logo-container {
            text-align: center;
            padding: 0 25px 25px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 25px;
        }
        
        .logo-img {
            height: 80px; /* Aumentei o logo */
            margin-bottom: 15px;
        }
        
        .system-name {
            font-size: 22px; /* Aumentei o tamanho */
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .company-name {
            font-size: 16px; /* Aumentei o tamanho */
            opacity: 0.8;
        }
        
        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }
        
        .nav-item {
            padding: 16px 25px; /* Aumentei o padding */
            transition: all 0.3s;
            font-size: 18px; /* Aumentei o tamanho da fonte */
        }
        
        .nav-item:hover {
            background-color: rgba(12, 148, 4, 0.2);
            cursor: pointer;
        }
        
        .nav-item.active {
            background-color: var(--accent);
        }
        
        .nav-item a {
            color: var(--text-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            font-weight: 500;
        }
        
        .nav-item i {
            margin-right: 15px; /* Aumentei o espaçamento */
            width: 24px;
            text-align: center;
            font-size: 20px; /* Aumentei os ícones */
        }
        
        .system-version {
            padding: 20px;
            font-size: 14px; /* Aumentei a versão */
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: auto;
            opacity: 0.7;
        }
        
        /* Main Content Styles - Aumentei todos os tamanhos */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 35px;
            width: calc(100% - var(--sidebar-width));
        }
        
        .welcome {
            margin-bottom: 30px;
            padding: 30px;
            background: var(--card-bg);
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        
        .welcome h1 {
            color: var(--primary-dark);
            margin-bottom: 15px;
            font-size: 28px; /* Aumentei o título */
        }
        
        .welcome p {
            font-size: 18px; /* Aumentei o texto */
        }
        
        .user-info {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .user-info h2 {
            color: var(--primary-dark);
            margin-bottom: 20px;
            font-size: 24px; /* Aumentei o subtítulo */
        }
        
        .user-info p {
            margin-bottom: 12px;
            font-size: 18px; /* Aumentei os textos */
        }
        
        .logout-btn {
            display: inline-flex;
            align-items: center;
            padding: 12px 25px;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s;
            font-weight: 500;
            font-size: 18px; /* Aumentei o botão */
        }
        
        .logout-btn:hover {
            background-color: #c0392b;
        }
        
        .logout-btn i {
            margin-right: 12px;
            font-size: 20px; /* Aumentei o ícone */
        }
        
        /* Responsividade */
        @media (max-width: 992px) {
            :root {
                --sidebar-width: 90px;
                --font-size-base: 15px;
            }
            
            .logo-container, .system-name, .company-name, .nav-item span {
                display: none;
            }
            
            .logo-img {
                height: 50px;
                margin-bottom: 0;
            }
            
            .nav-item {
                text-align: center;
                padding: 20px 10px;
                font-size: 0; /* Esconde o texto */
            }
            
            .nav-item i {
                margin-right: 0;
                font-size: 24px; /* Ícones ainda grandes */
                width: auto;
            }
            
            .main-content {
                padding: 20px;
            }
            
            .system-version {
                display: none;
            }
        }
        
        @media (max-width: 768px) {
            :root {
                --font-size-base: 14px;
            }
            
            .welcome h1 {
                font-size: 24px;
            }
            
            .user-info h2 {
                font-size: 22px;
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
                height: 50px;
                margin-bottom: 0;
                margin-right: 15px;
            }
            
            .system-name {
                display: block;
                font-size: 18px;
                margin-bottom: 0;
            }
            
            .company-name {
                display: none;
            }
            
            .nav-menu {
                display: flex;
                flex-grow: 1;
                justify-content: space-around;
            }
            
            .nav-item {
                padding: 12px 8px;
                font-size: 0;
            }
            
            .nav-item i {
                font-size: 20px;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 20px 15px;
            }
            
            .system-version {
                display: none;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo-container">
            <img src="imagens\logo-greeny-2025-1536x396.png" alt="Greeny Workwear Logo" class="logo-img">
            <div>
                <div class="system-name">EPIStock</div>
                <div class="company-name">Greeny Workwear</div>
            </div>
        </div>
        
        <ul class="nav-menu">
            <li class="nav-item active">
                <a href="#"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a>
            </li>
            <li class="nav-item">
                <a href="#"><i class="fas fa-clipboard-list"></i> <span>Pedidos</span></a>
            </li>
            <li class="nav-item">
                <a href="#"><i class="fas fa-boxes"></i> <span>Produtos</span></a>
            </li>
            <li class="nav-item">
                <a href="#"><i class="fas fa-users"></i> <span>Colaboradores</span></a>
            </li>
            <li class="nav-item">
                <a href="#"><i class="fas fa-chart-line"></i> <span>Finanças</span></a>
            </li>
            <li class="nav-item">
                <a href="#"><i class="fas fa-cog"></i> <span>Configurações</span></a>
            </li>
        </ul>
        
        <div class="system-version">
            Sistema Greeny Workwear | V 1.0.0
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="welcome">
            <h1>Bem-vindo, <?php echo htmlspecialchars($usuario['nome']); ?>!</h1>
            <p>Você está logado no sistema EPIStock.</p>
        </div>
        
        <div class="user-info">
            <h2>Seus dados:</h2>
            <p><strong>ID:</strong> <?php echo $usuario['id']; ?></p>
            <p><strong>E-mail:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
            <p><strong>Tipo de usuário:</strong> <?php echo $usuario['tipo_usuario']; ?></p>
            <p><strong>Cadastrado em:</strong> <?php echo date('d/m/Y', strtotime($usuario['data_cadastro'])); ?></p>
        </div>
        
        <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Sair</a>
    </div>
</body>
</html>