<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: logar.php');
    exit();
}

// Você pode adicionar verificações adicionais aqui, como:
// - Verificar se o tipo de usuário tem acesso à página
// - Verificar se a sessão ainda é válida
// - Registrar atividade do usuário

// Exemplo de uso em outras páginas:
// require_once 'auth.php';
// Agora você pode acessar $_SESSION['usuario']['nome'], etc.
?>