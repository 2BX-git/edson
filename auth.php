<?php
// auth.php

session_start();

// Definição dos usuários e senhas (senha armazenada como hash para segurança)
$usuarios = [
    'admin' => ['senha' => password_hash('admin123', PASSWORD_DEFAULT), 'tipo' => 'admin'],
    'operador' => ['senha' => password_hash('operador123', PASSWORD_DEFAULT), 'tipo' => 'operador']
];

// Função para verificar se o usuário está autenticado
function autenticado() {
    return isset($_SESSION['usuario']);
}

// Função para obter o tipo do usuário autenticado
function usuario_tipo() {
    return $_SESSION['usuario_tipo'] ?? null;
}

// Função para redirecionar para a página de login
function redirecionar_login() {
    header("Location: login.php");
    exit();
}

// Função para verificar acesso - protege as páginas restritas
function verificar_acesso() {
    if (!autenticado()) {
        redirecionar_login();
    }

    // Se for tentar acessar uma página diretamente sem estar logado, redireciona
    if ($_SERVER['REQUEST_URI'] !== '/login.php' && !isset($_SESSION['usuario'])) {
        redirecionar_login();
    }

    if (basename($_SERVER['PHP_SELF']) !== 'login.php' && !autenticado()) {
        redirecionar_login();
    }
}
?>
