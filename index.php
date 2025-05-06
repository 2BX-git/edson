<?php
// index.php

session_start();

// Verifica se o usuário está autenticado
if (isset($_SESSION['usuario'])) {
    header("Location: crm.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}
?>
