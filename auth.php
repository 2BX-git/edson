<?php
// auth.php

$usuarios = [
    'admin' => ['senha' => password_hash('A@dm!nS3gur0#2025', PASSWORD_DEFAULT), 'tipo' => 'admin'],
    'operador' => ['senha' => password_hash('Op3r@d0rC0nf!4v3l$2025', PASSWORD_DEFAULT), 'tipo' => 'operador']
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
