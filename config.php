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
