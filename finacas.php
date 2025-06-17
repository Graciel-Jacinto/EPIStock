<?php
require_once 'auth.php';

$usuario = $_SESSION['usuario'];

// Configurações da API de Clima (OpenWeatherMap)
$apiKey = 'aaf77c40cb51357912a63d23a7ed4d9c';
$cidadePadrao = 'Maputo';
$unidade = 'metric';

// Verifica a página atual
$paginaAtual = basename($_SERVER['PHP_SELF']);
$isDashboard = ($paginaAtual == 'dashboard.php');
$isPedidos = ($paginaAtual == 'pedidos.php');
$isProdutos = ($paginaAtual == 'produtos.php');
$isColaboradores = ($paginaAtual == 'colaboradores.php');
$isFinancas = ($paginaAtual == 'financas.php');
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EPIStock - <?php 
        if ($isDashboard) echo 'Dashboard';
        elseif ($isPedidos) echo 'Pedidos';
        elseif ($isProdutos) echo 'Produtos';
        elseif ($isColaboradores) echo 'Colaboradores';
        elseif ($isFinancas) echo 'Finanças';
        else echo 'Sistema';
    ?></title>
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
            padding: 30px;
            width: calc(100% - var(--sidebar-width));
            transition: all 0.3s ease;
        }
        
        /* Estilos para a seção de desenvolvimento */
        .dev-container {
            max-width: 800px;
            margin: 0 auto;
            background: var(--card-bg);
            border-radius: var(--border-radius);
            padding: 30px;
            text-align: center;
        }
        
        .dev-header {
            margin-bottom: 30px;
        }
        
        .dev-header i {
            font-size: 4rem;
            color: var(--accent);
            margin-bottom: 20px;
        }
        
        .dev-header h1 {
            color: var(--primary-dark);
            margin-bottom: 15px;
        }
        
        .dev-content {
            line-height: 1.8;
            text-align: left;
        }
        
        .dev-content h2 {
            color: var(--primary-dark);
            margin: 25px 0 15px;
            font-size: 1.4rem;
            text-align: center;
        }
        
        .dev-content p {
            margin-bottom: 15px;
            color: var(--text-dark);
        }
        
        .dev-info {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            font-style: italic;
            color: var(--text-light);
        }
        
        /* Responsividade */
        @media (max-width: 768px) {
            .dev-container {
                padding: 20px;
            }
            
            .dev-header h1 {
                font-size: 1.8rem;
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
            <li class="nav-item">
                <a href="#"><i class="fas fa-cog"></i> <span>Configurações</span></a>
            </li>
        </ul>
        
        <div class="system-version">
            v1.0.0 - Desenvolvido por Moz Websites
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <?php if ($isFinancas): ?>
            <!-- Página de Finanças -->
            <div class="dev-container">
                <div class="dev-header">
                    <i class="fas fa-code-branch"></i>
                    <h1>Módulo em Desenvolvimento</h1>
                    <p>Estamos trabalhando duro para trazer a melhor experiência para você</p>
                </div>
                
                <div class="dev-content">
                    <h2>Sobre o Módulo de Finanças</h2>
                    <p>O módulo de finanças do EPIStock está sendo cuidadosamente desenvolvido para fornecer ferramentas completas de gestão financeira para sua empresa.</p>
                    
                    <p>Quando concluído, este módulo oferecerá relatórios financeiros detalhados, análise de fluxo de caixa, controle de receitas e despesas, integração com contabilidade e previsões financeiras.</p>
                    
                    <h2>O Que Esperar</h2>
                    <p>Estamos comprometidos em desenvolver uma solução financeira robusta que atenda às necessidades específicas da gestão de EPIs. O módulo incluirá uma dashboard financeira interativa com os principais KPIs, relatórios personalizáveis por período e categorização automática de transações.</p>
                    
                    <h2>Próximas Atualizações</h2>
                    <p>Nas próximas versões, implementaremos gradualmente funcionalidades como conciliação bancária, emissão de relatórios fiscais, gestão de impostos e integração com sistemas de pagamento.</p>
                    
                    <div class="dev-info">
                        <p>Este sistema está sendo desenvolvido pela <strong>Moz Websites</strong> com a mais alta qualidade e atenção aos detalhes.</p>
                        <p>Agradecemos sua paciência enquanto trabalhamos para entregar a melhor solução para sua empresa.</p>
                    </div>
                </div>
            </div>
            
        <?php elseif ($isPedidos): ?>
            <!-- Página de Pedidos -->
            <div class="dev-container">
                <div class="dev-header">
                    <i class="fas fa-code-branch"></i>
                    <h1>Módulo em Desenvolvimento</h1>
                    <p>Estamos trabalhando duro para trazer a melhor experiência para você</p>
                </div>
                
                <div class="dev-content">
                    <h2>Sobre o Módulo de Pedidos</h2>
                    <p>O módulo de pedidos do EPIStock está sendo cuidadosamente desenvolvido para gerenciar todo o processo de solicitação e distribuição de EPIs.</p>
                    
                    <p>Quando concluído, este módulo permitirá criar, rastrear e gerenciar pedidos de EPIs de forma eficiente, com integração completa com os módulos de produtos e colaboradores.</p>
                    
                    <div class="dev-info">
                        <p>Este sistema está sendo desenvolvido pela <strong>Moz Websites</strong> com a mais alta qualidade e atenção aos detalhes.</p>
                        <p>Agradecemos sua paciência enquanto trabalhamos para entregar a melhor solução para sua empresa.</p>
                    </div>
                </div>
            </div>
            
        <?php elseif ($isProdutos): ?>
            <!-- Página de Produtos -->
            <div class="dev-container">
                <div class="dev-header">
                    <i class="fas fa-code-branch"></i>
                    <h1>Módulo em Desenvolvimento</h1>
                    <p>Estamos trabalhando duro para trazer a melhor experiência para você</p>
                </div>
                
                <div class="dev-content">
                    <h2>Sobre o Módulo de Produtos</h2>
                    <p>O módulo de produtos do EPIStock está sendo cuidadosamente desenvolvido para gerenciar seu inventário de EPIs de forma eficiente.</p>
                    
                    <p>Quando concluído, este módulo oferecerá controle completo de estoque, categorização de produtos, gestão de fornecedores e alertas de reposição.</p>
                    
                    <div class="dev-info">
                        <p>Este sistema está sendo desenvolvido pela <strong>Moz Websites</strong> com a mais alta qualidade e atenção aos detalhes.</p>
                        <p>Agradecemos sua paciência enquanto trabalhamos para entregar a melhor solução para sua empresa.</p>
                    </div>
                </div>
            </div>
            
        <?php elseif ($isColaboradores): ?>
            <!-- Página de Colaboradores -->
            <div class="dev-container">
                <div class="dev-header">
                    <i class="fas fa-code-branch"></i>
                    <h1>Módulo em Desenvolvimento</h1>
                    <p>Estamos trabalhando duro para trazer a melhor experiência para você</p>
                </div>
                
                <div class="dev-content">
                    <h2>Sobre o Módulo de Colaboradores</h2>
                    <p>O módulo de colaboradores do EPIStock está sendo cuidadosamente desenvolvido para gerenciar todas as informações da sua equipe.</p>
                    
                    <p>Quando concluído, este módulo permitirá cadastrar colaboradores, associar EPIs, gerenciar departamentos e controlar prazos de validade dos equipamentos.</p>
                    
                    <div class="dev-info">
                        <p>Este sistema está sendo desenvolvido pela <strong>Moz Websites</strong> com a mais alta qualidade e atenção aos detalhes.</p>
                        <p>Agradecemos sua paciência enquanto trabalhamos para entregar a melhor solução para sua empresa.</p>
                    </div>
                </div>
            </div>
            
        <?php else: ?>
            <!-- Dashboard -->
            <div class="dev-container">
                <div class="dev-header">
                    <i class="fas fa-code-branch"></i>
                    <h1>Sistema em Desenvolvimento</h1>
                    <p>Estamos trabalhando duro para trazer a melhor experiência para você</p>
                </div>
                
                <div class="dev-content">
                    <h2>Sobre o EPIStock</h2>
                    <p>O EPIStock é um sistema completo para gestão de Equipamentos de Proteção Individual (EPIs) desenvolvido especialmente para a Greeny Workwear.</p>
                    
                    <p>Quando concluído, este sistema oferecerá módulos integrados para gestão de produtos, pedidos, colaboradores e finanças, proporcionando controle total sobre o ciclo de vida dos EPIs na sua empresa.</p>
                    
                    <h2>Recursos em Desenvolvimento</h2>
                    <ul>
                        <li>Dashboard analítico com os principais indicadores</li>
                        <li>Gestão completa de inventário de EPIs</li>
                        <li>Controle de pedidos e distribuição</li>
                        <li>Gestão de colaboradores e EPIs associados</li>
                        <li>Relatórios financeiros e análise de custos</li>
                    </ul>
                    
                    <div class="dev-info">
                        <p>Este sistema está sendo desenvolvido pela <strong>Moz Websites</strong> com a mais alta qualidade e atenção aos detalhes.</p>
                        <p>Agradecemos sua paciência enquanto trabalhamos para entregar a melhor solução para sua empresa.</p>
                    </div>
                </div>
            </div>
            
        <?php endif; ?>
    </div>
</body>
</html>