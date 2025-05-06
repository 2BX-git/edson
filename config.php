<?php
// config.php
$host = "188.245.217.172";
$user = "root";
$pass = "a5450669cdb16113b0ed";
$dbname = "empresa";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erro ao conectar ao banco: " . $conn->connect_error);
}

function h(?string $str): string {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

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
    
    if ($_SERVER['REQUEST_URI'] !== '/login.php' && !isset($_SESSION['usuario'])) {
        redirecionar_login();
    }

    if (basename($_SERVER['PHP_SELF']) !== 'login.php' && !autenticado()) {
        redirecionar_login();
    }
}
?>
