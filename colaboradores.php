<?php
require_once 'auth.php';

$usuario = $_SESSION['usuario'];

// Configurações
$apiKey = 'aaf77c40cb51357912a63d23a7ed4d9c';
$cidadePadrao = 'Maputo';
$unidade = 'metric';

// Verifica a página atual
$paginaAtual = basename($_SERVER['PHP_SELF']);
$isDashboard = ($paginaAtual == 'dashboard.php');
$isPedidos = ($paginaAtual == 'pedidos.php');
$isProdutos = ($paginaAtual == 'produtos.php');
$isColaboradores = ($paginaAtual == 'colaboradores.php');
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
        else echo 'Sistema';
    ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="das.css">
    <style>
        /* [Mantenha todo o CSS anterior] */
        
        .em-desenvolvimento {
            text-align: center;
            padding: 50px 20px;
            max-width: 600px;
            margin: 50px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        .em-desenvolvimento i {
            font-size: 4rem;
            color: var(--accent);
            margin-bottom: 20px;
        }
        
        .em-desenvolvimento h2 {
            font-size: 2rem;
            color: var(--primary-dark);
            margin-bottom: 15px;
        }
        
        .em-desenvolvimento p {
            font-size: 1.1rem;
            color: var(--text-light);
            line-height: 1.6;
            margin-bottom: 10px;
        }
        
        .dev-company {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-style: italic;
            color: var(--text-light);
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
            <li class="nav-item">
                <a href="#"><i class="fas fa-chart-line"></i> <span>Finanças</span></a>
            </li>
            <li class="nav-item">
                <a href="#"><i class="fas fa-cog"></i> <span>Configurações</span></a>
            </li>
        </ul>
        
        <div class="system-version">
            v1.0.0
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <?php if ($isPedidos): ?>
            <!-- Página de Pedidos -->
            <div class="em-desenvolvimento">
                <i class="fas fa-tools"></i>
                <h2>Em Breve</h2>
                <p>Esta secção encontra-se atualmente em desenvolvimento.</p>
                <p>Estamos a trabalhar para trazer-lhe a melhor experiência possível.</p>
            </div>
        
        <?php elseif ($isProdutos): ?>
            <!-- Página de Produtos -->
            <div class="em-desenvolvimento">
                <i class="fas fa-box-open"></i>
                <h2>Produtos em Análise</h2>
                <p>Estamos a analisar cuidadosamente os produtos para integrar nesta secção do sistema.</p>
                
                <div class="dev-company">
                    <p>Desenvolvimento a cargo de:</p>
                    <p><strong>Moz Websites</strong></p>
                </div>
            </div>
        
        <?php elseif ($isColaboradores): ?>
            <!-- Página de Colaboradores -->
            <div class="em-desenvolvimento">
                <i class="fas fa-users-cog"></i>
                <h2>Gestão de Colaboradores</h2>
                <p>A área de gestão de colaboradores está em fase de desenvolvimento avançado.</p>
                <p>Estamos a implementar funcionalidades completas para a gestão da sua equipa.</p>
                
                <div class="dev-company">
                    <p>Em desenvolvimento por:</p>
                    <p><strong>Moz Websites</strong></p>
                </div>
            </div>
        
        <?php else: ?>
            <!-- Página Dashboard -->
            [Mantenha o conteúdo do dashboard existente]
        <?php endif; ?>
    </div>

    <?php if ($isDashboard): ?>
    <script>
        // [Mantenha o JavaScript do dashboard]
    </script>
    <?php endif; ?>
</body>
</html>