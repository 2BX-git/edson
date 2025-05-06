<?php
// auth.php

session_start();

$usuarios = [
    'admin' => ['senha' => password_hash('admin123', PASSWORD_DEFAULT), 'tipo' => 'admin'],
    'operador' => ['senha' => password_hash('operador123', PASSWORD_DEFAULT), 'tipo' => 'operador']
];

function autenticado() {
    return isset($_SESSION['usuario']);
}

function usuario_tipo() {
    return $_SESSION['usuario_tipo'] ?? null;
}

function redirecionar_login() {
    header("Location: login.php");
    exit();
}

function verificar_acesso() {
    if (!autenticado()) {
        redirecionar_login();
    }
}
