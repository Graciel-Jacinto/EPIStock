<?php
require_once 'auth.php';

$usuario = $_SESSION['usuario'];

// Configurações de data/hora
$dataAtual = date('d/m/Y');
$diaSemana = [
    'Sunday' => 'Domingo',
    'Monday' => 'Segunda-feira',
    'Tuesday' => 'Terça-feira',
    'Wednesday' => 'Quarta-feira',
    'Thursday' => 'Quinta-feira',
    'Friday' => 'Sexta-feira',
    'Saturday' => 'Sábado'
][date('l')];
$horaAtual = date('H:i');

// Frases motivacionais (em Português de Portugal)
$frasesMotivacionais = [
    "O sucesso nasce do querer, da determinação e persistência em se chegar a um objetivo.",
    "Não se limite. Muitas pessoas limitam-se ao que pensam que podem fazer. Vá tão longe quanto a sua mente o levar.",
    "Acredite em si próprio e chegará um dia em que os outros não terão outra escolha senão acreditar consigo.",
    "O trabalho duro supera o talento quando o talento não trabalha duro.",
    "Se não puder fazer tudo, faça tudo o que puder.",
    "Cada dia é uma nova oportunidade para ser melhor que ontem.",
    "A persistência é o caminho do êxito.",
    "O otimismo é a fé em ação. Nada se pode levar a efeito sem otimismo."
];
$fraseDoDia = $frasesMotivacionais[array_rand($frasesMotivacionais)];

// Configurações da API de Clima (OpenWeatherMap)
$apiKey = 'aaf77c40cb51357912a63d23a7ed4d9c';
$cidadePadrao = 'Maputo';
$unidade = 'metric';
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EPIStock - Dashboard</title>
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
            --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --font-size-base: 16px;
            --motivational-bg: #f8f4e5;
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
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .page-title h1 {
            color: var(--primary-dark);
            font-size: 1.8rem;
            font-weight: 600;
        }
        
        .page-title p {
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--primary-dark);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        .user-name {
            font-weight: 500;
        }
        
        .user-role {
            font-size: 0.8rem;
            color: var(--text-light);
        }
        
        /* Dashboard Cards */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        
        .card {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 25px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-dark);
        }
        
        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }
        
        /* Weather Card */
        .weather-card .card-icon {
            background-color: rgba(255, 165, 0, 0.1);
            color: #ffa500;
        }
        
        .weather-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 15px;
        }
        
        .weather-temp {
            font-size: 2.5rem;
            font-weight: 600;
        }
        
        .weather-details {
            flex: 1;
        }
        
        .weather-city {
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .weather-desc {
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        .weather-icon-lg {
            font-size: 3rem;
            color: #ff9800;
        }
        
        /* Time Card */
        .time-card .card-icon {
            background-color: rgba(22, 53, 82, 0.1);
            color: var(--primary-dark);
        }
        
        .time-display {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-grow: 1;
        }
        
        .current-date {
            font-size: 1.1rem;
            margin-bottom: 10px;
            color: var(--text-light);
        }
        
        .current-time {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .current-day {
            font-size: 1.2rem;
            color: var(--accent);
            font-weight: 500;
        }
        
        /* User Card */
        .user-card .card-icon {
            background-color: rgba(12, 148, 4, 0.1);
            color: var(--accent);
        }
        
        .user-details {
            margin-top: 20px;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            color: var(--text-light);
        }
        
        .detail-value {
            font-weight: 500;
        }
        
        /* Motivational Card */
        .motivational-card {
            background-color: var(--motivational-bg);
        }
        
        .motivational-card .card-icon {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }
        
        .motivational-quote {
            font-style: italic;
            font-size: 1.1rem;
            line-height: 1.6;
            margin: 15px 0;
            flex-grow: 1;
            display: flex;
            align-items: center;
        }
        
        .quote-author {
            text-align: right;
            font-weight: 500;
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background-color: var(--accent);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--accent-hover);
        }
        
        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--accent);
            color: var(--accent);
        }
        
        .btn-outline:hover {
            background-color: rgba(12, 148, 4, 0.1);
        }
        
        .btn i {
            margin-right: 8px;
        }
        
        /* Responsividade */
        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
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
                padding: 20px;
            }
            
            .system-version {
                display: none;
            }
            
            .page-title h1 {
                font-size: 1.5rem;
            }
            
            .weather-temp, .current-time {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            :root {
                --font-size-base: 15px;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .user-profile {
                width: 100%;
                justify-content: flex-end;
            }
            
            .weather-icon-lg {
                font-size: 2.5rem;
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
                height: 40px;
                margin-bottom: 0;
                margin-right: 0;
            }
            
            .system-name {
                display: none;
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
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .weather-info {
                flex-direction: column;
                text-align: center;
            }
            
            .motivational-quote {
                font-size: 1rem;
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
            <li class="nav-item active">
                <a href="#"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a>
            </li>
            <li class="nav-item">
                <a href="pedidos.php"><i class="fas fa-clipboard-list"></i> <span>Pedidos</span></a>
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
            Sistema Greeny Workwear | v1.0.0
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
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
            
            <!-- Time Card -->
            <div class="card time-card">
                <div class="card-header">
                    <h3 class="card-title">Data e Hora</h3>
                    <div class="card-icon">
                        <i class="far fa-clock"></i>
                    </div>
                </div>
                <div class="time-display">
                    <div class="current-date"><?php echo $dataAtual; ?></div>
                    <div class="current-time" id="current-time"><?php echo $horaAtual; ?></div>
                    <div class="current-day"><?php echo $diaSemana; ?></div>
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
            
            <!-- Motivational Card -->
            <div class="card motivational-card">
                <div class="card-header">
                    <h3 class="card-title">Frase do Dia</h3>
                    <div class="card-icon">
                        <i class="fas fa-quote-left"></i>
                    </div>
                </div>
                <div class="motivational-quote">
                    <?php echo $fraseDoDia; ?>
                </div>
                <div class="quote-author">
                    - Inspiração Diária
                </div>
            </div>
        </div>
    </div>

    <script>
        // Função para obter clima via API
        async function fetchWeather(city = '<?php echo $cidadePadrao; ?>') {
            const apiKey = '<?php echo $apiKey; ?>';
            const unit = '<?php echo $unidade; ?>';
            const lang = 'pt';
            
            try {
                // Primeiro tenta pegar a localização do utilizador
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        async (position) => {
                            const { latitude, longitude } = position.coords;
                            const response = await fetch(
                                `https://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&appid=${apiKey}&units=${unit}&lang=${lang}`
                            );
                            updateWeatherUI(await response.json());
                        },
                        async (error) => {
                            // Se o utilizador negar geolocalização, usa a cidade padrão
                            console.error('Erro ao obter localização:', error);
                            const response = await fetch(
                                `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=${unit}&lang=${lang}`
                            );
                            updateWeatherUI(await response.json());
                        }
                    );
                } else {
                    // Se o navegador não suportar geolocalização, usa a cidade padrão
                    const response = await fetch(
                        `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=${unit}&lang=${lang}`
                    );
                    updateWeatherUI(await response.json());
                }
            } catch (error) {
                console.error('Erro ao obter dados do clima:', error);
                document.getElementById('weather-city').textContent = 'Erro ao carregar dados';
            }
        }

        // Atualiza a UI com os dados do clima
        function updateWeatherUI(data) {
            if (data.cod !== 200) {
                document.getElementById('weather-city').textContent = 'Dados não disponíveis';
                return;
            }

            const cityElement = document.getElementById('weather-city');
            const tempElement = document.getElementById('weather-temp');
            const descElement = document.getElementById('weather-desc');
            const iconElement = document.getElementById('weather-icon');

            cityElement.textContent = `${data.name}, ${data.sys.country}`;
            tempElement.textContent = `${Math.round(data.main.temp)}°C`;
            descElement.textContent = data.weather[0].description;

            // Mapeia ícones do OpenWeatherMap para Font Awesome
            const iconMap = {
                '01d': 'fa-sun',         // céu limpo (dia)
                '01n': 'fa-moon',        // céu limpo (noite)
                '02d': 'fa-cloud-sun',   // poucas nuvens (dia)
                '02n': 'fa-cloud-moon',   // poucas nuvens (noite)
                '03d': 'fa-cloud',       // nuvens dispersas
                '03n': 'fa-cloud',
                '04d': 'fa-cloud',       // nuvens quebradas
                '04n': 'fa-cloud',
                '09d': 'fa-cloud-rain',  // chuva
                '09n': 'fa-cloud-rain',
                '10d': 'fa-cloud-sun-rain', // chuva (dia)
                '10n': 'fa-cloud-moon-rain',// chuva (noite)
                '11d': 'fa-bolt',        // trovoada
                '11n': 'fa-bolt',
                '13d': 'fa-snowflake',   // neve
                '13n': 'fa-snowflake',
                '50d': 'fa-smog',        // nevoeiro
                '50n': 'fa-smog'
            };

            const weatherIcon = data.weather[0].icon;
            iconElement.className = `fas ${iconMap[weatherIcon] || 'fa-cloud'} weather-icon-lg`;
        }

        // Atualiza a hora a cada minuto
        function updateClock() {
            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            document.getElementById('current-time').textContent = `${hours}:${minutes}`;
        }

        // Carrega os dados do clima quando a página é carregada
        document.addEventListener('DOMContentLoaded', () => {
            fetchWeather();
            updateClock();
            setInterval(updateClock, 60000);
        });
    </script>
</body>
</html>