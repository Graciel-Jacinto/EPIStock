<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: logar.php');
    exit();
}

// Garantir que $_SESSION['usuario'] é um array
if (!is_array($_SESSION['usuario'])) {
    // Se for string, tentar decodificar de JSON
    if (is_string($_SESSION['usuario'])) {
        $decoded = json_decode($_SESSION['usuario'], true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $_SESSION['usuario'] = $decoded;
        } else {
            // Se não for JSON válido, redirecionar para login
            header('Location: logar.php');
            exit();
        }
    } else {
        // Se não for array nem string JSON válida, redirecionar
        header('Location: logar.php');
        exit();
    }
}

// Verificar se os campos necessários existem no array
$required_fields = ['nome', 'email', 'tipo_usuario', 'id', 'data_cadastro'];
foreach ($required_fields as $field) {
    if (!isset($_SESSION['usuario'][$field])) {
        header('Location: logar.php');
        exit();
    }
}
?>