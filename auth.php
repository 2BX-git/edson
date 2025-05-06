<?php
// auth.php

session_start();

// Definição dos usuários com senhas HASHED mais seguras
$usuarios = [
    'admin' => [
        'senha' => password_hash('A@dm!nS3gur0#2025', PASSWORD_DEFAULT),
        'tipo' => 'admin'
    ],
    'operador' => [
        'senha' => password_hash('Op3r@d0rC0nf!4v3l$2025', PASSWORD_DEFAULT),
        'tipo' => 'operador'
    ]
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

    // Garante que não haja acesso direto indevido
    if ($_SERVER['REQUEST_URI'] !== '/login.php' && !isset($_SESSION['usuario'])) {
        redirecionar_login();
    }

    if (basename($_SERVER['PHP_SELF']) !== 'login.php' && !autenticado()) {
        redirecionar_login();
    }
}
