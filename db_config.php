<?php
/**
 * Configuração de conexão com o banco de dados
 * 
 * Este arquivo contém apenas as configurações básicas de conexão com o MySQL
 * Não inclui funções adicionais ou alterações na estrutura do banco de dados
 */

// Configurações do banco de dados - altere conforme seu ambiente
define('DB_HOST', 'localhost');      // Endereço do servidor MySQL
define('DB_USER', 'root');    // Nome de usuário do MySQL
define('DB_PASS', '');      // Senha do MySQL
define('DB_NAME', 'epistock_db');      // Nome do banco de dados

// Tentativa de conexão com o banco de dados
try {
    // Cria a conexão PDO
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER, 
        DB_PASS
    );
    
    // Configura o PDO para lançar exceções em caso de erro
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Configura para retornar arrays associativos por padrão
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    // Em caso de erro na conexão, exibe mensagem e encerra o script
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}