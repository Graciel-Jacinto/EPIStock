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
        else echo 'Sistema';
    ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="das.css">
    <style>
        /* [Mantenha todo o CSS anterior igual] */
        
        .em-desenvolvimento {
            text-align: center;
            padding: 50px 20px;
            max-width: 600px;
            margin: 0 auto;
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
            <li class="nav-item">
                <a href="#"><i class="fas fa-users"></i> <span>Colaboradores</span></a>
            </li>
            <li class="nav-item">
                <a href="finacas.php"><i class="fas fa-chart-line"></i> <span>Finanças</span></a>
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
                <p>Agradecemos a sua compreensão.</p>
            </div>
        
        <?php elseif ($isProdutos): ?>
            <!-- Página de Produtos -->
            <div class="em-desenvolvimento">
                <i class="fas fa-box-open"></i>
                <h2>Produtos em Análise</h2>
                <p>Estamos a analisar cuidadosamente os produtos para integrar nesta secção do sistema.</p>
                <p>Esta funcionalidade está a ser desenvolvida com o máximo cuidado para garantir a melhor experiência.</p>
                
                <div class="dev-company">
                    <p>Desenvolvimento a cargo de:</p>
                    <p><strong>Moz Websites</strong></p>
                </div>
            </div>
        
        <?php else: ?>
            <!-- Página Dashboard -->
            <div class="header">
                <div class="page-title">
                    <h1>Dashboard</h1>
                    <p>Bem-vindo ao sistema EPIStock</p>
                </div>
                
                <div class="user-profile">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($usuario['nome'], 0, 1)); ?>
                    </div>
                    <div>
                        <div class="user-name"><?php echo htmlspecialchars($usuario['nome']); ?></div>
                        <div class="user-role"><?php echo $usuario['tipo_usuario']; ?></div>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-grid">
                <!-- Weather Card -->
                <div class="card weather-card">
                    <div class="card-header">
                        <h3 class="card-title">Tempo Atual</h3>
                        <div class="card-icon">
                            <i class="fas fa-cloud-sun"></i>
                        </div>
                    </div>
                    <div class="weather-info">
                        <div class="weather-details">
                            <div class="weather-city" id="weather-city">A carregar...</div>
                            <div class="weather-desc" id="weather-desc">-</div>
                        </div>
                        <div class="weather-temp" id="weather-temp">-</div>
                        <i class="fas fa-sun weather-icon-lg" id="weather-icon"></i>
                    </div>
                </div>
                
                <!-- User Card -->
                <div class="card user-card">
                    <div class="card-header">
                        <h3 class="card-title">O Meu Perfil</h3>
                        <div class="card-icon">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    <div class="user-details">
                        <div class="detail-item">
                            <span class="detail-label">ID de Utilizador:</span>
                            <span class="detail-value"><?php echo $usuario['id']; ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">E-mail:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($usuario['email']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Tipo de Conta:</span>
                            <span class="detail-value"><?php echo $usuario['tipo_usuario']; ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Registado em:</span>
                            <span class="detail-value"><?php echo date('d/m/Y', strtotime($usuario['data_cadastro'])); ?></span>
                        </div>
                    </div>
                    <div style="margin-top: 20px; display: flex; gap: 10px;">
                        <a href="logout.php" class="btn btn-primary">
                            <i class="fas fa-sign-out-alt"></i> Sair
                        </a>
                        <a href="#" class="btn btn-outline">
                            <i class="fas fa-cog"></i> Configurações
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($isDashboard): ?>
    <script>
        // [Mantenha o JavaScript do dashboard igual]
    </script>
    <?php endif; ?>
</body>
</html>